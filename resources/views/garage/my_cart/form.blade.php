{{-- RENTAL MODAL --}}
<div id="rentModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

    <div class="bg-[#1a1a1a] w-full max-w-md p-6 rounded-2xl border border-gray-700">

        <h1 class="text-2xl font-black mb-6 text-lime-400">Manage Rental Request</h1>

        <p class="text-gray-400 mb-4">
            Select rental schedule before sending request.
        </p>

        <form id="rentForm" method="POST">
            @csrf

            <div class="mb-4">
                <label class="text-xs text-gray-400">Days to Rent</label>
                <input type="number"
                    id="days"
                    name="days"
                    min="1"
                    step="1"
                    class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white"
                    placeholder="Enter number of days"
                    required>
            </div>

            {{-- START --}}
            <label class="text-xs text-gray-400">Start Date</label>
            <input type="datetime-local"
                id="start_date"
                name="start_date"
                readonly
                class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white opacity-80 cursor-not-allowed"
                required>

            {{-- END --}}
            <label class="text-xs text-gray-400">End Date</label>
            <input type="datetime-local"
                id="end_date"
                name="end_date"
                readonly
                class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white opacity-80 cursor-not-allowed"
                required>

            {{-- TOTAL PRICE (AUTO CALCULATED) --}}
            <label class="text-xs text-gray-400">Total Price</label>
            <div class="bg-black border border-gray-700 p-3 rounded-lg mb-5 opacity-80 cursor-not-allowed">
                <p id="totalPrice" class="text-lime-400 font-bold text-lg">
                    ₱0
                </p>
                <p class="text-gray-500 text-xs">
                    Auto-calculated based on selected dates
                </p>
            </div>

            {{-- INFO --}}
            <div class="bg-black border border-gray-700 p-3 rounded-lg mb-5 opacity-80 cursor-not-allowed">
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
    console.log("Opening modal for:", cartId);

    selectedCartId = cartId;

    const modal = document.getElementById('rentModal');
    const form = document.getElementById('rentForm');
    const startDate = document.getElementById('start_date');

    if (!modal || !form || !startDate) {
        console.error("Modal, form, or start date not found");
        return;
    }

    form.action = `/cart/checkout/${cartId}`;

    // ✅ set current date & time
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); 
    startDate.value = now.toISOString().slice(0, 16);

    modal.classList.remove('hidden');
}

    function closeRentModal() {
        document.getElementById('rentModal').classList.add('hidden');
    }

    function formatLocalDatetime(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    document.getElementById('days').addEventListener('input', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        const days = parseInt(this.value);
        const startValue = startDateInput.value;

        if (!isNaN(days) && startValue) {
            const startDate = new Date(startValue);

            const endDate = new Date(startDate.getTime() + (days * 24 * 60 * 60 * 1000));

            endDateInput.value = formatLocalDatetime(endDate);
        }
    });
</script>