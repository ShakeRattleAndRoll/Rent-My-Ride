<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Models\Car;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return view('home.main');
    }
    return redirect()->route('login');
});

// Route for login
Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);

// Route for register
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store']);

// Post a car route
Route::get('/post-car', [CarController::class, 'create']);
Route::post('/cars', [CarController::class, 'store'])->middleware('auth');

// Route for available cars page
Route::get('/available', [CarController::class, 'index']);

// Route for profile page
Route::get('/profile', function () {
    return view('profile.main'); 
});

// Route for edit profile page
Route::get('/profile/edit', function () {
    return view('profile.edit');
});

// Route for update profile
Route::patch('/profile/update', [AuthController::class, 'update']);

// Route for message page
Route::get('/messages', function () {
    return view('message.message');
});

// Routes for garage listing
Route::get('/garage', function () {
    return redirect('/garage/my-listing');
});

Route::get('/garage/my-listing', [CarController::class, 'my_listings'])->middleware('auth');

// Route for garage pre-order feature
Route::get('/car/pre-order/{id}', function ($id) {
    $car = Car::findOrFail($id);
    return view('garage.pre-order', compact('car'));
})->middleware('auth');

//Route for garage rental
Route::post('/rent', [RentalController::class, 'store'])->middleware('auth');
Route::get('/garage/my-rental', [RentalController::class, 'index'])->middleware('auth');
Route::get('/car/pre-order/{id}', [RentalController::class, 'showPreOrders'])->middleware('auth');
Route::post('/rental/{id}/accept', [RentalController::class, 'accept'])->middleware('auth');
Route::post('/rental/{id}/deny', [RentalController::class, 'deny'])->middleware('auth');

//Route for garage cart
Route::get('/garage/my-cart', function () {
    $cartItems = Cart::where('user_id', Auth::id())->with('car')->get();
    return view('garage.my-cart', ['carts' => $cartItems]);
})->middleware('auth');

Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add')->middleware('auth');
Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove')->middleware('auth');
Route::post('/cart/checkout/{id}', [CartController::class, 'checkout'])->middleware('auth');

// Logout
Route::post('/logout', [AuthController::class, 'logout']);