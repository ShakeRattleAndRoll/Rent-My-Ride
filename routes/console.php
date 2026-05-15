<?php

use App\Models\User;
use App\Services\RentalNotificationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('rentals:generate-timeline-notifications', function () {
    $notifications = app(RentalNotificationService::class);

    User::query()
        ->select('id')
        ->chunkById(100, function ($users) use ($notifications) {
            $users->each(fn (User $user) => $notifications->generateTimelineNotifications($user));
        });

    $this->info('Rental timeline notifications generated.');
})->purpose('Generate rental ending and expired notifications for all users');

Schedule::command('rentals:generate-timeline-notifications')->everyMinute();
