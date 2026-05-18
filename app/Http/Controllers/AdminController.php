<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cart;
use App\Models\Rental;
use App\Models\User;
use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function pendingCars()
    {
        $this->authorizeAdmin();

        $pendingCars = $this->pendingCarsQuery()
            ->paginate(12)
            ->withPath(route('admin.cars.pending'));

        return view('admin.pending-cars', compact('pendingCars'));
    }

    // Return pending cars as JSON for AJAX updates
    public function pendingCarItems()
    {
        $this->authorizeAdmin();

        $pendingCars = $this->pendingCarsQuery()
            ->paginate(12)
            ->withPath(route('admin.cars.pending'));

        return response()->json([
            'html' => view('admin.partials.pending-cars-list', compact('pendingCars'))->render(),
            'count' => $pendingCars->total(),
        ]);
    }

    // Approve car post and notify owner
    public function approveCar(Car $car)
    {
        $this->authorizeAdmin();

        if ($car->approval_status !== 'pending') {
            return redirect()
                ->route('admin.cars.pending')
                ->with('error', 'This car post is already reviewed.');
        }

        $car->approve(Auth::user());

        app(RentalNotificationService::class)->carApproved($car);

        return redirect()
            ->route('admin.cars.pending')
            ->with('success', 'Car post approved.');
    }

    // Deny car post, notify owner, and delete post
    public function denyCar(Car $car)
    {
        $this->authorizeAdmin();

        if ($car->approval_status !== 'pending') {
            return redirect()
                ->route('admin.cars.pending')
                ->with('error', 'This car post is already reviewed.');
        }

        app(RentalNotificationService::class)->carDenied($car);

        $this->deleteCarPost($car);

        return redirect()
            ->route('admin.cars.pending')
            ->with('success', 'Car post denied and removed.');
    }

    // List users with search and pagination
    public function users(Request $request)
    {
        $this->authorizeAdmin();

        $users = User::withCount(['cars', 'rentals'])
            ->where('id', '!=', Auth::id())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('contact_number', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    // Delete user account with checks and notifications
    public function destroyUser(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === Auth::id() || $user->is_admin) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Admin accounts cannot be deleted here.');
        }

        if ($this->hasActiveOrUpcomingRentalForUser($user)) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'This user has an active or upcoming rental.');
        }

        $this->sendAccountTerminationEmail($user);

        DB::transaction(function () use ($user) {
            $ownedCarIds = Car::withTrashed()
                ->where('user_id', $user->id)
                ->pluck('id');

            Rental::where('user_id', $user->id)
                ->orWhereIn('car_id', $ownedCarIds)
                ->delete();

            Cart::where('user_id', $user->id)
                ->orWhereIn('car_id', $ownedCarIds)
                ->delete();

            $user->delete();
        });

        return redirect()
            ->route('admin.users')
            ->with('success', 'Account Deleted');
    }

    // List posted cars with search, filter, and pagination
    public function postedCars(Request $request)
    {
        $this->authorizeAdmin();

        $query = Car::with('user')
            ->withExists(['rentals as is_occupied' => function ($rentalQuery) {
                $rentalQuery->where('status', 'accepted')
                    ->where('start_date', '<=', now('Asia/Manila'))
                    ->where('end_date', '>=', now('Asia/Manila'));
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'LIKE', "%{$search}%")
                    ->orWhere('model', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $cars = $query->latest()->paginate(16)->withQueryString();

        return view('admin.posted-cars', compact('cars'));
    }

    public function destroyCar(Car $car)
    {
        $this->authorizeAdmin();

        if ($this->hasActiveOrUpcomingAcceptedRental($car)) {
            return redirect()
                ->route('admin.cars.posted')
                ->with('error', 'You cannot delete a car with an active or upcoming rental.');
        }

        app(RentalNotificationService::class)->carDeletedByAdmin($car);

        $this->deleteCarPost($car);

        return redirect()
            ->route('admin.cars.posted')
            ->with('success', 'Car post deleted.');
    }

    private function pendingCarsQuery()
    {
        return Car::with('user')
            ->where('approval_status', 'pending')
            ->latest();
    }

    private function deleteCarPost(Car $car): void
    {
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
    }

    private function hasActiveOrUpcomingRentalForUser(User $user): bool
    {
        return Rental::where('status', 'accepted')
            ->where('end_date', '>=', now('Asia/Manila'))
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('car', fn ($carQuery) => $carQuery->where('user_id', $user->id));
            })
            ->exists();
    }

    private function hasActiveOrUpcomingAcceptedRental(Car $car): bool
    {
        return Rental::where('car_id', $car->id)
            ->where('status', 'accepted')
            ->where('end_date', '>=', now('Asia/Manila'))
            ->exists();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    private function sendAccountTerminationEmail(User $user): void
    {
        Mail::raw(
            "Your account has been permanently terminated due to violations of our platform policies and guidelines. As a result, you no longer have access to Rent My Ride services, listings, or account features.\n\n" .
            "If you believe this action was made in error, you may contact our support team for further review.\n\n" .
            "Thank you for your understanding.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Rent My Ride account terminated');
            }
        );
    }
}
