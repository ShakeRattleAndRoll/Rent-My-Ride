<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\RentalNotification;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;

class RentalNotificationService
{
    public function requestCreated(Rental $rental): void
    {
        $rental->loadMissing('car', 'user');

        $start = Carbon::parse($rental->start_date)->format('F j, Y g:i A');
        $end   = Carbon::parse($rental->end_date)->format('F j, Y g:i A');

        $this->create($rental->car->user_id, $rental, 'rental_requested',
            'New rental request',
            "{$rental->user->username} wants to rent your {$rental->car->brand} {$rental->car->model} from {$start} to {$end}.",
            "/car/pre-order/{$rental->car_id}"
        );
    }

    public function accepted(Rental $rental, bool $automatic = false): void
    {
        $rental->loadMissing('car');

        $start = Carbon::parse($rental->start_date)->format('F j, Y g:i A');
        $end   = Carbon::parse($rental->end_date)->format('F j, Y g:i A');
        $title = $automatic ? 'Request auto-accepted' : 'Request accepted';

        $this->create($rental->user_id, $rental, 'rental_accepted',
            $title,
            "Your request for {$rental->car->brand} {$rental->car->model} was accepted. Rental period: {$start} to {$end}.",
            "/garage/my-rental?rental={$rental->id}"
        );

        $this->create($rental->car->user_id, $rental, 'owner_rental_accepted',
            $title,
            "{$rental->car->brand} {$rental->car->model} has a confirmed rental. Rental period: {$start} to {$end}.",
            "/garage/details/{$rental->car_id}"
        );
    }

    public function denied(Rental $rental, string $reason = 'The schedule is unavailable.'): void
    {
        $rental->loadMissing('car');

        $start = Carbon::parse($rental->start_date)->format('F j, Y g:i A');
        $end   = Carbon::parse($rental->end_date)->format('F j, Y g:i A');

        $this->create($rental->user_id, $rental, 'rental_denied',
            'Request denied',
            "Your request for {$rental->car->brand} {$rental->car->model} was denied. Rental period: {$start} to {$end}. {$reason}",
            "/garage/my-rental?rental={$rental->id}"
        );
    }

    public function cancelledByRenter(Rental $rental): void
    {
        $rental->loadMissing('car', 'user');

        $start = Carbon::parse($rental->start_date)->format('F j, Y g:i A');
        $end   = Carbon::parse($rental->end_date)->format('F j, Y g:i A');

        $this->create($rental->car->user_id, $rental, 'rental_cancelled',
            'Request cancelled',
            "{$rental->user->username} cancelled their request for {$rental->car->brand} {$rental->car->model}. Rental period: {$start} to {$end}.",
            "/car/pre-order/{$rental->car_id}"
        );
    }

    public function carApproved(Car $car): void
    {
        $this->createCarNotification($car->user_id, $car, 'car_post_approved',
            'Car post accepted',
            "Your {$car->brand} {$car->model} post was accepted and is now visible to renters.",
            "/garage/details/{$car->id}"
        );
    }

    public function carDenied(Car $car): void
    {
        $this->createCarNotification($car->user_id, $car, 'car_post_denied',
            'Car post denied',
            "Your {$car->brand} {$car->model} post was denied by an admin.",
            "/garage/my-listing"
        );
    }

    public function carDeletedByAdmin(Car $car): void
    {
        $this->createCarNotification($car->user_id, $car, 'car_post_deleted',
            'Car post deleted',
            "Your {$car->brand} {$car->model} post was deleted by an admin.",
            "/garage/my-listing"
        );
    }

    public function generateTimelineNotification(User $user): void
    {
        $this->generateTimelineNotifications($user);
    }

    public function generateTimelineNotifications(User $user): void
    {
        $rentals = Rental::with('car')
            ->where('status', 'accepted')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('car', fn ($carQuery) => $carQuery->where('user_id', $user->id));
            })
            ->get();

        foreach ($rentals as $rental) {
            if (! $rental->car) {
                continue;
            }

            $end           = Carbon::parse($rental->end_date);
            $start         = Carbon::parse($rental->start_date);
            $now           = now();
            $durationHours = $start->diffInHours($end);
            $endFormatted  = $end->format('F j, Y g:i A');

            $recipients = collect([$rental->user_id, $rental->car->user_id])
                ->unique()
                ->values();

            foreach ($recipients as $recipientId) {
                $isOwner = $rental->car->user_id === $recipientId;
                $url     = $isOwner
                    ? "/garage/details/{$rental->car_id}"
                    : "/garage/my-rental?rental={$rental->id}";

                if ($end->gt($now)) {

                    if ($end->lte($now->copy()->addMinutes(15))) {
                        $this->createOnce($recipientId, $rental, 'rental_ending_15min',
                            'Rental ends within 15 minutes',
                            "{$rental->car->brand} {$rental->car->model} ends on {$endFormatted}.",
                            $url
                        );
                    }

                    if ($durationHours > 1 && $end->lte($now->copy()->addHour())) {
                        $this->createOnce($recipientId, $rental, 'rental_ending_1hour',
                            'Rental ends within 1 hour',
                            "{$rental->car->brand} {$rental->car->model} ends on {$endFormatted}.",
                            $url
                        );
                    }

                    if ($durationHours > 24 && $end->lte($now->copy()->addDay())) {
                        $this->createOnce($recipientId, $rental, 'rental_ending_1day',
                            'Rental ends within 1 day',
                            "{$rental->car->brand} {$rental->car->model} ends on {$endFormatted}.",
                            $url
                        );
                    }
                }

                if ($end->lte($now)) {
                    $this->createOnce($recipientId, $rental, 'rental_expired',
                        'Rental expired',
                        "{$rental->car->brand} {$rental->car->model} ended on {$endFormatted}.",
                        $url
                    );
                }
            }
        }
    }

    private function create(int $userId, Rental $rental, string $type, string $title, string $body, ?string $url = null): void
    {
        RentalNotification::create([
            'user_id'   => $userId,
            'rental_id' => $rental->id,
            'car_id'    => $rental->car_id,
            'type'      => $type,
            'title'     => $title,
            'body'      => $body,
            'url'       => $url,
        ]);
    }

    private function createOnce(int $userId, Rental $rental, string $type, string $title, string $body, ?string $url = null): void
    {
        $existing = RentalNotification::withTrashed()->where([
            'user_id'   => $userId,
            'rental_id' => $rental->id,
            'type'      => $type,
        ])->first();

        if ($existing) {
            return;
        }

        RentalNotification::create([
            'user_id'   => $userId,
            'rental_id' => $rental->id,
            'type'      => $type,
            'car_id'    => $rental->car_id,
            'title'     => $title,
            'body'      => $body,
            'url'       => $url,
        ]);
    }

    private function createCarNotification(int $userId, Car $car, string $type, string $title, string $body, ?string $url = null): void
    {
        RentalNotification::create([
            'user_id' => $userId,
            'car_id'  => $car->id,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
            'url'     => $url,
        ]);
    }
}
