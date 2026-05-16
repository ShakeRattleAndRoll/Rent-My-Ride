@props(['car'])

@php
    $now = now('Asia/Manila');

    $acceptedRentals = $car->relationLoaded('rentals')
        ? $car->rentals->where('status', 'accepted')
        : $car->rentals()->where('status', 'accepted')->get();

    $ActiveRent = $acceptedRentals->first(function ($rental) use ($now) {
        $start = \Carbon\Carbon::parse($rental->start_date)->timezone('Asia/Manila');
        $end = \Carbon\Carbon::parse($rental->end_date)->timezone('Asia/Manila');

        return $start->lte($now) && $end->gte($now);
    });

    $UpcomingRent = $acceptedRentals
        ->filter(fn ($rental) => \Carbon\Carbon::parse($rental->start_date)->timezone('Asia/Manila')->gt($now))
        ->sortBy(fn ($rental) => \Carbon\Carbon::parse($rental->start_date)->timestamp)
        ->first();

    $IsOccupied = ! is_null($ActiveRent);
    $IsUpcoming = ! $IsOccupied && ! is_null($UpcomingRent);
    $LocksActions = $IsOccupied || $IsUpcoming;

    $isPendingApproval = ($car->approval_status ?? 'approved') === 'pending';
@endphp

<div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-[#121212] shadow-[0_0_0_1px_rgba(255,255,255,0.02)] transition-all duration-300 hover:-translate-y-1 hover:border-lime-400/40 hover:shadow-2xl">

    {{-- IMAGE --}}
    <div class="relative aspect-[16/10] overflow-hidden rounded-t-2xl">

        <img src="{{ asset('storage/' . $car->car_image) }}"
            alt="{{ $car->brand }} {{ $car->model }}"
            class="h-full w-full rounded-t-2xl object-cover transition-transform duration-500 group-hover:scale-105">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        {{-- PRICE --}}
        <div class="absolute bottom-3 left-3">
            <div class="rounded-xl bg-black/70 px-3 py-2 backdrop-blur">
                <p class="text-lg font-black text-white">
                    ₱{{ number_format($car->price, 0) }}
                </p>

                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">
                    per {{ $car->rent_unit }}
                </p>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="absolute right-3 top-3 flex flex-col gap-2">

            @if ($isPendingApproval)
                <span class="flex w-[78px] items-center justify-center rounded-full bg-yellow-400 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-black shadow-lg">
                    Pending
                </span>
            @else
                @if ($car->is_available)
                    <span data-availability-badge class="flex w-[78px] items-center justify-center rounded-full bg-lime-400 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-black shadow-lg">
                        Visible
                    </span>
                @else
                    <span data-availability-badge class="flex w-[78px] items-center justify-center rounded-full bg-red-500 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white shadow-lg">
                        Hidden
                    </span>
                @endif
            @endif

            @if ($IsOccupied)
                <span class="rounded-full bg-lime-500/90 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-black backdrop-blur">
                    Occupied
                </span>
            @elseif ($IsUpcoming)
                <span class="rounded-full bg-blue-500/90 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white backdrop-blur">
                    Upcoming
                </span>
            @else
                <span class="rounded-full bg-white/10 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white backdrop-blur">
                    Available
                </span>
            @endif

        </div>
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-1 flex-col p-5">

        {{-- TITLE --}}
        <div class="min-w-0">
            <h2 class="truncate text-xl font-black tracking-tight text-white">
                {{ $car->brand }}
            </h2>

            <p class="mt-1 truncate text-sm text-gray-400">
                {{ $car->model }}
            </p>
        </div>

        {{-- INFO ROW (ALL 3 SIDE BY SIDE) --}}
        <div class="mt-5 flex items-center justify-between gap-3 text-xs text-gray-300">

            {{-- Fuel --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gas-pump text-gray-400"></i>
                <span class="font-semibold">{{ $car->fuel_type }}</span>
            </div>

            {{-- Divider --}}
            <div class="h-4 w-px bg-white/10"></div>

            {{-- Transmission --}}
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gear text-gray-400"></i>
                <span class="font-semibold">{{ $car->transmission }}</span>
            </div>

            {{-- Divider --}}
            <div class="h-4 w-px bg-white/10"></div>

            {{-- Date Owned --}}
            <div class="flex items-center gap-2 whitespace-nowrap">
                <i class="fa-regular fa-calendar text-gray-400"></i>
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($car->date_owned)->format('M d, Y') }}
                </span>
            </div>

        </div>

        {{-- LISTING CONTROLS --}}
        <div class="mt-4 grid grid-cols-2 gap-2">
            <div data-availability-panel class="rounded-xl border border-white/10 bg-[#171717] p-2.5">
                <div class="mb-2 flex items-center justify-between gap-2">
                    <span data-availability-title class="{{ $car->is_available ? 'text-lime-400' : 'text-white' }} truncate text-[9px] font-black uppercase tracking-widest">
                        Visibility
                    </span>
                    <span data-availability-status class="rounded-full border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-widest {{ $car->is_available ? 'text-lime-300' : 'text-gray-500' }}">
                        {{ $car->is_available ? 'On' : 'Off' }}
                    </span>
                </div>

                <form action="{{ route('garage.availability', $car->id) }}"
                      method="POST"
                      data-livewire-form
                      data-stay-on-submit
                      data-availability-form
                      class="flex items-center justify-end">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="is_available" value="0">

                    <label class="relative inline-flex h-6 w-11 cursor-pointer items-center">
                        <input type="checkbox"
                               name="is_available"
                               value="1"
                               class="peer sr-only"
                               onchange="this.form.requestSubmit()"
                               {{ $car->is_available ? 'checked' : '' }}>
                        <span class="absolute inset-0 rounded-full bg-gray-700 transition-colors duration-200 peer-checked:bg-lime-500"></span>
                        <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-all duration-200 peer-checked:left-6"></span>
                    </label>
                </form>
            </div>

            <div data-auto-accept-panel class="rounded-xl border border-white/10 bg-[#171717] p-2.5">
                <div class="mb-2 flex items-center justify-between gap-2">
                    <span data-auto-accept-title class="{{ $car->auto_accept ? 'text-lime-400' : 'text-white' }} truncate text-[9px] font-black uppercase tracking-widest">
                        Auto Accept
                    </span>
                    <span data-auto-accept-status class="rounded-full border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-widest {{ $car->auto_accept ? 'text-lime-300' : 'text-gray-500' }}">
                        {{ $car->auto_accept ? 'On' : 'Off' }}
                    </span>
                </div>

                <form action="{{ route('car.toggle-auto-accept', $car->id) }}"
                      method="POST"
                      data-livewire-form
                      data-stay-on-submit
                      data-auto-accept-form
                      class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="auto_accept" value="0">

                    <select name="auto_accept_priority"
                            onchange="this.form.requestSubmit()"
                            aria-label="Auto-accept priority for {{ $car->brand }} {{ $car->model }}"
                            class="min-w-0 flex-1 rounded-lg border border-white/10 bg-[#242424] px-2 py-1.5 text-[9px] font-bold uppercase tracking-wider text-gray-300 outline-none transition focus:border-lime-400">
                        <option value="first_pending" {{ ($car->auto_accept_priority ?? 'first_pending') === 'first_pending' ? 'selected' : '' }}>First</option>
                        <option value="shortest" {{ ($car->auto_accept_priority ?? 'first_pending') === 'shortest' ? 'selected' : '' }}>Short</option>
                        <option value="longest" {{ ($car->auto_accept_priority ?? 'first_pending') === 'longest' ? 'selected' : '' }}>Long</option>
                        <option value="nearest" {{ ($car->auto_accept_priority ?? 'first_pending') === 'nearest' ? 'selected' : '' }}>Near</option>
                    </select>

                    <label class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center">
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
        </div>

        {{-- ACTIONS --}}
        <div class="mt-auto pt-6">

            <div class="grid grid-cols-2 gap-2">

                <a href="/garage/details/{{ $car->id }}"
                   wire:navigate
                   data-nav-navigate
                   class="flex items-center justify-center rounded-xl border border-white/10 bg-[#1b1b1b] px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-gray-300 hover:border-white/20 hover:bg-[#242424] hover:text-white">
                    Details
                </a>

                <a href="/car/pre-order/{{ $car->id }}"
                   wire:navigate
                   data-nav-navigate
                   class="relative flex items-center justify-center rounded-xl border border-white/10 bg-[#1b1b1b] px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-gray-300 hover:border-white/20 hover:bg-[#242424] hover:text-white">

                    Pre Orders

                    <span
                        data-car-pending-orders-badge="{{ $car->id }}"
                        class="absolute -right-1 -top-1 h-5 min-w-5 rounded-full bg-red-500 px-1 text-[10px] font-bold text-white {{ isset($car->pending_orders_count) && $car->pending_orders_count > 0 ? 'flex' : 'hidden' }} items-center justify-center">
                        {{ isset($car->pending_orders_count) && $car->pending_orders_count > 99 ? '99+' : ($car->pending_orders_count ?? 0) }}
                    </span>

                </a>

            </div>

            <div class="mt-2 grid grid-cols-2 gap-2">

                <a href="/garage/edit/{{ $car->id }}"
                   wire:navigate
                   data-nav-navigate
                   class="rounded-xl border border-white/10 bg-[#1b1b1b] px-4 py-3 text-center text-[11px] font-black uppercase tracking-[0.15em] text-gray-300 hover:border-white/20 hover:bg-[#242424] hover:text-white">
                    Edit
                </a>

                @if ($LocksActions)
                    <button type="button"
                            class="cursor-not-allowed rounded-xl border border-white/5 bg-[#181818] px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-gray-600">
                        Delete
                    </button>
                @else
                    <button type="button"
                            onclick="document.getElementById('delete-car-modal-{{ $car->id }}').classList.remove('hidden')"
                            class="rounded-xl bg-red-500 px-4 py-3 text-[11px] font-black uppercase tracking-[0.15em] text-white hover:bg-red-400">
                        Delete
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<x-modals.delete_post :carId="$car->id" />
