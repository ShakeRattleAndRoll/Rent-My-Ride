<x-layout>

<div class="bg-gray-900 min-h-screen">

    <div class="relative min-h-screen flex items-center justify-center py-10 px-4 ">
        <div class="bg-[#111] p-10 rounded-2xl w-96 shadow-lg text-center ">

            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
                alt="Rent My Ride Logo"
                class="mx-auto mb-10 w-65">

            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <!-- Errors -->
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500 text-red-400 text-sm px-4 py-3 rounded-md">
                        
                        @if ($errors->has('email'))
                            {{ $errors->first('email') }}
                        @elseif ($errors->has('password'))
                            {{ $errors->first('password') }}
                        @endif

                    </div>
                @endif

                <!-- Email / Username -->
                <input type="text" name="email" placeholder="Email or Username"
                    class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                        focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

                <!-- Password -->
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Password"
                        class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                            pr-12 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

                    <button type="button" onclick="togglePassword('password', 'eyeIcon')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                        <i id="eyeIcon" class="fa fa-eye"></i>
                    </button>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full py-3 rounded-md font-bold text-black bg-yellow-400 
                        hover:bg-yellow-300 active:scale-95 transition duration-200">
                    LOGIN
                </button>
            </form>
            
            <p class="text-gray-400 mt-4">
                Don't have account?
                <a href="/register" class="text-yellow-400 hover:text-yellow-300 font-semibold transition">Sign Up</a>
            </p>

        </div>
    </div>
</div>

<script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</x-layout>