<x-layout>
    <div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

        <x-back_button/>

        <x-garage_header
            active="notifications"
            title="Notifications"
            subtitle="Rental updates, reminders, and automatic decisions"
        />

        <div class="px-4 sm:px-10 pb-12 max-w-5xl mx-auto">

            {{-- TOP ACTION BAR --}}
            <div class="flex items-center justify-end gap-2 mb-5">

                {{-- Delete All --}}
                <form action="{{ route('notifications.delete-all') }}" method="POST" data-livewire-form>
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Delete all notifications? This cannot be undone.')"
                        class="px-5 py-2 rounded-full bg-[#1a1a1a] border border-red-500/20 text-red-400 hover:bg-red-500/10 hover:border-red-500/40 text-[10px] font-black uppercase tracking-widest transition">
                        Delete All
                    </button>
                </form>

                {{-- Mark All Read --}}
                <form action="{{ route('notifications.read-all') }}" method="POST" data-livewire-form>
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-5 py-2 rounded-full bg-[#1a1a1a] border border-white/10 text-gray-300 hover:text-white hover:border-white/30 text-[10px] font-black uppercase tracking-widest transition">
                        Mark All Read
                    </button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse ($items as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);

                        $isEndingType = in_array($notification->type, [
                            'rental_ending_soon',
                            'rental_ending_15min',
                            'rental_ending_1hour',
                            'rental_ending_1day',
                        ]);

                        $color = match (true) {
                            in_array($notification->type, ['rental_accepted', 'owner_rental_accepted'])
                                => 'text-lime-400 bg-lime-400/10 border-lime-400/30',
                            in_array($notification->type, ['rental_denied', 'rental_expired'])
                                => 'text-red-400 bg-red-600/10 border-red-600/30',
                            $isEndingType
                                => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30',
                            default
                                => 'text-blue-400 bg-blue-500/10 border-blue-500/30',
                        };

                        $icon = $isEndingType
                            ? 'fa-clock'
                            : (in_array($notification->type, ['rental_denied', 'rental_expired'])
                                ? 'fa-circle-exclamation'
                                : 'fa-bell');
                    @endphp

                    <div class="bg-[#1a1a1a] border {{ $isUnread ? 'border-lime-400/40' : 'border-gray-800' }} rounded-2xl p-5 flex items-start gap-4">

                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-full border flex items-center justify-center shrink-0 {{ $color }}">
                            <i class="fa-solid {{ $icon }} text-sm"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-white text-sm font-black uppercase tracking-tight">{{ $notification->title }}</h3>
                                    <p class="text-gray-400 text-xs mt-1 leading-relaxed">{{ $notification->body }}</p>
                                </div>
                                <span class="text-gray-600 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 mt-4">

                                {{-- Open --}}
                                @if ($notification->url)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" data-livewire-form>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="open" value="1">
                                        <button type="submit"
                                            class="px-4 py-2 rounded-full bg-lime-400 text-black text-[10px] font-black uppercase tracking-widest hover:bg-lime-300 transition">
                                            Open
                                        </button>
                                    </form>
                                @endif

                                {{-- Mark Read / Read label --}}
                                @if ($isUnread)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" data-livewire-form>
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 rounded-full border border-white/10 text-gray-400 hover:text-white hover:border-white/30 text-[10px] font-black uppercase tracking-widest transition">
                                            Mark Read
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-600 text-[10px] font-bold uppercase tracking-widest">Read</span>
                                @endif

                                {{-- Delete single --}}
                                <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" data-livewire-form class="ml-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        title="Delete notification"
                                        class="w-8 h-8 flex items-center justify-center rounded-full border border-red-500/20 text-red-400 hover:bg-red-500/10 hover:border-red-500/40 transition">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24">
                        <div class="w-16 h-16 rounded-full bg-[#1a1a1a] border border-gray-700 flex items-center justify-center mx-auto mb-4">
                            <i class="fa-regular fa-bell text-gray-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No notifications yet</p>
                        <p class="text-gray-600 text-xs mt-1">Rental activity and reminders will appear here.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-layout>