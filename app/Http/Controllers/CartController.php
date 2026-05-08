<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cart;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $car = Car::findOrFail($request->car_id);

        if ($car->user_id === Auth::id()) {
            if ($request->expectsJson()) {
                session()->flash('error', 'You cannot add your own car to the cart!');

                return response()->json(['redirect' => url()->previous()], 422);
            }

            return redirect()->back()->with('error', 'You cannot add your own car to the cart!');
        }

        $exists = Cart::where('user_id', Auth::id())->where('car_id', $car->id)->exists();
        
        if ($exists) {
            if ($request->expectsJson()) {
                session()->flash('error', 'This car is already in your cart!');

                return response()->json(['redirect' => url()->previous()], 422);
            }

            return redirect()->back()->with('error', 'This car is already in your cart!');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id
        ]);

        if ($request->expectsJson()) {
            session()->flash('success', 'Car added to your cart!');

            return response()->json(['redirect' => url('/garage/my-cart')]);
        }

        return redirect('/garage/my-cart')->with('success', 'Car added to your cart!');
    }
    
    public function checkout(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        Rental::create([
            'user_id'        => Auth::id(),
            'car_id'         => $cartItem->car_id,
            'status'         => 'pending',
            'days'           => $request->days,
            'rent_unit'      => $request->rent_unit,
            'total_price'    => $request->price_per_unit * $request->days,
            'start_date'     => null,
            'end_date'       => null,
        ]);

        $cartItem->delete();

        if ($request->expectsJson()) {
            session()->flash('success', 'Request sent! Wait for owner approval.');

            return response()->json(['redirect' => url('/garage/my-rental')]);
        }

        return redirect('/garage/my-rental')->with('success', 'Request sent! Wait for owner approval.');
    }

    public function destroy(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        if ($request->expectsJson()) {
            session()->flash('success', 'Item removed from cart.');

            return response()->json(['redirect' => url()->previous()]);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
