<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RentalController;
use App\Models\Car;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Local setup notes:
// composer require livewire/livewire
// cloudflared tunnel --url http://localhost:8000

// Home page
Route::get('/', function () {
    $featuredCars = Car::publiclyVisible()->with('user')->latest()->take(3)->get();
    $homeStats = [
        'cars' => Car::publiclyVisible()->count(),
        'owners' => Car::publiclyVisible()->distinct('user_id')->count('user_id'),
    ];

    return view('home.main', compact('featuredCars', 'homeStats'));
});

// Authentication: login and password reset
Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email')->middleware('guest');
Route::get('/forgot-password/verify-code', [AuthController::class, 'showPasswordResetCodeForm'])->name('password.verify-code')->middleware('guest');
Route::post('/forgot-password/verify-code', [AuthController::class, 'verifyPasswordResetCode'])->name('password.verify-code.submit')->middleware('guest');
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update')->middleware('guest');

// Authentication: registration and email verification
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store']);
Route::get('/register/verify-email', [AuthController::class, 'showEmailCodeForm'])->name('register.verify-email')->middleware('guest');
Route::post('/register/verify-email', [AuthController::class, 'verifyEmailCode'])->name('register.verify-email.submit')->middleware('guest');
Route::post('/register/resend-code', [AuthController::class, 'resendEmailCode'])->name('register.resend-code')->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout']);

// Public car browsing
Route::get('/available', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Car posting
Route::get('/garage/post-car', [CarController::class, 'create'])->middleware('auth');
Route::post('/cars', [CarController::class, 'store'])->middleware('auth');

// Profile pages and account updates
Route::get('/profile', [AuthController::class, 'profile'])->name('profile.main')->middleware('auth');
Route::get('/profile/edit', [AuthController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::patch('/profile/update', [AuthController::class, 'update'])->name('profile.update')->middleware('auth');
Route::patch('/profile/password/send-code', [AuthController::class, 'sendProfilePasswordCode'])->name('profile.password.send-code')->middleware('auth');
Route::get('/profile/password/verify-code', [AuthController::class, 'showProfilePasswordCodeForm'])->name('profile.password.verify-code')->middleware('auth');
Route::post('/profile/password/verify-code', [AuthController::class, 'verifyProfilePasswordCode'])->name('profile.password.verify-code.submit')->middleware('auth');
Route::get('/profile/password/reset', [AuthController::class, 'showProfilePasswordResetForm'])->name('profile.password.reset')->middleware('auth');
Route::post('/profile/password/reset', [AuthController::class, 'updateProfilePassword'])->name('profile.password.update')->middleware('auth');
Route::get('/profile/email/verify-code', [AuthController::class, 'showProfileEmailCodeForm'])->name('profile.email.verify-code')->middleware('auth');
Route::post('/profile/email/verify-code', [AuthController::class, 'verifyProfileEmailCode'])->name('profile.email.verify-code.submit')->middleware('auth');

// Route for user profile view
Route::get('/profile/{id}', [AuthController::class, 'show'])->name('user.profile');

// Messaging
Route::get('/messages/search-users', [MessageController::class, 'searchUsers'])->middleware('auth');
Route::get('/messages/notifications', [MessageController::class, 'notifications'])->name('messages.notifications')->middleware('auth');
Route::get('/messages/thread/{receiverId}', [MessageController::class, 'thread'])->name('messages.thread')->middleware('auth');
Route::get('/messages/{receiverId?}', [MessageController::class, 'index'])->name('messages.index')->middleware('auth');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');

// Message privacy controls
Route::post('/messages/mute/{targetId}', [MessageController::class, 'toggleMute'])->name('messages.mute')->middleware('auth');
Route::post('/messages/block/{targetId}', [MessageController::class, 'toggleBlock'])->name('messages.block')->middleware('auth');

// Admin dashboard and notifications
Route::middleware('auth')->group(function () {
    // Admin car review
    Route::get('/admin/cars/pending', [AdminController::class, 'pendingCars'])->name('admin.cars.pending');
    Route::get('/admin/cars/pending/items', [AdminController::class, 'pendingCarItems'])->name('admin.cars.pending.items');
    Route::patch('/admin/cars/{car}/approve', [AdminController::class, 'approveCar'])->name('admin.cars.approve');
    Route::delete('/admin/cars/{car}/deny', [AdminController::class, 'denyCar'])->name('admin.cars.deny');
    Route::get('/admin/cars/posted', [AdminController::class, 'postedCars'])->name('admin.cars.posted');
    Route::delete('/admin/cars/{car}', [AdminController::class, 'destroyCar'])->name('admin.cars.destroy');

    // Admin user management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    // User notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
    Route::get('/notifications/items', [NotificationController::class, 'items'])->name('notifications.items');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::delete('/notifications-delete-all', [NotificationController::class, 'destroyAll'])->name('notifications.delete-all');
});

// Garage landing
Route::get('/garage', function () {
    return redirect('/garage/my-listing');
});

// Garage listings
Route::middleware('auth')->group(function () {
    Route::get('/garage/my-listing', [CarController::class, 'my_listings'])->name('garage.my-listing');
    Route::get('/garage/my-listing/items', [CarController::class, 'myListingItems'])->name('garage.my-listing.items');

    // Edit and update listings
    Route::get('/garage/edit/{id}', [CarController::class, 'edit']);
    Route::patch('/garage/update/{id}', [CarController::class, 'update']);
    Route::patch('/garage/availability/{id}', [CarController::class, 'toggleAvailability'])->name('garage.availability');
    Route::patch('/car/{id}/toggle-auto-accept', [RentalController::class, 'toggleAutoAccept'])->name('car.toggle-auto-accept');

    // Listing details and deletion
    Route::get('/garage/details/{id}', [CarController::class, 'details']);
    Route::delete('/garage/delete/{id}', [CarController::class, 'destroy']);
});

// Rentals
Route::post('/rent', [RentalController::class, 'store'])->middleware('auth');
Route::get('/rentals/notifications', [RentalController::class, 'notifications'])->name('rentals.notifications')->middleware('auth');
Route::get('/rentals/my-statuses', [RentalController::class, 'myStatuses'])->name('rentals.my-statuses')->middleware('auth');
Route::get('/garage/my-rental', [RentalController::class, 'index'])->middleware('auth');
Route::get('/garage/my-rental/history', [RentalController::class, 'history'])->middleware('auth');
Route::get('/car/pre-order/{id}', [RentalController::class, 'showPreOrders'])->middleware('auth');
Route::get('/car/pre-order/{id}/items', [RentalController::class, 'preOrderItems'])->name('pre-orders.items')->middleware('auth');
Route::post('/rental/{id}/accept', [RentalController::class, 'accept'])->middleware('auth');
Route::post('/rental/{id}/deny', [RentalController::class, 'deny'])->middleware('auth');
Route::patch('/garage/rental/{id}/cancel', [RentalController::class, 'cancel'])->middleware('auth');
Route::patch('/garage/rental/{id}/hide', [RentalController::class, 'hideForRenter'])->middleware('auth');
Route::patch('/garage/rental/{id}/hide-owner', [RentalController::class, 'hideForOwner'])->middleware('auth');

// Garage cart
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
