<nav class="flex items-center justify-between px-10 py-5 bg-black h-20 relative"
    style="font-family: 'Montserrat', sans-serif; letter-spacing: -0.02em;">

    {{-- LEFT --}}
    <div class="flex items-center space-x-6">
        <button onclick="toggleSidebar()" class="md:hidden w-8 h-8 rounded-full bg-white flex items-center justify-center">
            ☰
        </button>

        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center cursor-pointer shrink-0">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>

        <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" class="w-24 cursor-pointer shrink-0" alt="Logo">
    </div>

    {{-- DESKTOP MENU --}}
    <div class="hidden md:flex items-center gap-1 text-[12px] uppercase tracking-wider font-bold">

        <a href="/" class="px-4 py-2 rounded-full {{ request()->is('/') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Homepage
        </a>

        <a href="/available" class="px-4 py-2 rounded-full {{ request()->is('available') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Available Cars
        </a>

        <a href="/post-car" class="px-4 py-2 rounded-full {{ request()->is('post-car') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Post a Car
        </a>

        <a href="/garage/my-listing" class="px-4 py-2 rounded-full {{ request()->is('garage*') || Request::is('car/pre-order*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Garage
        </a>

        <a href="/messages" class="px-4 py-2 rounded-full {{ request()->is('messages') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Messages
        </a>

        <a href="/profile" class="px-4 py-2 rounded-full {{ request()->is('profile*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
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
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>