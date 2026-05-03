<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Car;


class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string|min:1',
        ], [
            'email.required'    => 'Email or username is required.',
            'password.required' => 'Password is required.',
        ]);

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->email,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'No account found with those credentials. Please register first.',
        ])->onlyInput('email');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'       => 'required|unique:users',
            'first_name'     => 'required',
            'middle_name'    => 'required',
            'last_name'      => 'required',
            'dob'            => 'required|date',
            'sex'            => 'required',
            'address'        => 'required',
            'email'          => 'required|email|regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/|unique:users',
            'contact_number' => 'required|regex:/^09[0-9]{9}$/',
            'password'       => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'username'       => $request->username,
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'dob'            => $request->dob,
            'sex'            => $request->sex,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function update(Request $request)
    {

        $user = User::find(Auth::id());

        $request->validate([
            'username'       => 'required|unique:users,username,' . $user->id,
            'first_name'     => 'required',
            'middle_name'    => 'required',
            'last_name'      => 'required',
            'dob'            => 'required|date',
            'sex'            => 'required',
            'address'        => 'required',
            'contact_number' => 'required|regex:/^09[0-9]{9}$/',
            'email'          => 'required|email|regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/|unique:users,email,' . $user->id,
            'password'       => 'nullable|min:6|confirmed',
            'profile_picture'=> 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->username       = $request->username;
        $user->first_name     = $request->first_name;
        $user->middle_name    = $request->middle_name;
        $user->last_name      = $request->last_name;
        $user->dob            = $request->dob;
        $user->sex            = $request->sex;
        $user->address        = $request->address;
        $user->contact_number = $request->contact_number;
        $user->email          = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/profile')->with('success', 'Profile updated successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function profile() 
    {
        return view('profile.main'); 
    }

    public function edit() 
    {
        return view('profile.edit'); 
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $cars = Car::where('user_id', $id)->get();
    
        $currentUser = Auth::user();
        
        $carts = $currentUser ? $currentUser->carts : collect();
        
        $pendingRequests = $currentUser 
            ? \Illuminate\Support\Facades\DB::table('rentals')
                ->where('user_id', $currentUser->id)
                ->where('status', 'pending')
                ->pluck('car_id')
                ->toArray() 
            : [];

        return view('profile.show', [
            'user' => $user,
            'cars' => $cars,
            'carts' => $carts,
            'pendingRequests' => $pendingRequests
        ]);
    }
}