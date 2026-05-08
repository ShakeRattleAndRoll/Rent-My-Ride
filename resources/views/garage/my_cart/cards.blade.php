<div
    x-data="{ open: false }"
    class="relative bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex items-center gap-5 p-4 hover:border-gray-600 transition-all duration-200"
>
    {{-- Clickable overlay to open modal --}}
    <div @click="open = true" class="absolute inset-0 z-10 block cursor-pointer" aria-label="View Details"></div>

    {{-- Car Image --}}
    <div class="w-44 h-28 rounded-xl overflow-hidden shrink-0 bg-gray-800">
        <img src="{{ asset('storage/' . $cart->car->car_image) }}"
             alt="{{ $cart->car->brand }} {{ $cart->car->model }}"
             class="w-full h-full object-cover">
    </div>

    {{-- Car Info --}}
    <div class="flex-1 min-w-0">
        <h2 class="text-white text-lg font-bold leading-tight">{{ $cart->car->brand }}</h2>
        <p class="text-gray-400 text-sm mb-3">{{ $cart->car->model }}</p>

        <div class="flex flex-wrap gap-x-5 gap-y-1 text-gray-400 text-xs mb-3">
            <span class="flex items-center gap-1.5 text-gray-400 text-xs">
                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span>{{ \Carbon\Carbon::parse($cart->car->date_owned)->format('M d, Y') }}</span>
            </span>

            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16M3 22h14M13 8h2a2 2 0 012 2v3a1 1 0 001 1h0a1 1 0 001-1V9l-3-3"/>
                </svg>
                {{ $cart->car->fuel_type }}
            </span>

            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
                {{ $cart->car->transmission }}
            </span>
        </div>

        <div class="mt-2 flex items-center gap-2">
            @if ($cart->car->isOccupied())
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-red-500 text-black text-[10px] font-black uppercase rounded-md shadow-[0_0_10px_rgba(239,68,68,0.3)]">
                        Occupied
                    </span>
                    <p class="text-red-500/70 text-[10px] font-medium italic">
                        Currently in use by another renter
                    </p>
                </div>
            @else
                <span class="px-2 py-0.5 bg-lime-400/10 text-lime-400 text-[10px] font-bold uppercase rounded-md border border-lime-400/20">
                    Daily Rate
                </span>
                <p class="text-gray-500 text-xs">
                    Select dates at checkout to see total
                </p>
            @endif
        </div>
    </div>

    {{-- Price + Actions --}}
    <div class="flex flex-col items-end gap-2 shrink-0 relative z-20">
        <p class="text-white font-bold text-base">
            ₱{{ number_format($cart->car->price, 0) }}
            <span class="text-gray-400 font-normal text-sm">/ {{ $cart->car->rent_unit }}</span>
        </p>

        {{-- Checkout --}}
        @if($cart->car->isOccupied())
            <button type="button" disabled
                    class="bg-gray-800 text-gray-500 text-xs font-bold px-5 py-2 rounded-full w-32 cursor-not-allowed">
                Occupied
            </button>
        @else
            <button type="button"
                    onclick="openRentModal({{ $cart->id }}, '{{ $cart->car->rent_unit }}', {{ $cart->car->price }})"
                    class="bg-lime-400 hover:bg-lime-300 text-black text-xs font-bold px-5 py-2 rounded-full transition-all duration-200 w-32 text-center">
                Rent Now
            </button>
        @endif

        {{-- Remove --}}
        <form method="POST" action="{{ route('cart.remove', $cart->id) }}" data-livewire-form>
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-[#2a2a2a] hover:bg-red-500 border border-gray-700 hover:border-red-500 text-gray-400 hover:text-white text-xs font-bold px-5 py-2 rounded-full w-32">
                Remove
            </button>
        </form>
    </div>

    {{-- Modal --}}
    <x-modals.car_modal :car="$cart->car"/>

</div>
