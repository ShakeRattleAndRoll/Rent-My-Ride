<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    {{-- Garage page header --}}
    <x-garage_header
        active="cart"
        title="My Cart"
        subtitle="Cars you have saved for renting"
    />

    {{-- Renter reminder --}}
    @if ($carts->isNotEmpty())
        <div class="mx-10 mb-6 flex items-center justify-center gap-2 text-gray-500 text-xs font-medium tracking-wide">

            <i class="fa-solid fa-info text-lime-400"></i>

            <span>
                Before clicking 
                <span class="text-lime-400 font-black">Rent Now</span>, 
                contact the owner first.
            </span>

        </div>
    @endif

    {{-- Saved cart items --}}
    <div class="grid grid-cols-1 gap-4 px-4 pb-10 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 xl:grid-cols-4 lg:px-10">

        @forelse ($carts as $cart)
            @include('garage.my_cart.cards', ['cart' => $cart])
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-24 text-center">
            <a href="/available" wire:navigate>
                <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-cart-shopping text-gray-600 text-2xl"></i>
                </div>
            </a>
            <p class="text-gray-400 text-sm font-medium">Your cart is empty</p>
            <p class="text-gray-600 text-xs mt-1 mb-5">Cars you save will appear here</p>
            <a href="/available" wire:navigate class="px-10 py-3 bg-lime-400 text-black rounded-full font-bold hover:bg-lime-300 transition text-center">
                Browse Cars
            </a>
        </div>
        @endforelse

    </div>
</div>

{{-- Shared rental request form --}}
@include('garage.my_cart.form')
</x-layout>
