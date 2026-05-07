<x-layout title="Messages">
    <div class="bg-[#121212] min-h-screen text-white p-6 md:p-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-6xl mx-auto flex flex-col gap-6">

            {{-- Search Bar --}}
            <div x-data="userSearch()" class="relative w-full">
                <div class="relative">
                    <input
                        type="text"
                        x-model="query"
                        @input.debounce.300ms="search()"
                        @focus="open = query.length > 0"
                        @click.outside="open = false"
                        placeholder="Search users by name or username..."
                        class="w-full bg-[#1a1a1a] border border-gray-800 text-white text-sm rounded-2xl py-4 px-6 pr-24 focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all"
                    >

                    <button
                        type="button"
                        @click="search()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-yellow-400 p-2 rounded-xl text-black hover:bg-yellow-300 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <div
                    x-show="open && results.length > 0"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 mt-2 w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden"
                >
                    <template x-for="user in results" :key="user.id">
                        
                            :href="`/messages/${user.id}`"
                            class="flex items-center gap-3 px-5 py-3 hover:bg-white/5 transition-colors border-b border-gray-800/60 last:border-0"
                        >
                            <div class="w-9 h-9 rounded-full bg-yellow-400/10 border border-yellow-400/20 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <template x-if="user.avatar">
                                    <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!user.avatar">
                                    <span class="text-yellow-400 text-xs font-bold uppercase" x-text="user.name.charAt(0)"></span>
                                </template>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-white text-sm font-semibold truncate" x-text="user.name"></span>
                                <span class="text-gray-500 text-xs truncate" x-text="`@${user.username}`"></span>
                            </div>
                        </a>
                    </template>
                </div>

                <div
                    x-show="open && results.length === 0 && query.length > 0 && !loading"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 mt-2 w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl px-5 py-4 text-center z-50"
                >
                    <p class="text-gray-500 text-xs">No users found for "<span x-text="query"></span>"</p>
                </div>
            </div>

            {{-- Main Chat Area --}}
            <div class="flex gap-6 h-[85vh]">
                @include('message.sidebar')
                @include('message.chat_panel')
            </div>

        </div>
    </div>

    <script src="{{ asset('js/user-search.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
</x-layout>