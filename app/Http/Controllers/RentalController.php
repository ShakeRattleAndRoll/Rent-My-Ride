<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    // CREATE RENTAL (USER REQUEST)
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);

        $car = Car::findOrFail($request->car_id);

        // prevent self-renting
        if ($car->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot rent your own car!');
        }

        Rental::create([
            'user_id' => Auth::id(),
            'car_id' => $request->car_id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ]);

        return redirect('/garage/my-rental')
            ->with('success', 'Car added to your rentals!');
    }

    // SHOW PRE-ORDERS (OWNER VIEW)
    public function showPreOrders($id)
    {
        $car = Car::findOrFail($id);

        $preOrders = Rental::where('car_id', $id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('garage.pre-order', compact('car', 'preOrders'));
    }

    public function manage($id)
    {
        $order = Rental::with('user', 'car')->findOrFail($id);

        if ($order->car->user_id !== Auth::id()) {
            abort(403);
        }

        return view('garage.manage', compact('order')); 
    }

    // ACCEPT RENTAL
    public function accept($id)
    {
        $rental = Rental::with('car')->findOrFail($id);

        // security check
        if ($rental->car->user_id !== Auth::id()) {
            abort(403);
        }

        $rental->update([
            'status' => 'accepted'
        ]);

        return redirect()->back()->with('success', 'Rental request accepted!');
    }

    // DENY RENTAL
    public function deny($id)
    {
        $rental = Rental::with('car')->findOrFail($id);

        // security check
        if ($rental->car->user_id !== Auth::id()) {
            abort(403);
        }

        $rental->update([
            'status' => 'denied'
        ]);

        return redirect()->back()->with('success', 'Rental request denied.');
    }

    // USER RENTALS
    public function index()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('garage.my-rental', compact('rentals'));
    }

    // CANCEL RENTAL
    public function cancel($id)
    {
        $rental = Rental::findOrFail($id);

        // security: only owner
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