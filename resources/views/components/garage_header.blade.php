@props(['active' => 'listing', 'title', 'subtitle'])

<div class="px-10 pt-10 pb-6">
    <div class="flex items-center justify-between mb-1">
        <div>
            <h1 class="text-white text-2xl font-bold tracking-tight">{{ $title }}</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $subtitle }}</p>
        </div>

        <div class="flex items-center bg-[#1a1a1a] rounded-full p-1 gap-1 border border-gray-700">
            <a href="/garage/my-listing" wire:navigate
               class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $active === 'listing' || Request::is('car/pre-order*') ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-white' }}">
                My Listing
            </a>
            <a href="/garage/my-rental" wire:navigate
               class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $active === 'rental' ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-white' }}">
                My Rental
            </a> 
            <a href="/garage/my-cart" wire:navigate
               class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $active === 'cart' ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-white' }}">
                My Cart
            </a>
        </div>
    </div>
</div>