<x-layout>
    <div class="bg-[#121212] min-h-screen text-white" style="font-family: 'Montserrat', sans-serif;">

        {{-- Header --}}
        <x-garage_header
            active="post_car"
            title="Post a Car"
            subtitle="List your vehicle for rent"
        />
        
        <div class="px-10 pb-10">

            <div class="max-w-6xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5">
                
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

                    {{-- Reusable Form Fields --}}
                    <x-car_form_fields />

                    {{-- Buttons (kept outside component) --}}
                    <div class="col-span-full flex justify-end gap-4 mt-4">
                        <a href="/" class="px-8 py-3 font-bold text-gray-400 flex items-center">Cancel</a>
                        <button type="submit" class="px-12 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                            Post
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- FilePond --}}
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const inputElement = document.querySelector('#car_image');

        if (inputElement) {
            FilePond.create(inputElement, {
                allowImagePreview: true,
                imagePreviewHeight: 250,
                storeAsFile: true,
                labelIdle: 'Drag & Drop your car photo or <span class="filepond--label-action">Browse</span>',
            });
        }
    </script>

    {{-- FilePond Styles --}}
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