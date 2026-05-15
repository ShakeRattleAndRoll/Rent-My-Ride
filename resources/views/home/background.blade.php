<section class="relative flex h-[640px] w-full items-center justify-center overflow-hidden bg-[#070a10]">

    {{-- 3-D canvas --}}
    <div
        id="car-viewer"
        class="absolute inset-0"
        aria-label="Interactive car showcase"
    ></div>

    {{-- Overlay corner --}}
    <div class="absolute inset-0 z-10 pointer-events-none"
         style="background: radial-gradient(
             ellipse 72% 65% at 58% 50%,
             transparent 0%,
             rgba(0,0,0,0.32) 52%,
             rgba(0,0,0,0.80) 82%,
             #000 100%
         );">
    </div>

    {{-- ── Overlay bottom scrim --}}
    <div class="absolute inset-x-0 bottom-0 z-10 h-52 pointer-events-none
                bg-gradient-to-t from-black via-black/75 to-transparent">
    </div>

    {{-- ── Overlay left scrim --}}
    <div class="absolute inset-y-0 left-0 z-10 w-[42%] pointer-events-none
                bg-gradient-to-r from-black/70 to-transparent">
    </div>

    {{-- Text --}}
    <div class="absolute left-6 top-8 z-20 pointer-events-none max-w-sm md:left-10"
         style="font-family: 'Montserrat', sans-serif;">

        <p class="text-lime-400 text-[10px] font-black uppercase tracking-[0.28em] mb-3
                  drop-shadow-[0_1px_6px_rgba(0,0,0,0.8)]">
            Interactive showcase
        </p>

        <h1 class="text-3xl md:text-5xl font-black text-white uppercase leading-none
                   drop-shadow-[0_2px_16px_rgba(0,0,0,0.7)]">
            Rent My <span class="text-lime-400">Ride</span>
        </h1>

        <p class="mt-4 text-white/55 text-sm md:text-base font-medium leading-relaxed
                  drop-shadow-[0_1px_8px_rgba(0,0,0,0.9)]">
            Inspect the car, then find a ride or list your own.
        </p>

    </div>

    {{-- Slider --}}
    <div class="car-showcase-controls
                absolute bottom-5 left-1/2 z-30
                w-[min(42rem,calc(100vw-2rem))] -translate-x-1/2
                flex flex-col gap-2.5
                rounded-2xl px-5 py-4
                bg-black/55 border border-white/10
                backdrop-blur-md">

        {{-- View / Pan row --}}
        <label class="flex items-center gap-3" for="car-pan-control">
            <span class="w-9 text-[10px] font-black uppercase tracking-[0.18em] text-white/40"
                  style="font-family: 'Montserrat', sans-serif;">
                View
            </span>
            <input
                id="car-pan-control"
                type="range" min="0" max="360" value="0" step="1"
                aria-label="Rotate view around the car"
                class="flex-1 car-slider"
            >
            <span class="w-10 text-right text-[11px] font-bold text-lime-400 tabular-nums"
                  id="car-pan-val"
                  style="font-family: 'Montserrat', sans-serif;">
                0°
            </span>
        </label>

        {{-- Zoom row --}}
        <label class="flex items-center gap-3" for="car-zoom-control">
            <span class="w-9 text-[10px] font-black uppercase tracking-[0.18em] text-white/40"
                  style="font-family: 'Montserrat', sans-serif;">
                Zoom
            </span>
            <input
                id="car-zoom-control"
                type="range" min="3.8" max="8.5" value="6.2" step="0.1"
                aria-label="Zoom car in or out"
                class="flex-1 car-slider"
            >
            <span class="w-10 text-right text-[11px] font-bold text-lime-400 tabular-nums"
                  id="car-zoom-val"
                  style="font-family: 'Montserrat', sans-serif;">
                6.2×
            </span>
        </label>

    </div>

</section>

{{-- Slider styles --}}
{{-- walay style sa tailwind para ani --}}
<style>
    .car-slider {
        -webkit-appearance: none;
        appearance: none;
        height: 3px;
        border-radius: 2px;
        background: rgba(255,255,255,0.15);
        outline: none;
        cursor: pointer;
    }
    .car-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #a3e635;
        border: 2px solid #060a04;
        box-shadow: 0 0 0 3px rgba(163,230,53,0.25);
        cursor: pointer;
    }
    .car-slider::-moz-range-thumb {
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #a3e635;
        border: 2px solid #060a04;
        cursor: pointer;
    }
    .car-slider:focus-visible {
        outline: 2px solid #a3e635;
        outline-offset: 4px;
    }
</style>