<x-layout>
    <div class="min-h-screen bg-[#121212] text-white" style="font-family: 'Montserrat', sans-serif;">
        <x-garage_header
            active="post_car"
            title="Post a Car"
            subtitle="List your vehicle for rent"
        />

        <div class="px-4 sm:px-8 lg:px-10 pb-12">
            <form action="/cars" method="POST" enctype="multipart/form-data"
                  class="mx-auto grid max-w-6xl grid-cols-1 gap-6 lg:grid-cols-[340px_1fr]">
                @csrf

                @if(session('feedback'))
                    <div class="lg:col-span-2 rounded-lg border border-lime-400/30 bg-lime-400/10 px-4 py-3 text-sm font-bold text-lime-300">
                        {{ session('feedback') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="lg:col-span-2 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <p class="mb-2 font-black uppercase tracking-widest text-red-300">Please check these fields</p>
                        <ul class="space-y-1 text-xs font-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="overflow-hidden rounded-2xl border border-white/5 bg-[#1e1e1e] shadow-lg">
                        <label for="car_image" class="group relative block h-48 cursor-pointer overflow-hidden bg-[#2a2a2a]">
                            <div id="car-image-preview" class="flex h-full w-full items-center justify-center text-gray-600">
                                <i class="fa-solid fa-car-side text-5xl"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/45">
                                <span class="translate-y-2 rounded-full bg-lime-400 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-black opacity-0 transition group-hover:translate-y-0 group-hover:opacity-100">
                                    Upload Photo
                                </span>
                            </div>
                        </label>
                        <input class="sr-only" type="file" name="car_image" id="car_image" accept="image/*" required>

                        <div class="p-4">
                            <div class="mb-1 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p id="preview-brand" class="truncate text-base font-bold text-white">Car Brand</p>
                                    <p id="preview-model" class="truncate text-sm text-gray-400">Model</p>
                                </div>
                                <p class="shrink-0 text-right text-sm font-bold text-white">
                                    <span id="preview-price">PHP --</span>
                                    <span class="block text-[10px] font-normal text-gray-400">/per <span id="preview-unit">Day</span></span>
                                </p>
                            </div>

                            <hr class="my-3 border-white/5">

                            <div class="space-y-1 text-sm text-gray-400">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar w-4 text-gray-500"></i>
                                    <span id="preview-date">Date owned</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-gas-pump w-4 text-gray-500"></i>
                                    <span id="preview-fuel">Gasoline</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-gear w-4 text-gray-500"></i>
                                    <span id="preview-transmission">Automatic</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <label for="car_image"
                                   class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-yellow-400 py-3 text-[10px] font-bold uppercase tracking-widest text-black transition hover:bg-yellow-300">
                                <i class="fa-solid fa-image"></i>
                                Choose Car Photo
                            </label>
                        </div>
                    </div>
                </aside>

                <section class="rounded-lg border border-white/10 bg-[#1a1a1a] shadow-2xl">
                    <div class="flex flex-col gap-4 border-b border-white/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-lime-300">Vehicle Details</p>
                            <h1 class="mt-1 text-2xl font-black uppercase tracking-tight">Add New Listing</h1>
                        </div>
                        <a href="/garage/my-listing" wire:navigate data-nav-navigate
                           class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/10 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-300 transition hover:border-white/30 hover:text-white">
                            <i class="fa-solid fa-arrow-left text-[11px]"></i>
                            Cancel
                        </a>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="brand" class="post-car-label">Brand</label>
                            <input id="brand" type="text" name="brand" value="{{ old('brand') }}" required placeholder="Toyota" class="post-car-field" data-preview-source="brand">
                            @error('brand')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="model" class="post-car-label">Model</label>
                            <input id="model" type="text" name="model" value="{{ old('model') }}" required placeholder="Corolla" class="post-car-field" data-preview-source="model">
                            @error('model')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="date_owned" class="post-car-label">Date Owned</label>
                            <input id="date_owned" type="date" name="date_owned" value="{{ old('date_owned') }}" required class="post-car-field" data-preview-source="date">
                            @error('date_owned')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-[minmax(0,1fr)_130px] gap-3">
                            <div class="flex flex-col gap-2">
                                <label for="price" class="post-car-label">Price</label>
                                <input id="price" type="number" name="price" step="1" min="1" max="999999" value="{{ old('price') }}" required placeholder="1500" oninput="if(this.value.length > 6) this.value = this.value.slice(0, 6)" class="post-car-field" data-preview-source="price">
                                @error('price')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="rent_unit" class="post-car-label">Unit</label>
                                <select id="rent_unit" name="rent_unit" required class="post-car-field" data-preview-source="unit">
                                    <option value="Hour" {{ old('rent_unit') === 'Hour' ? 'selected' : '' }}>Hour</option>
                                    <option value="Day" {{ old('rent_unit', 'Day') === 'Day' ? 'selected' : '' }}>Day</option>
                                    <option value="Week" {{ old('rent_unit') === 'Week' ? 'selected' : '' }}>Week</option>
                                    <option value="Month" {{ old('rent_unit') === 'Month' ? 'selected' : '' }}>Month</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="transmission" class="post-car-label">Transmission</label>
                            <select id="transmission" name="transmission" required class="post-car-field" data-preview-source="transmission">
                                <option value="Automatic" {{ old('transmission', 'Automatic') === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="Manual" {{ old('transmission') === 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                            @error('transmission')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="fuel_type" class="post-car-label">Fuel Type</label>
                            <select id="fuel_type" name="fuel_type" required class="post-car-field" data-preview-source="fuel">
                                <option value="Gasoline" {{ old('fuel_type', 'Gasoline') === 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                                <option value="Diesel" {{ old('fuel_type') === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="Electric" {{ old('fuel_type') === 'Electric' ? 'selected' : '' }}>Electric</option>
                            </select>
                            @error('fuel_type')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label for="description" class="post-car-label">Description</label>
                            <textarea id="description" name="description" rows="6" placeholder="Clean interior, pickup notes, availability, or house rules." class="post-car-field resize-y">{{ old('description') }}</textarea>
                            @error('description')<p class="text-xs font-semibold text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-white/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-end">
                        <a href="/garage/my-listing" wire:navigate data-nav-navigate class="inline-flex items-center justify-center rounded-lg px-5 py-3 text-[11px] font-black uppercase tracking-widest text-gray-400 transition hover:text-white">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-7 py-3 text-[11px] font-bold uppercase tracking-widest text-black transition hover:bg-yellow-300">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Post Listing
                        </button>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const fields = {
                image: document.querySelector('#car_image'),
                brand: document.querySelector('[data-preview-source="brand"]'),
                model: document.querySelector('[data-preview-source="model"]'),
                date: document.querySelector('[data-preview-source="date"]'),
                price: document.querySelector('[data-preview-source="price"]'),
                unit: document.querySelector('[data-preview-source="unit"]'),
                fuel: document.querySelector('[data-preview-source="fuel"]'),
                transmission: document.querySelector('[data-preview-source="transmission"]'),
            };

            const preview = {
                image: document.querySelector('#car-image-preview'),
                brand: document.querySelector('#preview-brand'),
                model: document.querySelector('#preview-model'),
                date: document.querySelector('#preview-date'),
                price: document.querySelector('#preview-price'),
                unit: document.querySelector('#preview-unit'),
                fuel: document.querySelector('#preview-fuel'),
                transmission: document.querySelector('#preview-transmission'),
            };

            const formatDate = (value) => {
                if (!value) return 'Date owned';
                const date = new Date(`${value}T00:00:00`);
                return Number.isNaN(date.getTime()) ? 'Date owned' : date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            };

            const refreshPreview = () => {
                preview.brand.textContent = fields.brand?.value || 'Car Brand';
                preview.model.textContent = fields.model?.value || 'Model';
                preview.date.textContent = formatDate(fields.date?.value);
                preview.price.textContent = fields.price?.value ? `PHP ${Number(fields.price.value).toLocaleString()}` : 'PHP --';
                preview.unit.textContent = fields.unit?.value || 'Day';
                preview.fuel.textContent = fields.fuel?.value || 'Gasoline';
                preview.transmission.textContent = fields.transmission?.value || 'Automatic';
            };

            Object.values(fields).forEach((field) => {
                field?.addEventListener('input', refreshPreview);
                field?.addEventListener('change', refreshPreview);
            });

            fields.image?.addEventListener('change', () => {
                const file = fields.image.files?.[0];
                if (!file || !preview.image) return;

                const reader = new FileReader();
                reader.onload = () => {
                    preview.image.innerHTML = `<img src="${reader.result}" alt="" class="h-full w-full object-cover">`;
                };
                reader.readAsDataURL(file);
            });

            refreshPreview();
        })();
    </script>

    <style>
        .post-car-label {
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
        }

        .post-car-field {
            width: 100%;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #242424;
            color: #fff;
            padding: 0.9rem 1rem;
            outline: none;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .post-car-field:focus {
            border-color: rgba(250, 204, 21, 0.75);
            box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.08);
        }

        .post-car-field::placeholder {
            color: #6b7280;
        }
    </style>
</x-layout>
