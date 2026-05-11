{{-- Delete Notification Modal --}}
<div id="delete-notification-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="font-family: 'Montserrat', sans-serif;">

    {{-- Backdrop --}}
    <div onclick="closeDeleteNotifModal()"
         class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div class="relative bg-[#1a1a1a] border border-gray-800 rounded-3xl p-8 w-full max-w-sm shadow-2xl">

        {{-- Icon --}}
        <div class="w-14 h-14 rounded-full bg-red-600/10 border border-red-600/30 flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-trash text-red-400 text-lg"></i>
        </div>

        {{-- Text --}}
        <h2 class="text-white text-center text-base font-black uppercase tracking-tight mb-1">Delete Notification</h2>
        <p class="text-gray-500 text-center text-xs leading-relaxed mb-7">
            This notification will be permanently removed and cannot be recovered.
        </p>

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button onclick="closeDeleteNotifModal()"
                class="flex-1 py-2.5 rounded-full border border-gray-700 text-gray-400 hover:text-white hover:border-gray-500 text-[10px] font-black uppercase tracking-widest transition-all">
                Cancel
            </button>
            <form id="delete-notification-form" method="POST" data-livewire-form class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full py-2.5 rounded-full bg-red-600 hover:bg-red-500 text-white text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteNotifModal(actionUrl) {
        const form = document.getElementById('delete-notification-form');
        form.action = actionUrl;
        const modal = document.getElementById('delete-notification-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteNotifModal() {
        const modal = document.getElementById('delete-notification-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDeleteNotifModal();
    });
</script>