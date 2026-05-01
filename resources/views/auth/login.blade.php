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
            class="absolute inset-0 w-full h-full object-cover object-center blur-sm"
            draggable="false"
            alt="360 Car View"
        >
    </div>

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
<script>
    const total = 240;
    for (let i = 1; i <= total; i++) {
        const img = new Image();
        img.src = `/images/frames2/ezgif-frame_${String(i).padStart(3, '0')}.jpg`;
    }
</script>

</x-layout>