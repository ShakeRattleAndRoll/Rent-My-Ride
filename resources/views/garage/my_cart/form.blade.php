{{-- RENTAL MODAL --}}
<div id="rentModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

    <div class="bg-[#1a1a1a] w-full max-w-md p-6 rounded-2xl border border-gray-700">

        <h1 class="text-2xl font-black mb-6">Manage Rental Request</h1>

        <p class="text-gray-400 mb-4">
            Select rental schedule before sending request.
        </p>

        <form id="rentForm" method="POST">
            @csrf

            {{-- START --}}
            <div class="mb-4">
                <label class="text-xs text-gray-400">Start Date</label>
                <input type="datetime-local"
                       id="start_date"
                       name="start_date"
                       class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white"
                       required>
            </div>

            {{-- END --}}
            <div class="mb-4">
                <label class="text-xs text-gray-400">End Date</label>
                <input type="datetime-local"
                       id="end_date"
                       name="end_date"
                       class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white"
                       required>
            </div>

            {{-- TOTAL PRICE (AUTO CALCULATED) --}}
            <div class="bg-black border border-gray-700 p-3 rounded-lg mb-5">
                <p class="text-xs text-gray-400">Total Price</p>
                <p id="totalPrice" class="text-lime-400 font-bold text-lg">
                    ₱0
                </p>
                <p class="text-gray-500 text-xs">
                    Auto-calculated based on selected dates
                </p>
            </div>

            {{-- INFO --}}
            <div class="bg-black border border-gray-700 p-3 rounded-lg mb-5">
                <p class="text-xs text-gray-400">Note</p>
                <p class="text-white text-sm">
                    Your request will be sent to the car owner for approval.
                </p>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3 justify-end">

                <button type="button"
                        onclick="closeRentModal()"
                        class="px-5 py-2 bg-gray-700 rounded-full">
                    Cancel
                </button>

                <button type="submit"
                        class="px-5 py-2 bg-lime-500 text-black rounded-full font-bold">
                    Send
                </button>

            </div>
        </form>

    </div>
</div>

<script>
    let selectedCartId = null;

    function openRentModal(cartId) {
        console.log("Opening modal for:", cartId); // DEBUG

        selectedCartId = cartId;

        const modal = document.getElementById('rentModal');
        const form = document.getElementById('rentForm');

        if (!modal || !form) {
            console.error("Modal or form not found");
            return;
        }

        form.action = `/cart/checkout/${cartId}`;

        modal.classList.remove('hidden');
    }

    function closeRentModal() {
        document.getElementById('rentModal').classList.add('hidden');
    }
</script>