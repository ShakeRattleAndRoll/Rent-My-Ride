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

            $isTimelineType = $isEndingType;
            $usesActorAvatar = in_array($notification->type, ['rental_requested', 'rental_cancelled'], true);
            $actor = $usesActorAvatar ? $notification->rental?->user : null;
            $actorInitials = $actor
                ? Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($actor->username ?? 'User', 0, 2))
                : null;

            $color = match (true) {
                in_array($notification->type, ['rental_accepted', 'owner_rental_accepted'])
                    || $notification->type === 'car_post_approved'
                    => 'text-lime-400 bg-lime-400/10 border-lime-400/30',
                $isTimelineType
                    => 'text-yellow-300 bg-yellow-400/10 border-yellow-400/30',
                in_array($notification->type, ['rental_denied', 'rental_expired', 'car_post_denied', 'car_post_deleted'])
                    => 'text-red-400 bg-red-600/10 border-red-600/30',
                default
                    => 'text-blue-400 bg-blue-500/10 border-blue-500/30',
            };

            $icon = $isTimelineType
                ? 'fa-bell'
                : (in_array($notification->type, ['rental_denied', 'rental_expired', 'car_post_denied', 'car_post_deleted'])
                    ? 'fa-circle-exclamation'
                    : ($notification->type === 'car_post_approved' ? 'fa-car-side' : 'fa-bell'));
        @endphp

        <div class="bg-[#1a1a1a] border {{ $isUnread ? 'border-lime-400/40' : 'border-gray-800' }} rounded-2xl p-5 flex items-start gap-4" data-notification-row="{{ $notification->id }}">
            @if ($usesActorAvatar && $actor)
                <a href="{{ route('user.profile', $actor->id) }}" wire:navigate data-nav-navigate
                    class="w-10 h-10 rounded-full border border-white/10 bg-[#242424] flex items-center justify-center shrink-0 overflow-hidden text-white text-xs font-black uppercase">
                    @if ($actor->profile_picture)
                        <img src="{{ asset('storage/' . $actor->profile_picture) }}"
                            alt="{{ $actor->username }} profile picture"
                            class="w-full h-full object-cover">
                    @else
                        {{ $actorInitials }}
                    @endif
                </a>
            @else
                <div class="w-10 h-10 rounded-full border flex items-center justify-center shrink-0 {{ $color }}">
                    <i class="fa-solid {{ $icon }} text-sm"></i>
                </div>
            @endif

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

                <div class="flex items-center gap-2 mt-4">
                    @if ($notification->url)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" data-livewire-form data-replace-on-submit>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="open" value="1">
                            <button type="submit"
                                class="px-4 py-2 rounded-full bg-lime-400 text-black text-[10px] font-black uppercase tracking-widest hover:bg-lime-300 transition">
                                Open
                            </button>
                        </form>
                    @endif

                    @if ($isUnread)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" data-livewire-form data-preserve-scroll data-replace-on-submit>
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

                    <button type="button"
                        onclick="openDeleteNotifModal('{{ route('notifications.delete', $notification->id) }}')"
                        title="Delete notification"
                        class="ml-auto w-8 h-8 flex items-center justify-center rounded-full border border-red-500/20 text-red-400 hover:bg-red-500/10 hover:border-red-500/40 transition">
                        <i class="fa-solid fa-trash text-[11px]"></i>
                    </button>
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
