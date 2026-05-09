<div class="relative w-full h-[600px] flex items-center justify-center overflow-hidden">

    <div
        x-data="{
            frame: 1,
            total: 240,
            dragging: false,
            lastX: 0,
            accumulated: 0,
            step: 5,
            idleTimer: null,
            direction: 1,
            reverseFrame: null,
            reverseLastTime: null,

            frameSrc() {
                return `/images/frames/ezgif-frame-${String(this.frame).padStart(3, '0')}.jpg`
            },

            syncFrameFromVideo() {
                const video = this.$refs.carVideo

                if (!video || !video.duration) return

                const progress = video.currentTime / video.duration
                this.frame = Math.min(this.total, Math.max(1, Math.round(progress * this.total)))
            },

            syncVideoFromFrame() {
                const video = this.$refs.carVideo

                if (!video || !video.duration) return

                video.currentTime = ((this.frame - 1) / this.total) * video.duration
            },

            resumeIdle() {
                const video = this.$refs.carVideo

                if (!video) return

                cancelAnimationFrame(this.reverseFrame)

                if (this.direction === -1) {
                    this.reverseVideo()
                    return
                }

                video.play()
            },

            reverseVideo(time = null) {
                const video = this.$refs.carVideo

                if (!video || !video.duration || this.dragging) return

                video.pause()

                if (this.reverseLastTime === null || time === null) {
                    this.reverseLastTime = time
                }

                const elapsed = Math.min(((time || this.reverseLastTime) - this.reverseLastTime) / 1000, 0.05)
                this.reverseLastTime = time || this.reverseLastTime
                video.currentTime = Math.max(0, video.currentTime - elapsed)

                if (video.currentTime <= 0.03) {
                    video.currentTime = 0
                    this.direction = 1
                    this.reverseLastTime = null
                    video.play()
                    return
                }

                this.reverseFrame = requestAnimationFrame((nextTime) => this.reverseVideo(nextTime))
            },

            reverseAtEnd() {
                this.direction = -1
                this.reverseLastTime = null
                this.reverseVideo()
            },

            start(event) {
                this.dragging = true
                this.lastX = event.clientX
                this.accumulated = 0
                clearTimeout(this.idleTimer)
                cancelAnimationFrame(this.reverseFrame)
                this.syncFrameFromVideo()
                this.$refs.carVideo.pause()
                this.preloadNearby()
            },

            stop() {
                if (!this.dragging) return

                this.dragging = false
                this.syncVideoFromFrame()

                if (this.frame >= this.total - 1) {
                    this.direction = -1
                }

                if (this.frame <= 2) {
                    this.direction = 1
                }

                this.idleTimer = setTimeout(() => this.resumeIdle(), 350)
            },

            move(event) {
                if (!this.dragging) return

                this.accumulated += event.clientX - this.lastX
                this.lastX = event.clientX

                while (this.accumulated >= this.step) {
                    this.frame = this.frame >= this.total ? 1 : this.frame + 1
                    this.accumulated -= this.step
                }

                while (this.accumulated <= -this.step) {
                    this.frame = this.frame <= 1 ? this.total : this.frame - 1
                    this.accumulated += this.step
                }

                this.preloadNearby()
            },

            preloadNearby() {
                for (let offset = -4; offset <= 4; offset++) {
                    let next = this.frame + offset

                    if (next < 1) next += this.total
                    if (next > this.total) next -= this.total

                    const img = new Image()
                    img.src = `/images/frames/ezgif-frame-${String(next).padStart(3, '0')}.jpg`
                }
            }
        }"
        @pointerdown.prevent="start($event)"
        @pointermove.window="move($event)"
        @pointerup.window="stop()"
        @pointercancel.window="stop()"
        class="absolute inset-0 z-0 select-none touch-none cursor-grab active:cursor-grabbing"
    >
        <video
            x-ref="carVideo"
            class="absolute inset-0 w-full h-full object-cover object-center blur-sm"
            autoplay
            muted
            playsinline
            preload="metadata"
            poster="{{ asset('images/frames/ezgif-frame-001.jpg') }}"
            aria-label="Spinning car background"
            @loadedmetadata="resumeIdle()"
            @ended="reverseAtEnd()"
        >
            <source src="{{ asset('images/frames/output.mp4') }}" type="video/mp4">
        </video>

        <img
            x-show="dragging"
            :src="frameSrc()"
            class="absolute inset-0 w-full h-full object-cover object-center blur-sm"
            draggable="false"
            alt="Interactive car spin"
            x-cloak
        >
    </div>

    <div class="absolute inset-0 z-10 pointer-events-none bg-gradient-to-t from-black via-black/25 to-black/10"></div>

    <div class="relative z-20 pointer-events-none w-full max-w-3xl px-6 text-center" style="font-family: 'Montserrat', sans-serif;">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-5 uppercase leading-tight">
            Rent My <span class="text-lime-400">Ride</span>
        </h1>
        <p class="text-gray-200 text-base md:text-xl mb-8 font-medium max-w-xl mx-auto leading-relaxed">
            Find a car for your next trip, or list yours when it is idle.
        </p>
    </div>

</div>