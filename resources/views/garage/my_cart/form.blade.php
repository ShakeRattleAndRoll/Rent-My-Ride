{{-- RENTAL MODAL --}}
<div id="rentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="font-family: 'Montserrat', sans-serif;">

    <div class="bg-[#1e1e1e] rounded-2xl w-full max-w-xl relative border border-white/10 shadow-2xl flex flex-col max-h-[90vh]">

        {{-- Close Button --}}
        <button onclick="closeRentModal()"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10
                    w-8 h-8 flex items-center justify-center rounded-full
                    text-white hover:text-red-400 transition text-lg z-50">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Fixed Header --}}
        <div class="px-6 pt-6 pb-4 shrink-0">
            <h1 class="text-xl font-black uppercase tracking-tight text-white mb-1">Rental Request</h1>
            <p class="text-gray-500 text-xs">Choose your start date and duration. The end date will be calculated automatically.</p>
            <div class="border-t border-white/5 mt-4"></div>
        </div>

        {{-- Scrollable Form Body --}}
        <form id="rentForm" method="POST" action="/cart/checkout/0" data-livewire-form class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <input type="hidden" id="cart_id_hidden"        name="cart_id">
            <input type="hidden" id="rent_unit_hidden"      name="rent_unit">
            <input type="hidden" id="price_per_unit_hidden" name="price_per_unit">
            <input type="hidden" id="start_date_hidden"     name="start_date">
            <input type="hidden" id="end_date_hidden"       name="end_date">

            <div class="overflow-y-auto flex-1 px-6 py-4 space-y-5">

                {{-- Start Date & Time --}}
                <div>
                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 block">
                        Start Date &amp; Time
                    </label>
                    <input type="datetime-local"
                        id="startDate"
                        name="start_date_display"
                        class="w-full p-3 bg-[#2a2a2a] border border-white/5 rounded-xl text-white text-sm outline-none focus:border-lime-400 transition [color-scheme:dark]"
                        required>
                </div>

                {{-- Duration --}}
                <div>
                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 block">
                        Number of <span id="unitLabel">Days</span> to Rent
                    </label>
                    <input type="number"
                        id="days"
                        name="days"
                        min="1"
                        step="1"
                        class="w-full p-3 bg-[#2a2a2a] border border-white/5 rounded-xl text-white text-sm outline-none focus:border-lime-400 transition"
                        placeholder="Enter number"
                        required>
                </div>

                {{-- End Date & Time --}}
                <div>
                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 block">
                        End Date &amp; Time
                    </label>
                    <div class="w-full p-3 bg-[#242424] border border-white/5 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-regular fa-calendar-check text-lime-400 text-sm"></i>
                            <p id="displayEnd" class="text-white text-sm font-bold">—</p>
                        </div>
                        <span id="displayDays"
                            class="flex items-center gap-1.5 bg-[#1e1e1e] border border-white/5 rounded-lg px-2.5 py-1 text-[10px] font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">
                            <i class="fa-regular fa-clock text-gray-600"></i>
                            0 Days
                        </span>
                    </div>
                </div>

                {{-- Total Price --}}
                <div>
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

                <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 flex items-start gap-3">
                    <div class="p-2 bg-lime-400/10 rounded-lg shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-lime-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Note</p>
                        <p class="text-gray-400 text-xs leading-relaxed">
                            Your request will be sent to the car owner for approval. The end date is auto-calculated from your chosen start date and duration. Go to the owner's address to finalize the rental.
                        </p>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-white/5 shrink-0 flex gap-3 justify-end">
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