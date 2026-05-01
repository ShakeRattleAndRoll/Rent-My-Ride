<x-layout>
    <div class="bg-[#121212] min-h-screen font-['Montserrat']">
        
        {{-- Header Section --}}
        <x-garage_header 
            active="listings" 
            title="Pre-Order Management" 
            subtitle="Review rental requests for this vehicle" 
        />

        <div class="max-w-7xl mx-auto px-10 pb-20">
            
            {{-- Car Info Card --}}
            <div class="flex bg-[#1a1a1a] border border-gray-800 rounded-3xl overflow-hidden mb-8 h-64"> 
    
            <div class="w-1/3 h-full shrink-0">
                <img src="{{ asset('storage/' . ($car->car_image ?? $car->image)) }}" 
                    alt="{{ $car->brand }}" 
                    class="w-full h-full object-cover"> 
            </div>

            <div class="flex-1 p-8 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-yellow-500 text-[10px] font-bold uppercase tracking-widest mb-1">Current Vehicle</p>
                        <h1 class="text-white text-3xl font-black italic uppercase tracking-tighter leading-none mb-2">
                            {{ $car->brand }} {{ $car->model }}
                        </h1>
                        <p class="text-gray-400 text-sm italic">{{ $car->description ?? 'No description available' }}</p>
                        
                        <div class="flex gap-6 mt-6 text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                            <span>Transmission: <span class="text-white ml-1">{{ $car->transmission }}</span></span>
                            <span>Fuel: <span class="text-white ml-1">{{ $car->fuel_type }}</span></span>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-white text-2xl font-black italic leading-none">₱{{ number_format($car->price) }}</p>
                        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">Per Day</p>
                    </div>
                </div>
            </div>
        </div>

            {{-- 2. Pending Renters Table --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="h-px bg-gray-800 flex-1"></div>
                <h2 class="text-gray-500 text-xs font-bold uppercase tracking-[0.3em]">Pending Requests</h2>
                <div class="h-px bg-gray-800 flex-1"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-white font-bold text-[10px] uppercase tracking-widest opacity-60">
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Fullname</th>
                            <th class="px-6 py-4">Contact Number</th>
                            <th class="px-6 py-4">Email Address</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4 text-center">Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preOrders as $order)
                            <tr class="bg-[#1a1a1a] border border-gray-800 text-gray-300 text-[11px] hover:bg-[#222] transition-all group">
                                <td class="px-6 py-4 first:rounded-l-2xl border-y border-l border-transparent group-hover:border-gray-700">
                                    <span class="text-white font-bold">@</span>{{ $order->user->username }}
                                </td>
                                <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700 uppercase font-semibold">
                                    {{ $order->user->full_name }}
                                </td>
                                <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700">
                                    {{ $order->user->contact_number ?? 'NO CONTACT' }}
                                </td>
                                <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700 underline text-blue-400 decoration-blue-400/30">
                                    {{ $order->user->email }}
                                </td>
                                <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700 text-gray-500 italic">
                                    {{ $order->user->address ?? 'Not specified' }}
                                </td>
                                <td class="px-6 py-4 last:rounded-r-2xl border-y border-r border-transparent group-hover:border-gray-700 text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Accept Button --}}
                                         <form action="/rental/{{ $order->id }}/accept" method="POST" class="inline">
                                            @csrf
                                            <button class="bg-lime-500 hover:bg-lime-400 text-black px-5 py-1.5 rounded-full font-black text-[9px] uppercase tracking-tighter transition-all active:scale-95 shadow-lg shadow-lime-500/20">
                                                Accept
                                            </button>
                                        </form>

                                        {{-- Deny Button --}}
                                        <form action="/rental/{{ $order->id }}/deny" method="POST" class="inline">
                                            @csrf
                                            <button class="bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white border border-red-600/50 px-5 py-1.5 rounded-full font-black text-[9px] uppercase tracking-tighter transition-all active:scale-95">
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 mb-4 text-gray-500 opacity-70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        
                                        <p class="text-gray-400 text-sm font-bold uppercase tracking-[0.4em]">No pending requests</p>
                                        <p class="text-gray-600 text-xs mt-2 italic font-normal">When someone clicks "Rent Now", they will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>