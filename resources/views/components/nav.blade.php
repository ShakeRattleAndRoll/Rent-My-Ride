<nav class="flex items-center justify-between gap-12 px-5 py-5 bg-black h-20 relative sm:px-8 lg:px-10"
    style="font-family: 'Montserrat', sans-serif; letter-spacing: -0.02em;">
    @php
        $isAdmin = auth()->check() && auth()->user()->is_admin;
    @endphp

    {{-- LEFT --}}
    <div class="flex shrink-0 items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 rounded-full bg-white text-black flex items-center justify-center">
            <i class="fa-solid fa-bars text-sm"></i>
        </button>

        @auth
            <a href="/profile" wire:navigate data-nav-navigate class="w-10 h-10 rounded-full bg-[#242424] border border-white/10 flex items-center justify-center cursor-pointer shrink-0 overflow-hidden hover:border-lime-400 transition-colors">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                        alt="Profile" 
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @endif
            </a>

            @unless($isAdmin)
                <a href="/notifications" wire:navigate data-nav-navigate
                   class="relative w-10 h-10 rounded-full bg-[#242424] border border-white/10 flex items-center justify-center shrink-0 text-gray-300 hover:text-lime-400 hover:border-lime-400 transition-colors {{ request()->is('notifications*') ? 'border-lime-400 text-lime-400' : '' }}"
                   title="Notifications">
                    <i class="fa-solid fa-bell text-sm"></i>
                    <span data-unread-notifications-badge class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalUnreadNotifications > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalUnreadNotifications > 99 ? '99+' : $totalUnreadNotifications }}
                    </span>
                </a>
            @endunless
        @endauth

        @guest
            <div class="hidden lg:flex items-center gap-3 text-[12px] uppercase tracking-wider font-bold">
                <a href="{{ route('login') }}" wire:navigate data-nav-navigate class="px-5 py-2 rounded-full border border-white/20 text-white hover:border-lime-400 hover:text-lime-400 transition">
                    Log In
                </a>
                <a href="{{ route('register') }}" wire:navigate data-nav-navigate class="px-5 py-2 rounded-full bg-lime-400 text-black hover:bg-lime-300 transition">
                    Sign Up
                </a>
            </div>
        @endguest

        <a href="/" wire:navigate data-nav-navigate class="shrink-0">
            <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" class="w-24 cursor-pointer" alt="Logo">
        </a>
    </div>

    {{-- DESKTOP MENU --}}
    <div class="ml-12 hidden flex-1 items-center justify-end gap-1 text-[12px] uppercase tracking-wider font-bold lg:flex">

        @unless($isAdmin)
            <a href="/" wire:navigate data-nav-navigate class="px-4 py-2 rounded-full {{ request()->is('/') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
                Homepage
            </a>

            <a href="/available" wire:navigate data-nav-navigate class="px-4 py-2 rounded-full {{ request()->is('available') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
                Available Cars
            </a>

            {{-- Garage with pending orders badge --}}
            <div class="relative group">
                <button @guest data-auth-required @endguest class="relative flex items-center gap-1 px-4 py-2 rounded-full uppercase tracking-wider font-bold transition-all {{ request()->is('garage*') || request()->is('car/pre-order*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}"
                        style="font-family: inherit; letter-spacing: inherit;">
                    Garage
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <span data-pending-orders-badge class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingOrders > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalPendingOrders > 99 ? '99+' : $totalPendingOrders }}
                    </span>
                </button>

                <div class="absolute left-0 mt-2 w-48 bg-[#111] border border-white/10 rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="/garage/post-car" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                        Post a Car
                    </a>
                    {{-- My Listings with badge --}}
                    <a href="/garage/my-listing" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="relative flex items-center justify-between px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                        My Listings
                        <span data-pending-orders-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingOrders > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black">
                            {{ $totalPendingOrders > 99 ? '99+' : $totalPendingOrders }}
                        </span>
                    </a>
                    <a href="/garage/my-rental" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                        My Rental
                    </a>
                    <a href="/garage/my-cart" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="block px-5 py-3 text-white hover:bg-lime-400 hover:text-black transition text-[11px]" style="font-family: inherit;">
                        My Cart
                    </a>
                </div>
            </div>
        @endunless

        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.cars.pending') }}" wire:navigate data-nav-navigate class="relative px-4 py-2 rounded-full {{ request()->is('admin/cars*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
                    Pending Approvals
                    <span data-admin-pending-approvals-badge class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingCarApprovals > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalPendingCarApprovals > 99 ? '99+' : $totalPendingCarApprovals }}
                    </span>
                </a>
            @endif
        @endauth

        {{-- Messages with unread badge --}}
        <a href="/messages" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="relative px-4 py-2 rounded-full {{ request()->is('messages*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Messages
            <span data-unread-messages-badge class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalUnreadMessages > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                {{ $totalUnreadMessages > 99 ? '99+' : $totalUnreadMessages }}
            </span>
        </a>

        @auth
            <a href="/profile" wire:navigate data-nav-navigate class="px-4 py-2 rounded-full {{ request()->is('profile*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
                Profile
            </a>
        @endauth
    </div>
</nav>

