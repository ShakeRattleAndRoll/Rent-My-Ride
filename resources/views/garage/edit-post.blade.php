<x-layout>
    <div class="bg-[#121212] min-h-screen text-white p-10">
        <div class="max-w-6xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5" style="font-family: 'Montserrat', sans-serif;"> 
            
            <h1 class="text-3xl font-bold mb-2">EDIT LISTING</h1>
            <p class="text-gray-400 mb-10 text-sm">Update your vehicle details.</p>

            <form action="/garage/update/{{ $car->id }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @csrf
                @method('PATCH')

                {{-- Car Image --}}
                <div class="col-span-1 space-y-6">
                    <label class="block text-sm font-semibold text-gray-300">Car Picture</label>

                    @if($car->car_image)
                        <img src="{{ asset('storage/' . $car->car_image) }}" class="rounded-xl mb-2">
                    @endif

                    <input type="file" name="car_image" id="car_image" accept="image/*"/>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Date Bought/Owned</label>
                        <input type="date" name="date_owned"
                               value="{{ $car->date_owned }}"
                               class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>
                </div>

                {{-- Fields --}}
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <input type="text" name="brand" value="{{ $car->brand }}"
                        class="w-full bg-[#242424] text-white p-4 rounded-xl">

                    <input type="text" name="model" value="{{ $car->model }}"
                        class="w-full bg-[#242424] text-white p-4 rounded-xl">

                    <input type="number" name="price" value="{{ $car->price }}"
                        class="w-full bg-[#242424] text-white p-4 rounded-xl">

                    <input type="number" name="rent_period" value="{{ $car->rent_period }}"
                        class="w-full bg-[#242424] text-white p-4 rounded-xl">

                    <select name="transmission" class="w-full bg-[#242424] text-white p-4 rounded-xl">
                        <option {{ $car->transmission == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option {{ $car->transmission == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>

                    <select name="fuel_type" class="w-full bg-[#242424] text-white p-4 rounded-xl">
                        <option {{ $car->fuel_type == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                        <option {{ $car->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option {{ $car->fuel_type == 'Electric' ? 'selected' : '' }}>Electric</option>
                    </select>

                    <textarea name="description" rows="6"
                        class="md:col-span-2 w-full bg-[#242424] text-white p-4 rounded-xl">{{ $car->description }}</textarea>

                    <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                        <a href="/garage/my-listing" class="px-8 py-3 font-bold text-gray-400 flex items-center">Cancel</a>
                        <button type="submit" class="px-12 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                            Update
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-layout>