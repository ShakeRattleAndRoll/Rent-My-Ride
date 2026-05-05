<x-layout title="Messages">
    <div class="bg-[#121212] min-h-screen text-white p-6 md:p-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-6xl mx-auto flex gap-6 h-[85vh]">

            <div class="w-1/3 md:w-80 bg-[#1a1a1a] rounded-3xl border border-white/5 flex flex-col shadow-2xl overflow-hidden">
                <div class="p-8 bg-[#242424]/50 border-b border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-lime-400 p-[2px]">
                            <a href='/profile'>
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->username }}" 
                                 class="w-full h-full rounded-full object-cover border-2 border-[#1a1a1a]">
                            </a>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-black uppercase text-xs tracking-tighter">{{ auth()->user()->username }}</span>
                            <span class="text-xs text-gray-1000 truncate block">{{ auth()->user()->full_name }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-2 py-4 space-y-1 custom-scrollbar">
                    @forelse($contacts as $contact)

                        <div x-data="{ menuOpen: false }" class="relative group">
                            <a href="{{ route('messages.index', $contact->id) }}" wire:navigate
                            class="flex items-center gap-3 p-4 rounded-2xl transition {{ isset($activeContact) && $activeContact->id == $contact->id ? 'bg-yellow-400 text-black' : 'hover:bg-[#242424]' }}">
                                
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex-shrink-0 overflow-hidden border border-white/10">
                                    <img src="{{ $contact->profile_picture ? asset('storage/' . $contact->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->username) . '&background=random' }}" 
                                        class="w-full h-full object-cover">
                                    @if($contact->unread_count > 0 && !$contact->is_muted)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 border-2 border-[#1a1a1a] rounded-full text-white text-[10px] flex items-center justify-center font-black animate-pulse">
                                            {{ $contact->unread_count }}
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
                                
                                <a href="{{ route('user.profile', $contact->id) }}" class="flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold text-gray-300 hover:bg-yellow-400 hover:text-black transition">
                                    <i class="fa-solid fa-user w-4"></i> View Profile
                                </a>
                                
                                <form action="{{ route('messages.mute', $contact->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold transition text-left border-t border-white/5 text-gray-300 hover:bg-yellow-400 hover:text-black">
                                        <i class="fa-solid {{ $contact->is_muted ? 'fa-bell text-yellow-500' : 'fa-bell-slash' }} w-4"></i> 
                                        {{ $contact->is_muted ? 'Unmute User' : 'Mute User' }}
                                    </button>
                                </form>

                                <form action="{{ route('messages.block', $contact->id) }}" method="POST" 
                                    onsubmit="return confirm('{{ $contact->is_blocked_by_me ? 'Unblock this user?' : 'Block this user? You will no longer receive their messages.' }}')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-2 px-4 py-3 text-[10px] uppercase font-bold transition text-left border-t border-white/5 text-gray-300 hover:bg-yellow-400 hover:text-black">
                                        <i class="fa-solid {{ $contact->is_blocked_by_me ? 'fa-circle-check text-green-500' : 'fa-ban text-red-500' }} w-4"></i> 
                                        {{ $contact->is_blocked_by_me ? 'Unblock User' : 'Block User' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center text-xs mt-10 uppercase tracking-widest font-bold">No contacts yet</p>
                    @endforelse
                </div>
            </div>

            <div class="flex-1 bg-[#1a1a1a] rounded-3xl border border-white/5 shadow-2xl flex flex-col overflow-hidden relative">
                
                @if($activeContact)
                    <div class="p-6 border-b border-white/5 bg-[#1a1a1a]/80 backdrop-blur-md flex items-center justify-between">
                        <a href="{{ route('user.profile', $activeContact->id) }}"
                        class="inline-flex items-center gap-4
                                border border-transparent hover:border-white/30
                                rounded-xl px-3 py-2 -mx-3
                                transition-all duration-300">

                            <div class="w-11 h-11 bg-gray-800 rounded-full border border-white/10 overflow-hidden shrink-0">
                                <img src="{{ $activeContact->profile_picture
                                    ? asset('storage/' . $activeContact->profile_picture)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($activeContact->username) }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <div>
                                <h2 class="text-sm font-black uppercase tracking-tighter text-white">{{ $activeContact->username }}</h2>
                                <h3 class="text-xs font-semibold text-gray-400 tracking-tight">{{ $activeContact->full_name }}</h3>
                            </div>

                        </a>
                        <p class="text-[10px] text-lime-400 font-bold uppercase tracking-widest">● Active Conversation</p>
                    </div>

                    <div id="chat-container" class="flex-1 overflow-y-auto p-8 flex flex-col-reverse custom-scrollbar bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')]">
    
                        @forelse($messages->reverse() as $message)
                            <div class="flex flex-col {{ $message->sender_id == auth()->id() ? 'items-end ml-auto' : 'items-start' }} max-w-[70%] mt-6">
                                <div class="p-4 rounded-2xl shadow-md {{ $message->sender_id == auth()->id() ? 'bg-yellow-400 text-black rounded-tr-none' : 'bg-[#242424] text-gray-300 rounded-tl-none border border-white/5' }}">
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

                    @if(!$chatBlocked)
                        <div class="p-6 bg-[#1a1a1a] border-t border-white/5">
                            <form id="chat-form" action="{{ route('messages.store') }}" method="POST" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" id="receiver_id" name="receiver_id" value="{{ $activeContact->id }}">
                                
                                <input type="text" id="message_body" name="body" placeholder="Write your message..." 
                                    class="flex-1 bg-[#242424] text-white px-6 py-3.5 rounded-2xl border border-white/5 outline-none focus:border-yellow-400 transition-all font-medium text-sm">
                                
                                <button type="submit" class="w-11 h-11 bg-yellow-400 text-black rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-400/20 hover:bg-yellow-300 transition">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-black/20 text-center text-red-500 text-xs font-bold uppercase">
                            <i class="fa-solid fa-ban mr-2"></i> You cannot send messages in this conversation.
                        </div>
                    @endif

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
        </div>
    </div>

    <script src="{{ asset('js/messages.js') }}"></script>

</x-layout>