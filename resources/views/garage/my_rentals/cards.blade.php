<div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-[#121212] shadow-[0_0_0_1px_rgba(255,255,255,0.02)] transition-all duration-300 hover:-translate-y-1 hover:border-lime-400/40 hover:shadow-2xl"
     data-rental-card="{{ $rental->id }}">

    @php
        $now        = \Carbon\Carbon::now();
        $start      = \Carbon\Carbon::parse($rental->start_date);
        $end        = \Carbon\Carbon::parse($rental->end_date);
        $isPending  = $rental->status === 'pending';
        $isUpcoming = $rental->status === 'accepted' && $now->lt($start);
        $isActive   = $rental->status === 'accepted' && $now->between($start, $end);
        $isFinished = $rental->status === 'accepted' && $now->gt($end);
        $isDeclined = $rental->status === 'denied';
    @endphp

    {{-- IMAGE --}}
    <div class="relative aspect-[16/10] w-full overflow-hidden rounded-t-2xl bg-gray-800">

        <img src="{{ asset('storage/' . ($isPending ? $rental->car->car_image : $rental->snap_car_image)) }}"
            alt="{{ $isPending ? $rental->car->brand : $rental->snap_brand }}"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        {{-- PRICE + DAYS --}}
        <div class="absolute bottom-3 left-3 flex flex-col gap-1">

            <div class="rounded-xl bg-black/70 px-3 py-2 backdrop-blur">
                <p class="text-sm font-black text-white">
                    ₱{{ number_format($isPending ? $rental->car->price : $rental->snap_price, 0) }}
                    <span class="text-[10px] text-gray-400">
                        / {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }}
                    </span>
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-lg bg-black/60 px-2.5 py-1 backdrop-blur">
                <i class="fa-regular fa-clock text-[10px] text-gray-300"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-200">
                    {{ $rental->days }} {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }} (s)
                </span>
            </div>

        </div>

        {{-- STATUS --}}
        <div class="absolute right-3 top-3 flex flex-col items-end gap-2">

            @if ($isPending)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-yellow-400 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-black shadow-lg">
                    Pending
                </span>
            @elseif ($isUpcoming)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-blue-500/90 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white">
                    Upcoming
                </span>
            @elseif ($isActive)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-lime-400 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-black">
                    Active
                </span>
            @elseif ($isFinished)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-white/10 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white">
                    Completed
                </span>
            @elseif ($isDeclined)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-red-500 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white">
                    Declined
                </span>
            @endif

        </div>

    </div>

    {{-- CONTENT --}}
    <div class="flex flex-1 flex-col p-5">

        {{-- TITLE --}}
        <div class="min-w-0">
            <h2 class="truncate text-xl font-black tracking-tight text-white">
                {{ $isPending ? $rental->car->brand : $rental->snap_brand }}
            </h2>

            <p class="mt-1 truncate text-sm text-gray-400">
                {{ $isPending ? $rental->car->model : $rental->snap_model }}
            </p>
        </div>

        {{-- INFO ROW (MATCH CAR DESIGN STYLE) --}}
        <div class="mt-5 flex items-center justify-between gap-3 text-xs text-gray-300">

            {{-- Date Owned --}}
            <div class="flex items-center gap-2 whitespace-nowrap">
                <i class="fa-regular fa-calendar text-gray-400"></i>
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($isPending ? $rental->car->date_owned : $rental->snap_date_owned)->format('M d, Y') }}
                </span>
            </div>

            <div class="h-4 w-px bg-white/10"></div>

            {{-- Fuel --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gas-pump text-gray-400"></i>
                <span class="font-semibold">
                    {{ $isPending ? $rental->car->fuel_type : $rental->snap_fuel_type }}
                </span>
            </div>

            <div class="h-4 w-px bg-white/10"></div>

            {{-- Transmission --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gear text-gray-400"></i>
                <span class="font-semibold">
                    {{ $isPending ? $rental->car->transmission : $rental->snap_transmission }}
                </span>
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="mt-auto pt-5">

            @if ($isPending)
                <button type="button"
                    onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="w-full rounded-xl bg-red-500 px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-white hover:bg-red-400">
                    Cancel Request
                </button>

            @elseif ($isUpcoming || $isActive)
                <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="w-full rounded-xl border border-white/10 bg-[#1b1b1b] px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-gray-300 hover:bg-[#242424] hover:text-white">
                    View Details
                </button>

            @else
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                        class="rounded-xl border border-white/10 bg-[#1b1b1b] px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-gray-300 hover:bg-[#242424] hover:text-white">
                        Details
                    </button>

                    <button onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                        class="rounded-xl bg-red-500 px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-white hover:bg-red-400">
                        Remove
                    </button>
                </div>
            @endif

        </div>

    </div>
</div>

<x-modals.delete_confirmation
    :rentalId="$rental->id"
    :route="$isPending ? '/garage/rental/' . $rental->id . '/cancel' : '/garage/rental/' . $rental->id . '/hide'"
    :title="$isPending ? 'Cancel Request' : 'Remove Record'"
    :message="$isPending ? 'Are you sure you want to cancel this pending rental request?' : 'Are you sure you want to delete this rental record?'"
    :confirmText="$isPending ? 'Yes, Cancel' : 'Yes, Delete'" />

<x-modals.rental_details :rental="$rental" />
