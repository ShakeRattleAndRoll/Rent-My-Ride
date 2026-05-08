<div
    x-data="{ open: false, profileOpen: false}"
    class="relative car-card bg-[#1e1e1e] rounded-2xl overflow-hidden border border-white/5 hover:border-yellow-400/40 transition-all shadow-lg flex flex-col h-full"
>
    {{-- Clickable overlay --}}
    <div @click="open = true" class="absolute inset-0 z-10 block cursor-pointer" aria-label="View Details"></div>

    {{-- Car Image --}}
    <div class="w-full h-48 bg-[#2a2a2a] overflow-hidden">
        @if($car->car_image)
            <img src="{{ asset('storage/' . $car->car_image) }}"
                 class="w-full h-full object-cover"
                 alt="{{ $car->brand }} {{ $car->model }}">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-600">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Car Details --}}
    <div class="p-4 flex-grow">
        <div class="flex justify-between items-start mb-1">
            <div>
                <p class="font-bold text-white text-base">{{ $car->brand }}</p>
                <p class="text-gray-400 text-sm">{{ $car->model }}</p>
            </div>
            <p class="text-white font-bold text-sm text-right">
                ₱{{ number_format($car->price) }}
                <span class="text-gray-400 font-normal block text-[10px]">/per {{ $car->rent_unit }}</span>
            </p>
        </div>

        <hr class="border-white/5 my-3">

        <div class="space-y-1 text-sm text-gray-400">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-calendar w-4 text-gray-500"></i>
                {{ \Carbon\Carbon::parse($car->date_owned)->format('M j, Y') }}
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gas-pump w-4 text-gray-500"></i>
                {{ $car->fuel_type }}
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gear w-4 text-gray-500"></i>
                {{ $car->transmission }}
            </div>
        </div>
    </div>

    <div class="mt-auto p-4 pt-0 relative z-20" style="font-family: 'Montserrat', sans-serif;">
        @if(Auth::check() && $car->user_id === Auth::id())
            <a href="/garage/my-listing" wire:navigate data-nav-navigate
            class="w-full block py-3 bg-gray-800 border border-white/5 text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-xl text-center">
                You Own This
            </a>

        @elseif(Auth::check() && in_array($car->id, $pendingRequests))
            <div class="w-full py-3 bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold uppercase rounded-xl text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-spinner animate-spin"></i>
                Pending Approval
            </div>

        @elseif(Auth::check() && $carts->contains('car_id', $car->id))
            <div class="w-full py-3 bg-[#242424] border border-yellow-400/20 text-yellow-400 text-[10px] font-bold uppercase tracking-widest rounded-xl text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i>
                Already in Cart
            </div>

        @else 
            <form action="/cart/add" method="POST" data-livewire-form>
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <button type="submit"
                        class="w-full py-3 bg-yellow-400 hover:bg-yellow-300 text-black text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i>
                    Add to Cart
                </button>
            </form>
        @endif
    </div>

    <x-modals.car_modal :car="$car" />

</div>
