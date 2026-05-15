<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <x-garage_header
        active="rental"
        title="My Rentals"
        subtitle="Pending, upcoming, and active rentals"
    />

    <div class="px-4 pb-4 sm:px-6 lg:px-10">
        <div class="flex justify-end">
            <a href="/garage/my-rental/history" wire:navigate data-nav-navigate
               class="inline-flex items-center gap-2 rounded-full border border-gray-700 bg-[#1a1a1a] px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-gray-300 transition hover:border-lime-400/50 hover:text-lime-300">
                <i class="fa-solid fa-clock-rotate-left"></i>
                History
            </a>
        </div>
    </div>

    @if ($rentals->isNotEmpty())
        <div class="mx-10 mb-6 flex items-center justify-center gap-2 text-gray-500 text-xs font-medium tracking-wide">
            <i class="fa-solid fa-info text-lime-400"></i>
            <span>
                Approach the car owner to finalize the pending/s.
            </span>
        </div>
    @endif

    {{-- Rentals List --}}
    <div data-rentals-live-refresh class="grid grid-cols-1 gap-4 px-4 pb-10 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 xl:grid-cols-4 lg:px-10">

        @forelse ($rentals as $rental)
            @php
                $rentalStatusKey = $rental->status === 'accepted'
                    ? (now()->gt($rental->end_date) ? 'completed' : 'active')
                    : $rental->status;
            @endphp
            <div class="h-full" data-rental-row="{{ $rental->id }}" data-rental-status="{{ $rentalStatusKey }}">
                @include('garage.my_rentals.cards', ['rental' => $rental])
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-24 text-center">
                <a href="/available" wire:navigate>
                    <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-car-side text-gray-600 text-2xl"></i>
                    </div>
                </a>
                <p class="text-gray-400 text-sm font-medium">No active rentals</p>
                <p class="text-gray-600 text-xs mt-1 mb-5">Pending, upcoming, and active rentals will appear here</p>
                <a href="/available" wire:navigate class="px-10 py-3 bg-lime-400 text-black rounded-full font-bold hover:bg-lime-300 transition">
                    Browse Cars
                </a>
            </div>
        @endforelse

    </div>
</div>
<script src="{{ asset('js/rentals.js') }}"></script>
</x-layout>
