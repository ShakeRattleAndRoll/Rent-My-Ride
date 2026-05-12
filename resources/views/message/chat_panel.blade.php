<div class="min-h-[65vh] flex-1 bg-[#1a1a1a] rounded-3xl border border-white/5 shadow-2xl flex flex-col overflow-hidden relative lg:min-h-0">
    
    @if($activeContact)

        <div class="p-4 sm:p-6 border-b border-white/5 bg-[#1a1a1a]/80 backdrop-blur-md flex items-center justify-between gap-3">
            <a href="{{ route('user.profile', $activeContact->id) }}" wire:navigate
            class="inline-flex min-w-0 items-center gap-3 sm:gap-4 border border-transparent hover:border-white/30 rounded-xl px-3 py-2 -mx-3 transition-all duration-300">
                <div class="w-11 h-11 bg-gray-800 rounded-full border border-white/10 overflow-hidden shrink-0">
                    <img src="{{ $activeContact->profile_picture ? asset('storage/' . $activeContact->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($activeContact->username) }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h2 class="truncate text-sm font-black uppercase tracking-tighter text-white">{{ $activeContact->username }}</h2>
                    <h3 class="truncate text-xs font-semibold text-gray-400 tracking-tight">{{ $activeContact->full_name }}</h3>
                </div>
            </a>
            <p class="text-[10px] text-lime-400 font-bold uppercase tracking-widest">● Active Conversation</p>
        </div>

        <div
            id="chat-container"
            data-auth-id="{{ auth()->id() }}"
            data-thread-url="{{ route('messages.thread', $activeContact->id) }}"
            class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 flex flex-col-reverse custom-scrollbar bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')]"
        >
            @forelse($messages->reverse() as $message)
                <div data-message-id="{{ $message->id }}" class="flex flex-col {{ $message->sender_id == auth()->id() ? 'items-end ml-auto' : 'items-start' }} max-w-[86%] sm:max-w-[70%] mt-6">
                    <div class="p-3 sm:p-4 rounded-2xl shadow-md {{ $message->sender_id == auth()->id() ? 'bg-yellow-400 text-black rounded-tr-none' : 'bg-[#242424] text-gray-300 rounded-tl-none border border-white/5' }}">
                        <p class="text-sm leading-relaxed">{{ $message->body }}</p>
                    </div>
                    <span class="text-[9px] text-gray-600 font-bold mt-2 uppercase tracking-widest">
                        {{ $message->created_at->timezone('Asia/Manila')->format('g:i A') }}
                    </span>
                </div>
            @empty
                <div class="h-full flex items-center justify-center flex-col-reverse">
                    <p class="text-gray-600 text-xs uppercase font-black tracking-tighter">Start of conversation with {{ $activeContact->username }}</p>
                </div>
            @endforelse
        </div>

        <div
            id="chat-composer"
            data-blocked="{{ $chatBlocked ? '1' : '0' }}"
            data-store-url="{{ route('messages.store') }}"
            data-receiver-id="{{ $activeContact->id }}"
        >
            @if(!$chatBlocked)
            <div class="p-4 sm:p-6 bg-[#1a1a1a] border-t border-white/5" data-chat-form-wrap>
                <form id="chat-form" action="{{ route('messages.store') }}" method="POST" class="flex items-center gap-2 sm:gap-3">
                    @csrf
                    <input type="hidden" id="receiver_id" name="receiver_id" value="{{ $activeContact->id }}">
                    <input type="text" id="message_body" name="body" placeholder="Write your message..." 
                        class="min-w-0 flex-1 bg-[#242424] text-white px-4 sm:px-6 py-3.5 rounded-2xl border border-white/5 outline-none focus:border-yellow-400 transition-all font-medium text-sm">
                    <button type="submit" class="w-11 h-11 bg-yellow-400 text-black rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-400/20 hover:bg-yellow-300 transition">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            @else
            <div class="p-4 bg-black/20 text-center text-red-500 text-xs font-bold uppercase" data-chat-blocked-notice>
                <i class="fa-solid fa-ban mr-2"></i> You cannot send messages in this conversation.
            </div>
            @endif
        </div>

    @else

        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
            <div class="w-20 h-20 bg-[#242424] rounded-full flex items-center justify-center mb-4 border border-white/5">
                <i class="fa-regular fa-comments text-3xl text-yellow-400"></i>
            </div>
            <h3 class="font-black uppercase tracking-tighter text-gray-400">Your Messages</h3>
            <p class="text-xs text-gray-600 mt-2">Select a contact from the sidebar to start renting or listing.</p>
        </div>

    @endif
</div>
