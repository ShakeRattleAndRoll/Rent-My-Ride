{{-- Site footer --}}
<footer class="bg-[#0a0a0a] text-gray-400 py-6 border-t border-white/5" style="font-family: 'Montserrat', sans-serif;">
    <div class="max-w-7xl mx-auto px-6 flex flex-col items-center text-center gap-2">

        {{-- Logo --}}
        <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" alt="Rent My Ride" class="w-28 opacity-80">

        {{-- Tagline --}}
        <p class="text-xs text-gray-500">
            Your trusted platform for hassle-free car rentals.
        </p>

        {{-- Email --}}
        <a 
            href="mailto:rentmyrideweb@gmail.com"
            class="text-xs text-lime-400 hover:text-lime-300 transition"
        >
            rentmyrideweb@gmail.com
        </a>

        {{-- Copyright --}}
        <p class="text-[11px] text-gray-600">
            © {{ date('Y') }} Rent My Ride. All rights reserved.
        </p>

    </div>
</footer>
