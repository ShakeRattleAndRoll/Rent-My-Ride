<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Rental;

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
                $totalUnreadMessages = Message::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $totalPendingOrders = Rental::whereHas('car', function ($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->where('status', 'pending')
                    ->count();
            } else {
                $totalUnreadMessages = 0;
                $totalPendingOrders = 0;
            }

            $view->with('totalUnreadMessages', $totalUnreadMessages);
            $view->with('totalPendingOrders', $totalPendingOrders);
        });
    }
}