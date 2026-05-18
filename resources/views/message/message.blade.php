<x-layout title="Messages">
    <div class="bg-[#121212] min-h-screen text-white p-0 lg:p-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-6xl mx-auto flex flex-col gap-0 lg:gap-6">

            {{-- User search --}}
            <div
                x-data="{
                    query: '',
                    results: [],
                    open: false,
                    loading: false,
                    async search() {
                        const term = this.query.trim();

                        if (!term) {
                            this.results = [];
                            this.open = false;
                            return;
                        }

                        this.loading = true;
                        this.open = true;

                        try {
                            const response = await fetch(`/messages/search-users?q=${encodeURIComponent(term)}`, {
                                headers: { Accept: 'application/json' },
                            });

                            this.results = response.ok ? await response.json() : [];
                        } catch (error) {
                            this.results = [];
                        } finally {
                            this.loading = false;
                        }
                    },
                    goToUser(userId) {
                        this.open = false;
                        this.query = '';

                        if (window.Livewire?.navigate) {
                            window.Livewire.navigate(`/messages/${userId}`);
                            return;
                        }

                        window.location.href = `/messages/${userId}`;
                    },
                }"
                @click.outside="open = false"
                class="relative w-full px-3 py-3 lg:px-0 lg:py-0 {{ $activeContact ? 'hidden lg:block' : '' }}"
            >
                <div class="relative">
                    <input
                        x-ref="searchInput"
                        type="text"
                        x-model="query"
                        @input.debounce.300ms="search()"
                        @focus="open = query.length > 0"
                        @keydown.escape="open = false"
                        @keydown.enter.prevent="search()"
                        placeholder="Search users by name or username..."
                        class="w-full bg-[#1a1a1a] border border-gray-800 text-white text-sm rounded-2xl py-3.5 px-5 pr-16 focus:ring-2 focus:ring-lime-400 focus:border-transparent outline-none transition-all lg:py-4 lg:px-6 lg:pr-24"
                    >

                    <button
                        type="button"
                        @click="query.trim() ? search() : $refs.searchInput.focus()"
                        aria-label="Search users"
                        class="input-action-button right-3 bg-lime-400 p-2 rounded-xl text-black hover:bg-lime-300 transition-colors"
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
                        <a
                            :href="`/messages/${user.id}`"
                            wire:navigate
                            data-message-navigate
                            @click.prevent="goToUser(user.id)"
                            class="flex items-center gap-3 px-5 py-3 hover:bg-white/5 transition-colors border-b border-gray-800/60 last:border-0"
                        >
                            <div class="w-9 h-9 rounded-full bg-lime-400/10 border border-lime-400/20 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <template x-if="user.avatar">
                                    <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!user.avatar">
                                    <span class="text-lime-400 text-xs font-bold uppercase" x-text="(user.name || user.username || 'U').charAt(0)"></span>
                                </template>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-white text-sm font-semibold truncate" x-text="`@${user.username}`"></span>
                                <span class="text-gray-500 text-xs truncate" x-text="user.name || user.full_name"></span>
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

            {{-- Main chat area --}}
            <div class="px-3 pb-3 lg:px-0 lg:pb-2">
                <div class="flex {{ $activeContact ? 'h-[calc(100dvh-5.25rem)]' : 'h-[calc(100dvh-9.5rem)]' }} min-h-0 flex-row gap-0 lg:h-[85vh] lg:min-h-[620px] lg:gap-6">
                    @include('message.sidebar')
                    @include('message.chat_panel')
                </div>
            </div>

        </div>
    </div>

    {{-- Mobile chat header behavior --}}
    <style>
        @media (max-width: 1023px) {
            .message-chat-header > p {
                display: none;
            }
        }
    </style>

    {{-- Message page scripts --}}
    <script src="{{ asset('js/user-search.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
</x-layout>
