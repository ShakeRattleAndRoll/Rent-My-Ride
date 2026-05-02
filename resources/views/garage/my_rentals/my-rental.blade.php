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
            @include('garage.my_rentals.cards', ['rental' => $rental])
        @empty
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <a href="/available" wire:navigate>
                    <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-car-side text-gray-600 text-2xl"></i>
                    </div>
                </a>
                <p class="text-gray-400 text-sm font-medium">No rentals yet</p>
                <p class="text-gray-600 text-xs mt-1 mb-5">Cars you rent will appear here</p>
                <a href="/available" wire:navigate class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                    Browse Cars
                </a>
            </div>
        @endforelse

    </div>
</div>
</x-layout>