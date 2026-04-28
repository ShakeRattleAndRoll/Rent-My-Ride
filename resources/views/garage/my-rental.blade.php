<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    {{-- Page Header --}}
    <div class="px-10 pt-10 pb-6">
        <div class="flex items-center justify-between mb-1">
            <div>
                <h1 class="text-white text-2xl font-bold tracking-tight">My Rentals</h1>
                <p class="text-gray-400 text-sm mt-1">Cars you are currently renting or have rented</p>
            </div>

            <div class="flex items-center bg-[#1a1a1a] rounded-full p-1 gap-1 border border-gray-700">
                <a href="/garage/my-listing"
                   class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 text-gray-400 hover:text-white">
                    My Listing
                </a>
                <a href="/garage/my-rental"
                   class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-lime-400 text-black">
                    My Rental
                </a>
            </div>
        </div>
    </div>

    {{-- Rentals List --}}
    <div class="px-10 pb-10 space-y-4">

        @forelse ($rentals as $rental)
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex items-center gap-5 p-4 hover:border-gray-600 transition-all duration-200">

            {{-- Car Image: Check if your column is 'image' or 'car_image' --}}
            <div class="w-44 h-28 rounded-xl overflow-hidden shrink-0 bg-gray-800">
                <img src="{{ asset('storage/' . ($rental->car->car_image ?? $rental->car->image)) }}"
                     alt="{{ $rental->car->brand }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Rental Info --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-white text-lg font-bold leading-tight">{{ $rental->car->brand }}</h2>
                <p class="text-gray-400 text-sm mb-3">{{ $rental->car->model }}</p>

                <div class="flex flex-wrap gap-x-5 gap-y-1 text-gray-400 text-xs">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{-- Laravel handles the dates automatically if they are cast as dates in the model --}}
                        {{ \Carbon\Carbon::parse($rental->start_date)->format('M j') }} – {{ \Carbon\Carbon::parse($rental->end_date)->format('M j, Y') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16M3 22h14M13 8h2a2 2 0 012 2v3a1 1 0 001 1h0a1 1 0 001-1V9l-3-3"/>
                        </svg>
                        {{ $rental->car->fuel_type ?? 'Gasoline' }}
                    </span>
                </div>
            </div>

            {{-- Price + Status --}}
            <div class="flex flex-col items-end gap-2 shrink-0">
                <p class="text-white font-bold text-base">
                    {{-- Match 'price' or 'price_per_day' to your migration --}}
                    ₱{{ number_format($rental->car->price ?? $rental->car->price_per_day, 0) }}
                    <span class="text-gray-400 font-normal text-sm">/day</span>
                </p>

                @php
                    $now = now();
                    $start = \Carbon\Carbon::parse($rental->start_date);
                    $end = \Carbon\Carbon::parse($rental->end_date);
                    $isActive = $now->between($start, $end);
                    $isUpcoming = $now->lt($start);
                @endphp

                @if ($isActive)
                    <span class="bg-lime-400 text-black text-xs font-bold px-4 py-1.5 rounded-full">Active</span>
                @elseif ($isUpcoming)
                    <span class="bg-yellow-400 text-black text-xs font-bold px-4 py-1.5 rounded-full">Upcoming</span>
                @else
                    <span class="bg-gray-700 text-gray-300 text-xs font-bold px-4 py-1.5 rounded-full">Completed</span>
                @endif

                <a href="/garage/rental/{{ $rental->id }}"
                   class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-xs font-bold px-5 py-2 rounded-full transition-all duration-200 w-32 text-center">
                    View Details
                </a>
            </div>
        </div>
        @empty
        {{-- This shows if $rentals is empty --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M5 17H3a1 1 0 01-1-1v-5l2.5-6h11L18 11v5a1 1 0 01-1 1h-2M5 17a2 2 0 104 0M5 17a2 2 0 114 0M15 17a2 2 0 104 0M15 17a2 2 0 114 0"/>
                </svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">No rentals yet</p>
            <p class="text-gray-600 text-xs mt-1 mb-5">Cars you rent will appear here</p>
            <a href="/available" class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                Browse Cars
            </a>
        </div>
        @endforelse

    </div>
</div>
</x-layout>