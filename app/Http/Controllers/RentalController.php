<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Cart;
use App\Models\Car;
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

        if ($car->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot rent your own car!');
        }

        Rental::create([
            'user_id'     => Auth::id(),
            'car_id'      => $car->id,
            'status'      => 'pending',
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'days'        => $request->days,
            'rent_unit'   => $request->rent_unit,
            'total_price' => $request->days * $request->price_per_unit,
        ]);

        return redirect('/garage/my-rental')
            ->with('success', 'Rental request sent!');
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

        $unitToSeconds = [
            'Hour'  => 3600,
            'Day'   => 86400,
            'Week'  => 604800,
            'Month' => 2592000,
        ];

        $seconds = ($unitToSeconds[$rental->rent_unit] ?? 86400) * $rental->days;

        $rental->update([
            'status'     => 'accepted',
            'start_date' => now(),
            'end_date'   => now()->addSeconds($seconds),
            'snap_brand'        => $rental->car->brand,
            'snap_model'        => $rental->car->model,
            'snap_car_image'    => $rental->car->car_image,
            'snap_price'        => $rental->car->price,
            'snap_rent_unit'    => $rental->car->rent_unit,
            'snap_fuel_type'    => $rental->car->fuel_type,
            'snap_transmission' => $rental->car->transmission,
            'snap_date_owned'   => $rental->car->date_owned,
        ]);

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
            ->latest()
            ->get();

        return view('garage.my_rentals.my-rental', compact('rentals'));
    }

    public function myStatuses()
    {
        $rentals = Rental::with('car.user')
            ->where('user_id', Auth::id())
            ->where('hidden_by_renter', false)
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
}
