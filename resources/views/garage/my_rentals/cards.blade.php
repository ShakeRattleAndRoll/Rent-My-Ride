<div class="rental-card flex h-full flex-col overflow-hidden rounded-xl border border-gray-800 bg-[#1a1a1a] transition-all duration-200 hover:border-gray-600"
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
    <div class="aspect-[16/9] w-full overflow-hidden bg-gray-800">
        <img src="{{ asset('storage/' . ($isPending ? $rental->car->car_image : $rental->snap_car_image)) }}"
             alt="{{ $isPending ? $rental->car->brand : $rental->snap_brand }}"
             class="h-full w-full object-cover">
    </div>

    <div class="flex flex-1 flex-col p-3">
        {{-- Rental Info --}}
        <div class="min-w-0">

            {{-- Car Name + Status Badge --}}
            <div class="flex items-center justify-between gap-2 mb-1">
                <h2 class="truncate text-base font-bold leading-tight text-white">
                    {{ $isPending ? $rental->car->brand : $rental->snap_brand }}
                </h2>
                @if ($isPending)
                    <span class="shrink-0 bg-yellow-500/20 text-yellow-500 border border-yellow-500/50 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Pending
                    </span>
                @elseif ($isUpcoming)
                    <span class="shrink-0 bg-blue-500/20 text-blue-400 border border-blue-500/50 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Upcoming
                    </span>
                @elseif ($isActive)
                    <span class="shrink-0 bg-lime-400 text-black text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Active
                    </span>
                @elseif ($isFinished)
                    <span class="shrink-0 bg-gray-700 text-gray-300 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Completed
                    </span>
                @elseif ($isDeclined)
                    <span class="shrink-0 bg-red-600/20 text-red-500 border border-red-600/50 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Declined
                    </span>
                @endif
            </div>

            <p class="mb-2 truncate text-xs text-gray-400">
                {{ $isPending ? $rental->car->model : $rental->snap_model }}
            </p>

            @if ($isPending || $isUpcoming || $isActive)
                {{-- Car Specs --}}
                <div class="mb-3 flex flex-wrap gap-x-3 gap-y-1.5 text-[11px] text-gray-400">
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
                <div class="mt-1 flex flex-wrap gap-2">
                    <span class="flex items-center gap-1.5 bg-[#242424] border border-white/5 rounded-lg px-2.5 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <i class="fa-regular fa-clock text-gray-500"></i>
                        {{ $rental->days }} {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }}(s)
                    </span>
                    <span class="flex items-center gap-1.5 bg-lime-400/10 border border-lime-400/20 rounded-lg px-2.5 py-1 text-[10px] font-bold text-lime-400 uppercase tracking-widest">
                        <i class="fa-solid fa-peso-sign text-[9px]"></i>
                        Total: &#8369;{{ number_format($rental->total_price, 0) }}
                    </span>
                </div>
            @endif
        </div>

        {{-- Price + Actions --}}
        <div class="mt-auto flex flex-col gap-2.5 pt-4">

            <p class="text-sm font-bold text-white">
                &#8369;{{ number_format($isPending ? $rental->car->price : $rental->snap_price, 0) }}
                <span class="text-xs text-gray-400">/ {{ $isPending ? $rental->car->rent_unit : $rental->snap_rent_unit }}</span>
            </p>

            @if ($isPending)
                <button type="button"
                    onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="flex min-h-9 w-full items-center justify-center rounded-lg bg-red-500 px-3 py-2 text-center text-[11px] font-black uppercase tracking-widest text-white transition-all duration-200 hover:bg-red-400">
                    Cancel Request
                </button>
            @elseif ($isUpcoming || $isActive)
                <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                    class="flex min-h-9 w-full items-center justify-center rounded-lg border border-gray-700 bg-[#242424] px-3 py-2 text-center text-[11px] font-black uppercase tracking-widest text-gray-300 transition-all duration-200 hover:bg-[#303030] hover:text-white">
                    View Details
                </button>
            @elseif ($isFinished || $isDeclined)
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.remove('hidden')"
                        class="flex min-h-9 w-full items-center justify-center rounded-lg border border-gray-700 bg-[#242424] px-3 py-2 text-center text-[11px] font-black uppercase tracking-widest text-gray-300 transition-all duration-200 hover:bg-[#303030] hover:text-white">
                        Details
                    </button>
                    <button onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                        class="flex min-h-9 w-full items-center justify-center rounded-lg bg-red-500 px-3 py-2 text-center text-[11px] font-black uppercase tracking-widest text-white transition-all duration-200 hover:bg-red-400">
                        Remove
                    </button>
                </div>
            @endif

            {{-- Info row --}}
            <div class="flex w-full items-center gap-2">
                @if ($isPending)
                    <span class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full border border-yellow-500/40 text-yellow-500">
                        <i class="fa-solid fa-hourglass-half text-[11px]"></i>
                    </span>
                    <p class="text-gray-500 text-[10px] italic leading-tight">
                        Waiting for owner approval. You can cancel this request while it is pending.
                    </p>
                @elseif ($isUpcoming)
                    <span class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full border border-blue-500/40 text-blue-400">
                        <i class="fa-regular fa-calendar-check text-[11px]"></i>
                    </span>
                    <p class="text-gray-500 text-[10px] italic leading-tight">
                        Rental approved. Check the details before the scheduled pickup.
                    </p>
                @elseif ($isActive)
                    <span class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full border border-lime-400/40 text-lime-400">
                        <i class="fa-solid fa-key text-[11px]"></i>
                    </span>
                    <p class="text-gray-500 text-[10px] italic leading-tight">
                        Rental is currently active. View details for the return schedule.
                    </p>
                @elseif ($isFinished)
                    <span class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full border border-gray-600/40 text-gray-400">
                        <i class="fa-solid fa-trash text-[11px]"></i>
                    </span>
                    <p class="text-gray-500 text-[10px] italic leading-tight">
                        Rental completed. You can keep this record or remove it from your history.
                    </p>
                @elseif ($isDeclined)
                    <span class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full border border-gray-600/40 text-gray-400">
                        <i class="fa-solid fa-trash text-[11px]"></i>
                    </span>
                    <p class="text-gray-500 text-[10px] italic leading-tight">
                        Rental request declined due to availability or updated rental details.
                    </p>
                @endif
            </div>

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
