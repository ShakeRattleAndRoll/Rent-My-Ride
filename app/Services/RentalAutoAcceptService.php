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

    public const PRIORITIES = [
        self::PRIORITY_FIRST,
        self::PRIORITY_SHORTEST,
        self::PRIORITY_LONGEST,
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
                'denied' => $notifications->denied($rental, 'The schedule is unavailable or already expired.'),
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

                app(RentalNotificationService::class)->denied($rental, 'The schedule is unavailable or already expired.');
            });
        });
    }

    public function canAccept(Car $car, $startDate, $endDate, ?int $ignoreRentalId = null): bool
    {
        if (! $startDate || ! $endDate) {
            return false;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($end->lte(now()) || $end->lte($start)) {
            return false;
        }

        return ! Rental::where('car_id', $car->id)
            ->where('status', 'accepted')
            ->when($ignoreRentalId, fn ($query) => $query->where('id', '!=', $ignoreRentalId))
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();
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
