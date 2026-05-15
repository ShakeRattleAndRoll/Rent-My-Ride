<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCarApprovalController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $pendingCars = $this->pendingCars();

        return view('admin.pending-cars', compact('pendingCars'));
    }

    public function items()
    {
        $this->authorizeAdmin();

        $pendingCars = $this->pendingCars();

        return response()->json([
            'html' => view('admin.partials.pending-cars-list', compact('pendingCars'))->render(),
            'count' => $pendingCars->total(),
        ]);
    }

    public function approve(Car $car)
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

    private function pendingCars()
    {
        return Car::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(12)
            ->withPath(route('admin.cars.pending'));
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }
}
