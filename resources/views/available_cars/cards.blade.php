<div x-data="{ open: false }" class="relative car-card bg-[#1e1e1e] rounded-2xl overflow-hidden border border-white/5 hover:border-yellow-400/40 transition-all shadow-lg">

    {{-- Three dot for details of a car --}}
    {{-- Sumpayi ni por ilisda lng ang href kung mana na ang details na blade--}}
    <div class="absolute top-3 right-3 z-20">
        <button @click="open = !open" @click.away="open = false" class="p-2 bg-black/50 backdrop-blur-md rounded-full text-white hover:bg-black/80 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
            </svg>
        </button>

        <div x-show="open" 
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            class="absolute right-0 mt-2 w-40 bg-[#2a2a2a] border border-white/10 rounded-xl shadow-xl overflow-hidden z-30">
            <a href="/garage/details/{{ $car->id }}" wire:navigate class="block px-4 py-3 text-sm text-gray-300 hover:bg-yellow-400 hover:text-black font-semibold transition-colors">View Details</a>
            
            @if(Auth::check() && $car->user_id === Auth::id())
                <a href="/garage/edit/{{ $car->id }}" wire:navigate class="block px-4 py-3 text-sm text-gray-300 hover:bg-blue-500 hover:text-white font-semibold transition-colors">Edit Post</a>
            @else
                <button class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/10 font-semibold transition-colors">Report Ad</button>
            @endif
        </div>
    </div>

    {{-- Car Image --}}
    <div class="w-full h-48 bg-[#2a2a2a] overflow-hidden">
        @if($car->car_image)
            <img src="{{ asset('storage/' . $car->car_image) }}"
                 class="w-full h-full object-cover"
                 alt="{{ $car->brand }} {{ $car->model }}">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-600">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Car Details --}}
    <div class="p-4">
        <div class="flex justify-between items-start mb-1">
            <div>
                <p class="font-bold text-white text-base">{{ $car->brand }}</p>
                <p class="text-gray-400 text-sm">{{ $car->model }}</p>
            </div>
            <p class="text-white font-bold text-sm text-right">
                ₱{{ number_format($car->price) }}
                <span class="text-gray-400 font-normal">/{{ $car->rent_period }}</span>
            </p>
        </div>

        <hr class="border-white/5 my-3">

        <div class="space-y-1 text-sm text-gray-400">
            @if($car->date_owned)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($car->date_owned)->format('F j, Y') }}
                </div>
            @endif

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                {{ $car->fuel_type }}
            </div>

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                {{ $car->transmission }}
            </div>
        </div>
    </div>

    <div class="mt-auto">
        @if(Auth::check() && $car->user_id === Auth::id())
            {{-- Owner Badge --}}
            <a href="/garage/my-listing" 
            class="w-full block py-4 bg-gray-800 border border-gray-700 text-gray-400 text-xs font-['Montserrat'] font-semibold uppercase tracking-widest rounded-xl text-center">
                You Own This
            </a>
        @else
            {{-- If not the add to cart button --}}
            <form action="/cart/add" method="POST">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <button type="submit" 
                    class="w-full py-4 bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-['Montserrat'] font-bold uppercase tracking-widest rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                Add to Cart
            </button>
            </form>
        @endif
    </div>
</div>