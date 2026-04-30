<nav class="flex items-center justify-between px-10 py-5 bg-black h-20 relative"
    style="font-family: 'Montserrat', sans-serif; letter-spacing: -0.02em;">

    {{-- LEFT --}}
    <div class="flex items-center space-x-6">
        <button onclick="toggleSidebar()" class="md:hidden w-8 h-8 rounded-full bg-white flex items-center justify-center">
            ☰
        </button>

        <a href="/profile" wire:navigate class="w-10 h-10 rounded-full bg-[#242424] border border-white/10 flex items-center justify-center cursor-pointer shrink-0 overflow-hidden hover:border-lime-400 transition-colors">
            @auth
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                        alt="Profile" 
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @endif
            @else
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            @endauth
        </a>

        <a href="/" wire:navigate>
            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" class="w-24 cursor-pointer shrink-0" alt="Logo">
        </a>
    </div>

    {{-- DESKTOP MENU --}}
    <div class="hidden md:flex items-center gap-1 text-[12px] uppercase tracking-wider font-bold">

        <a href="/" wire:navigate class="px-4 py-2 rounded-full {{ request()->is('/') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Homepage
        </a>

        <a href="/available" wire:navigate class="px-4 py-2 rounded-full {{ request()->is('available') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Available Cars
        </a>

       <div class="relative group">
            <button class="flex items-center gap-1 px-4 py-2 rounded-full uppercase tracking-wider font-bold transition-all {{ request()->is('garage*') || request()->is('garage/post-car') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}"
                    style="font-family: inherit; letter-spacing: inherit;">
                Garage
                <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="absolute left-0 mt-2 w-48 bg-[#111] border border-white/10 rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="/garage/my-listing" wire:navigate class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                    My Listings
                </a>
                <a href="/garage/my-rental" wire:navigate class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                    My Rental
                </a>
                <a href="/garage/my-cart" wire:navigate class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                    My Cart
                </a>
                <a href="/garage/post-car" wire:navigate class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition border-t border-white/5 text-[11px]" style="font-family: inherit;">
                    Post a Car
                </a>
            </div>
        </div>

        <a href="/messages" wire:navigate class="px-4 py-2 rounded-full {{ request()->is('messages') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Messages
        </a>

        <a href="/profile" wire:navigate class="px-4 py-2 rounded-full {{ request()->is('profile*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Profile
        </a>
    </div>
</nav>

{{-- MOBILE SIDEBAR OVERLAY --}}
<div id="sidebarOverlay"
    class="fixed inset-0 bg-black/60 hidden z-40"
    onclick="toggleSidebar()"></div>

{{-- MOBILE SIDEBAR --}}
<div id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-[#111] text-white p-6 transform -translate-x-full transition-transform duration-300 z-50">

    <h2 class="text-lg font-bold mb-6">Menu</h2>

    <div class="flex flex-col gap-4 text-sm tracking-wider font-bold">

        <a href="/" class="hover:text-lime-400">Homepage</a>
        <a href="/available" class="hover:text-lime-400">Available Cars</a>
        <a href="/post-car" class="hover:text-lime-400">Post a Car</a>
        <a href="/garage/my-listing" class="hover:text-lime-400">Garage</a>
        <a href="/messages" class="hover:text-lime-400">Messages</a>
        <a href="/profile" class="hover:text-lime-400">Profile</a>

    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => { 
    console.log('Page swapped instantly!');});
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if(sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }
</script>