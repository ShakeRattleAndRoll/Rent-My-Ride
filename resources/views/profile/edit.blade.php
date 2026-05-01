<x-layout>
    <div class="bg-[#121212] min-h-screen text-white p-10">

        <div class="max-w-4xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5">

            <form action="/profile/update" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- TOP SECTION (like profile page) --}}
                <div class="flex flex-col items-center gap-6 mb-10">

                    {{-- Username --}}
                    <p class="text-sm text-gray-400 font-semibold">
                        {{ auth()->user()?->username ?? '' }}
                    </p>

                    {{-- Profile Picture --}}
                    <div class="flex flex-col items-center gap-6 mb-10">
                        <div class="w-44">
                            <input type="file" 
                                name="profile_picture" 
                                id="profile_picture" 
                                accept="image/*">
                        </div>
                        <p class="text-xs text-gray-500 font-['Montserrat'] tracking-widest uppercase">
                            {{ auth()->user()->username }}
                        </p>
                    </div>

                </div>

                <div class="max-w-3xl mx-auto flex flex-col gap-5">

                    {{-- Username --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Change Username</label>
                        <input type="text" name="username" required
                            value="{{ old('username', auth()->user()?->username) }}"
                            class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    {{-- Full Name Section --}}
                    <div>
                        <p class="text-sm text-gray-400 font-semibold">Full Name</p>
                    </div>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">First Name</label>
                            <input type="text" name="first_name" required
                                value="{{ old('first_name', auth()->user()?->first_name) }}"
                                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Middle Name</label>
                            <input type="text" name="middle_name" required
                                value="{{ old('middle_name', auth()->user()?->middle_name) }}"
                                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Last Name</label>
                            <input type="text" name="last_name" required
                                value="{{ old('last_name', auth()->user()?->last_name) }}"
                                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                        </div>

                    </div>

                    {{-- Sex + DOB --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Sex</label>
                            <select name="sex" required
                                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                                <option value="Male" {{ old('sex', auth()->user()?->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', auth()->user()?->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Date of Birth</label>
                            <input type="date" name="dob" required
                                value="{{ old('dob', auth()->user()?->dob) }}"
                                class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Address</label>
                        <input type="text" name="address" required
                            value="{{ old('address', auth()->user()?->address) }}"
                            class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    {{-- Contact --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Contact Number</label>
                        <input type="text" name="contact_number" required
                            value="{{ old('contact_number', auth()->user()?->contact_number) }}"
                            maxlength="11"
                            pattern="09[0-9]{9}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                            class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Email</label>
                        <input type="email" name="email" required
                            value="{{ old('email', auth()->user()?->email) }}"
                            class="w-full bg-[#242424] text-white p-4 rounded-xl border border-white/5 outline-none focus:border-yellow-400">
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Change Password</label>

                        <div class="relative">
                            <input type="password" 
                                name="password"
                                id="password"
                                placeholder="Enter new password"
                                class="w-full bg-[#242424] text-white p-4 pr-12 rounded-xl border border-white/5 outline-none focus:border-yellow-400">

                            <button type="button" 
                                    onclick="togglePassword('password', 'eyeIconPassword')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                <i id="eyeIconPassword" class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Confirm Password</label>

                        <div class="relative">
                            <input type="password" 
                                name="password_confirmation"
                                id="confirm_password"
                                placeholder="Confirm new password"
                                class="w-full bg-[#242424] text-white p-4 pr-12 rounded-xl border border-white/5 outline-none focus:border-yellow-400">

                            <button type="button" 
                                    onclick="togglePassword('confirm_password', 'eyeIconConfirm')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                <i id="eyeIconConfirm" class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Buttons --}}
                <div class="flex justify-center gap-6 mt-10">
                    <a href="/profile"
                        class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script src="{{ asset('js/auth/visiblepass.js') }}"></script>

    {{-- Preload profile picture --}}
    {{-- Filepond config for profile --}}
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const inputElement = document.querySelector('#profile_picture');
        const pond = FilePond.create(inputElement, {
            labelIdle: `<span class="text-xs font-['Montserrat']">Upload Photo</span>`,
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleButtonRemoveItemPosition: 'center bottom',
            storeAsFile: true, 
        });

        @if(auth()->user()->profile_picture)
            pond.addFile("{{ asset('storage/' . auth()->user()->profile_picture) }}");
        @endif
    </script>

    {{-- Style for filepond --}}
    <style>
        .filepond--panel-root { background-color: #242424; border: 2px dashed #333; }
        .filepond--drop-label { color: #888; }
        .filepond--file-action-button { background-color: rgba(0, 0, 0, 0.5); color: white; }
    </style>

</x-layout>