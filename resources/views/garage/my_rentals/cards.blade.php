<div class="rental-card bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex items-center gap-5 p-4 hover:border-gray-600 transition-all duration-200"
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

    {{-- Car Image --}}
    <div class="w-44 h-28 rounded-xl overflow-hidden shrink-0 bg-gray-800">
        <img src="{{ asset('storage/' . ($isPending ? $rental->car->car_image : $rental->snap_car_image)) }}"
             alt="{{ $isPending ? $rental->car->brand : $rental->snap_brand }}"
             class="w-full h-full object-cover">
    </div>

    {{-- Rental Info --}}
    <div class="flex-1 min-w-0">
        <h2 class="text-white text-lg font-bold leading-tight">
            {{ $isPending ? $rental->car->brand : $rental->snap_brand }}
        </h2>
        <p class="text-gray-400 text-sm mb-3">
            {{ $isPending ? $rental->car->model : $rental->snap_model }}
        </p>

        {{-- Car Specs --}}
        <div class="flex flex-wrap gap-x-5 gap-y-1 text-gray-400 text-xs mb-3">
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-gray-500"></i>
                {{ \Carbon\Carbon::parse($isPending ? $rental->car->date_owned : $rental->snap_date_owned)->format('M j, Y') }}
            </span>
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-gas-pump text-gray-500"></i>
                {{ $isPending ? $rental->car->fuel_type : $rental->snap_fuel_type }}
            </span>
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-gear text-gray-500"></i>
                {{ $isPending ? $rental->car->transmission : $rental->snap_transmission }}
            </span>
        </div>

        {{-- Duration + Total --}}
        <div class="flex flex-wrap gap-2 mt-1">
            <span class="flex items-center gap-1.5 bg-[#242424] border border-white/5 rounded-lg px-2.5 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <i class="fa-regular fa-clock text-gray-500"></i>
                {{ $rental->days }} {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }}(s)
            </span>
            <span class="flex items-center gap-1.5 bg-lime-400/10 border border-lime-400/20 rounded-lg px-2.5 py-1 text-[10px] font-bold text-lime-400 uppercase tracking-widest">
                <i class="fa-solid fa-peso-sign text-[9px]"></i>
                Total: ₱{{ number_format($rental->total_price, 0) }}
            </span>
        </div>
    </div>

    {{-- Price + Status --}}
    <div class="flex flex-col items-end gap-2 shrink-0">

        <p class="text-white font-bold text-base">
            ₱{{ number_format($isPending ? $rental->car->price : $rental->snap_price, 0) }}
            <span class="text-gray-400 text-sm">/ {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }}</span>
        </p>

        @if ($isPending)
            <span class="bg-yellow-500/20 text-yellow-500 border border-yellow-500/50 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Pending Approval
            </span>
        @elseif ($isUpcoming)
            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/50 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Upcoming
            </span>
        @elseif ($isActive)
            <span class="bg-lime-400 text-black text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Active
            </span>
        @elseif ($isFinished)
            <span class="bg-gray-700 text-gray-300 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Completed
            </span>
        @elseif ($isDeclined)
            <span class="bg-red-600/20 text-red-500 border border-red-600/50 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Declined
            </span>
        @endif

        @if ($isPending)
            <form action="/garage/rental/{{ $rental->id }}/cancel" method="POST" class="w-full">
                @csrf
                @method('PATCH')
                <button type="submit"
                    onclick="return confirm('Cancel this rental request?')"
                    class="w-full text-center border border-red-600/50 text-red-500 hover:bg-red-600 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 uppercase tracking-widest mt-1">
                    Cancel
                </button>
            </form>
        @elseif ($isUpcoming)
            <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 text-center uppercase tracking-widest">
                View Details
            </button>
        @elseif ($isActive)
            <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 text-center uppercase tracking-widest">
                View Details
            </button>
        @elseif ($isFinished || $isDeclined)
            <div class="flex items-center gap-2 mt-1">
                @if ($isDeclined)
                    <p class="text-gray-500 text-[10px] italic text-center leading-tight px-1">
                        This request is no longer available because it has been denied or the listing has been updated.
                    </p>
                @endif
                <button onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-red-600/40 text-red-500 hover:bg-red-600 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-trash text-[11px]"></i>
                </button>
                <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 text-center uppercase tracking-widest">
                    View Details
                </button>
            </div>
        @endif

    </div>
</div>

<x-modals.delete_confirmation
    :rentalId="$rental->id"
    :route="'/garage/rental/' . $rental->id . '/hide'" />

<x-modals.rental_details :rental="$rental" />