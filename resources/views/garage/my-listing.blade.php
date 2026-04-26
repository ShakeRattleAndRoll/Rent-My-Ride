<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    {{-- Page Header --}}
    <div class="px-10 pt-10 pb-6">
        <div class="flex items-center justify-between mb-1">
            <div>
                <h1 class="text-white text-2xl font-bold tracking-tight">My Listings</h1>
                <p class="text-gray-400 text-sm mt-1">Manage your posted cars</p>
            </div>

            {{-- Toggle: My Listing / My Rental --}}
            <div class="flex items-center bg-[#1a1a1a] rounded-full p-1 gap-1 border border-gray-700">
                <a href="/garage/my-listing"
                   class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-lime-400 text-black">
                    My Listing
                </a>
                <a href="/garage/my-rental"
                   class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 text-gray-400 hover:text-white">
                    My Rental
                </a>
            </div>
        </div>
    </div>

    {{-- Listings --}}
    <div class="px-10 pb-10 space-y-4">

        @forelse ($listings as $car)
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex items-center gap-5 p-4 hover:border-gray-600 transition-all duration-200">

            {{-- Car Image --}}
            <div class="w-44 h-28 rounded-xl overflow-hidden shrink-0 bg-gray-800">
                <img src="{{ asset('storage/' . $car->image) }}"
                     alt="{{ $car->brand }} {{ $car->model }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Car Info --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-white text-lg font-bold leading-tight">{{ $car->brand }}</h2>
                <p class="text-gray-400 text-sm mb-3">{{ $car->model }}</p>

                <div class="flex flex-wrap gap-x-5 gap-y-1 text-gray-400 text-xs">
                    <span class="flex items-center gap-1.5">
                        {{-- Calendar icon --}}
                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($car->created_at)->format('F j, Y') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        {{-- Fuel icon --}}
                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16M3 22h14M13 8h2a2 2 0 012 2v3a1 1 0 001 1h0a1 1 0 001-1V9l-3-3"/>
                        </svg>
                        {{ $car->fuel_type }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        {{-- Gear icon --}}
                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                        </svg>
                        {{ $car->transmission }}
                    </span>
                </div>
            </div>

            {{-- Price + Actions --}}
            <div class="flex flex-col items-end gap-2 shrink-0">
                <p class="text-white font-bold text-base">₱{{ number_format($car->price_per_day, 0) }} <span class="text-gray-400 font-normal text-sm">/day</span></p>

                <a href="/garage/edit/{{ $car->id }}"
                   class="bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-bold px-5 py-2 rounded-full transition-all duration-200 w-32 text-center">
                    Edit Post
                </a>

                <form method="POST" action="/garage/delete/{{ $car->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-bold px-5 py-2 rounded-full transition-all duration-200 w-32 text-center">
                        Delete Post
                    </button>
                </form>
            </div>

        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">No listings yet</p>
            <p class="text-gray-600 text-xs mt-1 mb-5">Cars you post for rent will appear here</p>
            <a href="/post-car"
            class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                Post a Car
            </a>
        </div>
        @endforelse

    </div>

</div>
</x-layout>