{{-- MOBILE SIDEBAR OVERLAY --}}
<div id="sidebarOverlay"
    class="fixed inset-0 bg-black/60 hidden z-40"
    onclick="toggleSidebar()"></div>

{{-- MOBILE SIDEBAR --}}
<div id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-[#111] text-white p-6 transform -translate-x-full transition-transform duration-300 z-50 flex flex-col overflow-y-auto">

    <h2 class="text-lg font-bold mb-6">Menu</h2>

    <div class="flex flex-col gap-4 text-sm tracking-wider font-bold">

        @unless($isAdmin)
            <a href="/" wire:navigate data-nav-navigate class="px-2 py-1 rounded-lg {{ request()->is('/') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">Homepage</a>
            <a href="/available" wire:navigate data-nav-navigate class="px-2 py-1 rounded-lg {{ request()->is('available') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">Available Cars</a>

            {{-- Garage Dropdown --}}
            <div>
            <button onclick="toggleGarageDropdown()" class="relative w-full flex items-center justify-between px-2 py-1 rounded-lg {{ request()->is('garage*') || request()->is('car/pre-order*') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }} transition-colors">
                <span class="flex items-center gap-2">
                    Garage
                    <span data-pending-orders-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingOrders > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalPendingOrders > 99 ? '99+' : $totalPendingOrders }}
                    </span>
                </span>
                <svg id="garageArrow" class="w-3 h-3 transition-transform duration-200 {{ request()->is('garage*') || request()->is('car/pre-order*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="garageDropdown" class="{{ request()->is('garage*') || request()->is('car/pre-order*') ? 'flex' : 'hidden' }} flex-col gap-1 mt-2 pl-4 border-l border-white/10">
                <a href="/garage/post-car" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="px-2 py-1 rounded-lg {{ request()->is('garage/post-car') ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-lime-400' }} transition-colors">Post a Car</a>
                {{-- My Listings with badge --}}
                <a href="/garage/my-listing" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="flex items-center justify-between px-2 py-1 rounded-lg {{ request()->is('garage/my-listing') ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-lime-400' }} transition-colors">
                    My Listings
                    <span data-pending-orders-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingOrders > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black">
                        {{ $totalPendingOrders > 99 ? '99+' : $totalPendingOrders }}
                    </span>
                </a>
                <a href="/garage/my-rental" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="px-2 py-1 rounded-lg {{ request()->is('garage/my-rental*') ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-lime-400' }} transition-colors">My Rental</a>
                <a href="/garage/my-cart" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="px-2 py-1 rounded-lg {{ request()->is('garage/my-cart') ? 'bg-lime-400 text-black' : 'text-gray-400 hover:text-lime-400' }} transition-colors">My Cart</a>
            </div>
            </div>
        @endunless

        {{-- Messages with unread badge --}}
        <a href="/messages" wire:navigate data-nav-navigate @guest data-auth-required @endguest class="relative flex items-center justify-between px-2 py-1 rounded-lg {{ request()->is('messages*') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">
            Messages
            <span data-unread-messages-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalUnreadMessages > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                {{ $totalUnreadMessages > 99 ? '99+' : $totalUnreadMessages }}
            </span>
        </a>

        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.cars.pending') }}" wire:navigate data-nav-navigate class="relative flex items-center justify-between px-2 py-1 rounded-lg {{ request()->is('admin/cars*') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">
                    Admin
                    <span data-admin-pending-approvals-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalPendingCarApprovals > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalPendingCarApprovals > 99 ? '99+' : $totalPendingCarApprovals }}
                    </span>
                </a>
            @endif

            @unless($isAdmin)
                <a href="/notifications" wire:navigate data-nav-navigate class="relative flex items-center justify-between px-2 py-1 rounded-lg {{ request()->is('notifications*') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">
                    Notifications
                    <span data-unread-notifications-badge class="w-4 h-4 bg-red-600 rounded-full text-white text-[9px] {{ $totalUnreadNotifications > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse">
                        {{ $totalUnreadNotifications > 99 ? '99+' : $totalUnreadNotifications }}
                    </span>
                </a>
            @endunless

            <a href="/profile" wire:navigate data-nav-navigate class="px-2 py-1 rounded-lg {{ request()->is('profile*') ? 'bg-lime-400 text-black' : 'hover:text-lime-400' }}">Profile</a>
        @else
            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="{{ route('login') }}" wire:navigate class="rounded-lg border border-white/20 px-3 py-2 text-center text-xs uppercase text-white hover:border-lime-400 hover:text-lime-400 transition">Log In</a>
                <a href="{{ route('register') }}" wire:navigate class="rounded-lg bg-lime-400 px-3 py-2 text-center text-xs uppercase text-black hover:bg-lime-300 transition">Sign Up</a>
            </div>
        @endauth

    </div>

    {{-- Sidebar Footer --}}
    <div class="mt-auto pt-6 border-t border-white/5 text-center">

        <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" alt="Rent My Ride" class="w-28 mx-auto mb-3">

        <p class="text-xs text-gray-500">
            Your trusted platform for hassle-free car rentals.
        </p>

        <p class="text-xs text-gray-600 mt-3">
            © {{ date('Y') }} Rent My Ride.
        </p>

    </div>

</div>
