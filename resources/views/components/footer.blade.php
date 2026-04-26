
{{-- Char2 pani di pani final :) --}}

<footer class="bg-[#0a0a0a] text-gray-400 pt-16 pb-8 border-t border-white/5" style="font-family: 'Montserrat', sans-serif;">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            {{-- Brand --}}
            <div class="flex flex-col gap-4">
                <img src="{{ asset('images/Rent-My-Ride-Logo.png') }}" alt="Rent My Ride" class="w-36">
                <p class="text-sm text-gray-500 leading-relaxed">
                    Your trusted platform for hassle-free car rentals. Drive the experience.
                </p>
                <div class="flex gap-4 mt-2">
                    <a href="#" class="text-gray-500 hover:text-lime-400 transition"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="#" class="text-gray-500 hover:text-lime-400 transition"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="#" class="text-gray-500 hover:text-lime-400 transition"><i class="fa-brands fa-twitter text-lg"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="flex flex-col gap-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-2">Quick Links</h4>
                <a href="/" class="text-sm hover:text-white transition">Home</a>
                <a href="/available" class="text-sm hover:text-white transition">Browse Cars</a>
                <a href="/post-car" class="text-sm hover:text-white transition">List Your Car</a>
                <a href="/garage" class="text-sm hover:text-white transition">My Garage</a>
                <a href="/profile" class="text-sm hover:text-white transition">Profile</a>
            </div>

            {{-- Support --}}
            <div class="flex flex-col gap-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-2">Support</h4>
                <a href="#" class="text-sm hover:text-white transition">Help Center</a>
                <a href="#" class="text-sm hover:text-white transition">Safety Guidelines</a>
                <a href="#" class="text-sm hover:text-white transition">Privacy Policy</a>
                <a href="#" class="text-sm hover:text-white transition">Terms of Service</a>
            </div>

            {{-- Contact --}}
            <div class="flex flex-col gap-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-2">Contact Us</h4>
                <p class="text-sm flex items-center gap-2">
                    <i class="fa-solid fa-envelope text-lime-400"></i> support@rentmyride.com
                </p>
                <p class="text-sm flex items-center gap-2">
                    <i class="fa-solid fa-phone text-lime-400"></i> +63 912 345 6789
                </p>
                <p class="text-sm flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-lime-400"></i> Cebu City, Philippines
                </p>
            </div>

        </div>

        {{-- Divider --}}
        <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-600">© {{ date('Y') }} Rent My Ride. All rights reserved.</p>
            <p class="text-xs text-gray-600">Made with <span class="text-lime-400">♥</span> in the Philippines</p>
        </div>

    </div>
</footer>