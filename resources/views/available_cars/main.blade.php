<x-layout>
    <div class="bg-[#121212] min-h-screen">

        {{-- HERO SECTION --}}
        <div class="relative w-full h-[300px] flex items-center justify-center">
            <img src="{{ asset('images/bg-picture-availablecars.jpg') }}" 
                 class="absolute inset-0 w-full h-full object-cover object-center"
                 alt="Rent My Ride Hero">

            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-black/40 to-black/60"></div>

            <div class="relative z-10 w-full max-w-4xl px-6 text-center" style="font-family: 'Montserrat', sans-serif;">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tighter uppercase">
                    Available <span class="text-lime-400">Inventory</span>
                </h1>

                <div  x-data="{ open: false }" class="mb-8 relative">
                    <form action="{{ route('cars.index') }}" method="GET" class="relative max-w-xl mx-auto">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by brand or model (e.g. Toyota, Civic)..."
                            @focus="open = true"
                            @click.stop
                            class="w-full bg-[#1a1a1a] border border-gray-800 text-white text-sm rounded-2xl py-4 px-6 focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-yellow-400 p-2 rounded-xl text-black hover:bg-yellow-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>

                        <div 
                            x-show="open"
                            @click.outside="open = false"
                            @click.stop
                            x-transition
                            class="absolute top-full left-0 mt-3 w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl p-4 shadow-xl z-50"
                        >
                        <form method="GET" action="{{ route('cars.index') }}" class="flex flex-col gap-3">

                            <input type="hidden" name="search" :value="$el.closest('div').querySelector('input[name=search]').value">

                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min Price" class="w-1/2 p-2 rounded bg-black text-white border border-gray-700">
                                <input type="number" name="max_price" placeholder="Max Price" class="w-1/2 p-2 rounded bg-black text-white border border-gray-700">
                            </div>

                            <div class="flex gap-2">
                                <select name="fuel_type" class="w-1/2 p-2 rounded bg-black text-white border border-gray-700">
                                    <option value="">All Fuel Types</option>
                                    <option value="gasoline">Gasoline</option>
                                    <option value="diesel">Diesel</option>
                                    <option value="electric">Electric</option>
                                </select>

                                <select name="transmission" class="w-1/2 p-2 rounded bg-black text-white border border-gray-700">
                                    <option value="">All Transmission</option>
                                    <option value="automatic">Automatic</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-yellow-400 text-black py-2 rounded-lg hover:bg-yellow-300">
                                Apply Filters
                            </button>

                        </form>
                    </div>

                    @if(request('search'))
                        <div class="text-center mt-3">
                            <a href="{{ route('cars.index') }}" class="text-gray-500 hover:text-yellow-400 text-xs uppercase font-bold tracking-widest">
                                × Clear Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-20">
            
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-white font-bold uppercase tracking-widest text-sm">
                    Results ({{ $cars->filter(fn($car) => !$car->isOccupied())->count() }})
                </h2>
                <div class="h-px flex-grow mx-6 bg-white/5"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($cars as $car)
                    @if(!$car->isOccupied())
                        @include('available_cars.cards', ['car' => $car, 'carts' => $carts])
                    @endif
                    @empty
                        <div class="col-span-full text-center py-24 bg-[#1a1a1a] rounded-3xl border border-white/5">
                            <p class="text-gray-500 font-medium">No vehicles found. {{ request('search') }}</p>
                        </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>