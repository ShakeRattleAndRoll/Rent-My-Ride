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
                Password reset
            </p>

            <h1 class="text-3xl font-black uppercase leading-tight mb-3">
                New Password
            </h1>

            <p class="text-gray-400 text-sm leading-relaxed">
                Your code is confirmed. Type your new password below.
            </p>
        </div>

        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="text-[10px] uppercase tracking-widest text-white font-bold">New Password</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a new password"
                        class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                        pr-10 focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300/60 transition"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password', 'eyeIcon1')"
                        class="input-action-button right-3 text-gray-400 hover:text-white transition"
                    >
                        <i id="eyeIcon1" class="fa fa-eye-slash text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] uppercase tracking-widest text-white font-bold">Confirm Password</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Re-enter your new password"
                        class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                        pr-10 focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300/60 transition"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                        class="input-action-button right-3 text-gray-400 hover:text-white transition"
                    >
                        <i id="eyeIcon2" class="fa fa-eye-slash text-xs"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-400 text-xs px-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="w-full py-3 rounded-md font-black text-black bg-red-300 uppercase tracking-wide
                hover:bg-red-200 active:scale-95 transition duration-200"
            >
                Save New Password
            </button>
        </form>
    </div>
</div>

<script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</x-layout>
