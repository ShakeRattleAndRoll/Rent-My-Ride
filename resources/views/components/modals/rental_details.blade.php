@props(['rental'])

@php
    $now        = now();
    $start      = \Carbon\Carbon::parse($rental->start_date);
    $end        = \Carbon\Carbon::parse($rental->end_date);
    $isUpcoming = $rental->status === 'accepted' && $now->lt($start);
    $isActive   = $rental->status === 'accepted' && $now->between($start, $end);
    $isFinished = $rental->status === 'accepted' && $now->gt($end);
    $isDeclined = $rental->status === 'denied';
@endphp

<div id="details-modal-{{ $rental->id }}" 
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
     onclick="if(event.target === this) document.getElementById('details-modal-{{ $rental->id }}').classList.add('hidden')">

    <div class="relative w-full max-w-lg mx-4">

        {{-- X Close Button --}}
        <button onclick="document.getElementById('details-modal-{{ $rental->id }}').classList.add('hidden')"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10
                   w-8 h-8 flex items-center justify-center rounded-full
                   text-white hover:text-red-400 transition text-lg z-50">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Modal Content --}}
        <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl shadow-2xl overflow-hidden">

            {{-- Car Image --}}
            <div class="w-full h-48 overflow-hidden">
                <img src="{{ asset('storage/' . $rental->snap_car_image) }}"
                     alt="{{ $rental->snap_brand }}"
                     class="w-full h-full object-cover">
            </div>

            <div class="p-6">

                {{-- Title + Status/Owner --}}
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h2 class="text-white text-xl font-black uppercase tracking-tight leading-none">
                            {{ $rental->snap_brand }}
                        </h2>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $rental->snap_model }}
                        </p>

                        {{-- Specs below name --}}
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3 text-gray-400 text-xs">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-gray-500"></i>
                                {{ \Carbon\Carbon::parse($rental->snap_date_owned)->format('M j, Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-gear text-gray-500"></i>
                                {{ $rental->snap_transmission }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-gas-pump text-gray-500"></i>
                                {{ $rental->snap_fuel_type }}
                            </span>
                        </div>
                    </div>

                    {{-- Owner Profile + Badge --}}
                    @if ($isUpcoming)
                        <div class="flex flex-col items-center gap-1 shrink-0">
                            <a href="{{ route('user.profile', $rental->car->user->id) }}"
                               class="flex items-center gap-2
                                      border border-transparent hover:border-white/20
                                      rounded-xl px-2 py-1 transition-all duration-300">
                                <img src="{{ $rental->car->user->profile_picture
                                        ? asset('storage/' . $rental->car->user->profile_picture)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($rental->car->user->username) }}"
                                     class="w-9 h-9 rounded-full object-cover border border-white/10 shrink-0"
                                     alt="{{ $rental->car->user->username }}">
                                <div class="flex flex-col min-w-0">
                                    <p class="text-white text-[9px] font-black uppercase tracking-tight truncate">{{ $rental->car->user->username }}</p>
                                    <p class="text-gray-500 text-[9px] truncate">{{ $rental->car->user->full_name }}</p>
                                </div>
                            </a>
                            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/50 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shrink-0">Upcoming</span>
                        </div>
                    @elseif ($isActive)
                        <div class="flex flex-col items-center gap-1 shrink-0">
                            <a href="{{ route('user.profile', $rental->car->user->id) }}"
                               class="flex items-center gap-2
                                      border border-transparent hover:border-white/20
                                      rounded-xl px-2 py-1 transition-all duration-300">
                                <img src="{{ $rental->car->user->profile_picture
                                        ? asset('storage/' . $rental->car->user->profile_picture)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($rental->car->user->username) }}"
                                     class="w-9 h-9 rounded-full object-cover border border-white/10 shrink-0"
                                     alt="{{ $rental->car->user->username }}">
                                <div class="flex flex-col min-w-0">
                                    <p class="text-white text-[9px] font-black uppercase tracking-tight truncate">{{ $rental->car->user->username }}</p>
                                    <p class="text-gray-500 text-[9px] truncate">{{ $rental->car->user->full_name }}</p>
                                </div>
                            </a>
                            <span class="bg-lime-400 text-black text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shrink-0">Active</span>
                        </div>
                    @elseif ($isFinished || $isDeclined)
                        <div class="flex flex-col items-center gap-1 shrink-0">
                            <a href="{{ route('user.profile', $rental->car->user->id) }}"
                               class="flex items-center gap-2
                                      border border-transparent hover:border-white/20
                                      rounded-xl px-2 py-1 transition-all duration-300">
                                <img src="{{ $rental->car->user->profile_picture
                                        ? asset('storage/' . $rental->car->user->profile_picture)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($rental->car->user->username) }}"
                                     class="w-9 h-9 rounded-full object-cover border border-white/10 shrink-0"
                                     alt="{{ $rental->car->user->username }}">
                                <div class="flex flex-col min-w-0">
                                    <p class="text-white text-[9px] font-black uppercase tracking-tight truncate">{{ $rental->car->user->username }}</p>
                                    <p class="text-gray-500 text-[9px] truncate">{{ $rental->car->user->full_name }}</p>
                                </div>
                            </a>
                            @if ($isFinished)
                                <span class="bg-gray-700 text-gray-300 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Completed</span>
                            @else
                                <span class="bg-red-600/20 text-red-500 border border-red-600/50 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Declined</span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Divider --}}
                <div class="h-px bg-white/5 mb-4"></div>

                {{-- Specs Grid --}}
                <div class="grid grid-cols-2 gap-3 mb-4 text-xs">

                    <div class="bg-[#242424] rounded-xl p-3 border border-white/5">
                        <p class="text-gray-500 uppercase tracking-widest text-[9px] font-bold mb-1">Price</p>
                        <p class="text-white font-semibold">
                            ₱{{ number_format($rental->snap_price, 0) }}
                            <span class="text-gray-500">/ {{ $rental->snap_rent_unit }}</span>
                        </p>
                    </div>

                    <div class="bg-[#242424] rounded-xl p-3 border border-white/5">
                        <p class="text-gray-500 uppercase tracking-widest text-[9px] font-bold mb-1">Duration</p>
                        <p class="text-white font-semibold">
                            {{ $rental->days }} {{ $rental->snap_rent_unit }}(s)
                        </p>
                    </div>

                    @if (!$isDeclined)
                        <div class="bg-[#242424] rounded-xl p-3 border border-white/5">
                            <p class="text-gray-500 uppercase tracking-widest text-[9px] font-bold mb-1">Start Date</p>
                            <p class="text-white font-semibold">
                                {{ \Carbon\Carbon::parse($rental->start_date)->format('M j, Y g:i A') }}
                            </p>
                        </div>

                        <div class="bg-[#242424] rounded-xl p-3 border border-white/5">
                            <p class="text-gray-500 uppercase tracking-widest text-[9px] font-bold mb-1">End Date</p>
                            <p class="text-white font-semibold">
                                {{ \Carbon\Carbon::parse($rental->end_date)->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    @endif

                    <div class="bg-[#242424] rounded-xl p-3 border border-white/5 col-span-2">
                        <p class="text-gray-500 uppercase tracking-widest text-[9px] font-bold mb-1">Total Price</p>
                        <p class="text-lime-400 font-black">
                            ₱{{ number_format($rental->total_price, 0) }}
                        </p>
                    </div>

                </div>
                
                {{-- Info row --}}
                <div class="flex w-full items-center gap-2">
                    
                    @if ($isUpcoming)
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
</div>