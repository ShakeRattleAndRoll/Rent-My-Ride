<x-layout>
<div class="bg-[#121212] min-h-screen text-white p-6 md:p-12" style="font-family: 'Montserrat', sans-serif;">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-white text-2xl font-bold tracking-tight">Edit Settings</h1>
                <p class="text-gray-400 text-sm mt-1">Manage your rental account</p>
            </div>
            <a href="/profile" wire:navigate class="text-gray-400 hover:text-white transition flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-arrow-left-long"></i> Back to Profile
            </a>
        </div>

        <form action="/profile/update" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Profile Section --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-[#1a1a1a] p-8 rounded-3xl border border-white/5 shadow-2xl flex flex-col items-center">
                        <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-6">Profile Photo</label>
                        
                        <div class="w-full">
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-white font-black uppercase tracking-tight">{{ auth()->user()->username }}</p>
                            <p class="text-gray-500 text-[10px] font-bold uppercase mt-1">Account Owner</p>
                        </div>
                    </div>

                    {{-- Quick Action Card --}}
                    <div class="bg-lime-400 p-6 rounded-3xl flex flex-col items-center text-center">
                        <i class="fa-solid fa-bolt-lightning text-black text-2xl mb-2"></i>
                        <p class="text-black font-black text-xs uppercase">Need Help?</p>
                        <p class="text-black/70 text-[10px] font-medium mt-1 leading-tight">Changes to email may require re-verification.</p>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    
                    {{-- Personal Info Section --}}
                    <div class="bg-[#1a1a1a] p-8 rounded-3xl border border-white/5 shadow-2xl">
                        <h3 class="text-sm font-black uppercase tracking-widest text-lime-400 mb-6 flex items-center gap-2">
                            Personal Information
                        </h3>
                        
                        <div class="space-y-5">
                            {{-- Username --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Unique Username</label>
                                <input type="text" name="username" required value="{{ old('username', auth()->user()?->username) }}"
                                    class="w-full bg-[#242424] text-white px-5 py-4 rounded-2xl border outline-none font-semibold transition-all
                                    {{ $errors->has('username') ? 'border-red-500 focus:border-red-500' : 'border-white/5 focus:border-lime-400' }}">
                                @error('username')
                                    <p class="text-red-400 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Full Name --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">First Name</label>
                                    <input type="text" name="first_name" required value="{{ old('first_name', auth()->user()?->first_name) }}"
                                        class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Middle</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name', auth()->user()?->middle_name) }}"
                                        class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Last Name</label>
                                    <input type="text" name="last_name" required value="{{ old('last_name', auth()->user()?->last_name) }}"
                                        class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Gender</label>
                                    <select name="sex" class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold appearance-none">
                                        <option value="Male" {{ old('sex', auth()->user()?->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex', auth()->user()?->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Birthdate</label>
                                    <input type="date" name="dob" required value="{{ old('dob', auth()->user()?->dob) }}"
                                        class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CONTACT & LOCATION --}}
                    <div class="bg-[#1a1a1a] p-8 rounded-3xl border border-white/5 shadow-2xl">
                        <h3 class="text-sm font-black uppercase tracking-widest text-lime-400 mb-6 flex items-center gap-2">
                            Contact & Location
                        </h3>
                        <div class="space-y-5">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Address</label>
                                <input type="text" name="address" required value="{{ old('address', auth()->user()?->address) }}"
                                    class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Contact Number</label>
                                    <input type="text" name="contact_number" required value="{{ old('contact_number', auth()->user()?->contact_number) }}"
                                        maxlength="11" class="w-full bg-[#242424] text-white p-4 rounded-2xl border border-white/5 outline-none focus:border-lime-400 font-semibold">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Email</label>
                                    <input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}"
                                        class="w-full bg-[#242424] text-white p-4 rounded-2xl border outline-none font-semibold transition-all
                                        {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : 'border-white/5 focus:border-lime-400' }}">
                                    @error('email')
                                        <p class="text-red-400 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECURITY SETTINGS --}}
                    <div x-data="{ open: false }" class="rounded-2xl border border-red-500/10 bg-[#181818]">
                        <button type="button" class="w-full flex items-center justify-between gap-4 p-6 text-left" @click="open = !open">
                            <span class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-300">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <span>
                                    <span class="block text-xs font-black uppercase tracking-widest text-red-300">Security Settings</span>
                                    <span class="mt-1 block text-xs text-gray-500">Reset your password with an email code.</span>
                                </span>
                            </span>
                            <i class="fa-solid text-gray-500" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>

                        <div x-show="open" x-transition class="border-t border-white/5 p-6">
                            <div>
                                <button
                                    type="submit"
                                    form="profile-password-code-form"
                                    class="w-full rounded-xl bg-red-500/10 px-5 py-4 text-xs font-black uppercase tracking-widest text-red-300 transition hover:bg-red-500/20"
                                >
                                    Send Reset Password Code
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- FORM BUTTONS --}}
                    <div class="flex flex-col md:flex-row gap-4 pt-4">
                        <button type="submit" class="flex-1 py-4 bg-lime-400 text-xs text-black rounded-2xl font-black uppercase tracking-widest hover:bg-lime-300 transition shadow-xl shadow-lime-400/10">
                            Save Changes
                        </button>
                        <a href="/profile" wire:navigate class="flex-1 py-4 bg-[#242424] text-xs text-white rounded-2xl font-black uppercase tracking-widest hover:bg-[#2a2a2a] transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <form id="profile-password-code-form" method="POST" action="{{ route('profile.password.send-code') }}" data-livewire-form class="hidden">
            @csrf
            @method('PATCH')
        </form>

    </div>
</div>

<script src="{{ asset('js/auth/visiblepass.js') }}"></script>

{{-- Filepond config --}}
<script>
    FilePond.registerPlugin(FilePondPluginImagePreview);
    const inputElement = document.querySelector('#profile_picture');
    const pond = FilePond.create(inputElement, {
        labelIdle: `<span class="text-[10px] uppercase font-bold text-gray-500">Drop or <span class="text-lime-400">Browse</span></span>`,
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

<style>
    .filepond--panel-root { background-color: #242424 !important; border: 2px dashed #333 !important; }
    .filepond--drop-label { color: #888 !important; }
    .filepond--file-action-button { background-color: rgba(0, 0, 0, 0.7) !important; color: white !important; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>
</x-layout>
