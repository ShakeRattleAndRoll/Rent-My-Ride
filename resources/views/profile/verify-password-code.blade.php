{{-- Profile password code verification page --}}
<x-layout>

<div class="bg-[#121212] min-h-screen flex items-center justify-center px-4 py-10">
    <div
        class="w-full max-w-md bg-[#1a1a1a] border border-white/10 p-6 sm:p-8 rounded-2xl shadow-2xl text-white"
        style="font-family: 'Montserrat', sans-serif;"
    >
        <div class="text-center mb-6">
            <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-red-500/10 text-red-300">
                <i class="fa-solid fa-lock text-lg"></i>
            </div>

            <p class="text-red-300 text-xs font-black uppercase tracking-[0.22em] mb-3">
                Confirm password change
            </p>

            <h1 class="text-3xl font-black uppercase leading-tight mb-3">
                Enter the Code
            </h1>

            <p class="text-gray-400 text-sm leading-relaxed">
                We sent a 6-digit password change code to
                <span class="text-white font-bold break-all">{{ $email }}</span>.
            </p>
        </div>

        <form method="POST" action="{{ route('profile.password.verify-code.submit') }}" class="space-y-4" data-livewire-form data-livewire-html>
            @csrf

            <div class="space-y-1">
                <label class="text-[10px] uppercase tracking-widest text-white font-bold">Password Code</label>
                <input
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    required
                    autofocus
                    autocomplete="one-time-code"
                    placeholder="123456"
                    value="{{ old('code') }}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                    class="w-full px-4 py-4 rounded-md bg-black border border-white/10 text-white text-center text-2xl font-black tracking-[0.35em] placeholder-gray-700
                    focus:outline-none focus:ring-2 {{ $errors->has('code') ? 'focus:ring-red-500 border-red-500' : 'focus:ring-red-300 focus:border-red-300/60' }}
                    transition"
                >
                @error('code')
                    <p class="text-red-400 text-xs px-1 italic text-center">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="w-full py-3 rounded-md font-black text-black bg-red-300 uppercase tracking-wide
                hover:bg-red-200 active:scale-95 transition duration-200"
            >
                Continue
            </button>
        </form>

        <a href="{{ route('profile.edit') }}" wire:navigate class="block mt-5 text-center text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-white transition">
            Back to Profile Edit
        </a>
    </div>
</div>

</x-layout>
