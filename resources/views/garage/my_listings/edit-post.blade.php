<x-layout>

    <div class="bg-[#121212] min-h-screen text-white" style="font-family: 'Montserrat', sans-serif;">

        {{-- Header --}}
        <x-garage_header
            active="listing"
            title="Edit Listing"
            subtitle="Update your vehicle details"
        />

        <div class="px-10 pb-10">

            <div class="max-w-6xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5">

                <h1 class="text-3xl font-bold mb-2">EDIT LISTING</h1>
                <p class="text-gray-400 mb-10 text-sm">Update your vehicle details.</p>

                <form action="/garage/update/{{ $car->id }}" method="POST" enctype="multipart/form-data"
                      class="grid grid-cols-1 md:grid-cols-3 gap-10">

                    @csrf
                    @method('PATCH')

                    {{-- Reusable Fields Component --}}
                    <x-car_form_fields :car="$car" />

                    {{-- Buttons --}}
                    <div class="col-span-full flex justify-end gap-4 mt-4">

                        <a href="/garage/my-listing"
                           class="px-8 py-3 font-bold text-gray-400 flex items-center">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-12 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                            Update
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const inputElement = document.querySelector('#car_image');

        const pond = FilePond.create(inputElement, {
            storeAsFile: true,
            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            imagePreviewHeight: 250,
        });
    </script>

    {{-- FilePond Style --}}
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