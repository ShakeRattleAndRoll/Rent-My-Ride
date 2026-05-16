<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalAutoAcceptService
{
    public const PRIORITY_FIRST = 'first_pending';
    public const PRIORITY_SHORTEST = 'shortest';
    public const PRIORITY_LONGEST = 'longest';
    public const PRIORITY_NEAREST = 'nearest';

    public const PRIORITIES = [
        self::PRIORITY_FIRST,
        self::PRIORITY_SHORTEST,
        self::PRIORITY_LONGEST,
        self::PRIORITY_NEAREST,
    ];

    public function createRental(Car $car, array $attributes): Rental
    {
        return DB::transaction(function () use ($car, $attributes) {
            $car->refresh();

            $status = 'pending';

            if ($car->auto_accept) {
                $status = $this->canAccept($car, $attributes['start_date'], $attributes['end_date'])
                    ? 'accepted'
                    : 'denied';
            }

            $rental = Rental::create(array_merge($attributes, [
                'status' => $status,
            ], $status === 'pending' ? [] : $this->snapshot($car)));

            $notifications = app(RentalNotificationService::class);

            match ($rental->status) {
                'accepted' => $notifications->accepted($rental, true),
                'denied' => $notifications->denied($rental, $this->denialReasonForSchedule($car, $rental->start_date, $rental->end_date)),
                default => $notifications->requestCreated($rental),
            };

            return $rental;
        });
    }

    public function processPending(Car $car, ?string $priority = null): void
    {
        DB::transaction(function () use ($car, $priority) {
            $car->refresh();
            $priority = $this->normalizePriority($priority ?: $car->auto_accept_priority);

            $query = Rental::where('car_id', $car->id)
                ->where('status', 'pending');

            match ($priority) {
                self::PRIORITY_SHORTEST => $query->orderBy('days')->orderBy('created_at'),
                self::PRIORITY_LONGEST => $query->orderByDesc('days')->orderBy('created_at'),
                self::PRIORITY_NEAREST => $query->orderBy('start_date')->orderBy('created_at'),
                default => $query->orderBy('created_at'),
            };

            $query->get()->each(function (Rental $rental) use ($car) {
                if ($this->canAccept($car, $rental->start_date, $rental->end_date)) {
                    $rental->update(array_merge([
                        'status' => 'accepted',
                    ], $this->snapshot($car)));

                    app(RentalNotificationService::class)->accepted($rental, true);

                    return;
                }

                $rental->update(array_merge([
                    'status' => 'denied',
                ], $this->snapshot($car)));

                app(RentalNotificationService::class)->denied(
                    $rental,
                    $this->denialReasonForSchedule($car, $rental->start_date, $rental->end_date)
                );
            });
        });
    }

    public function denyUnavailablePending(Car $car): int
    {
        return DB::transaction(function () use ($car) {
            $denied = 0;
            $notifications = app(RentalNotificationService::class);

            Rental::where('car_id', $car->id)
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->get()
                ->each(function (Rental $rental) use ($car, $notifications, &$denied) {
                    $reason = $this->pendingDenialReason($car, $rental);

                    if (! $reason) {
                        return;
                    }

                    $rental->update(array_merge([
                        'status' => 'denied',
                    ], $this->snapshot($car)));

                    $notifications->denied($rental, $reason);
                    $denied++;
                });

            return $denied;
        });
    }

    public function canAccept(Car $car, $startDate, $endDate, ?int $ignoreRentalId = null): bool
    {
        if (! $startDate || ! $endDate) {
            return false;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->lte(now()) || $end->lte(now()) || $end->lte($start)) {
            return false;
        }

        return ! $this->hasAcceptedConflict($car, $start, $end, $ignoreRentalId);
    }

    public function denialReasonForSchedule(Car $car, $startDate, $endDate, ?int $ignoreRentalId = null): string
    {
        if (! $startDate || ! $endDate) {
            return 'The schedule is unavailable.';
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->lte(now())) {
            return 'The requested start time has already passed.';
        }

        if ($end->lte($start)) {
            return 'The requested schedule is invalid.';
        }

        $conflict = $this->acceptedConflict($car, $start, $end, $ignoreRentalId);

        if ($conflict) {
            return 'The requested schedule conflicts with an accepted rental from '
                . $this->formatScheduleDate($conflict->start_date)
                . ' to '
                . $this->formatScheduleDate($conflict->end_date)
                . '.';
        }

        return 'The schedule is unavailable or already expired.';
    }

    private function pendingDenialReason(Car $car, Rental $rental): ?string
    {
        $reason = $this->denialReasonForSchedule($car, $rental->start_date, $rental->end_date);

        if ($reason !== 'The schedule is unavailable or already expired.') {
            return $reason;
        }

        return null;
    }

    private function hasAcceptedConflict(Car $car, Carbon $start, Carbon $end, ?int $ignoreRentalId = null): bool
    {
        return (bool) $this->acceptedConflict($car, $start, $end, $ignoreRentalId);
    }

    private function acceptedConflict(Car $car, Carbon $start, Carbon $end, ?int $ignoreRentalId = null): ?Rental
    {
        return Rental::where('car_id', $car->id)
            ->where('status', 'accepted')
            ->when($ignoreRentalId, fn ($query) => $query->where('id', '!=', $ignoreRentalId))
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->orderBy('start_date')
            ->first();
    }

    private function formatScheduleDate($date): string
    {
        return Carbon::parse($date)->format('M j, Y g:i A');
    }

    public function normalizePriority(?string $priority): string
    {
        return in_array($priority, self::PRIORITIES, true)
            ? $priority
            : self::PRIORITY_FIRST;
    }

    public function snapshot(Car $car): array
    {
        return [
            'snap_brand'        => $car->brand,
            'snap_model'        => $car->model,
            'snap_car_image'    => $car->car_image,
            'snap_price'        => $car->price,
            'snap_rent_unit'    => $car->rent_unit,
            'snap_fuel_type'    => $car->fuel_type,
            'snap_transmission' => $car->transmission,
            'snap_date_owned'   => $car->date_owned,
        ];
    }
}
