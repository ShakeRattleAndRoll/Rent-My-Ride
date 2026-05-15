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

                {{-- Search and filter area --}}
                <div x-data="{ open: false }" class="mb-8 relative">
                    <form action="{{ route('cars.index') }}" method="GET" class="max-w-xl mx-auto">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search brand or model..."
                                class="w-full bg-[#1a1a1a] border border-gray-800 text-white text-sm rounded-2xl py-4 px-6 pr-24 focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all"
                            >
                            
                            {{-- Filter Toggle --}}
                            <button title="Filters" type="button" @click="open = !open" class="absolute right-14 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            </button>

                            {{-- Search Submit --}}
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-yellow-400 p-2 rounded-xl text-black hover:bg-yellow-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>

                        {{-- Dropdown filter panel --}}
                        <div 
                            x-show="open"
                            x-cloak
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute top-full left-0 mt-3 w-full bg-[#1a1a1a] border border-gray-800 rounded-3xl p-6 shadow-2xl z-50 text-left"
                        >
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                {{-- Brand --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] uppercase font-bold text-gray-500 ml-1">Brand</label>
                                    <select name="brand" class="w-full bg-black border border-gray-800 text-white rounded-lg p-2 text-sm focus:border-yellow-400 outline-none">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ ucfirst($brand) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Model --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] uppercase font-bold text-gray-500 ml-1">Model</label>
                                    <select name="model" class="w-full bg-black border border-gray-800 text-white rounded-lg p-2 text-sm focus:border-yellow-400 outline-none">
                                        <option value="">All Models</option>
                                        @foreach($models as $model)
                                            <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ ucfirst($model) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                {{-- Price Range --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] uppercase font-bold text-gray-500 ml-1">Price Range</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-1/2 bg-black border border-gray-800 text-white rounded-lg p-2 text-sm">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-1/2 bg-black border border-gray-800 text-white rounded-lg p-2 text-sm">
                                    </div>
                                </div>
                                {{-- Specs --}}
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[10px] uppercase font-bold text-gray-500 ml-1">Fuel</label>
                                        <select name="fuel_type" class="w-full bg-black border border-gray-800 text-white rounded-lg p-2 text-sm">
                                            <option value="">All</option>
                                            <option value="gasoline" {{ request('fuel_type') == 'gasoline' ? 'selected' : '' }}>Gas</option>
                                            <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>EV</option>
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[10px] uppercase font-bold text-gray-500 ml-1">Trans.</label>
                                        <select name="transmission" class="w-full bg-black border border-gray-800 text-white rounded-lg p-2 text-sm">
                                            <option value="">All</option>
                                            <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Auto</option>
                                            <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- SUBMIT & RESET --}}
                            <div class="flex gap-3 pt-2">
                                <a href="{{ route('cars.index') }}" class="flex-1 text-center py-3 rounded-xl border border-gray-800 text-gray-400 hover:bg-white/5 transition-all text-xs font-bold uppercase tracking-widest leading-loose">
                                    Reset
                                </a>
                                <button type="submit" class="flex-[2] bg-yellow-400 text-black py-3 rounded-xl hover:bg-yellow-300 font-bold uppercase tracking-widest transition-all">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="relative max-w-7xl mx-auto px-6 py-20">
            
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-white font-bold uppercase tracking-widest text-sm">
                    Results ({{ $cars->total() }})
                </h2>
                <div class="h-px flex-grow mx-6 bg-white/5"></div>
            </div>

            {{-- Car --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($cars as $car)
                    @include('available_cars.cards', ['car' => $car, 'carts' => $carts])
                @empty
                    <div class="col-span-full text-center py-24 bg-[#1a1a1a] rounded-3xl border border-white/5">
                        <p class="text-gray-500 font-medium">No vehicles found. Try adjusting your filters.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="w-full mt-20 mb-10">
                {{ $cars->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-layout>
