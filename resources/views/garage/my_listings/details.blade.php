<x-layout>
<div class="min-h-screen bg-[#121212] text-white" style="font-family: 'Montserrat', sans-serif;">

    <x-back_button/>

    <x-garage_header
        active="listing"
        title="Details"
        subtitle="Status and history"
    />

    <div class="px-4 pb-10 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">

            {{-- CAR SUMMARY --}}
            <section class="grid overflow-hidden rounded-xl border border-gray-800 bg-[#1a1a1a] transition-all duration-200 md:grid-cols-[320px_1fr]">
                <div class="aspect-[16/9] overflow-hidden bg-gray-800 md:aspect-auto">
                    <img src="{{ asset('storage/' . $car->car_image) }}"
                         class="h-full w-full object-cover"
                         alt="{{ $car->brand }}">
                </div>

                <div class="flex flex-col gap-5 p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest text-lime-300">Vehicle</p>
                            <h2 class="mt-1 truncate text-2xl font-black leading-tight">{{ $car->brand }}</h2>
                            <p class="truncate text-sm text-gray-400">{{ $car->model }}</p>
                        </div>

                        <div class="shrink-0 rounded-lg border border-white/10 bg-[#242424] px-4 py-3 text-left sm:text-right">
                            <p class="text-xl font-black">&#8369;{{ number_format($car->price, 0) }}</p>
                            <p class="text-[11px] font-semibold text-gray-400">/ {{ $car->rent_unit }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-400 sm:grid-cols-3">
                        <div class="rounded-lg border border-white/5 bg-[#202020] px-3 py-2">
                            <p class="mb-1 text-[9px] font-black uppercase tracking-widest text-gray-500">Date Owned</p>
                            <p class="flex items-center gap-2 text-xs font-semibold text-gray-300">
                                <i class="fa-regular fa-calendar text-gray-500"></i>
                                {{ \Carbon\Carbon::parse($car->date_owned)->format('M j, Y') }}
                            </p>
                        </div>
                        <div class="rounded-lg border border-white/5 bg-[#202020] px-3 py-2">
                            <p class="mb-1 text-[9px] font-black uppercase tracking-widest text-gray-500">Fuel</p>
                            <p class="flex items-center gap-2 text-xs font-semibold text-gray-300">
                                <i class="fa-solid fa-gas-pump text-gray-500"></i>
                                {{ $car->fuel_type }}
                            </p>
                        </div>
                        <div class="rounded-lg border border-white/5 bg-[#202020] px-3 py-2">
                            <p class="mb-1 text-[9px] font-black uppercase tracking-widest text-gray-500">Transmission</p>
                            <p class="flex items-center gap-2 text-xs font-semibold text-gray-300">
                                <i class="fa-solid fa-gears text-gray-500"></i>
                                {{ $car->transmission }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- RENTAL LIST --}}
            <section class="mt-8">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-lime-300">Rental History</p>
                        <h3 class="text-lg font-black">Accepted renters</h3>
                    </div>
                    <p class="text-xs font-medium text-gray-500">Only accepted rentals are shown here.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($rentals->where('status', 'accepted') as $rental)

                    @php
                        $user       = $rental->user;
                        $now        = \Carbon\Carbon::now('Asia/Manila');
                        $start      = \Carbon\Carbon::parse($rental->start_date, 'Asia/Manila');
                        $end        = \Carbon\Carbon::parse($rental->end_date, 'Asia/Manila');
                        $isUpcoming = $now->lt($start);
                        $isActive   = $now->between($start, $end);
                        $isFinished = $now->gt($end);
                    @endphp

                    <article class="rental-card flex h-full flex-col rounded-xl border border-gray-800 bg-[#1a1a1a] p-3 transition-all duration-200 hover:border-gray-600"
                             data-rental-card="{{ $rental->id }}">

                        {{-- USER INFO --}}
                        <div class="flex items-center gap-3 border-b border-white/5 pb-3">
                            <a href="{{ route('user.profile', $user->id) }}" wire:navigate data-nav-navigate
                               class="block h-14 w-14 shrink-0 overflow-hidden rounded-full border-2 border-transparent bg-gray-700 transition-all duration-300 hover:border-white/30">
                                <img
                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username ?? $user->username ?? 'User') }}"
                                    alt="{{ $user->name }}"
                                    class="h-full w-full object-cover"
                                >
                            </a>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-black text-white">{{ $user->username ?? 'N/A' }}</p>
                                <p class="truncate text-xs text-gray-400">{{ $user->full_name }}</p>
                            </div>
                        </div>

                        <div class="mt-3 space-y-2 text-xs text-gray-300">
                            <p class="truncate"><span class="text-gray-500">Contact:</span> {{ $user->contact_number ?? 'N/A' }}</p>
                            <p class="truncate"><span class="text-gray-500">Email:</span> {{ $user->email }}</p>
                            <p class="truncate"><span class="text-gray-500">Address:</span> {{ $user->address ?? 'N/A' }}</p>
                        </div>

                        <div class="mt-4 grid gap-2 text-xs">
                            <div class="rounded-lg border border-white/5 bg-[#202020] px-3 py-2">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Date Rented</p>
                                <p class="mt-1 text-gray-300">
                                    {{ $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->format('M j, Y g:i A') : 'TBD' }}
                                </p>
                            </div>
                            <div class="rounded-lg border border-white/5 bg-[#202020] px-3 py-2">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Return Date</p>
                                <p class="mt-1 text-gray-300">
                                    {{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('M j, Y g:i A') : 'TBD' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="rounded-lg border border-white/5 bg-[#242424] px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                {{ $rental->days ?? 'N/A' }} {{ $rental->rent_unit }}/s
                            </span>
                            <span class="rounded-lg border border-lime-400/20 bg-lime-400/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-lime-400">
                                Total: &#8369;{{ number_format($rental->total_price) }}
                            </span>
                        </div>

                        {{-- STATUS --}}
                        <div class="mt-auto flex items-center justify-between gap-2 pt-4">
                            @if ($isUpcoming)
                                <span class="rounded-full border border-blue-500/50 bg-blue-500/20 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-blue-400">
                                    Upcoming
                                </span>
                            @elseif ($isActive)
                                <span class="rounded-full bg-lime-400 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-black">
                                    Active
                                </span>
                            @elseif ($isFinished)
                                <span class="rounded-full bg-gray-700 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-300">
                                    Completed
                                </span>
                                <button onclick="document.getElementById('delete-modal-{{ $rental->id }}').classList.remove('hidden')"
                                    class="flex h-9 w-9 items-center justify-center rounded-full border border-red-600/40 text-red-500 transition-all duration-200 hover:bg-red-600 hover:text-white">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                </button>
                            @endif
                        </div>
                    </article>

                    <x-modals.delete_confirmation 
                        :rentalId="$rental->id" 
                        :route="'/garage/rental/' . $rental->id . '/hide-owner'" />

                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-gray-800 bg-[#1a1a1a] py-20 text-center">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-gray-700 bg-[#202020]">
                                <i class="fa-solid fa-user-clock text-2xl text-gray-600"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-400">No renters yet.</p>
                            <p class="mt-1 text-xs text-gray-600">Accepted rentals for this listing will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
</x-layout>
