<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Car;
use App\Models\Rental;
use App\Models\RentalNotification;
use App\Models\UserRelation;
use App\Services\RentalNotificationService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $mutedUserIds = UserRelation::where('user_id', Auth::id())
                    ->where('type', 'mute')
                    ->pluck('target_id');

                $totalUnreadMessages = Message::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->whereNotIn('sender_id', $mutedUserIds)
                    ->count();

                $totalPendingOrders = Rental::whereHas('car', function ($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->where('status', 'pending')
                    ->count();

                app(RentalNotificationService::class)->generateTimelineNotifications(Auth::user());

                $totalUnreadNotifications = RentalNotification::where('user_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                $totalPendingCarApprovals = Auth::user()->is_admin
                    ? Car::where('approval_status', 'pending')->count()
                    : 0;
            } else {
                $totalUnreadMessages = 0;
                $totalPendingOrders = 0;
                $totalUnreadNotifications = 0;
                $totalPendingCarApprovals = 0;
            }

            $view->with('totalUnreadMessages', $totalUnreadMessages);
            $view->with('totalPendingOrders', $totalPendingOrders);
            $view->with('totalUnreadNotifications', $totalUnreadNotifications);
            $view->with('totalPendingCarApprovals', $totalPendingCarApprovals);
        });
    }
}
