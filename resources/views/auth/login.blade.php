<x-layout>

<div class="bg-black min-h-screen">

    <div class="relative min-h-screen flex items-center py-10 px-4 overflow-hidden">

        {{-- BACKGROUND --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(163,230,53,0.12),transparent_28%),linear-gradient(135deg,#050505_0%,#000_55%,#111_100%)]"></div>
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- CONTENT --}}
        <div
            class="relative z-10 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center justify-items-center"
            style="font-family: 'Montserrat', sans-serif;"
        >

            {{-- LEFT SIDE --}}
            <div class="text-center lg:text-left px-2 lg:px-0">

                <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-4">
                    Welcome back
                </p>

                <h1 class="text-4xl md:text-6xl font-black text-white uppercase leading-tight mb-5">
                    Rent My <span class="text-lime-400">Ride</span>
                </h1>

                <p class="text-gray-300 text-base md:text-xl leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Sign in to find your next ride, manage bookings, or keep your listed car earning when it is idle.
                </p>

                {{-- FEATURES --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-2xl mx-auto lg:mx-0">

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-car-side text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Browse</p>
                        <p class="text-gray-400 text-xs mt-1">Find available cars nearby.</p>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-comments text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Connect</p>
                        <p class="text-gray-400 text-xs mt-1">Message owners directly.</p>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-key text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Book</p>
                        <p class="text-gray-400 text-xs mt-1">Request the ride you need.</p>
                    </div>

                </div>
            </div>

            {{-- LOGIN CARD --}}
            <div
                class="bg-[#111] border border-white/10 p-6 sm:p-8 md:p-10 rounded-2xl w-full max-w-md mx-auto lg:ml-auto shadow-2xl text-center text-white"
            >

                <img
                    src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
                    alt="Rent My Ride Logo"
                    class="mx-auto mb-10 w-64"
                >

                <form method="POST" action="/login" class="space-y-3" autocomplete="off">

                    @csrf

                    {{-- ERRORS --}}
                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500 text-red-400 text-sm px-4 py-3 rounded-md text-left">
                            @if ($errors->has('email'))
                                {{ $errors->first('email') }}
                            @elseif ($errors->has('password'))
                                {{ $errors->first('password') }}
                            @endif
                        </div>
                    @endif

                    {{-- EMAIL --}}
                    <div class="text-left">
                        <label class="block text-[10px] text-white uppercase tracking-widest mb-1">
                            Email / Username
                        </label>

                        <input
                            type="text"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter email or username"
                            autocomplete="off"
                            autocapitalize="none"
                            spellcheck="false"
                            class="w-full p-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60 transition"
                        >
                    </div>

                    {{-- PASSWORD --}}
                    <div class="text-left">
                        <label class="block text-[10px] text-white uppercase tracking-widest mb-1">
                            Password
                        </label>

                        <div class="relative">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter password"
                                autocomplete="new-password"
                                readonly
                                onfocus="this.removeAttribute('readonly')"
                                class="w-full p-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                pr-12 focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60 transition"
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password', 'eyeIcon')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                            >
                                <i id="eyeIcon" class="fa fa-eye-slash"></i>
                            </button>

                        </div>
                    </div>

                    <div class="flex justify-end text-xs">
                        <a href="{{ route('password.request') }}" wire:navigate class="font-semibold text-lime-400 hover:text-lime-300 transition">
                            Forgot password?
                        </a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <button
                        type="submit"
                        class="w-full py-3 rounded-md font-black text-black bg-lime-400 uppercase tracking-wide
                        hover:bg-lime-300 active:scale-95 transition duration-200"
                    >
                        LOGIN
                    </button>

                </form>

                {{-- REGISTER --}}
                <p class="text-gray-400 mt-4 text-sm">
                    Don't have account?
                    <a href="/register" wire:navigate class="text-lime-400 hover:text-lime-300 font-semibold transition">
                        Sign Up
                    </a>
                </p>

            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</x-layout>
