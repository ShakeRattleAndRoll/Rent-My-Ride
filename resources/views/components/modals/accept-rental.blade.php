<div id="acceptModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
    <div class="bg-[#1e1e1e] rounded-2xl w-full max-w-xl p-6 relative border border-white/10 shadow-2xl">

        <button onclick="closeAcceptModal()"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10 w-8 h-8 flex items-center justify-center rounded-full text-white hover:text-red-400 transition text-lg z-50">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h1 class="text-xl font-black uppercase tracking-tight text-white mb-1">Confirm Acceptance</h1>
        <p class="text-gray-500 text-xs mb-6">Review the rental schedule before confirming.</p>

        <div class="border-t border-white/5 mb-6"></div>

        {{-- Summary --}}
        <div class="space-y-4 mb-6">

            <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 flex justify-between">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Duration</p>
                    <p id="modalDuration" class="text-white font-black text-lg"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4">
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Start</p>
                    <p id="modalStartDate" class="text-white text-sm font-bold"></p>
                </div>

                <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4">
                    <p class="text-[10px] text-gray-500 font-bold uppercase">End</p>
                    <p id="modalEndDate" class="text-white text-sm font-bold"></p>
                </div>
            </div>

            <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 flex justify-between">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Total</p>
                    <p id="modalTotalPrice" class="text-lime-400 font-black text-2xl"></p>
                </div>
            </div>

        </div>

        <form id="acceptForm" method="POST" data-livewire-form>
            @csrf
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAcceptModal()"
                    class="px-5 py-2 bg-[#2a2a2a] text-gray-400 rounded-full text-xs font-bold uppercase">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-lime-400 text-black rounded-full text-xs font-bold uppercase">
                    Confirm
                </button>
            </div>
        </form>

    </div>
</div>
