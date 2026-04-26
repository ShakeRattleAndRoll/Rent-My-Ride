<x-layout>

<div class="bg-gray-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-10 px-4 ">
        <div class="bg-[#111] p-10 rounded-2xl w-96 shadow-lg text-center ">

            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
                alt="Rent My Ride Logo"
                class="mx-auto mb-10 w-65">

            <form method="POST" action="/login">
                @csrf

                <input type="text" name="email" placeholder="Email/Username"
                    class="w-full mb-2 p-3 rounded-full bg-gray-800 text-white border-none">

                @error('email')
                    <p class="text-red-400 text-sm mb-3 px-2">{{ $message }}</p>
                @enderror

                <div class="relative mb-2">
                    <input type="password" name="password" id="password" placeholder="Password"
                        class="w-full p-3 rounded-full bg-gray-800 text-white border-none pr-12">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                        <i id="eyeIcon" class="fa fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-400 text-sm mb-3 px-2">{{ $message }}</p>
                @enderror

                <div class="mb-6"></div>

                <button type="submit" class="bg-yellow-400 w-full py-2 rounded-full font-bold text-black cursor-pointer">
                    LOGIN
                </button>
            </form>

            <p class="text-gray-400 mt-4">
                Don't have account?
                <a href="/register" class="text-blue-400">Sign Up</a>
            </p>

        </div>
    </div>
</div>

<script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</x-layout>