<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $car = Car::findOrFail($request->car_id);

        if ($car->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot add your own car to the cart!');
        }

        $exists = Cart::where('user_id', Auth::id())->where('car_id', $car->id)->exists();
        
        if (!$exists) {
            Cart::create([
                'user_id' => Auth::id(),
                'car_id' => $car->id
            ]);
        }

        return redirect()->back()->with('success', 'Car added to your cart!');
    }
    
    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
