{{-- Cart item card --}}
<div
    x-data="{ open: false }"
    class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-[#121212] shadow-[0_0_0_1px_rgba(255,255,255,0.02)] transition-all duration-300 hover:-translate-y-1 hover:border-lime-400/40 hover:shadow-2xl"
>

    {{-- Clickable overlay --}}
    <div @click="open = true" class="absolute inset-0 z-10 cursor-pointer" aria-label="View Details"></div>

    {{-- IMAGE --}}
    <div class="relative aspect-[16/10] w-full overflow-hidden rounded-t-2xl bg-gray-800">

        <img src="{{ asset('storage/' . $cart->car->car_image) }}"
             alt="{{ $cart->car->brand }} {{ $cart->car->model }}"
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        {{-- PRICE (IMAGE OVERLAY) --}}
        <div class="absolute bottom-3 left-3 rounded-xl bg-black/70 px-3 py-2 backdrop-blur">
            <p class="text-sm font-black text-white">
                ₱{{ number_format($cart->car->price, 0) }}
                <span class="text-[10px] text-gray-400">
                    / {{ $cart->car->rent_unit }}
                </span>
            </p>
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="flex flex-1 flex-col p-5">

        {{-- TITLE --}}
        <div class="min-w-0">
            <h2 class="truncate text-xl font-black tracking-tight text-white">
                {{ $cart->car->brand }}
            </h2>

            <p class="mt-1 truncate text-sm text-gray-400">
                {{ $cart->car->model }}
            </p>
        </div>

        {{-- INFO ROW (MATCHED STYLE) --}}
        <div class="mt-5 flex items-center justify-between gap-3 text-xs text-gray-300">

            {{-- Date --}}
            <div class="flex items-center gap-2 whitespace-nowrap">
                <i class="fa-regular fa-calendar text-gray-400"></i>
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($cart->car->date_owned)->format('M d, Y') }}
                </span>
            </div>

            <div class="h-4 w-px bg-white/10"></div>

            {{-- Fuel --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gas-pump text-gray-400"></i>
                <span class="font-semibold">
                    {{ $cart->car->fuel_type }}
                </span>
            </div>

            <div class="h-4 w-px bg-white/10"></div>

            {{-- Transmission --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gear text-gray-400"></i>
                <span class="font-semibold">
                    {{ $cart->car->transmission }}
                </span>
            </div>

        </div>

        {{-- NOTE --}}
        <div class="mt-4 flex items-center gap-2">
            <span class="rounded-md border border-lime-400/20 bg-lime-400/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest text-lime-400">
                Daily Rate
            </span>
            <p class="text-xs text-gray-500">
                Select dates at checkout to see total
            </p>
        </div>

        {{-- ACTIONS --}}
        <div class="relative z-20 mt-auto pt-5">

            <div class="grid grid-cols-2 gap-2">

                {{-- Rent Now --}}
                <button type="button"
                        onclick="openRentModal({{ $cart->id }}, '{{ $cart->car->rent_unit }}', {{ $cart->car->price }})"
                        class="rounded-xl bg-lime-400 px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-black hover:bg-lime-300">
                    Rent Now
                </button>

                {{-- Remove --}}
                <form method="POST" action="{{ route('cart.remove', $cart->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-xl bg-red-500 px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-white hover:bg-red-400">
                        Remove
                    </button>
                </form>

            </div>

        </div>

    </div>

    {{-- MODAL --}}
    <x-modals.car_modal :car="$cart->car"/>

</div>
