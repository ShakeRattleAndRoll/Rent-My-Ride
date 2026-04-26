<x-layout>
    <div class="bg-[#121212] min-h-screen text-white p-10">
        <div class="max-w-4xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- Left Column --}}
                <div class="col-span-1 flex flex-col items-center gap-6">

                    {{-- Username --}}
                    <p class="text-sm text-gray-400 font-semibold">{{ auth()->user()?->username ?? '' }}</p>

                    {{-- Profile Picture --}}
                    <div class="w-48 h-48 rounded-full bg-[#2a2a2a] border-2 border-white/10 flex items-center justify-center overflow-hidden">
                        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Stats --}}
                    <div class="w-full bg-[#242424] rounded-2xl p-6 border border-white/5">
                        <p class="text-center text-sm font-semibold text-gray-300 mb-4">Stats</p>
                        <div class="flex justify-around">
                            <div class="flex flex-col items-center gap-2">
                                <p class="text-xs text-gray-400">Total Rentals</p>
                                <div class="w-12 h-12 rounded-full bg-[#1a1a1a] flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">0</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <p class="text-xs text-gray-400">Total Rented</p>
                                <div class="w-12 h-12 rounded-full bg-[#1a1a1a] flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="col-span-1 md:col-span-2 flex flex-col gap-5">

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">First Name</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->first_name ?? '' }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Middle Name</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->middle_name ?? '' }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Last Name</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->last_name ?? '' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Sex</label>
                            <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                                {{ ucfirst(auth()->user()?->sex ?? '') }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm text-gray-400 font-semibold">Date of Birth</label>
                            <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                                {{ auth()->user()?->dob ? \Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') : '' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Address</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->address ?? '' }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Contact Number</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->contact_number ?? '' }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-gray-400 font-semibold">Email</label>
                        <div class="w-full bg-[#242424] text-gray-500 p-4 rounded-xl border border-white/5">
                            {{ auth()->user()?->email ?? '' }}
                        </div>
                    </div>

                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex justify-center gap-6 mt-10">
                <a href="/profile/edit" class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                    Edit Account Info
                </a>
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layout>