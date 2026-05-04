<x-layout>
    <div class="bg-[#121212] min-h-screen py-12 px-6">
        <div class="max-w-6xl mx-auto">
            
        <x-back_button/>

            {{-- Profile --}}
            <div class="bg-[#1e1e1e] rounded-3xl p-8 border border-white/10 mb-10 shadow-2xl">
                <div class="flex flex-col items-center md:flex-row md:items-start gap-8">
                    
                    {{-- Avatar --}}
                    <div class="relative">
                        <img src="{{ $user->profile_picture 
                            ? asset('storage/' . $user->profile_picture) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&background=random' }}" 
                            class="w-32 h-32 rounded-full object-cover border-4 border-yellow-400 shadow-lg" 
                            alt="Profile Picture">
                    </div>

                    {{-- User Details --}}
                    <div class="flex-1 text-center md:text-left">
                        <p class="text-gray-500 uppercase font-bold tracking-widest text-base">{{ $user->username }}</p>
                        <h1 class="text-white text-gray-500 text-4xl uppercase font-black tracking-tight mb-4">{{ $user->full_name }}</h1>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-gray-400 text-sm">

                            {{-- Email --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-envelope text-gray-500"></i>
                                </div>
                                <a href="mailto:{{ $user->email }}">
                                    {{ $user->email }}
                                </a>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-phone text-gray-500 text-xs"></i>
                                </div>
                                <span>{{ $user->contact_number ?? 'No contact number' }}</span>
                            </div>

                            {{-- Address --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-location-dot text-gray-500"></i>
                                </div>
                                <span>{{ $user->address ?? 'Location not set' }}</span>
                            </div>

                            {{-- Gender --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-venus-mars text-gray-500 text-xs"></i>
                                </div>
                                <span>{{ ucfirst($user->sex ?? 'Not specified') }}</span>
                            </div>

                            {{-- Birthdate --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-cake-candles text-gray-500 text-xs"></i>
                                </div>
                                <span>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('F d, Y') : 'Not specified' }}</span>
                            </div>

                            {{-- Member Since --}}
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-star text-gray-500 text-xs"></i>
                                </div>
                                <span>Member since {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</span>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col gap-6 min-w-[200px]">

                        @if(auth()->id() !== $user->id)
                            <a href="{{ route('messages.index', $user->id) }}" wire:navigate
                                class="flex items-center justify-center gap-2 px-6 py-3 bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-black rounded-full uppercase tracking-widest transition shadow-lg shadow-yellow-400/10">
                                <i class="fa-solid fa-paper-plane"></i>
                                Send Message
                            </a>
                        @endif

                        <div class="bg-black/30 rounded-2xl p-6 border border-white/5 text-center">
                            <p class="text-white text-4xl font-black">{{ $cars->count() }}</p>
                            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Cars Listed</p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Owner's Listings --}}
            <h2 class="text-white text-xl font-black uppercase tracking-widest mb-6 flex items-center gap-3">
                <span class="w-8 h-1 bg-yellow-400 rounded-full"></span>
                Listings by this owner
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($cars as $car)
                    @include('available_cars.cards', ['car' => $car])
                @empty
                    <div class="col-span-full py-20 text-center bg-[#1e1e1e] rounded-3xl border border-dashed border-white/10">
                        <i class="fa-solid fa-car-side text-gray-800 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-semibold italic">This user hasn't listed any cars yet.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-layout>