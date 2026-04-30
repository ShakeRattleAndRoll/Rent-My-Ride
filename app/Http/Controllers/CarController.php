<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{

    public function create()
    {
        return view('post.main'); 
    }

    // get all of car database
    public function index()
    {
        $cars = Car::all(); 

        return view('available_cars.main', ['cars'=>$cars]);
    }

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
            'description'  => ['nullable','string'],
        ]);

        if ($request->hasFile('car_image')) {
            $attributes['car_image'] = $request->file('car_image')->store('car_photos', 'public');
        }

        $attributes['user_id'] = Auth::id();

        Car::create($attributes);

        return redirect()->back()->with( 'success', 'Car Added Successfully!');
    }

    public function my_listings()
    {
        $myCars = Car::where('user_id', Auth::id())->get();

        return view('garage.my-listing', ['listings' => $myCars]);
    }

    
}