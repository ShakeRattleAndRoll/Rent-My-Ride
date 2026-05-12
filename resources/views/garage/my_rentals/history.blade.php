<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <x-garage_header
        active="rental"
        title="Rental History"
        subtitle="Completed and declined rentals"
    />

    <div class="px-4 pb-4 sm:px-6 lg:px-10">
        <div class="flex justify-end">
            <a href="/garage/my-rental" wire:navigate data-nav-navigate
               class="inline-flex items-center gap-2 rounded-full border border-gray-700 bg-[#1a1a1a] px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-gray-300 transition hover:border-lime-400/50 hover:text-lime-300">
                <i class="fa-solid fa-arrow-left"></i>
                Current Rentals
            </a>
        </div>
    </div>

    @if ($rentals->isNotEmpty())
        <div class="mx-10 mb-6 flex items-center justify-center gap-2 text-gray-500 text-xs font-medium tracking-wide">
            <i class="fa-solid fa-info text-lime-400"></i>
            <span>
                Completed and Declined rentals will appear here.
            </span>
        </div>
    @endif

    {{-- History List --}}
    <div class="grid grid-cols-1 gap-4 px-4 pb-10 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 xl:grid-cols-4 lg:px-10">

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
                <a href="/garage/my-rental" wire:navigate>
                    <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-clock-rotate-left text-gray-600 text-2xl"></i>
                    </div>
                </a>
                <p class="text-gray-400 text-sm font-medium">No rental history yet</p>
                <p class="text-gray-600 text-xs mt-1 mb-5">Completed and declined rentals will appear here</p>
                <a href="/garage/my-rental" wire:navigate class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                    View Rentals
                </a>
            </div>
        @endforelse

    </div>
</div>
</x-layout>
