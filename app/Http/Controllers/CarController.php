<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cart;
use App\Models\Rental;
use App\Models\UserRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{

    public function create()
    {
        return view('garage.post_a_car.post-a-car'); 
    }

    // GET ALL CARS and search fileter
    public function index(Request $request)
    {

        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');
        $models = Car::select('model')->distinct()->orderBy('model')->pluck('model');

        $query = Car::query();

        if (Auth::check()) {
            $query->whereNotIn('user_id', $this->blockedUserIdsFor(Auth::id()));
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('brand', 'LIKE', "%{$searchTerm}%")
                ->orWhere('model', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        $cars = $query->orderBy('created_at', 'desc')->paginate(20);

        $carts = Auth::check() ? Auth::user()->carts : collect();

        $pendingRequests = Auth::check()
            ? DB::table('rentals')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->pluck('car_id')
                ->toArray()
            : [];

        return view('available_cars.main', [
            'cars' => $cars,
            'carts' => $carts,
            'pendingRequests' => $pendingRequests,
            'brands' => $brands, 
            'models' => $models,    
        ]);
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
            'price'        => ['required', 'integer', 'min:1'],
            'rent_unit'    => ['required', 'string'],
            'transmission' => ['required'],
            'fuel_type'    => ['required'],
            'description'  => ['nullable', 'string'],
        ]);

        $attributes['price'] = (int) $attributes['price'];

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
            ->where('hidden_by_owner', false)
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

        DB::transaction(function () use ($car) {
            $pendingRentals = Rental::where('car_id', $car->id)
                ->where('status', 'pending')
                ->get();

            foreach ($pendingRentals as $rental) {
                $rental->update([
                    'status'            => 'denied',
                    'snap_brand'        => $car->brand,
                    'snap_model'        => $car->model,
                    'snap_car_image'    => $car->car_image,
                    'snap_price'        => $car->price,
                    'snap_rent_unit'    => $car->rent_unit,
                    'snap_fuel_type'    => $car->fuel_type,
                    'snap_transmission' => $car->transmission,
                    'snap_date_owned'   => $car->date_owned,
                ]);
            }

            Cart::where('car_id', $car->id)->delete();
            $car->delete();
        });

        if (request()->expectsJson()) {
            session()->flash('success', 'Car deleted successfully!');

            return response()->json(['redirect' => url()->previous()]);
        }

        return redirect()->back()->with('success', 'Car deleted successfully!');
    }

    // UPDATE CAR
    public function update(Request $request, $id)
    {

        $car = Car::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

        $attributes = $request->validate([
            'car_image'    => ['nullable', 'image', 'max:2048'],
            'date_owned'   => ['sometimes', 'required', 'date'],
            'brand'        => ['sometimes', 'required', 'string'],
            'model'        => ['sometimes', 'required', 'string'],
            'price'        => ['required', 'integer', 'min:1'],
            'rent_unit'    => ['required', 'string'],
            'transmission' => ['sometimes', 'required'],
            'fuel_type'    => ['sometimes', 'required'],
            'description'  => ['nullable', 'string'],
            'existing_image' => ['nullable', 'string'],
        ]);

        $attributes['price'] = (int) $attributes['price'];

        // If new image uploaded
        if ($request->hasFile('car_image')) {
            $attributes['car_image'] = $request->file('car_image')->store('car_photos', 'public');
        } else {
            $attributes['car_image'] = $request->input('existing_image') ?: $car->car_image;
        }

        DB::transaction(function () use ($car, $attributes) {
            $pendingRentals = Rental::where('car_id', $car->id)
                ->where('status', 'pending')
                ->get();

            foreach ($pendingRentals as $rental) {
                $rental->update([
                    'status'            => 'denied',
                    'snap_brand'        => $car->brand,      
                    'snap_model'        => $car->model,
                    'snap_car_image'    => $car->car_image,
                    'snap_price'        => $car->price,
                    'snap_rent_unit'    => $car->rent_unit,
                    'snap_fuel_type'    => $car->fuel_type,
                    'snap_transmission' => $car->transmission,
                    'snap_date_owned'   => $car->date_owned,
                ]);
            }

            $car->update($attributes);
        });

        return redirect('/garage/my-listing')->with('feedback', 'Car updated successfully!');
    }
    
    public function show(Car $car)
    {
        if (Auth::check() && Auth::id() !== $car->user_id && $this->hasBlockRelationWith($car->user_id)) {
            return response()
                ->view('profile.blocked', ['user' => $car->user], 403);
        }

        return view('available_cars.cars_details', compact('car'));
    }

    private function blockedUserIdsFor($userId)
    {
        return UserRelation::where('type', 'block')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('target_id', $userId);
            })
            ->get()
            ->map(fn ($relation) => $relation->user_id == $userId
                ? $relation->target_id
                : $relation->user_id)
            ->unique()
            ->values();
    }

    private function hasBlockRelationWith($targetId)
    {
        return UserRelation::where('type', 'block')
            ->where(function ($query) use ($targetId) {
                $query->where(function ($q) use ($targetId) {
                    $q->where('user_id', Auth::id())
                        ->where('target_id', $targetId);
                })->orWhere(function ($q) use ($targetId) {
                    $q->where('user_id', $targetId)
                        ->where('target_id', Auth::id());
                });
            })
            ->exists();
    }
}
