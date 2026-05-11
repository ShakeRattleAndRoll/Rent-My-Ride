<?php

namespace App\Http\Controllers;

use App\Models\RentalNotification;
use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(RentalNotificationService $notifications)
    {
        $notifications->generateTimelineNotifications(Auth::user());

        $items = RentalNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('items'));
    }

    public function count(RentalNotificationService $notifications)
    {
        $notifications->generateTimelineNotifications(Auth::user());

        return response()->json([
            'unread_notifications' => RentalNotification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count(),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = RentalNotification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['read_at' => now()]);

        $redirect = $request->boolean('open')
            ? $this->openUrl($notification)
            : route('notifications.index');

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirect]);
        }

        return redirect($redirect);
    }

    public function markAllRead(Request $request)
    {
        RentalNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['redirect' => route('notifications.index')]);
        }

        return redirect()->back()->with('success', 'Notifications marked as read.');
    }

    private function openUrl(RentalNotification $notification): string
    {
        $notification->loadMissing('car');

        if (
            $notification->car &&
            $notification->user_id === $notification->car->user_id &&
            in_array($notification->type, ['owner_rental_accepted', 'rental_ending_soon', 'rental_expired'], true)
        ) {
            return url("/garage/details/{$notification->car_id}");
        }

        if (
            $notification->rental_id &&
            in_array($notification->type, ['rental_accepted', 'rental_denied', 'rental_ending_soon', 'rental_expired'], true)
        ) {
            return url("/garage/my-rental?rental={$notification->rental_id}");
        }

        return $notification->url ?: route('notifications.index');
    }

    public function destroy(Request $request, $id)
    {
        RentalNotification::where('user_id', Auth::id())->findOrFail($id)->delete();

        if ($request->expectsJson()) {
            session()->flash('success', 'Notification deleted.');

            return response()->json([
                'success' => true,
                'redirect' => route('notifications.index'),
            ]);
        }

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    public function destroyAll(Request $request)
    {
        RentalNotification::where('user_id', Auth::id())->delete();

        if ($request->expectsJson()) {
            session()->flash('success', 'All notifications deleted.');

            return response()->json([
                'success' => true,
                'redirect' => route('notifications.index'),
            ]);
        }

        return redirect()->back()->with('success', 'All notifications deleted.');
    }
}
