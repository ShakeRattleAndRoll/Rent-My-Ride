{{-- RENTAL MODAL --}}
<div id="rentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="font-family: 'Montserrat', sans-serif;">

    <div class="bg-[#1e1e1e] rounded-2xl w-full max-w-xl p-6 relative border border-white/10 shadow-2xl">

        {{-- Close Button --}}
        <button onclick="closeRentModal()"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10
                    w-8 h-8 flex items-center justify-center rounded-full
                    text-white hover:text-red-400 transition text-lg z-50">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Title --}}
        <h1 class="text-xl font-black uppercase tracking-tight text-white mb-1">Rental Request</h1>
        <p class="text-gray-500 text-xs mb-6">Enter how long you want to rent. The owner will set the start date upon approval.</p>

        <div class="border-t border-white/5 mb-6"></div>

        <form id="rentForm" method="POST" action="/cart/checkout/0" data-livewire-form>
            @csrf

            <input type="hidden" id="cart_id_hidden" name="cart_id">
            <input type="hidden" id="rent_unit_hidden" name="rent_unit">
            <input type="hidden" id="price_per_unit_hidden" name="price_per_unit">

            {{-- Duration --}}
            <div class="mb-5">
                <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 block">
                    Number of <span id="unitLabel">Days</span> to Rent
                </label>

                <input type="number"
                    id="days"
                    name="days"
                    min="1"
                    step="1"
                    max="30"
                    class="w-full p-3 bg-[#2a2a2a] border border-white/5 rounded-xl text-white text-sm outline-none focus:border-lime-400 transition"
                    placeholder="Enter number"
                    required>
            </div>

            {{-- LIVE DISPLAY --}}
            <div class="flex flex-wrap gap-2 mt-1 mb-5">
                <span id="displayDays"
                    class="flex items-center gap-1.5 bg-[#242424] border border-white/5 rounded-lg px-2.5 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="fa-regular fa-clock text-gray-500"></i>
                    0 Days
                </span>
            </div>

            {{-- Total Price --}}
            <div class="mb-5">
                <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 block">Total Price</label>
                <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p id="totalPrice" class="text-lime-400 font-black text-2xl leading-none">₱0</p>
                        <p class="text-gray-600 text-[10px] mt-1">
                            Auto-calculated based on <span id="unitLabelSub">duration</span> entered
                        </p>
                    </div>
                    <div class="p-3 bg-lime-400/10 rounded-xl">
                        <i class="fa-solid fa-peso-sign text-lime-400"></i>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/5 mb-5"></div>

            {{-- Note --}}
            <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 flex items-start gap-3 mb-6">
                <div class="p-2 bg-yellow-400/10 rounded-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Note</p>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Your request will be sent to the car owner for approval. The start and end dates will be automatically set when the owner accepts. Go to the owner's address to finalize the rental.
                    </p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-end">
                <button type="button"
                        onclick="closeRentModal()"
                        class="px-6 py-2.5 bg-[#2a2a2a] border border-white/5 text-gray-400 text-xs font-bold uppercase tracking-widest rounded-full hover:bg-[#333] transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-lime-400 hover:bg-lime-300 text-black text-xs font-bold uppercase tracking-widest rounded-full transition">
                    Send Request
                </button>
            </div>

        </form>
    </div>
</div>

<script src="{{ asset('js/my-cart-form.js') }}"></script>
