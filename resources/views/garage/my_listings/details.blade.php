<x-layout>
<div class="bg-[#121212] min-h-screen text-white" style="font-family: 'Montserrat', sans-serif;">

<x-back_button/>

    {{-- HEADER --}}
    <div class="px-10 pt-10 pb-6">
        <h1 class="text-3xl font-bold">Details</h1>
        <p class="text-gray-400 text-sm mt-1">Status and History</p>
    </div>

    <div class="px-10 pb-10 max-w-7xl mx-auto">

        {{-- CAR CARD --}}
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-3xl p-6 flex gap-6 items-center">

            {{-- Car Image --}}
            <div class="w-60 h-40 rounded-2xl overflow-hidden bg-gray-800 shrink-0">
                <img src="{{ asset('storage/' . $car->car_image) }}"
                     class="w-full h-full object-cover"
                     alt="{{ $car->brand }}">
            </div>

            {{-- Car Info --}}
            <div class="flex-1">
                <h2 class="text-2xl font-bold">{{ $car->brand }}</h2>
                <p class="text-gray-400">{{ $car->model }}</p>

                <div class="flex items-center gap-6 mt-4 text-gray-400 text-xs">
                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($car->date_owned)->format('F j, Y') }}</span>
                    <span><i class="fa-solid fa-gas-pump mr-1"></i>{{ $car->fuel_type }}</span>
                    <span><i class="fa-solid fa-gears mr-1"></i>{{ $car->transmission }}</span>
                </div>
            </div>

            {{-- Price --}}
            <div class="text-right">
                <p class="text-2xl font-bold">₱{{ number_format($car->price, 0) }}</p>
                <p class="text-gray-400 text-xs">/ {{ $car->rent_unit }}</p>
            </div>
        </div>

        {{-- RENTAL LIST --}}
        <div class="mt-10 space-y-6">

            @forelse ($rentals->where('status', 'accepted') as $rental)

            @php
                $user = $rental->user;
            @endphp

            <div class="rental-card bg-[#2a2a2a] rounded-3xl p-6 flex items-center justify-between"
                 data-rental-card="{{ $rental->id }}">

                {{-- LEFT USER INFO --}}
                <div class="flex items-center gap-5">

                    {{-- Avatar --}}
                    <a href="{{ route('user.profile', $user->id) }}" wire:navigate data-nav-navigate
                    class="block w-20 h-20 rounded-full overflow-hidden bg-gray-700
                            border-2 border-transparent hover:border-white/30 transition-all duration-300">
                        <img
                            src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username ?? $user->username ?? 'User') }}"
                            alt="{{ $user->name }}"
                            class="w-full h-full object-cover"
                        >
                    </a>

                    {{-- Details --}}
                    <div class="text-sm space-y-1 text-gray-300">
                        <p><span class="text-gray-400">Username:</span> {{ $user->username ?? 'N/A' }}</p>
                        <p><span class="text-gray-400">Fullname:</span> {{ $user->full_name }}</p>
                        <p><span class="text-gray-400">Contact Number:</span> {{ $user->contact_number ?? 'N/A' }}</p>
                        <p><span class="text-gray-400">Email:</span> {{ $user->email }}</p>
                        <p><span class="text-gray-400">Address:</span> {{ $user->address ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- RIGHT RENTAL INFO --}}
                <div class="text-right text-sm space-y-1">

                    <p><span class="text-gray-400">Date Rented:</span>
                        {{ $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->timezone('Asia/Manila')->format('F j, Y g:i A') : 'TBD' }}
                    </p>

                    <p><span class="text-gray-400">Return Date:</span>
                        {{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->timezone('Asia/Manila')->format('F j, Y g:i A') : 'TBD' }}
                    </p>

                    <p><span class="text-gray-400">Duration:</span>
                        {{ $rental->days ?? 'N/A' }} {{ $rental->rent_unit }}/s
                    </p>

                    <p><span class="text-gray-400">Total Price:</span> ₱{{ number_format($rental->total_price) }}</p>

                    {{-- STATUS --}}
                    <div class="mt-3 flex items-center justify-end gap-2">
                        @if($rental->status === 'accepted' && \Carbon\Carbon::parse($rental->end_date)->timezone('Asia/Manila')->isFuture())
                            <span class="bg-green-500 text-black px-4 py-1 rounded-full text-xs font-bold">
                                Active
                            </span>
                        @else
                            <button onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                                class="w-8 h-8 flex items-center justify-center rounded-full border border-red-600/40 text-red-500 hover:bg-red-600 hover:text-white transition-all duration-200">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                            <span class="bg-gray-600 text-white px-4 py-1 rounded-full text-xs font-bold">
                                Done
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <x-modals.delete_confirmation 
                :rentalId="$rental->id" 
                :route="'/garage/rental/' . $rental->id . '/hide-owner'" />

            @empty
                <p class="text-gray-500 text-center py-10">No renters yet.</p>
            @endforelse

        </div>

    </div>
</div>
</x-layout>
