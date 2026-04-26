<nav class="flex items-center justify-between px-10 py-5 bg-black h-20" style="font-family: 'Montserrat', sans-serif; letter-spacing: -0.02em;">
     
    <div class="flex items-center space-x-6">
        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center cursor-pointer shrink-0">
             <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
             </svg>
        </div>
        <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" class="w-24 cursor-pointer shrink-0" alt="Logo">
    </div>

    <div class="flex items-center gap-1 text-[12px] uppercase tracking-wider font-bold">
        
        <a href="/" 
           class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('/') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Homepage
        </a>

        <a href="/available" 
           class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('available') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Available Cars
        </a>

        <a href="/post-car" 
           class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('post-car') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Post a Car
        </a>

        <a href="/garage/my-listing" 
        class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('garage*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Garage
        </a>

        <a href="/messages" 
           class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('messages') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Messages
        </a>

        <a href="/profile" 
        class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->is('profile*') ? 'bg-lime-400 text-black' : 'text-white hover:text-lime-400' }}">
            Profile
        </a>
    </div>
</nav>