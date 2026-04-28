<div class="car-card bg-[#1e1e1e] rounded-2xl overflow-hidden border border-white/5 hover:border-yellow-400/40 transition-all shadow-lg">

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

        <div class="flex justify-end mt-4">
            <form action="/rent" method="POST">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                
                <button type="submit" class="p-2 bg-[#2a2a2a] rounded-full hover:bg-yellow-400 hover:text-black transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>