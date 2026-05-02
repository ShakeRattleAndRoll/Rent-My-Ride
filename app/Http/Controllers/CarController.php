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
        return view('garage.post_a_car.post-a-car'); 
    }

    // GET ALL CARS
    public function index()
    {
        $cars = Car::latest()->get();
        return view('available_cars.main', ['cars' => $cars]);
    }

    // Connected sa available cars na search filter wala pani na human 
    // public function index(Request $request)
    // {
    //     $query = Car::query();

    //     if ($request->filled('search')) {
    //         $searchTerm = $request->search;
            
    //         $query->where(function($q) use ($searchTerm) {
    //             $q->where('brand', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('model', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('category', 'like', '%' . $searchTerm . '%');
    //         });
    //     }

    //     $cars = $query->latest()->get();

    //     return view('cars.index', compact('cars'));
    // }

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
            'rent_unit'    => ['required', 'string'],
            'transmission' => ['required'],
            'fuel_type'    => ['required'],
            'description'  => ['nullable', 'string'],
        ]);

        if ($request->hasFile('car_image')) {
            $attributes['car_image'] = $request->file('car_image')->store('car_photos', 'public');
        }

        $attributes['user_id'] = Auth::id();

        Car::create($attributes);

        return redirect('/garage/my-listing')->with('success', 'Car Added Successfully!');
    }

    // MY LISTINGS
    public function my_listings()
    {
        $myCars = Car::where('user_id', Auth::id())
            ->with('rentals')
            ->withCount(['rentals as pending_orders_count' => function($query) {
                $query->where('status', 'pending');
            }])
            ->latest()
            ->get();

        return view('garage.my_listings.my-listing', ['listings' => $myCars]);
    }

    // EDIT POST
    public function edit($id)
    {
        $car = Car::findOrFail($id);

        return view('garage.my_listings.edit-post', compact('car'));
    }

    // DETAILS PAGE
    public function details($id)
    {
        $car = Car::with('user')->findOrFail($id);

        $rentals = Rental::where('car_id', $id)
            ->with('user', 'car')
            ->latest()
            ->get();

        return view('garage.my_listings.details', compact('car', 'rentals'));
    }

    // DELETE CAR Only owner can delete
    public function destroy($id)
    {
        $car = Car::where('id', $id)
                  ->where('user_id', Auth::id()) 
                  ->firstOrFail();

        $car->delete();

        return redirect()->back()->with('success', 'Car deleted successfully!');
    }

    // UPDATE CAR
    public function update(Request $request, $id)
    {
        $car = Car::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

        $attributes = $request->validate([
            'car_image'    => ['nullable', 'image'],
            'date_owned'   => ['required', 'date'],
            'brand'        => ['required', 'string'],
            'model'        => ['required', 'string'],
            'price'        => ['required', 'numeric'],
            'rent_unit'    => ['required', 'string'],
            'transmission' => ['required'],
            'fuel_type'    => ['required'],
            'description'  => ['nullable', 'string'],
        ]);

        // If new image uploaded
        if ($request->hasFile('car_image')) {
            $attributes['car_image'] = $request->file('car_image')->store('car_photos', 'public');
        }

        $car->update($attributes);

        return redirect('/garage/my-listing')->with('feedback', 'Car updated successfully!');
    }
    
    public function show(Car $car)
    {
        return view('available_cars.cars_details', compact('car'));
    }
}