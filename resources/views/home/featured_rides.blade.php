<section class="py-20 bg-black">
    <div class="max-w-7xl mx-auto px-6" style="font-family: 'Montserrat', sans-serif;">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-3">Featured rides</p>
                <h2 class="text-2xl md:text-4xl font-black text-white">
                    Recently added cars
                </h2>
            </div>
            <a href="/available" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-lg border border-lime-400/40 px-5 py-3 text-sm font-black uppercase text-lime-300 hover:bg-lime-400 hover:text-black transition">
                View inventory <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        @if($featured->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featured as $car)
                    <div
                        x-data="{ open: false }"
                        class="group relative overflow-hidden rounded-lg border border-white/10 bg-[#111] hover:border-lime-400/50 transition"
                    >
                        <button type="button" @click="open = true" class="absolute inset-0 z-10 cursor-pointer" aria-label="View {{ $car->brand }} {{ $car->model }}"></button>
                        <div class="h-48 bg-[#1d1d1d] overflow-hidden">
                            @if($car->car_image)
                                <img src="{{ asset('storage/' . $car->car_image) }}"
                                     alt="{{ $car->brand }} {{ $car->model }}"
                                     class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-gray-600">
                                    <i class="fa-regular fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-white font-black text-lg leading-tight">{{ $car->brand }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $car->model }}</p>
                                </div>
                                <p class="text-lime-300 font-black text-right">
                                    &#8369;{{ number_format($car->price) }}
                                    <span class="block text-[11px] text-gray-500 font-bold">per {{ $car->rent_unit }}</span>
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-300">
                                <span class="rounded-full bg-white/[0.05] px-3 py-1">{{ $car->fuel_type }}</span>
                                <span class="rounded-full bg-white/[0.05] px-3 py-1">{{ $car->transmission }}</span>
                            </div>
                        </div>
                        <x-modals.car_modal :car="$car" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-lg border border-white/10 bg-[#111] px-6 py-14 text-center">
                <i class="fa-solid fa-car text-lime-400 text-3xl mb-4"></i>
                <h3 class="text-white font-black text-xl mb-2">No featured cars yet</h3>
                <p class="text-gray-400 mb-6">Be the first to add a car to the marketplace.</p>
                <a href="/garage/post-car" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-lime-400 px-6 py-3 text-sm font-black uppercase text-black hover:bg-lime-300 transition">
                    Post Your Car
                </a>
            </div>
        @endif
    </div>
</section>