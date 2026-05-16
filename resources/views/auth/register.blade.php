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
                    Join the ride
                </p>

                <h1 class="text-4xl md:text-6xl font-black text-white uppercase leading-tight mb-5">
                    Create Your <span class="text-lime-400">Account</span>
                </h1>

                <p class="text-gray-300 text-base md:text-xl leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Start renting cars, connecting with owners, and managing your own listings in one modern platform.
                </p>

                {{-- FEATURES --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-2xl mx-auto lg:mx-0">

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-shield-halved text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Secure</p>
                        <p class="text-gray-400 text-xs mt-1">Protected account access.</p>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-bolt text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Fast</p>
                        <p class="text-gray-400 text-xs mt-1">Quick and easy booking flow.</p>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-[#111]/80 p-4">
                        <i class="fa-solid fa-users text-lime-400 text-xl mb-3"></i>
                        <p class="text-white text-sm font-black uppercase">Community</p>
                        <p class="text-gray-400 text-xs mt-1">Connect with trusted renters.</p>
                    </div>

                </div>
            </div>

            {{-- REGISTER CARD --}}
            <div class="bg-[#111] border border-white/10 p-6 sm:p-8 rounded-2xl w-full max-w-lg mx-auto lg:ml-auto shadow-2xl text-white">

                {{-- LOGO --}}
                <img
                    src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
                    alt="Rent My Ride Logo"
                    class="mx-auto mb-6 w-48"
                >

                {{-- FORM --}}
                <form method="POST" action="/register" class="space-y-3" id="registerForm">

                    @csrf

                    {{-- USERNAME --}}
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-widest text-white font-bold">Username</label>
                        <input
                            name="username"
                            placeholder="johndoe123"
                            required
                            autocomplete="off"
                            spellcheck="false"
                            value="{{ old('username') }}"
                            class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                            focus:outline-none focus:ring-2 {{ $errors->has('username') ? 'focus:ring-red-500 border-red-500' : 'focus:ring-lime-400 focus:border-lime-400/60' }}
                            transition autofill:bg-black autofill:text-white"
                        >
                        @error('username')
                            <p class="text-red-400 text-xs px-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FIRST + MIDDLE NAME --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">First Name</label>
                            <input
                                name="first_name"
                                placeholder="John"
                                required
                                autocomplete="off"
                                spellcheck="false"
                                value="{{ old('first_name') }}"
                                class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60
                                transition autofill:bg-black autofill:text-white"
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Middle Name</label>
                            <input
                                name="middle_name"
                                placeholder="Smith (optional)"
                                autocomplete="off"
                                spellcheck="false"
                                value="{{ old('middle_name') }}"
                                class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60
                                transition autofill:bg-black autofill:text-white"
                            >
                        </div>

                    </div>

                    {{-- LAST NAME --}}
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-widest text-white font-bold">Last Name</label>
                        <input
                            name="last_name"
                            placeholder="Doe"
                            required
                            autocomplete="off"
                            spellcheck="false"
                            value="{{ old('last_name') }}"
                            class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60
                            transition autofill:bg-black autofill:text-white"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3">

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Date of Birth</label>
                            <input
                                name="dob"
                                type="date"
                                required
                                value="{{ old('dob') }}"
                                class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white [color-scheme:dark]   
                                focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60 transition"
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Sex</label>
                            <div class="bg-black border border-white/10 rounded-md px-5 py-4 flex items-center justify-around">
                                <label class="flex items-center gap-2 text-sm text-gray-300">
                                    <input type="radio" name="sex" value="Male" {{ old('sex') == 'Male' ? 'checked' : '' }} required class="accent-lime-400">
                                    Male
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-300">
                                    <input type="radio" name="sex" value="Female" {{ old('sex') == 'Female' ? 'checked' : '' }} required class="accent-lime-400">
                                    Female
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- ADDRESS --}}
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-widest text-white font-bold">Address</label>
                        <input
                            name="address"
                            placeholder="House No., Street, Barangay, City, Province"
                            required
                            autocomplete="off"
                            spellcheck="false"
                            value="{{ old('address') }}"
                            class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60
                            transition autofill:bg-black autofill:text-white"
                        >
                    </div>

                    {{-- CONTACT + EMAIL --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Contact Number</label>
                            <input
                                name="contact_number"
                                value="{{ old('contact_number') }}"
                                placeholder="09123456789"
                                required
                                maxlength="11"
                                pattern="09[0-9]{9}"
                                title="Must be 11 digits and start with 09"
                                autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60
                                transition autofill:bg-black autofill:text-white"
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Email</label>
                            <input
                                name="email"
                                type="email"
                                placeholder="john.doe@gmail.com"
                                required
                                autocomplete="off"
                                spellcheck="false"
                                value="{{ old('email') }}"
                                pattern="^[^@]+@[^@]+\.[a-zA-Z]{2,}$"
                                title="Email must be in the format: example@domain.com"
                                class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                focus:outline-none focus:ring-2 {{ $errors->has('email') ? 'focus:ring-red-500 border-red-500' : 'focus:ring-lime-400 focus:border-lime-400/60' }}
                                transition autofill:bg-black autofill:text-white"
                            >
                            @error('email')
                                <p class="text-red-400 text-xs px-1 italic">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- PASSWORD + CONFIRM --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-widest text-white font-bold">Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Create a strong password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                    pr-10 focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60 transition"
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
                                    placeholder="Re-enter your password"
                                    required
                                    autocomplete="new-password"
                                    oninput="checkPasswordMatch()"
                                    class="w-full px-3 py-3 rounded-md bg-black border border-white/10 text-white placeholder-gray-500
                                    pr-10 focus:outline-none focus:ring-2 focus:ring-lime-400 focus:border-lime-400/60 transition"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                                    class="input-action-button right-3 text-gray-400 hover:text-white transition"
                                >
                                    <i id="eyeIcon2" class="fa fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <p id="passwordMessage" class="text-sm px-1 hidden"></p>

                    @error('password')
                        <p class="text-red-400 text-sm px-1">{{ $message }}</p>
                    @enderror

                    {{-- BUTTON --}}
                    <button
                        class="w-full py-3 rounded-md font-black text-black bg-lime-400 uppercase tracking-wide
                        hover:bg-lime-300 active:scale-95 transition duration-200" 
                    >
                        SEND VERIFICATION CODE
                    </button>

                </form>

                {{-- LOGIN --}}
                <p class="text-gray-400 mt-3 text-sm text-center">
                    Already have an account?
                    <a href="/login" wire:navigate class="text-lime-400 hover:text-lime-300 font-semibold transition">Log in</a>
                </p>

            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/confirmpassword/checkpassmatch.js') }}"></script>

</x-layout>
