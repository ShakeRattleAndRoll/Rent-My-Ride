<x-layout title="Messages">
    <div class="bg-[#121212] min-h-screen text-white p-6 md:p-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-6xl mx-auto flex gap-6 h-[85vh]">

            <div class="w-1/3 md:w-80 bg-[#1a1a1a] rounded-3xl border border-white/5 flex flex-col shadow-2xl overflow-hidden">
                <div class="p-6 bg-[#242424]/50 border-b border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-lime-400 p-[2px]">
                            <a href='/profile'>
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->username }}" 
                                 class="w-full h-full rounded-full object-cover border-2 border-[#1a1a1a]">
                            </a>
                        </div>
                        <span class="font-black uppercase text-xs tracking-tighter">{{ auth()->user()->username }}</span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-2 py-4 space-y-1 custom-scrollbar">
                    @forelse($contacts as $contact)
                        <a href="{{ route('messages.index', $contact->id) }}" wire:navigate
                           class="flex items-center gap-3 p-4 rounded-2xl transition group {{ isset($activeContact) && $activeContact->id == $contact->id ? 'bg-yellow-400 text-black' : 'hover:bg-[#242424]' }}">
                            <div class="w-10 h-10 rounded-full bg-gray-700 flex-shrink-0 overflow-hidden border border-white/10">
                            <img src="{{ $contact->profile_picture ? asset('storage/' . $contact->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->username) . '&background=random' }}" 
                                class="w-full h-full object-cover">
                        </div> 
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-xs uppercase truncate block">{{ $contact->username }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-center text-xs mt-10 uppercase tracking-widest font-bold">No contacts yet</p>
                    @endforelse
                </div>
            </div>

            <div class="flex-1 bg-[#1a1a1a] rounded-3xl border border-white/5 shadow-2xl flex flex-col overflow-hidden relative">
                
                @if($activeContact)
                    <div class="p-6 border-b border-white/5 bg-[#1a1a1a]/80 backdrop-blur-md flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 bg-gray-800 rounded-full border border-white/10 overflow-hidden">
                                <img src="{{ $activeContact->profile_picture ? asset('storage/' . $activeContact->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($activeContact->username) }}" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h2 class="text-sm font-black uppercase tracking-tighter text-white">{{ $activeContact->username }}</h2>
                                <p class="text-[10px] text-lime-400 font-bold uppercase tracking-widest">● Active Conversation</p>  
                            </div>
                        </div>
                    </div>

                    <div id="chat-container" class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')]">
                        @forelse($messages as $message)
                            <div class="flex flex-col {{ $message->sender_id == auth()->id() ? 'items-end ml-auto' : 'items-start' }} max-w-[70%]">
                                <div class="p-4 rounded-2xl shadow-md {{ $message->sender_id == auth()->id() ? 'bg-yellow-400 text-black rounded-tr-none' : 'bg-[#242424] text-gray-300 rounded-tl-none border border-white/5' }}">
                                    <p class="text-sm leading-relaxed">{{ $message->body }}</p>
                                </div>
                                <span class="text-[9px] text-gray-600 font-bold mt-2 uppercase tracking-widest">
                                    {{ $message->created_at->format('g:i A') }}
                                </span>
                            </div>
                        @empty
                            <div class="h-full flex items-center justify-center">
                                <p class="text-gray-600 text-xs uppercase font-black tracking-tighter">Start of conversation with {{ $activeContact->username }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-6 bg-[#1a1a1a] border-t border-white/5">
                        <form id="chat-form" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" id="receiver_id" value="{{ $activeContact->id }}">
                            
                            <input type="text" id="message_body" required placeholder="Write your message..." 
                                class="flex-1 bg-[#242424] text-white px-6 py-3.5 rounded-2xl border border-white/5 outline-none focus:border-yellow-400 transition-all font-medium text-sm">
                            
                            <button type="submit" class="w-11 h-11 bg-yellow-400 text-black rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-400/20 hover:bg-yellow-300 transition">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
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
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chat-form');
        const chatContainer = document.getElementById("chat-container");

        chatContainer.scrollTop = chatContainer.scrollHeight;

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const body = document.getElementById('message_body').value;
            const receiverId = document.getElementById('receiver_id').value;

            const newMessage = `
                <div class="flex flex-col items-end ml-auto max-w-[70%]">
                    <div class="p-4 rounded-2xl shadow-md bg-yellow-400 text-black rounded-tr-none">
                        <p class="text-sm leading-relaxed">${body}</p>
                    </div>
                    <span class="text-[9px] text-gray-600 font-bold mt-2 uppercase tracking-widest">Just now</span>
                </div>
            `;
            
            chatContainer.insertAdjacentHTML('beforeend', newMessage);
            chatContainer.scrollTop = chatContainer.scrollHeight; 
            document.getElementById('message_body').value = ''; 

            fetch("{{ route('messages.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    body: body
                })
            });
        });
    </script>
    
</x-layout>