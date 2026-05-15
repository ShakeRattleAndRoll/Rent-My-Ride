@forelse($preOrders as $order)
    @php
        $startTimestamp = \Carbon\Carbon::parse($order->start_date)->timestamp;
        $endTimestamp = \Carbon\Carbon::parse($order->end_date)->timestamp;
    @endphp
    <tr class="bg-[#1a1a1a] border border-gray-800 text-gray-300 text-[11px] hover:bg-[#222] transition-all group"
        data-id="{{ $order->id }}"
        data-created="{{ $order->created_at->timestamp }}"
        data-start="{{ $startTimestamp }}"
        data-end="{{ $endTimestamp }}"
        data-duration="{{ $endTimestamp - $startTimestamp }}">

        <td class="px-6 py-4 first:rounded-l-2xl border-y border-l border-transparent group-hover:border-gray-700">
            <a href="{{ route('user.profile', $order->user->id) }}" wire:navigate data-nav-navigate
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

        <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700">
            <div class="flex flex-col gap-0.5">
                <span class="text-white font-semibold">
                    {{ \Carbon\Carbon::parse($order->start_date)->format('M j, Y') }}
                </span>
                <span class="text-gray-500 text-[10px]">
                    {{ \Carbon\Carbon::parse($order->start_date)->format('g:i A') }}
                </span>
            </div>
        </td>

        <td class="px-6 py-4 border-y border-transparent group-hover:border-gray-700">
            <div class="flex flex-col gap-0.5">
                <span class="text-lime-400 font-semibold">
                    {{ \Carbon\Carbon::parse($order->end_date)->format('M j, Y') }}
                </span>
                <span class="text-gray-500 text-[10px]">
                    {{ \Carbon\Carbon::parse($order->end_date)->format('g:i A') }}
                    &bull;
                    {{ $order->days }} {{ $order->rent_unit }}{{ $order->days > 1 ? 's' : '' }}
                </span>
            </div>
        </td>

        <td class="px-6 py-4 last:rounded-r-2xl border-y border-r border-transparent group-hover:border-gray-700 text-center">
            <div class="flex justify-center gap-2">
                <button
                    onclick="openAcceptModal(
                        {{ $order->id }},
                        '{{ $order->rent_unit }}',
                        {{ $order->days }},
                        {{ $order->total_price }},
                        '{{ \Carbon\Carbon::parse($order->start_date, 'Asia/Manila')->format("Y-m-d\TH:i:s") }}',
                        '{{ \Carbon\Carbon::parse($order->end_date, 'Asia/Manila')->format("Y-m-d\TH:i:s") }}'
                    )"
                    class="bg-lime-500 hover:bg-lime-400 text-black px-5 py-1.5 rounded-full font-black text-[9px] uppercase tracking-tighter transition-all active:scale-95 shadow-lg shadow-lime-500/20">
                    Accept
                </button>

                <form action="/rental/{{ $order->id }}/deny" method="POST" class="inline" data-livewire-form>
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
