<x-layout>
<div class="bg-gray-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-10 px-4">

        <div class="bg-[#111] p-6 sm:p-8 md:p-10 rounded-2xl w-full max-w-md sm:max-w-lg text-white">

            <a href="/login" class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white transition mb-4">
                <span class="text-xl">←</span>
                Login
            </a>

            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
            alt="Rent My Ride Logo"
            class="mx-auto mb-10 w-65">

            <form method="POST" action="/register" class="space-y-4" id="registerForm">
                @csrf

                <input name="username" placeholder="Username" required
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <input name="first_name" placeholder="First name" required
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <input name="middle_name" placeholder="Middle name" required
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <input name="last_name" placeholder="Last name" required
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <input name="dob" type="date" required
                        class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                    <div class="bg-[#1f2937] rounded-full px-4 py-2 flex items-center justify-around">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="sex" value="Male" required>
                            Male
                        </label>
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="sex" value="Female" required>
                            Female
                        </label>
                    </div>

                </div>

                <input name="address" placeholder="Address" required
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <input
                    name="contact_number"
                    placeholder="Contact Number (09XXXXXXXXX)"
                    required
                    maxlength="11"
                    pattern="09[0-9]{9}"
                    title="Must be 11 digits and start with 09"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                <input 
                    name="email" type="email" placeholder="Email" required
                    pattern="^[^@]+@[^@]+\.[a-zA-Z]{2,}$"
                    title="Email must be in the format: example@domain.com"
                    class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400">

                {{-- Password with toggle --}}
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400 pr-12">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                        <i id="eyeIcon1" class="fa fa-eye"></i>
                    </button>
                </div>

                {{-- Confirm Password with toggle --}}
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required
                        class="w-full px-4 py-3 rounded-full bg-[#1f2937] text-white outline-none focus:border focus:border-yellow-400 pr-12"
                        oninput="checkPasswordMatch()">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                        <i id="eyeIcon2" class="fa fa-eye"></i>
                    </button>
                </div>

                <p id="passwordMessage" class="text-sm px-2 hidden"></p>

                @error('password')
                    <p class="text-red-400 text-sm px-2">{{ $message }}</p>
                @enderror

                <button class="bg-yellow-400 text-black w-full py-3 rounded-full mt-4 font-bold hover:bg-yellow-300 transition">
                    CREATE ACCOUNT
                </button>
            </form>

        </div>

    </div>
</div>

<script src="{{ asset('js/confirmpassword/checkpassmatch.js') }}"></script>

</x-layout>