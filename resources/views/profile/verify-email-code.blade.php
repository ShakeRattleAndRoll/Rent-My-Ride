<x-layout>

<div class="bg-[#121212] min-h-screen flex items-center justify-center px-4 py-10">
    <div
        class="w-full max-w-md bg-[#1a1a1a] border border-white/10 p-6 sm:p-8 rounded-2xl shadow-2xl text-white"
        style="font-family: 'Montserrat', sans-serif;"
    >
        <div class="text-center mb-6">
            <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-lime-400/10 text-lime-400">
                <i class="fa-solid fa-envelope-circle-check text-xl"></i>
            </div>

            <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-3">
                Verify new email
            </p>

            <h1 class="text-3xl font-black uppercase leading-tight mb-3">
                Enter the Code
            </h1>

            <p class="text-gray-400 text-sm leading-relaxed">
                We sent a 6-digit verification code to
                <span class="text-white font-bold break-all">{{ $email }}</span>.
            </p>
        </div>

        <form method="POST" action="{{ route('profile.email.verify-code.submit') }}" class="space-y-4" data-livewire-form data-livewire-html>
            @csrf

            <div class="space-y-1">
                <label class="text-[10px] uppercase tracking-widest text-white font-bold">Email Code</label>
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
                    focus:outline-none focus:ring-2 {{ $errors->has('code') ? 'focus:ring-red-500 border-red-500' : 'focus:ring-lime-400 focus:border-lime-400/60' }}
                    transition"
                >
                @error('code')
                    <p class="text-red-400 text-xs px-1 italic text-center">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="w-full py-3 rounded-md font-black text-black bg-lime-400 uppercase tracking-wide
                hover:bg-lime-300 active:scale-95 transition duration-200"
            >
                Verify Email
            </button>
        </form>
    </div>
</div>

</x-layout>
