<div class="{{ $activeContact ? 'hidden lg:flex' : 'flex' }} w-full min-w-0 shrink-0 rounded-2xl bg-[#1a1a1a] border-white/5 flex-col overflow-hidden shadow-2xl lg:w-80 lg:rounded-3xl lg:border">

    {{-- Signed-in user summary --}}
    <div class="p-5 sm:p-8 bg-[#242424]/50 border-b border-white/5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-lime-400 p-[2px]">
                <a href='/profile' wire:navigate>
                    <img
                        src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->username }}"
                        class="w-full h-full rounded-full object-cover border-2 border-[#1a1a1a]">
                </a>
            </div>
            <div class="flex-1 min-w-0">
                <span class="font-black uppercase text-xs tracking-tighter">{{ auth()->user()->username }}</span>
                <span class="text-xs text-gray-1000 truncate block">{{ auth()->user()->full_name }}</span>
            </div>
        </div>
    </div>

    {{-- Contact list --}}
    <div class="flex-1 overflow-y-auto px-2 py-4 space-y-1 custom-scrollbar">
        @forelse($contacts as $contact)
            @include('message.contact_item')
        @empty
            <p class="text-gray-500 text-center text-xs mt-10 uppercase tracking-widest font-bold">No contacts yet</p>
        @endforelse
    </div>

</div>
