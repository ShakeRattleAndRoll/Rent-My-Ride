<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Car;
use App\Models\UserRelation;
use Illuminate\Validation\ValidationException;


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
        $validated = $request->validate([
            'username'       => 'required|unique:users',
            'first_name'     => 'required',
            'middle_name'    => 'nullable',
            'last_name'      => 'required',
            'dob'            => 'required|date',
            'sex'            => 'required',
            'address'        => 'required',
            'email'          => 'required|email|regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/|unique:users',
            'contact_number' => 'required|regex:/^09[0-9]{9}$/',
            'password'       => 'required|min:6|confirmed',
        ]);

        $code = (string) random_int(100000, 999999);

        $request->session()->put('pending_registration', [
            'data' => [
                'username'       => $validated['username'],
                'first_name'     => $validated['first_name'],
                'middle_name'    => $validated['middle_name'] ?? null,
                'last_name'      => $validated['last_name'],
                'dob'            => $validated['dob'],
                'sex'            => $validated['sex'],
                'address'        => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'email'          => $validated['email'],
                'password'       => Hash::make($validated['password']),
            ],
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->toIso8601String(),
            'attempts' => 0,
        ]);

        $this->sendRegistrationCode($validated['email'], $code);

        return redirect()
            ->route('register.verify-email')
            ->with('success', 'We sent a verification code to your email.');
    }

    public function showEmailCodeForm(Request $request)
    {
        if (!$request->session()->has('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.verify-email-code', [
            'email' => $request->session()->get('pending_registration.data.email'),
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get('pending_registration');

        if (!$pending) {
            return redirect()->route('register');
        }

        if (now()->greaterThan(\Carbon\Carbon::parse($pending['expires_at']))) {
            $request->session()->forget('pending_registration');

            throw ValidationException::withMessages([
                'code' => 'The verification code has expired. Please sign up again.',
            ]);
        }

        if (($pending['attempts'] ?? 0) >= 5) {
            $request->session()->forget('pending_registration');

            throw ValidationException::withMessages([
                'code' => 'Too many incorrect attempts. Please sign up again.',
            ]);
        }

        if (!Hash::check($request->code, $pending['code_hash'])) {
            $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
            $request->session()->put('pending_registration', $pending);

            throw ValidationException::withMessages([
                'code' => 'The verification code is incorrect.',
            ]);
        }

        $data = $pending['data'];

        if (User::where('email', $data['email'])->orWhere('username', $data['username'])->exists()) {
            $request->session()->forget('pending_registration');

            throw ValidationException::withMessages([
                'code' => 'This email or username was already registered. Please sign up again.',
            ]);
        }

        $user = User::create([
            ...$data,
            'email_verified_at' => now(),
        ]);

        $request->session()->forget('pending_registration');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Email verified. Your account is ready!');
    }

    public function resendEmailCode(Request $request)
    {
        $pending = $request->session()->get('pending_registration');

        if (!$pending) {
            return redirect()->route('register');
        }

        $code = (string) random_int(100000, 999999);
        $pending['code_hash'] = Hash::make($code);
        $pending['expires_at'] = now()->addMinutes(10)->toIso8601String();
        $pending['attempts'] = 0;
        $request->session()->put('pending_registration', $pending);

        $this->sendRegistrationCode($pending['data']['email'], $code);

        return back()->with('success', 'A new verification code was sent.');
    }

    private function sendRegistrationCode(string $email, string $code): void
    {
        Mail::raw(
            "Your Rent My Ride verification code is {$code}. This code expires in 10 minutes.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your Rent My Ride verification code');
            }
        );
    }

    private function sendPasswordCode(string $email, string $code): void
    {
        Mail::raw(
            "Your Rent My Ride password reset code is {$code}. This code expires in 10 minutes.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your Rent My Ride password reset code');
            }
        );
    }

    private function validatePasswordCode(Request $request, array $pending, string $sessionKey): void
    {
        if (now()->greaterThan(\Carbon\Carbon::parse($pending['expires_at']))) {
            $request->session()->forget($sessionKey);

            throw ValidationException::withMessages([
                'code' => 'The code has expired. Please request a new one.',
            ]);
        }

        if (($pending['attempts'] ?? 0) >= 5) {
            $request->session()->forget($sessionKey);

            throw ValidationException::withMessages([
                'code' => 'Too many incorrect attempts. Please request a new code.',
            ]);
        }

        if (!Hash::check($request->code, $pending['code_hash'])) {
            $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
            $request->session()->put($sessionKey, $pending);

            throw ValidationException::withMessages([
                'code' => 'The code is incorrect.',
            ]);
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with that email address.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        $request->session()->put('password_reset_code', [
            'email' => $user->email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->toIso8601String(),
            'attempts' => 0,
        ]);

        $this->sendPasswordCode($user->email, $code);

        return redirect()
            ->route('password.verify-code')
            ->with('success', 'We sent a password reset code to your email.');
    }

    public function showPasswordResetCodeForm(Request $request)
    {
        if (!$request->session()->has('password_reset_code')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-password-code', [
            'email' => $request->session()->get('password_reset_code.email'),
        ]);
    }

    public function verifyPasswordResetCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get('password_reset_code');

        if (!$pending) {
            return redirect()->route('password.request');
        }

        $this->validatePasswordCode($request, $pending, 'password_reset_code');

        $request->session()->put('password_reset_verified', [
            'email' => $pending['email'],
            'verified_at' => now()->toIso8601String(),
        ]);
        $request->session()->forget('password_reset_code');

        return redirect()->route('password.reset');
    }

    public function resetPassword(Request $request)
    {
        if (!$request->session()->has('password_reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'email' => $request->session()->get('password_reset_verified.email'),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $verified = $request->session()->get('password_reset_verified');

        if (!$verified || $verified['email'] !== $request->email) {
            return redirect()->route('password.request');
        }

        if (now()->greaterThan(\Carbon\Carbon::parse($verified['verified_at'])->addMinutes(15))) {
            $request->session()->forget('password_reset_verified');

            throw ValidationException::withMessages([
                'email' => 'Your reset session expired. Please request a new code.',
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => \Illuminate\Support\Str::random(60),
        ])->save();

        $request->session()->forget('password_reset_verified');

        return redirect()->route('login')->with('success', 'Password reset successfully. You can log in now.');
    }

    public function update(Request $request)
    {

        $user = User::find(Auth::id());

        $request->validate([
            'username'       => 'required|unique:users,username,' . $user->id,
            'first_name'     => 'required',
            'middle_name'    => 'nullable',
            'last_name'      => 'required',
            'dob'            => 'required|date',
            'sex'            => 'required',
            'address'        => 'required',
            'contact_number' => 'required|regex:/^09[0-9]{9}$/',
            'email'          => 'required|email|regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/|unique:users,email,' . $user->id,
            'profile_picture'=> 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $emailChanged = $request->email !== $user->email;

        $user->username       = $request->username;
        $user->first_name     = $request->first_name;
        $user->middle_name    = $request->middle_name;
        $user->last_name      = $request->last_name;
        $user->dob            = $request->dob;
        $user->sex            = $request->sex;
        $user->address        = $request->address;
        $user->contact_number = $request->contact_number;

        if (!$emailChanged) {
            $user->email = $request->email;
        }

        $user->save();

        if ($emailChanged) {
            $code = (string) random_int(100000, 999999);

            $request->session()->put('profile_email_change', [
                'user_id' => $user->id,
                'email' => $request->email,
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(10)->toIso8601String(),
                'attempts' => 0,
            ]);

            $this->sendRegistrationCode($request->email, $code);

            return redirect()
                ->route('profile.email.verify-code')
                ->with('success', 'Profile updated. Verify your new email to finish changing it.');
        }

        return redirect('/profile')->with('success', 'Profile updated successfully.');
    }

    public function sendProfilePasswordCode(Request $request)
    {
        $user = Auth::user();
        $code = (string) random_int(100000, 999999);

        $request->session()->put('profile_password_change', [
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->toIso8601String(),
            'attempts' => 0,
        ]);

        $this->sendPasswordCode($user->email, $code);

        return redirect()
            ->route('profile.password.verify-code')
            ->with('success', 'We sent a password reset code to your email.');
    }

    public function showProfilePasswordCodeForm(Request $request)
    {
        if (!$request->session()->has('profile_password_change')) {
            return redirect()->route('profile.edit');
        }

        return view('profile.verify-password-code', [
            'email' => Auth::user()->email,
        ]);
    }

    public function verifyProfilePasswordCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get('profile_password_change');

        if (!$pending || (int) $pending['user_id'] !== Auth::id()) {
            return redirect()->route('profile.edit');
        }

        $this->validatePasswordCode($request, $pending, 'profile_password_change');

        $request->session()->put('profile_password_verified', [
            'user_id' => Auth::id(),
            'verified_at' => now()->toIso8601String(),
        ]);
        $request->session()->forget('profile_password_change');

        return redirect()->route('profile.password.reset');
    }

    public function showProfilePasswordResetForm(Request $request)
    {
        $verified = $request->session()->get('profile_password_verified');

        if (!$verified || (int) $verified['user_id'] !== Auth::id()) {
            return redirect()->route('profile.main');
        }

        return view('profile.reset-password');
    }

    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $verified = $request->session()->get('profile_password_verified');

        if (!$verified || (int) $verified['user_id'] !== Auth::id()) {
            return redirect()->route('profile.main');
        }

        if (now()->greaterThan(\Carbon\Carbon::parse($verified['verified_at'])->addMinutes(15))) {
            $request->session()->forget('profile_password_verified');

            throw ValidationException::withMessages([
                'password' => 'Your password reset session expired. Please request a new code.',
            ]);
        }

        $user = Auth::user();
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => \Illuminate\Support\Str::random(60),
        ])->save();

        $request->session()->forget('profile_password_verified');

        return redirect()->route('profile.main')->with('success', 'Password updated successfully.');
    }

    public function showProfileEmailCodeForm(Request $request)
    {
        $pending = $request->session()->get('profile_email_change');

        if (!$pending || (int) $pending['user_id'] !== Auth::id()) {
            return redirect()->route('profile.edit');
        }

        return view('profile.verify-email-code', [
            'email' => $pending['email'],
        ]);
    }

    public function verifyProfileEmailCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get('profile_email_change');

        if (!$pending || (int) $pending['user_id'] !== Auth::id()) {
            return redirect()->route('profile.edit');
        }

        $this->validatePasswordCode($request, $pending, 'profile_email_change');

        $user = Auth::user();
        $user->forceFill([
            'email' => $pending['email'],
            'email_verified_at' => now(),
        ])->save();

        $request->session()->forget('profile_email_change');

        return redirect()->route('profile.main')->with('success', 'Email verified and updated successfully.');
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

        if (Auth::check() && Auth::id() !== $user->id) {
            $isBlocked = UserRelation::where('type', 'block')
                ->where(function ($query) use ($id) {
                    $query->where(function ($q) use ($id) {
                        $q->where('user_id', Auth::id())
                            ->where('target_id', $id);
                    })->orWhere(function ($q) use ($id) {
                        $q->where('user_id', $id)
                            ->where('target_id', Auth::id());
                    });
                })
                ->exists();

            if ($isBlocked) {
                return response()
                    ->view('profile.blocked', ['user' => $user], 403);
            }
        }

        $cars = Car::where('user_id', $id)
            ->when(
                ! Auth::check() || (Auth::id() !== $user->id && ! Auth::user()->is_admin),
                fn ($query) => $query->publiclyVisible()
            )
            ->withExists(['rentals as is_occupied' => function ($query) {
                $query->where('status', 'accepted')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            }])
            ->latest()
            ->get();
    
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
