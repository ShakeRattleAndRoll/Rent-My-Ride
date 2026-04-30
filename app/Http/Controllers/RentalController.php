<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;

class RentalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);

        $car = \App\Models\Car::find($request->car_id);

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

        return redirect('/garage/my-rental')->with('Success', 'Car added to your rentals!');
    }

    //Pre-order function
    public function showPreOrders($id)
    {
        $car = \App\Models\Car::findOrFail($id);

        $preOrders = Rental::where('car_id', $id)
                        ->where('status', 'pending')
                        ->with('user')
                        ->get();

        return view('garage.pre-order', compact('car', 'preOrders'));
    }

    // If owner accepts
    public function accept($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'You have accepted the rental request!');
    }

    //if owner denied
    public function deny($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->update(['status' => 'denied']);

        return redirect()->back()->with('success', 'Rental request denied.');
    }

    // return sa rental
    public function index()
    {
        $rentals = Rental::with('car')->where('user_id', Auth::id())->get();

        return view('garage.my-rental', compact('rentals'));
    }

    //Cancel Pre-order
    public function cancel($id)
{
    $rental = Rental::findOrFail($id);

    // Security: make sure user owns the rental
    if ($rental->user_id !== Auth::id()) {
        abort(403);
    }

    // Only allow cancel if still pending
    if ($rental->status !== 'pending') {
        return redirect()->back()->with('error', 'Only pending rentals can be cancelled.');
    }

    $rental->delete();
    return redirect()->back()->with('success', 'Rental request cancelled.');
}
}


