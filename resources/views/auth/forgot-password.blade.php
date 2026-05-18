{{-- Forgot password request page --}}
<x-layout>

<div class="bg-black min-h-screen">
    <div class="relative min-h-screen flex items-center justify-center py-10 px-4 overflow-hidden">

        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(163,230,53,0.12),transparent_28%),linear-gradient(135deg,#050505_0%,#000_55%,#111_100%)]"></div>
        <div class="absolute inset-0 bg-black/40"></div>

        <div
            class="relative z-10 w-full max-w-md bg-[#111] border border-white/10 p-6 sm:p-8 rounded-2xl shadow-2xl text-white"
            style="font-family: 'Montserrat', sans-serif;"
        >
            <img
                src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
                alt="Rent My Ride Logo"
                class="mx-auto mb-8 w-48"
            >

            <div class="text-center mb-6">
                <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-lime-400/10 text-lime-400">
                    <i class="fa-solid fa-key text-lg"></i>
                </div>

                <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-3">
                    Account recovery
                </p>

                <h1 class="text-3xl font-black uppercase leading-tight mb-3">
                    Forgot Password
                </h1>

                <p class="text-gray-400 text-sm leading-relaxed">
                    Enter your email and we will send a code to reset your password.
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4" data-livewire-form>
                @csrf

                <div class="space-y-1">
                    <label class="text-[10px] uppercase tracking-widest text-white font-bold">Email</label>
                    <input
                        name="email"
                        type="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                        focus:outline-none focus:ring-2 {{ $errors->has('email') ? 'focus:ring-red-500 border-red-500' : 'focus:ring-lime-400 focus:border-lime-400/60' }}
                        transition"
                    >
                    @error('email')
                        <p class="text-red-400 text-xs px-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    class="w-full py-3 rounded-md font-black text-black bg-lime-400 uppercase tracking-wide
                    hover:bg-lime-300 active:scale-95 transition duration-200"
                >
                    Send Reset Code
                </button>
            </form>

            <p class="text-gray-400 mt-5 text-sm text-center">
                Remembered it?
                <a href="{{ route('login') }}" wire:navigate class="text-lime-400 hover:text-lime-300 font-semibold transition">Log in</a>
            </p>
        </div>

    </div>
</div>

</x-layout>
