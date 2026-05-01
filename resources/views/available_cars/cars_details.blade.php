<x-layout>
    <div class="bg-[#121212] min-h-screen flex items-center justify-center p-6"
        style="font-family: 'Montserrat', sans-serif;">

        <div class="bg-[#1e1e1e] rounded-2xl w-full max-w-xl p-6 relative border border-white/10">

            {{-- CLOSE BUTTON (TOP RIGHT OF CARD) --}}
            <a href="{{ url()->previous() }}"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10 
                    w-8 h-8 flex items-center justify-center rounded-full
                    text-white hover:text-red-400 transition text-lg z-50">
                <i class="fa-solid fa-xmark"></i>
            </a>

            {{-- TOP SECTION --}}
            <div class="flex gap-6 mb-6">

                {{-- Car Image --}}
                <div class="w-48 h-40 rounded-xl overflow-hidden bg-gray-800 shrink-0">
                    <img src="{{ asset('storage/' . $car->car_image) }}"
                        alt="{{ $car->brand }}"
                        class="w-full h-full object-cover">
                </div>

                {{-- RIGHT CONTENT --}}
                <div class="flex-1 flex flex-col justify-between">

                    {{-- BRAND + PRICE --}}
                    <div class="flex justify-between items-start">
                        <h2 class="text-white text-xl font-black leading-tight">
                            {{ $car->brand }}
                            <span class="block text-gray-400 text-sm font-medium">
                                {{ $car->model }}
                            </span>
                        </h2>

                        <p class="text-white font-bold text-sm">
                            ₱{{ number_format($car->price, 0) }}
                            <span class="text-gray-400 font-normal">/day</span>
                        </p>
                    </div>

                    {{-- SPECS --}}
                    <div class="mt-3 space-y-2 text-sm text-gray-300">

                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-gray-500 w-4"></i>
                            <span>{{ \Carbon\Carbon::parse($car->date_owned)->format('F j, Y') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gas-pump text-gray-500 w-4"></i>
                            <span>{{ $car->fuel_type }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gear text-gray-500 w-4"></i>
                            <span>{{ $car->transmission }}</span>
                        </div>

                    </div>

                    {{-- BUTTONS (MATCH IMAGE DESIGN) --}}
                    <div class="mt-5 flex gap-3">

                        {{-- MESSAGE BUTTON --}}
                        <a href="/messages" wire:navigate
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-bold rounded-full transition">

                            <i class="fa-solid fa-message"></i>
                            Message
                        </a>

                        {{-- ADD TO CART --}}
                        <form method="POST" action="/cart/add" class="flex-1">
                            @csrf
                            <input type="hidden" name="car_id" value="{{ $car->id }}">

                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-bold rounded-full transition">

                                <i class="fa-solid fa-cart-plus"></i>
                                Add to Cart
                            </button>
                        </form>

                    </div>

                </div>
            </div>

            {{-- DIVIDER --}}
            <div class="border-t border-white/5 mb-5"></div>

            {{-- OWNER INFO --}}
            <div class="grid grid-cols-2 gap-y-5 mb-6 text-sm">

                <div>
                    <p class="text-white font-bold mb-1">Car Owner</p>

                    <div class="flex items-center gap-3 text-gray-400">

                        <img src="{{ $car->user->profile_picture 
                            ? asset('storage/' . $car->user->profile_picture) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($car->user->username) }}"
                            class="w-12 h-12 rounded-full object-cover border border-white/10"
                            alt="Owner">

                        <div class="flex flex-col leading-tight">
                            <p class="text-xs text-gray-500 font-semibold">
                                {{ $car->user->username }}
                            </p>
                            <p class="text-white font-semibold">
                                {{ $car->user->full_name }}
                            </p>
                        </div>

                    </div>
                </div>

                <div>
                    <p class="text-white font-bold mb-1">Owner Email</p>
                    <a href="mailto:{{ $car->user->email }}" class="text-blue-400 hover:underline">
                        {{ $car->user->email }}
                    </a>
                </div>

                <div>
                    <p class="text-white font-bold mb-1">Address</p>
                    <p class="text-gray-400">{{ $car->user->address ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-white font-bold mb-1">Owner Contact Number</p>
                    <p class="text-gray-400">{{ $car->user->contact_number ?? 'N/A' }}</p>
                </div>

            </div>

            {{-- DIVIDER --}}
            <div class="border-t border-white/5 mb-4"></div>

            {{-- DESCRIPTION --}}
            <p class="text-center text-gray-400 text-sm font-semibold mb-3">Description</p>
            <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 min-h-[100px]">
                <p class="text-gray-400 text-sm">
                    {{ $car->description ?? 'No description provided.' }}
                </p>
            </div>

        </div>
    </div>
</x-layout>