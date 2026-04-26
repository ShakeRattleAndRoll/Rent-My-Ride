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

        Rental::create([
            'user_id' => Auth::id(), 
            'car_id' => $request->car_id,
            'start_date' => now(),        
            'end_date' => now()->addDays(7),
        ]);

        return redirect('/garage/my-rental')->with('feedback', 'Car added to your rentals!');
    }
    


}


