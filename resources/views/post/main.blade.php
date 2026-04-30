<x-layout>
    <div class="bg-[#121212] min-h-screen text-white p-10">
        <div class="max-w-6xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5" style="font-family: 'Montserrat', sans-serif;"> 
            
            <h1 class="text-3xl font-bold mb-2">ADD NEW LISTING</h1>
            <p class="text-gray-400 mb-10 text-sm">Submit your vehicle details for verification.</p>

            <form action="/cars" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @csrf

                {{-- Success --}}
                @if(session('feedback'))
                    <div class="col-span-full bg-green-500/20 border border-green-500 text-green-500 p-4 rounded-xl mb-4">
                        {{ session('feedback') }}
                    </div>
                @endif

                {{-- Error --}}
                @if ($errors->any())
                    <div class="col-span-full bg-red-500/20 border border-red-500 text-red-500 p-4 rounded-xl mb-4">
                        <p class="font-bold text-sm">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-xs mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-span-1 space-y-6">
                    <label class="block text-sm font-semibold text-gray-300">Car Picture</label>

                    <input type="file" name="car_image" id="car_image" accept="image/*"/>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Date Bought/Owned</label>
                        <input type="date" name="date_owned" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Car Brand</label>
                        <input type="text" name="brand" placeholder="e.g. Toyota" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Car Model</label>
                        <input type="text" name="model" placeholder="e.g. Corolla" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Price (Per Day)</label>
                        <input type="number" name="price" placeholder="₱ Enter Amount" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Rent Period</label>
                        <input type="number" name="rent_period" placeholder="e.g. Per Day" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Transmission Type</label>
                        <select name="transmission" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Fuel Type</label>
                        <select name="fuel_type" class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                            <option value="Gasoline">Gasoline</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Electric">Electric</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-sm text-gray-400 font-semibold">Description</label>
                        <textarea name="description" rows="6" placeholder="Enter description ..." class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400"></textarea>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                        <a href="/" class="px-8 py-3 font-bold text-gray-400 flex items-center">Cancel</a>
                        <button type="submit" class="px-12 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">Post</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Configuration for filepond --}}
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const inputElement = document.querySelector('input[id="car_image"]');

        const pond = FilePond.create(inputElement, {
            allowImagePreview: true,
            imagePreviewHeight: 250,
            storeAsFile: true,
            labelIdle: 'Drag & Drop your car photo or <span class="filepond--label-action">Browse</span>',
        });
    </script>

    {{-- Style for filepond --}}
    <style>
        .filepond--panel-root {
            background-color: #242424;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }
        .filepond--drop-label {
            color: #9ca3af;
        }
    </style>

</x-layout>