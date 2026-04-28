<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;
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
Route::post('/cars', [CarController::class, 'store']);

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

// Routes for garage page
Route::get('/garage', function () {
    return redirect('/garage/my-listing');
});

Route::get('/garage/my-listing', function () {
    return view('garage.my-listing', ['listings' => []]);
});

Route::get('/garage/my-rental', function () {
    return view('garage.my-rental', ['rentals' => []]);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout']);

// Route for my-rental
Route::post('/rent', [RentalController::class, 'store'])->middleware('auth');
Route::get('/garage/my-rental', [RentalController::class, 'index']);