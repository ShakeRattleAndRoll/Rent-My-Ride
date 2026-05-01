<x-layout>
<div class="bg-gray-900 min-h-screen">

    <div 
        x-data="{
            frame: 1,
            total: 240,
            direction: 1,

            interval: null,
            baseSpeed: 40,
            slowSpeed: 90,

            startAuto() {
                this.runAuto(this.baseSpeed)
            },

            runAuto(speed) {
                clearInterval(this.interval)

                this.interval = setInterval(() => {
                    this.frame += this.direction

                    // Slow down near edges
                    if (this.frame >= this.total - 5 || this.frame <= 5) {
                        this.runAuto(this.slowSpeed)
                    } else {
                        this.runAuto(this.baseSpeed)
                    }

                    // Reverse direction (ping-pong)
                    if (this.frame >= this.total) {
                        this.frame = this.total
                        this.direction = -1
                    }

                    if (this.frame <= 1) {
                        this.frame = 1
                        this.direction = 1
                    }

                }, speed)
            }
        }"

        x-init="startAuto()"

        class="absolute inset-0 flex items-center justify-center select-none pointer-events-none"
    >
        <img 
            :src="'/images/frames2/ezgif-frame-' + String(frame).padStart(3, '0') + '.jpg'"
            class="absolute inset-0 w-full h-[1200px] object-cover object-center blur-sm"
            draggable="false"
            alt="360 Car View"
        >
    </div>

    <div class="relative min-h-screen flex items-center justify-center py-10 px-4">

        <div class="bg-[#111] p-6 sm:p-8 md:p-10 rounded-2xl w-full max-w-lg text-white">
            
            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}"
            alt="Rent My Ride Logo"
            class="mx-auto mb-10 w-65">

            <form method="POST" action="/register" class="space-y-4" id="registerForm">
            @csrf

            <!-- Username -->
            <div class="space-y-1">
                <input name="username" placeholder="Username" required
                    value="{{ old('username') }}"
                    class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                        focus:outline-none focus:ring-2 {{ $errors->has('username') ? 'focus:ring-red-500 border border-red-500' : 'focus:ring-yellow-400' }} transition">
                
                @error('username')
                    <p class="text-red-400 text-xs px-2 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name Fields --> 
            <input name="first_name" placeholder="First name" required value="{{ old('first_name') }}"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <input name="middle_name" placeholder="Middle name" required value="{{ old('middle_name') }}"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <input name="last_name" placeholder="Last name" required value="{{ old('last_name') }}"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <!-- DOB + Sex -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <input name="dob" type="date" required value="{{ old('dob') }}"
                    class="w-full p-3 rounded-md bg-gray-800 text-white 
                        focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

                <div class="bg-gray-800 rounded-md px-4 py-3 flex items-center justify-around">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="sex" value="Male" {{ old('sex') == 'Male' ? 'checked' : '' }} required>
                        Male
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="sex" value="Female" {{ old('sex') == 'Female' ? 'checked' : '' }} required>
                        Female
                    </label>
                </div>

            </div>

            <!-- Address -->
            <input name="address" placeholder="Address" required value="{{ old('address') }}"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <!-- Contact -->
            <input
                name="contact_number" value="{{ old('contact_number') }}"
                placeholder="Contact Number (09XXXXXXXXX)"
                required
                maxlength="11"
                pattern="09[0-9]{9}"
                title="Must be 11 digits and start with 09"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <!-- Email -->
            <input 
                name="email" type="email" placeholder="Email" required value="{{ old('email') }}"
                pattern="^[^@]+@[^@]+\.[a-zA-Z]{2,}$"
                title="Email must be in the format: example@domain.com"
                class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                    focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">

            <!-- Password -->
            <div class="relative">
                <input type="password" name="password" id="password" placeholder="Password" required
                    class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                        pr-12 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                    <i id="eyeIcon1" class="fa fa-eye"></i>
                </button>
            </div>

            <!-- Confirm Password -->
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required
                    class="w-full p-3 rounded-md bg-gray-800 text-white placeholder-gray-400 
                        pr-12 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                    oninput="checkPasswordMatch()">
                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                    <i id="eyeIcon2" class="fa fa-eye"></i>
                </button>
            </div>

            <!-- Password Match Message -->
            <p id="passwordMessage" class="text-sm px-2 hidden"></p>

            @error('password')
                <p class="text-red-400 text-sm px-2">{{ $message }}</p>
            @enderror

            <!-- Button -->
            <button
                class="w-full py-3 rounded-md font-bold text-black bg-yellow-400 
                    hover:bg-yellow-300 active:scale-95 transition duration-200">
                CREATE ACCOUNT
            </button>
        </form>

        <p class="text-gray-400 mt-4 text-sm text-center">
            Already have an account?
            <a href="/login" class="text-yellow-400 hover:text-yellow-300 font-semibold transition">Log in</a>
        </p>

        </div>

    </div>
</div>

<script src="{{ asset('js/confirmpassword/checkpassmatch.js') }}"></script>
<script>
    const total = 240;
    for (let i = 1; i <= total; i++) {
        const img = new Image();
        img.src = `/images/frames2/ezgif-frame_${String(i).padStart(3, '0')}.jpg`;
    }
</script>
</x-layout>