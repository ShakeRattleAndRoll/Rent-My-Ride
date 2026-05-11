<x-layout>
<div class="bg-[#121212] min-h-screen text-white p-6 md:p-12" style="font-family: 'Montserrat', sans-serif;">

    <div class="max-w-5xl mx-auto flex flex-col md:flex-row gap-8">
        
        <div class="w-full md:w-1/3 flex flex-col gap-6">
            <div class="bg-[#1a1a1a] p-8 rounded-3xl border border-white/5 shadow-2xl flex flex-col items-center text-center">
                
                {{-- Profile Picture --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-white to-white rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <div class="relative w-40 h-40 rounded-full bg-[#2a2a2a] border-4 border-[#1a1a1a] overflow-hidden">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                 alt="Profile" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                <span class="text-4xl font-black text-gray-600">{{ substr(auth()->user()->username, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <h2 class="mt-6 text-2xl font-black text-white tracking-tight uppercase">
                    {{ auth()->user()->username }}
                </h2>
                <p class="text-yellow-400 text-xs font-bold uppercase tracking-widest mt-1">Verified Member</p>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">
                    Member since {{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('F d, Y') }}
                </p>

                <hr class="w-full border-white/5 my-6">

                {{-- Stats --}}
                <div class="grid grid-cols-2 w-full gap-4">
                    <div class="bg-[#242424] p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Total Cars</p>
                        <span class="text-xl font-black text-white">{{ auth()->user()->rentals()->count() }}</span>
                    </div>
                    <div class="bg-[#242424] p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Listings</p>
                        <span class="text-xl font-black text-white">{{ \App\Models\Car::where('user_id', auth()->id())->count() }}</span>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form action="/logout" method="POST" class="w-full mt-6">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-xl font-bold text-xs transition uppercase tracking-widest">
                        Log Out Account
                    </button>
                </form>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="flex-1">
            <div class="bg-[#1a1a1a] p-8 md:p-10 rounded-3xl border border-white/5 shadow-2xl h-full">

                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xl font-black uppercase tracking-tighter">Account Information</h3>
                    <a href="/profile/edit" wire:navigate class="px-6 py-2 bg-yellow-400 text-black rounded-full font-bold text-xs hover:bg-yellow-300 transition shadow-lg shadow-yellow-400/20">
                        Edit Profile
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    
                    {{-- Full Name --}}
                    <div class="group">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Full Name</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2">{{ auth()->user()?->full_name }}</p>
                    </div>

                    {{-- Email --}}
                    <div class="group min-w-0">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Email Address</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2 break-all leading-snug">{{ auth()->user()?->email }}</p>
                    </div>

                    {{-- Sex --}}
                    <div class="group">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Gender</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2">{{ ucfirst(auth()->user()?->sex ?? 'N/A') }}</p>
                    </div>

                    {{-- DOB --}}
                    <div class="group">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Birthdate</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2">
                            {{ auth()->user()?->dob ? \Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') : 'Not Set' }}
                        </p>
                    </div>

                    {{-- Contact --}}
                    <div class="group">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Phone Number</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2">{{ auth()->user()?->contact_number ?? 'No contact added' }}</p>
                    </div>

                    {{-- Address --}}
                    <div class="group md:col-span-2">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 group-hover:text-yellow-400 transition">Current Address</p>
                        <p class="text-white font-semibold text-lg border-b border-white/5 pb-2">{{ auth()->user()?->address ?? 'No address listed' }}</p>
                    </div>

                </div>

                {{-- Security Note --}}
                <div class="mt-12 p-6 bg-[#242424] rounded-2xl border border-white/5 flex items-start gap-4">
                    <div class="p-3 bg-yellow-400/10 rounded-xl text-yellow-400">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white uppercase tracking-wide">Data Protection</p>
                        <p class="text-xs text-gray-500 mt-1">Your personal information is only shared with verified car owners during a rental transaction.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="mt-6 rounded-2xl border border-red-500/10 bg-[#181818]">
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
                        <form method="POST" action="{{ route('profile.password.send-code') }}">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-red-500/10 px-5 py-4 text-xs font-black uppercase tracking-widest text-red-300 transition hover:bg-red-500/20"
                            >
                                Send Reset Password Code
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</x-layout>
