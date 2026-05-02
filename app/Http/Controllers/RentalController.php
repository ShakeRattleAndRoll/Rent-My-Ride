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

    // ACCEPT RENTAL
    public function accept($id)
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
        ]);

        return redirect()->route('garage.my-listing')->with('success', 'Rental accepted successfully.');
    }

    // DENY RENTAL
    public function deny($id)
    {
        $rental = Rental::with('car')->findOrFail($id);

        if ($rental->car->user_id !== Auth::id()) {
            abort(403);
        }

        $rental->update(['status' => 'denied']);

        return redirect()->back()->with('success', 'Rental request denied.');
    }

    // USER RENTALS
    public function index()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('garage.my_rentals.my-rental', compact('rentals'));
    }

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

    
}