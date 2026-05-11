<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Models\Car;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Livewire\Message\ChatManager;

// Download og livewire para walay loading copy ning nasa baba sa terminal [ctrl + `]
// composer require livewire/livewire

// cloudflared tunnel --url http://localhost:8000 

Route::get('/', function () {
    if (Auth::check()) {
        $featuredCars = Car::with('user')->latest()->take(3)->get();
        $homeStats = [
            'cars' => Car::count(),
            'owners' => Car::distinct('user_id')->count('user_id'),
        ];

        return view('home.main', compact('featuredCars', 'homeStats'));
    }
    return redirect()->route('login');
});

// Route for login
Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);

// Route for register
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store']);

// Logout
Route::post('/logout', [AuthController::class, 'logout']);

// Post a car route
Route::get('/garage/post-car', [CarController::class, 'create']);
Route::post('/cars', [CarController::class, 'store'])->middleware('auth');

// Route for available cars page
Route::get('/available', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Route for profile page
Route::get('/profile', [AuthController::class, 'profile'])->name('profile.main')->middleware('auth');

// Route for edit profile page
Route::get('/profile/edit', [AuthController::class, 'edit'])->name('profile.edit')->middleware('auth');

// Route for update profile
Route::patch('/profile/update', [AuthController::class, 'update'])->name('profile.update')->middleware('auth');

// Route for user profile view
Route::get('/profile/{id}', [AuthController::class, 'show'])->name('user.profile');

// Message search users
Route::get('/messages/search-users', [MessageController::class, 'searchUsers'])->middleware('auth');
Route::get('/messages/notifications', [MessageController::class, 'notifications'])->name('messages.notifications')->middleware('auth');
Route::get('/messages/thread/{receiverId}', [MessageController::class, 'thread'])->name('messages.thread')->middleware('auth');
// Route for message page
Route::get('/messages/{receiverId?}', [MessageController::class, 'index'])->name('messages.index')->middleware('auth');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');
    // Mute or Block Message
Route::post('/messages/mute/{targetId}', [MessageController::class, 'toggleMute'])->name('messages.mute')->middleware('auth');
Route::post('/messages/block/{targetId}', [MessageController::class, 'toggleBlock'])->name('messages.block')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.delete-all');   
});

// Routes for garage listing
Route::get('/garage', function () {
    return redirect('/garage/my-listing');
});

Route::middleware('auth')->group(function () {

    Route::get('/garage/my-listing', [CarController::class, 'my_listings'])->name('garage.my-listing');

    // Edit and update listing routes
    Route::get('/garage/edit/{id}', [CarController::class, 'edit']);
    Route::patch('/garage/update/{id}', [CarController::class, 'update']);
    Route::patch('/car/{id}/toggle-auto-accept', [RentalController::class, 'toggleAutoAccept'])->name('car.toggle-auto-accept');

    // Route for car details page
    Route::get('/garage/details/{id}', [CarController::class, 'details']);

    // Route for delete listing
    Route::delete('/garage/delete/{id}', [CarController::class, 'destroy']);

});

//Route for garage rental
Route::post('/rent', [RentalController::class, 'store'])->middleware('auth');
Route::get('/rentals/notifications', [RentalController::class, 'notifications'])->name('rentals.notifications')->middleware('auth');
Route::get('/rentals/my-statuses', [RentalController::class, 'myStatuses'])->name('rentals.my-statuses')->middleware('auth');
Route::get('/garage/my-rental', [RentalController::class, 'index'])->middleware('auth');
Route::get('/car/pre-order/{id}', [RentalController::class, 'showPreOrders'])->middleware('auth');
Route::post('/rental/{id}/accept', [RentalController::class, 'accept'])->middleware('auth');
Route::post('/rental/{id}/deny', [RentalController::class, 'deny'])->middleware('auth');
Route::patch('/garage/rental/{id}/cancel', [RentalController::class, 'cancel'])->middleware('auth');
Route::patch('/garage/rental/{id}/hide', [RentalController::class, 'hideForRenter'])->middleware('auth');
Route::patch('/garage/rental/{id}/hide-owner', [RentalController::class, 'hideForOwner'])->middleware('auth');

//Route for garage cart
Route::get('/garage/my-cart', function () {
    $cartItems = Cart::where('user_id', Auth::id())
        ->with('car')
        ->latest()
        ->get();

    return view('garage.my_cart.my-cart', ['carts' => $cartItems]);  
})->middleware('auth');

Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add')->middleware('auth');
Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove')->middleware('auth');
Route::post('/cart/checkout/{id}', [CartController::class, 'checkout'])->middleware('auth');

Route::post('/rental/{id}/request', [RentalController::class, 'requestRental'])->middleware('auth');
Route::get('/available-cars', [CarController::class, 'index'])->name('cars.index');
