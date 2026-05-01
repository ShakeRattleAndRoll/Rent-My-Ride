<x-layout>
    <div class="bg-[#121212] min-h-screen">

        {{-- HERO SECTION --}}
        <div class="relative w-full h-[300px] flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/bg-picture-availablecars.jpg') }}" 
                 class="absolute inset-0 w-full h-full object-cover object-center"
                 alt="Rent My Ride Hero">

            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-black/40 to-black/60"></div>

            <div class="relative z-10 w-full max-w-4xl px-6 text-center" style="font-family: 'Montserrat', sans-serif;">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tighter uppercase">
                    Available <span class="text-lime-400">Inventory</span>
                </h1>

                <div class="relative w-full max-w-2xl mx-auto group">

                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <svg class="w-6 h-6 text-gray-400 group-focus-within:text-lime-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input type="text" 
                        name="search" 
                        placeholder="Search by brand, model, or category..." 
                        class="w-full pl-14 pr-6 py-4 rounded-full bg-[#1a1a1a] text-white border border-white/10 outline-none focus:border-yellow-400 transition-all shadow-lg placeholder:text-gray-600">
                    
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-20">
            
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-white font-bold uppercase tracking-widest text-sm">Results ({{ $cars->count() }})</h2>
                <div class="h-px flex-grow mx-6 bg-white/5"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($cars as $car) 
                    @include('available_cars.cards', ['car' => $car]) 
                @empty
                    <div class="col-span-full text-center py-24 bg-[#1a1a1a] rounded-3xl border border-white/5">
                        <p class="text-gray-500 font-medium">No vehicles found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>