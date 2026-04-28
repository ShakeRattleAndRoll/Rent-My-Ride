<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'username'       => 'required',
            'first_name'     => 'required',
            'middle_name'    => 'required',
            'last_name'      => 'required',
            'dob'            => 'required|date',
            'sex'            => 'required',
            'address'        => 'required',
            'email'          => 'required|email|unique:users',
            'contact_number' => 'required',
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

