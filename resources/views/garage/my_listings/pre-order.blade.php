<x-layout>
    <div class="bg-[#121212] min-h-screen font-['Montserrat']">
        
        <x-back_button/>

        {{-- Header Section --}}
        <x-garage_header 
            active="listing" 
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
                                {{ $car->brand }}
                            </h1>
                            <h1 class="text-white text-3xl font-black italic uppercase tracking-tighter leading-none mb-2">
                                {{ $car->model }}
                            </h1>
                            
                            <div class="flex gap-6 mt-6 text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                                <span>Date Owned: <span class="text-white ml-1">{{ $car->date_owned->format('M j, Y') }}</span></span>
                                <span>Transmission: <span class="text-white ml-1">{{ $car->transmission }}</span></span>
                                <span>Fuel: <span class="text-white ml-1">{{ $car->fuel_type }}</span></span>
                            </div>

                            <p class="text-gray-400 mt-6 text-sm italic">{{ $car->description ?? 'No description available' }}</p>
                        </div>

                        <div class="text-right">
                            <p class="text-white text-2xl font-black italic leading-none">₱{{ number_format($car->price) }}</p>
                            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">Per {{ $car->rent_unit }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="h-px bg-gray-800 flex-1"></div>
                <h2 class="text-gray-500 text-xs font-bold uppercase tracking-[0.3em]">Pending Requests</h2>
                <div class="h-px bg-gray-800 flex-1"></div>
            </div>

            {{-- Sort Buttons --}}
            <div class="flex items-center gap-2 mb-6">
                <button onclick="sortTable('fcfs')"
                    id="btn-fcfs"
                    class="sort-btn px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-200 bg-yellow-400 text-black">
                    First Come First Serve
                </button>
                <button onclick="sortTable('longest')"
                    id="btn-longest"
                    class="sort-btn px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-200 bg-[#1a1a1a] text-gray-400 border border-gray-700 hover:border-gray-500">
                    Longest Days
                </button>
                <button onclick="sortTable('shortest')"
                    id="btn-shortest"
                    class="sort-btn px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-200 bg-[#1a1a1a] text-gray-400 border border-gray-700 hover:border-gray-500">
                    Shortest Days
                </button>
            </div>

            {{-- Pending Renters Table --}}
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
                            <tr class="bg-[#1a1a1a] border border-gray-800 text-gray-300 text-[11px] hover:bg-[#222] transition-all group"
                                data-created="{{ $order->created_at->timestamp }}"
                                data-days="{{ $order->days }}">

                                <td class="px-6 py-4 first:rounded-l-2xl border-y border-l border-transparent group-hover:border-gray-700">
                                    <a href="{{ route('user.profile', $order->user->id) }}"
                                       class="inline-flex items-center gap-3
                                              border border-transparent hover:border-white/30
                                              rounded-xl px-2 py-1 -mx-2
                                              transition-all duration-300">
                                        <img 
                                            src="{{ $order->user->profile_picture ? asset('storage/' . $order->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($order->user->username ?? $order->user->full_name ?? 'User') . '&length=1&background=1a1a1a&color=ffffff&bold=true&size=128' }}"
                                            alt="{{ $order->user->username }}"
                                            class="w-8 h-8 rounded-full object-cover border border-gray-700"
                                        >
                                        <span>{{ $order->user->username }}</span>
                                    </a>
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

                                        {{-- Accept --}}
                                        <button 
                                            onclick="openAcceptModal({{ $order->id }}, '{{ $order->rent_unit }}', {{ $order->days }}, {{ $order->total_price }})"
                                            class="bg-lime-500 hover:bg-lime-400 text-black px-5 py-1.5 rounded-full font-black text-[9px] uppercase tracking-tighter transition-all active:scale-95 shadow-lg shadow-lime-500/20">
                                            Accept
                                        </button>

                                        {{-- Deny --}}
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

    <x-modals.accept-rental />
    <script src="{{ asset('js/rental-accept.js') }}"></script>
    <script src="{{ asset('js/pre-order-sort.js') }}"></script>
</x-layout>