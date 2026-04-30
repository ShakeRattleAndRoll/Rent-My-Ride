<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Not done para ni sa katong e click ang shop it e save sa my-rental

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
            'start_date' => now(),        
            'end_date' => now()->addDays(7),
        ]);

        return redirect('/garage/my-rental')->with('Success', 'Car added to your rentals!');
    }

    public function index()
    {
        $rentals = Rental::with('car')->where('user_id', Auth::id())->get();

        return view('garage.my-rental', compact('rentals'));
    }
}


