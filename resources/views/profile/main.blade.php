<x-layout>

<div class="bg-[#121212] min-h-screen text-white p-10">

    <div class="max-w-4xl mx-auto bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-white/5">

        {{-- Edit Success Message --}}
        @if (session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-3 rounded-xl mb-6 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col items-center gap-6">

            {{-- Username --}}
            <p class="text-sm text-gray-400 font-semibold">
                {{ auth()->user()?->username ?? '' }}
            </p>

            {{-- Profile Picture --}}
            <div class="w-48 h-48 rounded-full bg-[#2a2a2a] border-2 border-white/10 flex items-center justify-center overflow-hidden">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                        alt="Profile" 
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                @endif
            </div>

            {{-- Stats --}}
            <div class="flex justify-center gap-10">
                <div class="flex flex-col items-center gap-2">
                    <p class="text-xs text-gray-400">Total Cars</p>
                    <span class="text-white font-bold text-lg">
                        {{ auth()->user()->rentals()->count() }}
                    </span>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <p class="text-xs text-gray-400">Active Listings</p>
                    <span class="text-white font-bold text-lg">
                        {{ \App\Models\Car::where('user_id', auth()->id())->count() }}
                    </span>
                </div>
            </div>

        </div>

        {{-- Details --}}
        <div class="mt-10 max-w-3xl mx-auto flex flex-col gap-6">

            {{-- Full Name --}}
            <div>
                <p class="text-sm text-gray-400 font-semibold">Full Name</p>
                <p class="text-lg text-white font-semibold">
                    {{ auth()->user()?->full_name }}
                </p>
            </div>

            {{-- Sex + DOB --}}
            <div class="grid grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-400 font-semibold">Sex</p>
                    <p class="text-white">
                        {{ ucfirst(auth()->user()?->sex ?? '') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400 font-semibold">Date of Birth</p>
                    <p class="text-white">
                        {{ auth()->user()?->dob ? \Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') : '' }}
                    </p>
                </div>

            </div>

            {{-- Address --}}
            <div>
                <p class="text-sm text-gray-400 font-semibold">Address</p>
                <p class="text-white">
                    {{ auth()->user()?->address ?? '' }}
                </p>
            </div>

            {{-- Contact --}}
            <div>
                <p class="text-sm text-gray-400 font-semibold">Contact Number</p>
                <p class="text-white">
                    {{ auth()->user()?->contact_number ?? '' }}
                </p>
            </div>

            {{-- Email --}}
            <div>
                <p class="text-sm text-gray-400 font-semibold">Email</p>
                <p class="text-white">
                    {{ auth()->user()?->email ?? '' }}
                </p>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="flex justify-center gap-6 mt-10">

            <a href="/profile/edit"
                class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition text-center">
                Edit Account Info
            </a>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="px-10 py-3 bg-yellow-400 text-black rounded-full font-bold hover:bg-yellow-300 transition">
                    Log Out
                </button>
            </form>

        </div>

    </div>

</div>

</x-layout>