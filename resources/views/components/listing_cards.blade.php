@props(['car'])

@php
    $ActiveRent = $car->rentals()->where('status', 'accepted')->orderBy('start_date', 'desc')->first();
    $IsOccupied = !is_null($ActiveRent) && \Carbon\Carbon::parse($ActiveRent->end_date)->timezone('Asia/Manila')->isFuture();
@endphp

<div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-800 bg-[#1a1a1a] transition-all duration-200 hover:border-gray-600">

    {{-- Car Image --}}
    <div class="aspect-[16/9] w-full overflow-hidden bg-gray-800">
        <img src="{{ asset('storage/' . $car->car_image) }}"
             alt="{{ $car->brand }} {{ $car->model }}"
             class="h-full w-full object-cover">
    </div>

    <div class="flex flex-1 flex-col p-3">
        {{-- Car Info --}}
        <div class="min-w-0">
            <h2 class="truncate text-base font-bold leading-tight text-white">{{ $car->brand }}</h2>
            <p class="mb-2 truncate text-xs text-gray-400">{{ $car->model }}</p>

            <div class="mb-3 flex flex-wrap gap-x-3 gap-y-1.5 text-[11px] text-gray-400">
                <span class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($car->date_owned)->format('M d, Y') }}</span>
                </span>

                <span class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M3 22V6a2 2 0 012-2h8a2 2 0 012 2v16M3 22h14M13 8h2a2 2 0 012 2v3a1 1 0 001 1h0a1 1 0 001-1V9l-3-3"/>
                    </svg>
                    <span class="truncate">{{ $car->fuel_type }}</span>
                </span>

                <span class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                    <span class="truncate">{{ $car->transmission }}</span>
                </span>
            </div>

            {{-- Status + Details --}}
            <div class="flex flex-wrap items-center gap-2">
                @if ($IsOccupied)
                    <span class="rounded-full bg-green-500 px-2.5 py-1 text-[11px] font-bold text-white">Occupied</span>
                @else
                    <span class="rounded-full bg-gray-600 px-2.5 py-1 text-[11px] font-bold text-white">Unoccupied</span>
                @endif

                <a href="/garage/details/{{ $car->id }}" wire:navigate data-nav-navigate
                   class="rounded-full border border-gray-700 bg-[#2a2a2a] px-3 py-1 text-[11px] font-bold text-gray-300 transition-all duration-200 hover:bg-[#333]">
                    Details
                </a>
            </div>
        </div>

        {{-- Price + Actions --}}
        <div class="mt-auto flex flex-col gap-2.5 pt-4">
            <form action="{{ route('car.toggle-auto-accept', $car->id) }}" method="POST" data-livewire-form data-preserve-scroll
                  class="flex items-center justify-between gap-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="auto_accept" value="0">
                <input type="hidden" name="auto_accept_priority" value="{{ $car->auto_accept_priority ?? 'first_pending' }}">
                <span class="text-[9px] font-black uppercase tracking-widest {{ $car->auto_accept ? 'text-lime-400' : 'text-gray-500' }}">
                    Auto Accept
                </span>
                <label class="relative inline-flex h-5 w-10 cursor-pointer items-center">
                    <input type="checkbox"
                        name="auto_accept"
                        value="1"
                        class="peer sr-only"
                        onchange="this.form.requestSubmit()"
                        {{ $car->auto_accept ? 'checked' : '' }}>
                    <span class="absolute inset-0 rounded-full bg-gray-700 transition-colors duration-200 peer-checked:bg-lime-500"></span>
                    <span class="relative ml-1 inline-block h-3.5 w-3.5 rounded-full bg-white transition-transform duration-200 peer-checked:translate-x-5"></span>
                </label>
            </form>

            <p class="text-sm font-bold text-white">
                &#8369;{{ number_format($car->price, 0) }}
                <span class="text-xs font-normal text-gray-400">/ per {{ $car->rent_unit }}</span>
            </p>

            <div class="grid grid-cols-1 gap-2 min-[420px]:grid-cols-2">
                {{-- Pre Orders --}}
                <a href="/car/pre-order/{{ $car->id }}" wire:navigate data-nav-navigate
                   class="relative w-full rounded-full bg-yellow-400 px-3 py-1.5 text-center text-[11px] font-bold text-black transition-all duration-200 hover:bg-yellow-300">
                    Pre orders

                    <span data-car-pending-orders-badge="{{ $car->id }}" class="absolute -right-1 -top-1 h-4 w-4 rounded-full bg-red-500 text-[10px] font-bold text-white {{ isset($car->pending_orders_count) && $car->pending_orders_count > 0 ? 'flex' : 'hidden' }} items-center justify-center">
                        {{ isset($car->pending_orders_count) && $car->pending_orders_count > 99 ? '99+' : ($car->pending_orders_count ?? 0) }}
                    </span>
                </a>

                {{-- Edit --}}
                @if ($IsOccupied)
                    <button type="button"
                            class="w-full cursor-not-allowed rounded-full border border-gray-700 bg-gray-800 px-3 py-1.5 text-center text-[11px] font-bold text-gray-500">
                        Edit Post
                    </button>
                @else
                    <a href="/garage/edit/{{ $car->id }}" wire:navigate data-nav-navigate
                       class="w-full rounded-full bg-yellow-400 px-3 py-1.5 text-center text-[11px] font-bold text-black transition-all duration-200 hover:bg-yellow-300">
                        Edit Post
                    </a>
                @endif

                {{-- DELETE --}}
                @if ($IsOccupied)
                    <button type="button"
                            class="w-full cursor-not-allowed rounded-full border border-gray-700 bg-gray-800 px-3 py-1.5 text-center text-[11px] font-bold text-gray-500 min-[420px]:col-span-2">
                        Delete Post
                    </button>
                @else
                    <button type="button"
                            onclick="document.getElementById('delete-car-modal-{{ $car->id }}').classList.remove('hidden')"
                            class="w-full rounded-full bg-red-500 px-3 py-1.5 text-center text-[11px] font-bold text-white transition-all duration-200 hover:bg-red-400 min-[420px]:col-span-2">
                        Delete Post
                    </button>
                @endif
            </div>
        </div>
    </div>

</div>

<x-modals.delete_post :carId="$car->id" />
