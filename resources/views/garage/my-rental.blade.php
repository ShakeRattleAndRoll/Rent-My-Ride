<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <x-garage_header
        active="rental"
        title="My Rentals"
        subtitle="Cars you are currently renting or have rented"
    />

    @if ($rentals->isNotEmpty())
        <div class="mx-10 mb-6 flex items-center justify-center gap-2 text-gray-500 text-xs font-medium tracking-wide">

            <i class="fa-solid fa-info text-lime-400"></i>

            <span>
                Approach the car owner to finalize the pending/s.
            </span>

        </div>
    @endif

    {{-- Rentals List --}}
    <div class="px-10 pb-10 space-y-4">

        @forelse ($rentals as $rental)
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex items-center gap-5 p-4 hover:border-gray-600 transition-all duration-200">

            {{-- Car Image --}}
            <div class="w-44 h-28 rounded-xl overflow-hidden shrink-0 bg-gray-800">
                <img src="{{ asset('storage/' . ($rental->car->car_image ?? $rental->car->image)) }}"
                     alt="{{ $rental->car->brand }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Rental Info --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-white text-lg font-bold leading-tight">{{ $rental->car->brand }}</h2>
                <p class="text-gray-400 text-sm mb-3">{{ $rental->car->model }}</p>

                <div class="flex flex-wrap gap-x-5 gap-y-1 text-gray-400 text-xs mb-3">

                    {{-- Date --}}
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($rental->start_date)->format('M j') }} – 
                        {{ \Carbon\Carbon::parse($rental->end_date)->format('M j, Y') }}
                    </span>

                    {{-- Fuel --}}
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16M3 22h14"/>
                        </svg>
                        {{ $rental->car->fuel_type }}
                    </span>

                    {{-- Transmission --}}
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        {{ $rental->car->transmission }}
                    </span>
                </div>
            </div>

            {{-- Price + Status --}}
            <div class="flex flex-col items-end gap-2 shrink-0">

                <p class="text-white font-bold text-base">
                    ₱{{ number_format($rental->car->price ?? $rental->car->price_per_day, 0) }}
                    <span class="text-gray-400 text-sm">/day</span>
                </p>

                @php
                    $now = now();
                    $start = \Carbon\Carbon::parse($rental->start_date);
                    $end = \Carbon\Carbon::parse($rental->end_date);

                    $isPending = ($rental->status === 'pending');
                    $isActive = ($rental->status === 'accepted' && $now->between($start, $end));
                    $isFinished = ($rental->status === 'accepted' && $now->gt($end));
                    $isDeclined = ($rental->status === 'denied');
                @endphp

                {{-- Status --}}
                @if ($isPending)
                    <span class="bg-yellow-500/20 text-yellow-500 border border-yellow-500/50 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                        Pending Approval
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

                {{-- BUTTON SWITCH --}}
                @if ($isPending)

                    {{-- Cancel Button --}}
                    <form action="/garage/rental/{{ $rental->id }}/cancel" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            onclick="return confirm('Cancel this rental request?')"
                            class="w-full text-center border border-red-600/50 text-red-500 hover:bg-red-600 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 uppercase tracking-widest mt-1">
                            Cancel
                        </button>
                    </form>

                @else

                    {{-- View Details --}}
                    <a href="/garage/rental/{{ $rental->id }}"
                    class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-[10px] font-bold px-5 py-2 rounded-full transition-all duration-200 w-32 text-center uppercase tracking-widest mt-1">
                        View Details
                    </a>

                @endif

            </div>
        </div>

        @empty
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                <i class="fa-solid fa-car-side text-gray-600 text-2xl"></i>
            </div>
            <p class="text-gray-400 text-sm font-medium">No rentals yet</p>
            <p class="text-gray-600 text-xs mt-1 mb-5">Cars you rent will appear here</p>
            <a href="/available" class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                Browse Cars
            </a>
        </div>
        @endforelse

    </div>
</div>
</x-layout>