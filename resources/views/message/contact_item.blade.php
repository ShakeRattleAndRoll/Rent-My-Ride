<div x-data="{ menuOpen: false }" class="relative group">
    <a href="{{ route('messages.index', $contact->id) }}" wire:navigate data-message-navigate
    class="flex items-center gap-3 p-4 rounded-2xl transition {{ isset($activeContact) && $activeContact->id == $contact->id ? 'bg-yellow-400 text-black' : 'hover:bg-[#242424]' }}">
        
        {{-- Avatar wrapper needs position:relative for the badge --}}
        <div class="relative w-10 h-10 flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-gray-700 overflow-hidden border border-white/10">
                <img src="{{ $contact->profile_picture ? asset('storage/' . $contact->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->username) . '&background=random' }}" 
                    class="w-full h-full object-cover">
            </div>

            {{-- Badge is now OUTSIDE the img container, correctly anchored --}}
            @if(!$contact->is_muted)
                <span
                    data-contact-unread-badge="{{ $contact->id }}"
                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 border-2 border-[#1a1a1a] rounded-full text-white text-[10px] {{ $contact->unread_count > 0 ? 'flex' : 'hidden' }} items-center justify-center font-black animate-pulse"
                >
                    {{ $contact->unread_count > 99 ? '99+' : $contact->unread_count }}
                </span>
            @endif
        </div>

        <div class="flex-1 min-w-0 pr-6"> 
            <div class="flex items-center gap-2">
                <span class="font-bold text-xs uppercase truncate block">{{ $contact->username }}</span>
                @if($contact->is_muted)
                    <i class="fa-solid fa-bell-slash text-[9px] text-gray-600"></i>
                @endif
            </div>
            <span class="text-xs {{ isset($activeContact) && $activeContact->id == $contact->id ? 'text-black/70' : 'text-gray-500' }} truncate block">{{ $contact->full_name }}</span>
        </div>
    </a>
    
    <button 
        @click.stop.prevent="menuOpen = !menuOpen"
        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-full hover:bg-white/10 transition {{ isset($activeContact) && $activeContact->id == $contact->id ? 'text-black hover:bg-black/10' : 'text-gray-500' }}">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <div 
        x-show="menuOpen" 
        @click.away="menuOpen = false"
        x-transition
        x-cloak
        class="absolute right-2 top-12 w-40 bg-[#242424] border border-white/10 rounded-xl shadow-2xl z-[100] overflow-hidden">
        
        <a href="{{ route('user.profile', $contact->id) }}" wire:navigate data-nav-navigate class="flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold text-gray-300 hover:bg-yellow-400 hover:text-black transition">
            <i class="fa-solid fa-user w-4"></i> View Profile
        </a>
        
        <form action="{{ route('messages.mute', $contact->id) }}" method="POST" data-livewire-form>
            @csrf
            <button type="submit" 
                    class="w-full flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold transition text-left border-t border-white/5 text-gray-300 hover:bg-yellow-400 hover:text-black">
                <i class="fa-solid {{ $contact->is_muted ? 'fa-bell text-yellow-500' : 'fa-bell-slash' }} w-4"></i> 
                {{ $contact->is_muted ? 'Unmute User' : 'Mute User' }}
            </button>
        </form>

        <form action="{{ route('messages.block', $contact->id) }}" method="POST"
            data-livewire-form
            data-confirm="{{ $contact->is_blocked_by_me ? 'Unblock this user?' : 'Block this user? You will no longer receive their messages.' }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold transition text-left border-t border-white/5 text-gray-300 hover:bg-yellow-400 hover:text-black">
                <i class="fa-solid {{ $contact->is_blocked_by_me ? 'fa-circle-check text-green-500' : 'fa-ban text-red-500' }} w-4"></i> 
                {{ $contact->is_blocked_by_me ? 'Unblock User' : 'Block User' }}
            </button>
        </form>
    </div>
</div>
