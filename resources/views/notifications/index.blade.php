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
                <button type="button"
                    onclick="openDeleteNotifModal('{{ route('notifications.delete-all') }}', true)"
                    class="px-5 py-2 rounded-full bg-[#1a1a1a] border border-red-500/20 text-red-400 hover:bg-red-500/10 hover:border-red-500/40 text-[10px] font-black uppercase tracking-widest transition">
                    Delete All
                </button>

                {{-- Mark All Read --}}
                <form action="{{ route('notifications.read-all') }}" method="POST" data-livewire-form data-replace-on-submit>
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-5 py-2 rounded-full bg-[#1a1a1a] border border-white/10 text-gray-300 hover:text-white hover:border-white/30 text-[10px] font-black uppercase tracking-widest transition">
                        Mark All Read
                    </button>
                </form>
            </div>

            <div data-notifications-list data-refresh-url="{{ route('notifications.items') }}">
                @include('notifications.partials.list', ['items' => $items])
            </div>
        </div>
    </div>

    <x-modals.delete_notification />
    <script src="{{ asset('js/notifications-live.js') }}"></script>
</x-layout>
