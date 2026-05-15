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
                            <p class="text-lime-400 text-[10px] font-bold uppercase tracking-widest mb-1">Current Vehicle</p>
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

            {{-- Auto-Accept --}}
            <div data-auto-accept-panel class="flex items-center justify-between gap-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl p-6 mb-8">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 data-auto-accept-title class="{{ $car->auto_accept ? 'text-lime-400' : 'text-white' }} font-bold text-sm uppercase tracking-wider">Auto-Accept Requests</h3>
                        <span data-auto-accept-status class="rounded-full border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-widest {{ $car->auto_accept ? 'text-lime-300' : 'text-gray-500' }}">
                            {{ $car->auto_accept ? 'On' : 'Off' }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-[10px] mt-1">When enabled, pending and new requests are accepted by priority if their dates are still available.</p>
                </div>

                <form action="{{ route('car.toggle-auto-accept', $car->id) }}" method="POST" data-livewire-form data-stay-on-submit data-auto-accept-form
                      class="flex items-center justify-end gap-4">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="auto_accept" value="0">

                    <select name="auto_accept_priority"
                        onchange="this.form.requestSubmit()"
                        class="bg-[#242424] border border-white/10 text-gray-300 text-[10px] font-bold uppercase tracking-widest rounded-xl px-3 py-2 outline-none focus:border-lime-400">
                        <option value="first_pending" {{ ($car->auto_accept_priority ?? 'first_pending') === 'first_pending' ? 'selected' : '' }}>First Pending</option>
                        <option value="shortest"      {{ ($car->auto_accept_priority ?? 'first_pending') === 'shortest'      ? 'selected' : '' }}>Shortest Duration</option>
                        <option value="longest"       {{ ($car->auto_accept_priority ?? 'first_pending') === 'longest'       ? 'selected' : '' }}>Longest Duration</option>
                        <option value="nearest"       {{ ($car->auto_accept_priority ?? 'first_pending') === 'nearest'       ? 'selected' : '' }}>Nearest Start Date</option>
                    </select>

                    <label class="relative inline-flex h-6 w-11 cursor-pointer items-center">
                        <input type="checkbox"
                            name="auto_accept"
                            value="1"
                            class="peer sr-only"
                            onchange="this.form.requestSubmit()"
                            {{ $car->auto_accept ? 'checked' : '' }}>
                        <span class="absolute inset-0 rounded-full bg-gray-700 transition-colors duration-200 peer-checked:bg-lime-500"></span>
                        <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-all duration-200 peer-checked:left-6"></span>
                    </label>
                </form>
            </div>

            {{-- Pending Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="h-px bg-gray-800 flex-1"></div>
                <h2 class="text-gray-500 text-xs font-bold uppercase tracking-[0.3em]">Pending Requests</h2>
                <div class="h-px bg-gray-800 flex-1"></div>
            </div>

            {{-- Sort Dropdown --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Sort by</span>
                <div class="relative">
                    <select id="sort-select"
                        onchange="sortTable(this.value)"
                        class="appearance-none bg-[#1a1a1a] border border-gray-700 hover:border-gray-500 text-gray-300 text-[10px] font-black uppercase tracking-widest rounded-full px-5 py-2 pr-8 outline-none focus:border-lime-400 cursor-pointer transition-all duration-200">
                        <option value="fcfs">First Pending</option>
                        <option value="longest">Longest Duration</option>
                        <option value="shortest">Shortest Duration</option>
                        <option value="nearest">Nearest Start Date</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending Renters Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-white font-bold text-[10px] uppercase tracking-widest opacity-60">
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Fullname</th>
                            <th class="px-6 py-4">Contact Number</th>
                            <th class="px-6 py-4">Start Date</th>
                            <th class="px-6 py-4">End Date</th>
                            <th class="px-6 py-4 text-center">Decision</th>
                        </tr>
                    </thead>
                    <tbody data-pre-orders-list data-refresh-url="{{ route('pre-orders.items', $car->id) }}">
                        @include('garage.my_listings.partials.pre-order-rows', ['preOrders' => $preOrders])
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <x-modals.accept-rental />
    <script src="{{ asset('js/rental-accept.js') }}"></script>
    <script src="{{ asset('js/pre-order-sort.js') }}"></script>
    <script src="{{ asset('js/pre-orders-live.js') }}"></script>
</x-layout>
