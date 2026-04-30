<x-layout>
<div class="bg-[#121212] min-h-screen text-white font-['Montserrat'] p-10">

    <div class="max-w-2xl mx-auto bg-[#1a1a1a] p-8 rounded-2xl border border-gray-800">

        <h1 class="text-2xl font-black mb-6">Manage Rental Request</h1>

        <p class="text-gray-400 mb-6">
            User: <span class="text-white font-bold">{{ $order->user->full_name }}</span>
        </p>

        <p class="text-gray-400 mb-6">
            Car: <span class="text-white font-bold">{{ $order->car->brand }} {{ $order->car->model }}</span>
        </p>

        {{-- DATE & TIME FORM --}}
        <form method="POST" action="/rental/{{ $order->id }}/accept" class="space-y-4">
        @csrf

        {{-- BORROW DATE & TIME --}}
        <div>
            <label class="text-xs text-gray-400">Date & Time Borrowed</label>
            <input type="datetime-local"
                name="start_date"
                class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white"
                required>
        </div>

        {{-- RETURN DATE & TIME --}}
        <div>
            <label class="text-xs text-gray-400">Date & Time Return</label>
            <input type="datetime-local"
                name="end_date"
                class="w-full mt-1 p-3 bg-black border border-gray-700 rounded-lg text-white"
                required>
        </div>

        {{-- AUTO TOTAL PRICE (DISPLAY ONLY) --}}
        <div class="bg-black border border-gray-700 p-3 rounded-lg">
            <p class="text-xs text-gray-400">Total Price (Auto-calculated)</p>
            <p class="text-white font-bold text-lg">
                ₱{{ number_format($order->car->price, 0) }} / day × days (calculated on submit)
            </p>
        </div>

        <div class="flex gap-3">

            <button type="submit"
                    class="bg-lime-500 hover:bg-lime-400 text-black px-6 py-2 rounded-full font-bold">
                Accept Request
            </button>

            <a href="/car/pre-order/{{ $order->car_id }}"
            class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-full">
                Cancel
            </a>

        </div>
    </form>

    </div>
</div>
</x-layout>