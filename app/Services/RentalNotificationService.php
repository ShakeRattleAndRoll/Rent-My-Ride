<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\RentalNotification;
use App\Models\User;
use Carbon\Carbon;

class RentalNotificationService
{
    public function requestCreated(Rental $rental): void
    {
        $rental->loadMissing('car', 'user');

        $this->create($rental->car->user_id, $rental, 'rental_requested',
            'New rental request',
            "{$rental->user->username} wants to rent your {$rental->car->brand} {$rental->car->model}.",
            "/car/pre-order/{$rental->car_id}"
        );
    }

    public function accepted(Rental $rental, bool $automatic = false): void
    {
        $rental->loadMissing('car');

        $title = $automatic ? 'Request auto-accepted' : 'Request accepted';

        $this->create($rental->user_id, $rental, 'rental_accepted',
            $title,
            "Your request for {$rental->car->brand} {$rental->car->model} was accepted.",
            "/garage/my-rental?rental={$rental->id}"
        );

        $this->create($rental->car->user_id, $rental, 'owner_rental_accepted',
            $title,
            "{$rental->car->brand} {$rental->car->model} has a confirmed rental.",
            "/garage/details/{$rental->car_id}"
        );
    }

    public function denied(Rental $rental, string $reason = 'The schedule is unavailable.'): void
    {
        $rental->loadMissing('car');

        $this->create($rental->user_id, $rental, 'rental_denied',
            'Request denied',
            "Your request for {$rental->car->brand} {$rental->car->model} was denied. {$reason}",
            "/garage/my-rental?rental={$rental->id}"
        );
    }

    public function cancelledByRenter(Rental $rental): void
    {
        $rental->loadMissing('car', 'user');

        $this->create($rental->car->user_id, $rental, 'rental_cancelled',
            'Request cancelled',
            "{$rental->user->username} cancelled their request for {$rental->car->brand} {$rental->car->model}.",
            "/car/pre-order/{$rental->car_id}"
        );
    }

    public function generateTimelineNotifications(User $user): void
    {
        $rentals = Rental::with('car')
            ->where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('car', fn ($carQuery) => $carQuery->where('user_id', $user->id));
            })
            ->get();

        foreach ($rentals as $rental) {
            $end = Carbon::parse($rental->end_date);
            $isOwner = $rental->car->user_id === $user->id;
            $url = $isOwner ? "/garage/details/{$rental->car_id}" : "/garage/my-rental?rental={$rental->id}";

            if ($end->isFuture() && $end->lte(now()->addDay())) {
                $this->createOnce($user->id, $rental, 'rental_ending_soon',
                    'Rental ends within 1 day',
                    "{$rental->car->brand} {$rental->car->model} ends on {$end->format('M j, Y g:i A')}.",
                    $url
                );
            }

            if ($end->lte(now())) {
                $this->createOnce($user->id, $rental, 'rental_expired',
                    'Rental expired',
                    "{$rental->car->brand} {$rental->car->model} ended on {$end->format('M j, Y g:i A')}.",
                    $url
                );
            }
        }
    }

    private function create(int $userId, Rental $rental, string $type, string $title, string $body, ?string $url = null): void
    {
        RentalNotification::create([
            'user_id' => $userId,
            'rental_id' => $rental->id,
            'car_id' => $rental->car_id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'url' => $url,
        ]);
    }

    private function createOnce(int $userId, Rental $rental, string $type, string $title, string $body, ?string $url = null): void
    {
        RentalNotification::firstOrCreate([
            'user_id' => $userId,
            'rental_id' => $rental->id,
            'type' => $type,
        ], [
            'car_id' => $rental->car_id,
            'title' => $title,
            'body' => $body,
            'url' => $url,
        ]);
    }
}
