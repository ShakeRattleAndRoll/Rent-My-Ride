<?php

namespace App\Http\Controllers;

use App\Models\RentalNotification;
use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private const TIMELINE_TYPES = [
        'rental_ending_soon',
        'rental_ending_15min',
        'rental_ending_1hour',
        'rental_ending_1day',
        'rental_expired',
    ];

    public function index(RentalNotificationService $notifications)
    {
        $notifications->generateTimelineNotifications(Auth::user());

        $items = $this->notificationItems();

        return view('notifications.index', compact('items'));
    }

    public function items(RentalNotificationService $notifications)
    {
        $notifications->generateTimelineNotifications(Auth::user());

        $items = $this->notificationItems();

        return response()->json([
            'html' => view('notifications.partials.list', compact('items'))->render(),
            'unread_notifications' => RentalNotification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count(),
        ]);
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
            in_array($notification->type, array_merge(['owner_rental_accepted'], self::TIMELINE_TYPES), true)
        ) {
            return url("/garage/details/{$notification->car_id}");
        }

        if (
            $notification->rental_id &&
            in_array($notification->type, array_merge(['rental_accepted', 'rental_denied'], self::TIMELINE_TYPES), true)
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

    private function notificationItems()
    {
        return RentalNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15)
            ->withPath(route('notifications.index'));
    }
}
