<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

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

        Car::create($attributes);

        return redirect()->back()->with( 'feedback', 'Car Added Successfully!');
    }
}