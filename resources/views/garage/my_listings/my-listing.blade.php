<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <x-garage_header
        active="listing"
        title="My Listings"
        subtitle="Manage your posted cars"
    />

    {{-- Listings --}}
    <div class="grid grid-cols-1 gap-4 px-4 pb-10 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 xl:grid-cols-4 lg:px-10">

        @forelse ($listings as $car)
            <x-listing_cards :car="$car" />
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-24 text-center">
            <a href='/garage/post-car' wire:navigate>
                <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-plus text-gray-600 text-2xl"></i>
                </div>
            </a>
            <p class="text-gray-400 text-sm font-medium">No listings yet</p>
            <p class="text-gray-600 text-xs mt-1 mb-5">Cars you post for rent will appear here</p>
            <a href="/garage/post-car" wire:navigate
               class="px-10 py-3 bg-lime-400 text-black rounded-full font-bold hover:bg-lime-300 transition text-center">
                Post a Car
            </a>
        </div>
        @endforelse

    </div>

</div>
</x-layout>
