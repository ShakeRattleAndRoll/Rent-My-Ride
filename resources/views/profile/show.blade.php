{{-- Public user profile page --}}
<x-layout>
    <div class="min-h-screen bg-[#121212] px-4 py-8 text-white sm:px-6 lg:px-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="mx-auto max-w-7xl">
            <x-back_button />

            <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#1a1a1a] shadow-2xl">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div class="p-5 sm:p-7 lg:p-8">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                            <div class="mx-auto shrink-0 sm:mx-0">
                                <div class="relative h-28 w-28 rounded-full border-2 border-lime-400 bg-[#242424] p-1 shadow-lg shadow-lime-400/10 sm:h-32 sm:w-32">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                            class="h-full w-full rounded-full object-cover"
                                            alt="{{ $user->username }} profile picture">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center rounded-full bg-black">
                                            <span class="text-4xl font-black text-gray-600">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="min-w-0 flex-1 text-center sm:text-left">
                                <p class="mb-2 text-[10px] font-black uppercase tracking-[0.22em] text-lime-400">
                                    @ {{ $user->username }}
                                </p>
                                <h1 class="text-3xl font-black uppercase leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                                    {{ $user->full_name }}
                                </h1>
                                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-400">
                                    Member since {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}
                                </p>

                                <div class="mt-6 grid grid-cols-1 gap-3 text-left sm:grid-cols-2">
                                    <a href="mailto:{{ $user->email }}" class="flex min-w-0 items-center gap-3 rounded-xl border border-white/5 bg-black/20 px-4 py-3 text-sm text-gray-300 transition hover:border-lime-400/40 hover:text-white">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400">
                                            <i class="fa-solid fa-envelope text-xs"></i>
                                        </span>
                                        <span class="truncate">{{ $user->email }}</span>
                                    </a>

                                    <div class="flex min-w-0 items-center gap-3 rounded-xl border border-white/5 bg-black/20 px-4 py-3 text-sm text-gray-300">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/5 text-gray-400">
                                            <i class="fa-solid fa-phone text-xs"></i>
                                        </span>
                                        <span class="truncate">{{ $user->contact_number ?? 'No contact number' }}</span>
                                    </div>

                                    <div class="flex min-w-0 items-center gap-3 rounded-xl border border-white/5 bg-black/20 px-4 py-3 text-sm text-gray-300 sm:col-span-2">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/5 text-gray-400">
                                            <i class="fa-solid fa-location-dot text-xs"></i>
                                        </span>
                                        <span class="truncate">{{ $user->address ?? 'Location not set' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="border-t border-white/10 bg-black/20 p-5 sm:p-7 lg:border-l lg:border-t-0 lg:p-8">
                        <div class="grid grid-cols-2 gap-3 lg:grid-cols-1">
                            <div class="rounded-xl border border-white/5 bg-[#202020] p-4 text-center">
                                <p class="text-3xl font-black text-white">{{ $cars->count() }}</p>
                                <p class="mt-1 text-[9px] font-black uppercase tracking-widest text-gray-500">Cars Listed</p>
                            </div>

                            <div class="rounded-xl border border-white/5 bg-[#202020] p-4 text-center">
                                <p class="text-sm font-black uppercase text-white">{{ ucfirst($user->sex ?? 'N/A') }}</p>
                                <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-gray-500">Profile Info</p>
                            </div>
                        </div>

                        @if(auth()->id() !== $user->id)
                            <a href="{{ route('messages.index', $user->id) }}" wire:navigate data-message-navigate @guest data-auth-required @endguest
                                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-lime-400 px-5 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-lime-300">
                                <i class="fa-solid fa-paper-plane"></i>
                                Send Message
                            </a>
                        @endif
                    </aside>
                </div>
            </section>

            <section class="mt-8">
                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-lime-400">Owner Listings</p>
                        <h2 class="mt-1 text-2xl font-black uppercase tracking-tight text-white">Available Cars</h2>
                    </div>
                    <p class="text-sm text-gray-500">{{ $cars->count() }} {{ \Illuminate\Support\Str::plural('listing', $cars->count()) }}</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse($cars as $car)
                        @include('available_cars.cards', ['car' => $car])
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-white/10 bg-[#1a1a1a] px-6 py-20 text-center">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-black/30 text-gray-700">
                                <i class="fa-solid fa-car-side text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-400">This user has not listed any cars yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layout>
