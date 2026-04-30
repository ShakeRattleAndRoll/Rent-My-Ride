<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{

    public function create()
    {
        return view('post.main'); 
    }

    // GET ALL CARS
    public function index()
    {
        $cars = Car::all(); 

        return view('available_cars.main', ['cars' => $cars]);
    }

    // STORE CAR
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $attributes = $request->validate([
            'car_image'    => ['required', 'image'],
            'date_owned'   => ['required', 'date'],
            'brand'        => ['required', 'string'],
            'model'        => ['required', 'string'],
            'price'        => ['required', 'numeric'],
            'rent_period'  => ['required', 'string'],
            'transmission' => ['required'],
            'fuel_type'    => ['required'],
            'description'  => ['nullable', 'string'],
        ]);

        if ($request->hasFile('car_image')) {
            $attributes['car_image'] = $request->file('car_image')->store('car_photos', 'public');
        }

        $attributes['user_id'] = Auth::id();

        Car::create($attributes);

        return redirect()->back()->with('success', 'Car Added Successfully!');
    }

    // MY LISTINGS
    public function my_listings()
    {
        $myCars = Car::where('user_id', Auth::id())->with('rentals')->withCount(['rentals as pending_orders_count' => function($query) {
            $query->where('status', 'pending');
        }])->get();

        return view('garage.my-listing', ['listings' => $myCars]);
    }

    // EDIT POST
    public function edit($id)
    {
        $car = Car::findOrFail($id);

        return view('garage.edit-post', compact('car'));
    }

    // DETAILS PAGE
    public function details($id)
    {
        $car = Car::findOrFail($id);

        $rentals = Rental::where('car_id', $id)
            ->with('user', 'car')
            ->latest()
            ->get();

        return view('garage.details', compact('car', 'rentals'));
    }

    // DELETE CAR
    public function destroy($id)
    {
        $car = Car::where('id', $id)
                  ->where('user_id', Auth::id()) // safety: only owner can delete
                  ->firstOrFail();

        $car->delete();

        return redirect()->back()->with('success', 'Car deleted successfully!');
    }
}