@props(['formId' => 'edit-form'])

{{-- Confirm Update Modal --}}
<div id="confirm-update-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">

        {{-- Icon --}}
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-yellow-400/10 border border-yellow-400/30 flex items-center justify-center mb-4">
                <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-xl"></i>
            </div>
            <h2 class="text-white font-bold text-lg">Confirm Update</h2>
        </div>

        {{-- Message --}}
        <p class="text-gray-400 text-sm mb-8 leading-relaxed text-center">
            Are you sure you want to update this listing?
            <span class="text-yellow-400 font-semibold">Pending pre-orders will only be denied if you changed the price or rental unit.</span>
        </p>

        {{-- Buttons --}}
        <div class="flex justify-center gap-3">
            <button type="button"
                    onclick="document.getElementById('confirm-update-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-full border border-white/10 text-gray-400 hover:text-white hover:border-white/30 text-sm font-bold transition">
                Cancel
            </button>
            <button type="button"
                    onclick="document.getElementById('{{ $formId }}').submit()"
                    class="px-6 py-2.5 rounded-full bg-lime-400 text-black font-bold text-sm hover:bg-lime-300 transition">
                Yes, Update
            </button>
        </div>

    </div>
</div>