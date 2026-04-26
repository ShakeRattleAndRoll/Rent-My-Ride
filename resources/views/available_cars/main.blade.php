<x-layout>
    <div class="bg-[#121212] min-h-screen pb-20">

        <div class="w-full flex flex-col items-center justify-center pt-20 pb-12 px-4 bg-[#0d0d0d] border-b border-white/5">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tighter uppercase">
                Available <span class="text-yellow-400">Inventory</span>
            </h1>
            
            <div class="w-full max-w-2xl relative">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       placeholder="Search by brand, model, or category..." 
                       class="w-full pl-14 pr-6 py-4 rounded-xl bg-[#1a1a1a] text-white border border-white/10 outline-none focus:border-yellow-400 transition-all shadow-lg placeholder:text-gray-600">
            </div>
        </div>

        {{-- Filter (not done)--}}
        <div class="max-w-7xl mx-auto px-6 mt-12">
            
            <div class="flex items-center gap-4 mb-10 text-sm font-bold uppercase tracking-widest text-gray-500">
                <span class="text-yellow-400 cursor-pointer">All</span>
                <span class="text-white cursor-pointer hover:text-lime-400">USV</span>
                <span class="text-white cursor-pointer hover:text-lime-400">Sedan</span>
                <span class="text-white cursor-pointer hover:text-lime-400">Van</span>
                <span class="text-white cursor-pointer hover:text-lime-400">Pickup</span>
                <span class="text-white cursor-pointer hover:text-lime-400">AUV</span>
                <div class="h-[1px] flex-grow bg-white/5 ml-4"></div>
            </div>

            {{-- Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($cars as $car) 
                    @include('available_cars.cards', ['car' => $car]) 
                @empty
                    <div class="col-span-full text-center py-24 bg-[#1a1a1a] rounded-2xl border border-white/5">
                        <p class="text-gray-500 font-medium">No vehicles matching your criteria were found.</p>
                        <button onclick="window.location.reload()" class="mt-4 text-sm text-yellow-400 font-bold uppercase tracking-widest">Refresh List</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>