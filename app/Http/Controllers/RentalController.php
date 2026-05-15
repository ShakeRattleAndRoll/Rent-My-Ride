<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Cart;
use App\Models\Car;
use App\Services\RentalAutoAcceptService;
use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    // CREATE RENTAL (USER REQUEST)
    public function store(Request $request)
    {
        $request->validate([
            'cart_id'        => ['required', 'exists:carts,id'],
            'days'           => ['required', 'integer', 'min:1'],
            'start_date'     => ['required'],
            'end_date'       => ['required'],
            'rent_unit'      => ['required', 'string'],
            'price_per_unit' => ['required', 'numeric'],
        ]);

        $cart = Cart::with('car')->findOrFail($request->cart_id);
        $car  = $cart->car;

        if ($car->approval_status !== 'approved' || ! $car->is_available) {
            return redirect()->back()->with('error', 'This car is not available right now.');
        }

        if ($car->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot rent your own car!');
        }

        $rental = app(RentalAutoAcceptService::class)->createRental($car, [
            'user_id'     => Auth::id(),
            'car_id'      => $car->id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'days'        => $request->days,
            'rent_unit'   => $request->rent_unit,
            'total_price' => $request->days * $request->price_per_unit,
        ]);

        $message = match ($rental->status) {
            'accepted' => 'Rental request auto-accepted!',
            'denied' => 'Rental request denied because the schedule is unavailable.',
            default => 'Rental request sent!',
        };
        
        return redirect('/garage/my-rental')
            ->with($rental->status === 'denied' ? 'error' : 'success', $message);
    }

    // SHOW PRE-ORDERS (OWNER VIEW)
    public function showPreOrders($id)
    {
        $car = Car::findOrFail($id);

        $preOrders = Rental::where('car_id', $id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('garage.my_listings.pre-order', compact('car', 'preOrders'));
    }

    public function notifications()
    {
        $pendingOrders = Rental::query()
            ->selectRaw('car_id, COUNT(*) as pending_orders_count')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('status', 'pending')
            ->groupBy('car_id')
            ->get();

        return response()->json([
            'total_pending_orders' => $pendingOrders->sum('pending_orders_count'),
            'cars' => $pendingOrders->map(fn ($order) => [
                'id' => $order->car_id,
                'pending_orders_count' => $order->pending_orders_count,
            ]),
        ]);
    }

    // ACCEPT RENTAL
    public function accept(Request $request, $id)
    {
        $rental = Rental::with('car')->findOrFail($id);

        if ($rental->car->user_id !== Auth::id()) {
            abort(403);
        }

        $autoAccept = app(RentalAutoAcceptService::class);

        if (! $autoAccept->canAccept($rental->car, $rental->start_date, $rental->end_date, $rental->id)) {
            $rental->update(array_merge([
                'status' => 'denied',
            ], $autoAccept->snapshot($rental->car)));

            app(RentalNotificationService::class)->denied($rental, 'The schedule is unavailable or already expired.');

            $message = 'Rental request denied because the schedule is unavailable.';

            if ($request->expectsJson()) {
                session()->flash('error', $message);

                return response()->json(['redirect' => url()->previous()], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        // start_date and end_date are already stored from the renter's form; just flip the status
        $rental->update(array_merge([
            'status'            => 'accepted',
        ], $autoAccept->snapshot($rental->car)));

        app(RentalNotificationService::class)->accepted($rental);

        if ($request->expectsJson()) {
            session()->flash('success', 'Rental accepted successfully.');

            return response()->json(['redirect' => route('garage.my-listing')]);
        }

        return redirect()->route('garage.my-listing')->with('success', 'Rental accepted successfully.');
    }

    // DENY RENTAL
    public function deny(Request $request, $id)
    {
        $rental = Rental::with('car')->findOrFail($id);

        if ($rental->car->user_id !== Auth::id()) {
            abort(403);
        }

        $rental->update([
            'status'            => 'denied',
            'snap_brand'        => $rental->car->brand,
            'snap_model'        => $rental->car->model,
            'snap_car_image'    => $rental->car->car_image,
            'snap_price'        => $rental->car->price,
            'snap_rent_unit'    => $rental->car->rent_unit,
            'snap_fuel_type'    => $rental->car->fuel_type,
            'snap_transmission' => $rental->car->transmission,
            'snap_date_owned'   => $rental->car->date_owned,
        ]);

        app(RentalNotificationService::class)->denied($rental, 'The owner denied the request.');

        if ($request->expectsJson()) {
            session()->flash('success', 'Rental request denied.');

            return response()->json(['redirect' => url()->previous()]);
        }

        return redirect()->back()->with('success', 'Rental request denied.');
    }

    // USER RENTALS
    public function index()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->where('hidden_by_renter', false)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere(function ($query) {
                        $query->where('status', 'accepted')
                            ->where('end_date', '>=', now());
                    });
            })
            ->latest()
            ->get();

        return view('garage.my_rentals.my-rental', compact('rentals'));
    }

    public function history()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->where('hidden_by_renter', false)
            ->where(function ($query) {
                $query->where('status', 'denied')
                    ->orWhere(function ($query) {
                        $query->where('status', 'accepted')
                            ->where('end_date', '<', now());
                    });
            })
            ->latest()
            ->get();

        return view('garage.my_rentals.history', compact('rentals'));
    }

    public function myStatuses()
    {
        $rentals = Rental::with('car.user')
            ->where('user_id', Auth::id())
            ->where('hidden_by_renter', false)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere(function ($query) {
                        $query->where('status', 'accepted')
                            ->where('end_date', '>=', now());
                    });
            })
            ->latest()
            ->get();

        return response()->json([
            'rentals' => $rentals->map(fn ($rental) => [
                'id' => $rental->id,
                'status_key' => $this->rentalStatusKey($rental),
                'html' => view('garage.my_rentals.cards', compact('rental'))->render(),
            ]),
        ]);
    }

    private function rentalStatusKey(Rental $rental)
    {
        if ($rental->status === 'accepted') {
            return now()->gt($rental->end_date) ? 'completed' : 'active';
        }

        return $rental->status;
    }

    // CANCEL RENTAL
    public function cancel($id)
    {
        $rental = Rental::findOrFail($id);

        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        if ($rental->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending rentals can be cancelled.');
        }

        app(RentalNotificationService::class)->cancelledByRenter($rental);

        $rental->delete();

        return redirect()->back()->with('success', 'Rental request cancelled.');
    }

    public function hideForRenter($id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $rental->update(['hidden_by_renter' => true]);

        return redirect()->back()->with('success', 'Record removed.');
    }

    public function hideForOwner($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->update(['hidden_by_owner' => true]);

        return redirect()->back()->with('success', 'Record removed.');
    }

    public function toggleAutoAccept($id)
    {
        $car = Car::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    
        $autoAccept = app(RentalAutoAcceptService::class);
        $priority = $autoAccept->normalizePriority(request('auto_accept_priority', $car->auto_accept_priority));
        $enabled = request()->has('auto_accept')
            ? request()->boolean('auto_accept')
            : ! $car->auto_accept;

        $car->update([
            'auto_accept' => $enabled,
            'auto_accept_priority' => $priority,
        ]);

        if ($enabled) {
            $autoAccept->processPending($car, $priority);
        }

        if (request()->expectsJson()) {
            session()->flash('success', 'Auto-accept setting updated!');

            return response()->json([
                'redirect' => url()->previous(),
                'auto_accept' => $car->fresh()->auto_accept
            ]);
        }

        return redirect()->back()->with('success', 'Auto-accept setting updated!');
    }

}
