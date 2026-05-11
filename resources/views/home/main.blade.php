<x-layout>

    @include('home.background')

    @php
        $featured = $featuredCars ?? collect();
        $totalCars = $homeStats['cars'] ?? $featured->count();
        $totalOwners = $homeStats['owners'] ?? 0;
    @endphp

    <section class="bg-black border-y border-white/10 py-8">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-4" style="font-family: 'Montserrat', sans-serif;">
            <div class="group rounded-lg border border-white/10 bg-[#111] p-5 transition hover:border-lime-400/40">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-gray-500 text-[11px] font-black uppercase tracking-[0.18em] mb-2">Inventory</p>
                        <p class="text-white text-3xl font-black leading-none">{{ $totalCars }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400 group-hover:bg-lime-400 group-hover:text-black transition">
                        <i class="fa-solid fa-car-side text-xl"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-400">Listed rides ready to browse</p>
            </div>

            <div class="group rounded-lg border border-white/10 bg-[#111] p-5 transition hover:border-lime-400/40">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-gray-500 text-[11px] font-black uppercase tracking-[0.18em] mb-2">Community</p>
                        <p class="text-white text-3xl font-black leading-none">{{ $totalOwners }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400 group-hover:bg-lime-400 group-hover:text-black transition">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-400">Local owners sharing their cars</p>
            </div>

            <div class="group rounded-lg border border-white/10 bg-[#111] p-5 transition hover:border-lime-400/40">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-gray-500 text-[11px] font-black uppercase tracking-[0.18em] mb-2">Booking</p>
                        <p class="text-white text-3xl font-black leading-none">Direct</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400 group-hover:bg-lime-400 group-hover:text-black transition">
                        <i class="fa-solid fa-comments text-xl"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-400">Chat with owners before you reserve</p>
            </div>
        </div>
    </section>

    @include('home.featured_rides')

    <section class="bg-[#050505] py-20 border-y border-white/10">
        <div class="max-w-7xl mx-auto px-6" style="font-family: 'Montserrat', sans-serif;">
            <div class="max-w-3xl mb-10">
                <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-3">Choose your lane</p>
                <h2 class="text-2xl md:text-4xl font-black text-white mb-4">Built for both sides of the rental</h2>
                <p class="text-gray-300 leading-relaxed">A clean flow for people who need a ride and people who want their car to earn.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-lg border border-white/10 bg-black p-7">
                    <div class="mb-8 flex h-12 w-12 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400">
                        <i class="fa-solid fa-key text-xl"></i>
                    </div>
                    <h3 class="text-white text-2xl font-black mb-3">For renters</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Browse available cars, compare the essentials, and message the owner before requesting the ride.</p>
                    <a href="/available" wire:navigate class="text-lime-300 font-black uppercase text-sm hover:text-lime-200 transition">
                        Find a ride <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <div class="rounded-lg border border-white/10 bg-black p-7">
                    <div class="mb-8 flex h-12 w-12 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400">
                        <i class="fa-solid fa-sack-dollar text-xl"></i>
                    </div>
                    <h3 class="text-white text-2xl font-black mb-3">For owners</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Post your car, manage requests, and keep control of who rents it and when it is available.</p>
                    <a href="/garage/post-car" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="text-lime-300 font-black uppercase text-sm hover:text-lime-200 transition">
                        List a car <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('home.how_it_works')

    <section class="py-20 bg-black">
        <div class="max-w-7xl mx-auto px-6" style="font-family: 'Montserrat', sans-serif;">
            <div class="rounded-lg border border-white/10 bg-[#111] px-6 py-12 md:px-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <p class="text-lime-400 text-xs font-black uppercase tracking-[0.22em] mb-3">Start today</p>
                    <h2 class="text-2xl md:text-4xl font-black text-white mb-3">Ready when you are</h2>
                    <p class="text-gray-300 max-w-2xl leading-relaxed">Pick a ride for the next trip, or make your own car available.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/available" wire:navigate class="px-8 py-3 bg-lime-400 text-black rounded-lg font-black uppercase text-sm hover:bg-lime-300 transition text-center">
                        <i class="fa-solid fa-car mr-2"></i> Browse Cars
                    </a>
                    <a href="/garage/post-car" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="px-8 py-3 border border-lime-400/40 text-lime-300 rounded-lg font-black uppercase text-sm hover:bg-lime-400 hover:text-black transition text-center">
                        <i class="fa-solid fa-plus mr-2"></i> Post a Car
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-layout>
