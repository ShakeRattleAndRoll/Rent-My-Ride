<x-layout>
<div class="bg-[#121212] min-h-screen text-white" style="font-family: 'Montserrat', sans-serif;">

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
                    <span>{{ \Carbon\Carbon::parse($car->created_at)->format('F j, Y') }}</span>
                    <span>{{ $car->fuel_type }}</span>
                    <span>{{ $car->transmission }}</span>
                </div>
            </div>

            {{-- Price --}}
            <div class="text-right">
                <p class="text-2xl font-bold">₱{{ number_format($car->price, 0) }}</p>
                <p class="text-gray-400 text-xs">/ day</p>
            </div>
        </div>

        {{-- RENTAL LIST --}}
        <div class="mt-10 space-y-6">

            @forelse ($rentals as $rental)

            @php
                $user = $rental->user;
            @endphp

            <div class="bg-[#2a2a2a] rounded-3xl p-6 flex items-center justify-between">

                {{-- LEFT USER INFO --}}
                <div class="flex items-center gap-5">

                    {{-- Avatar --}}
                    <div class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center text-black text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    {{-- Details --}}
                    <div class="text-sm space-y-1 text-gray-300">
                        <p><span class="text-gray-400">Username:</span> {{ $user->username ?? 'N/A' }}</p>
                        <p><span class="text-gray-400">Fullname:</span> {{ $user->full_name  }}</p>
                        <p><span class="text-gray-400">Contact Number:</span> {{ $user->contact_number ?? 'N/A' }}</p>
                        <p><span class="text-gray-400">Email:</span> {{ $user->email }}</p>
                        <p><span class="text-gray-400">Address:</span> {{ $user->address ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- RIGHT RENTAL INFO --}}
                <div class="text-right text-sm space-y-1">

                    <p><span class="text-gray-400">Date Rented:</span>
                        {{ \Carbon\Carbon::parse($rental->start_date)->format('F j, Y') }}
                    </p>

                    <p><span class="text-gray-400">Return Date:</span>
                        {{ \Carbon\Carbon::parse($rental->end_date)->format('F j, Y') }}
                    </p>

                    <p><span class="text-gray-400">Total Price:</span> ₱{{ number_format($rental->car->price) }}</p>

                    {{-- STATUS --}}
                    <div class="mt-3">
                        @if($rental->status === 'accepted')
                            <span class="bg-green-500 text-black px-4 py-1 rounded-full text-xs font-bold">
                                Active
                            </span>
                        @elseif($rental->status === 'pending')
                            <span class="bg-yellow-400 text-black px-4 py-1 rounded-full text-xs font-bold">
                                Pending
                            </span>
                        @else
                            <span class="bg-gray-600 text-white px-4 py-1 rounded-full text-xs font-bold">
                                Done
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @empty
                <p class="text-gray-500 text-center py-10">No renters yet.</p>
            @endforelse

        </div>

    </div>
</div>
</x-layout>