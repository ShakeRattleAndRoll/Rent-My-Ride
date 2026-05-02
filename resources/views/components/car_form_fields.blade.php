@props(['car' => null])

<div class="col-span-1 space-y-6">
    <label class="block text-sm font-semibold text-gray-300">Car Picture</label>

    <input type="file" name="car_image" id="car_image" accept="image/*"/>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Date Bought/Owned</label>
        <input type="date" name="date_owned"
               value="{{ old('date_owned', $car->date_owned ?? '') }}"
               class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
    </div>
</div>

<div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Car Brand</label>
        <input type="text" name="brand"
               value="{{ old('brand', $car->brand ?? '') }}"
               placeholder="e.g. Toyota"
               class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Car Model</label>
        <input type="text" name="model"
               value="{{ old('model', $car->model ?? '') }}"
               placeholder="e.g. Corolla"
               class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Price</label>
        <input type="number" name="price"
               value="{{ old('price', $car->price ?? '') }}"
               placeholder="₱ Enter Amount"
               class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Rent Duration</label>
        <select name="rent_unit"
                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
            <option value="Hour" {{ old('rent_unit', $car->rent_unit ?? '') == 'Hour' ? 'selected' : '' }}>Per Hour</option>
            <option value="Day"  {{ old('rent_unit', $car->rent_unit ?? '') == 'Day'  ? 'selected' : '' }}>Per Day</option>
            <option value="Week" {{ old('rent_unit', $car->rent_unit ?? '') == 'Week' ? 'selected' : '' }}>Per Week</option>
            <option value="Month" {{ old('rent_unit', $car->rent_unit ?? '') == 'Month' ? 'selected' : '' }}>Per Month</option>
        </select>
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Transmission Type</label>
        <select name="transmission" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5">
            <option value="Automatic" {{ old('transmission', $car->transmission ?? '') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
            <option value="Manual" {{ old('transmission', $car->transmission ?? '') == 'Manual' ? 'selected' : '' }}>Manual</option>
        </select>
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-sm text-gray-400 font-semibold">Fuel Type</label>
        <select name="fuel_type" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5">
            <option value="Gasoline" {{ old('fuel_type', $car->fuel_type ?? '') == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
            <option value="Diesel" {{ old('fuel_type', $car->fuel_type ?? '') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
            <option value="Electric" {{ old('fuel_type', $car->fuel_type ?? '') == 'Electric' ? 'selected' : '' }}>Electric</option>
        </select>
    </div>

    <div class="flex flex-col gap-2 md:col-span-2">
        <label class="text-sm text-gray-400 font-semibold">Description</label>
        <textarea name="description" rows="6"
                  placeholder="Enter description ..."
                  class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">{{ old('description', $car->description ?? '') }}</textarea>
    </div>
</div>