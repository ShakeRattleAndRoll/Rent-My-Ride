<x-layout>

    <div class="relative w-full h-[600px] flex items-center justify-center overflow-hidden">
        
        <div 
            x-data="{
                frame: 1,
                total: 240,
                direction: 1,

                dragging: false,
                lastX: 0,
                accumulated: 0,
                step: 6,

                idleDelay: 2000,
                idleTimer: null,
                interval: null,

                baseSpeed: 40, // normal speed
                slowSpeed: 90, // slow at edges

                start(e) {
                    this.dragging = true
                    this.lastX = e.clientX
                    this.stopAuto()
                },

                stop() {
                    this.dragging = false
                    this.startIdleTimer()
                },

                move(e) {
                    if (!this.dragging) return

                    let delta = e.clientX - this.lastX
                    this.lastX = e.clientX

                    this.accumulated += delta

                    while (this.accumulated >= this.step) {
                        this.frame++
                        this.accumulated -= this.step
                    }

                    while (this.accumulated <= -this.step) {
                        this.frame--
                        this.accumulated += this.step
                    }

                    if (this.frame > this.total) this.frame = this.total
                    if (this.frame < 1) this.frame = 1
                },

                startAuto() {
                    this.runAuto(this.baseSpeed)
                },

                runAuto(speed) {
                    clearInterval(this.interval)

                    this.interval = setInterval(() => {
                        this.frame += this.direction

                        // slow zone near edges
                        if (this.frame >= this.total - 5 || this.frame <= 5) {
                            this.runAuto(this.slowSpeed)
                        } else {
                            this.runAuto(this.baseSpeed)
                        }

                        // reverse at ends
                        if (this.frame >= this.total) {
                            this.frame = this.total
                            this.direction = -1
                        }

                        if (this.frame <= 1) {
                            this.frame = 1
                            this.direction = 1
                        }

                    }, speed)
                },

                stopAuto() {
                    clearInterval(this.interval)
                    clearTimeout(this.idleTimer)
                },

                startIdleTimer() {
                    this.idleTimer = setTimeout(() => {
                        this.startAuto()
                    }, this.idleDelay)
                }
            }"

            x-init="startAuto()"

            @mousedown.window="start($event)"
            @mouseup.window="stop()"
            @mouseleave.window="stop()"
            @mousemove.window="move($event)"

            @touchstart.window="dragging = true; lastX = $event.touches[0].clientX; stopAuto()"
            @touchend.window="dragging = false; startIdleTimer()"
            @touchmove.window="
                if (!dragging) return;
                let delta = $event.touches[0].clientX - lastX;
                lastX = $event.touches[0].clientX;
                accumulated += delta;

                while (accumulated >= step) {
                    frame++;
                    accumulated -= step;
                }
                while (accumulated <= -step) {
                    frame--;
                    accumulated += step;
                }

                if (frame > total) frame = total;
                if (frame < 1) frame = 1;
            "

            class="absolute inset-0 flex items-center justify-center select-none"
        >
            <img 
                :src="'/images/frames/ezgif-frame-' + String(frame).padStart(3, '0') + '.jpg'"
                class="absolute inset-0 w-full h-full object-cover object-center blur-sm"
                draggable="false"
                alt="360 Car View"
            >
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/10 to-black/10"></div>

        <div class="relative z-10 w-full max-w-4xl px-6 text-center" style="font-family: 'Montserrat', sans-serif;">
            
            <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tighter uppercase">
                Drive the <span class="text-lime-400">Experience</span>
            </h1>
            
            <p class="text-gray-300 text-lg md:text-xl mb-10 font-medium tracking-wide">
                Car rentals for your next adventure.
            </p>

        </div>
    </div>

    <section class="py-20 bg-black">
        <div class="max-w-7xl mx-auto px-6 text-center" style="font-family: 'Montserrat', sans-serif;">

            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                Welcome to <span class="text-lime-400">Rent My Ride</span>
            </h2>

            <p class="text-gray-300 text-base md:text-lg max-w-2xl mx-auto mb-16">
                Your trusted platform for hassle-free car rentals. Whether you're looking to rent a car for your next adventure or earn money by listing your vehicle, we've got you covered with a seamless and secure experience.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

                @include('home.cards', [
                    'icon' => 'fa-solid fa-car',
                    'title' => 'Wide Selection',
                    'description' => 'Browse through hundreds of vehicles ranging from economy cars to luxury sports cars. Find the perfect ride for any occasion.'
                ])

                @include('home.cards', [
                    'icon' => 'fa-solid fa-sack-dollar',
                    'title' => 'Earn Money',
                    'description' => 'Have a car sitting idle? List it on our platform and start earning passive income while helping others get on the road.'
                ])

                @include('home.cards', [
                    'icon' => 'fa-solid fa-shield-halved',
                    'title' => 'Safe & Secure',
                    'description' => 'All transactions are protected with advanced security measures. Your safety and privacy are our top priorities.'
                ])

                @include('home.cards', [
                    'icon' => 'fa-solid fa-comments',
                    'title' => 'Direct Communication',
                    'description' => 'Connect directly with car owners to ask questions and arrange bookings easily.'
                ])

                @include('home.cards', [
                    'icon' => 'fa-solid fa-tags',
                    'title' => 'Affordable Pricing',
                    'description' => 'Find rental cars that fit your budget without compromising quality.'
                ])

                @include('home.cards', [
                    'icon' => 'fa-solid fa-location-dot',
                    'title' => 'Nearby Rentals',
                    'description' => 'Discover cars available near your location for faster pickup and convenience.'
                ])

            </div>

        </div>
    </section>

    @include('home.how_it_works')

    <section class="py-16 bg-black pt-20">
    <div class="max-w-3xl mx-auto px-6 text-center" style="font-family: 'Montserrat', sans-serif;">

        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
            Ready to get started?
        </h2>

        <p class="text-gray-400 text-base md:text-lg mb-10">
            Join thousands of users already renting and listing cars on Rent My Ride. It only takes a minute to get on the road.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/available" wire:navigate class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                <i class="fa-solid fa-car mr-2"></i> Browse Available Cars
            </a>
            <a href="/garage/post-car" wire:navigate class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                <i class="fa-solid fa-plus mr-2"></i> Post Your Car
            </a>
        </div>

    </div>
</section>

<script>
    const total = 240;
    for (let i = 1; i <= total; i++) {
        const img = new Image();
        img.src = `/images/frames2/ezgif-frame_${String(i).padStart(3, '0')}.jpg`;
    }
</script>

</x-layout>