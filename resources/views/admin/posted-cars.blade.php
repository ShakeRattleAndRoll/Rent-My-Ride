<x-layout>
    <div class="min-h-screen bg-[#121212]" style="font-family: 'Montserrat', sans-serif;">
        {{-- Hero and filters --}}
        <div class="relative flex h-[280px] items-center justify-center">
            <img
                src="{{ asset('images/bg-picture-availablecars.jpg') }}"
                class="absolute inset-0 h-full w-full object-cover object-center"
                alt="Posted cars">
            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-black/50 to-black/70"></div>

            <div class="relative z-10 w-full max-w-4xl px-6 text-center">
                <p class="mb-2 text-[11px] font-black uppercase tracking-[0.22em] text-lime-400">Admin</p>
                <h1 class="mb-6 text-4xl font-black uppercase tracking-tight text-white md:text-6xl">Posted Cars</h1>

                <form
                    action="{{ route('admin.cars.posted') }}"
                    method="GET"
                    class="mx-auto grid max-w-2xl gap-3 sm:grid-cols-[1fr_160px_44px]">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search car, owner, or email..."
                        class="rounded-xl border border-gray-800 bg-[#1a1a1a] px-4 py-3 text-sm text-white outline-none focus:border-lime-400">

                    <select
                        name="status"
                        class="rounded-xl border border-gray-800 bg-[#1a1a1a] px-4 py-3 text-sm text-white outline-none focus:border-lime-400">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>

                    <button
                        type="submit"
                        class="flex h-11 items-center justify-center rounded-xl bg-lime-400 text-black transition hover:bg-lime-300"
                        title="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Results summary --}}
        <div class="mx-auto max-w-7xl px-6 py-16">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-widest text-white">Results ({{ $cars->total() }})</h2>
                <a
                    href="{{ route('admin.cars.posted') }}"
                    wire:navigate
                    data-nav-navigate
                    class="text-xs font-black uppercase tracking-widest text-gray-500 hover:text-lime-400">
                    Reset
                </a>
            </div>

            {{-- Posted car cards --}}
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($cars as $car)
                    @php
                        $isOccupied = (bool) ($car->is_occupied ?? false);
                    @endphp

                    <div
                        x-data="{ open: false }"
                        class="relative flex h-full flex-col overflow-hidden rounded-2xl border border-white/5 bg-[#1e1e1e] shadow-lg transition hover:border-lime-400/40">
                        <div
                            @click="open = true"
                            class="absolute inset-x-0 top-0 bottom-[64px] z-10 block cursor-pointer"
                            aria-label="View Details"></div>

                        <div class="relative block h-48 bg-[#2a2a2a]">
                            @if($car->car_image)
                                <img
                                    src="{{ asset('storage/' . $car->car_image) }}"
                                    class="h-full w-full object-cover"
                                    alt="{{ $car->brand }} {{ $car->model }}">
                            @endif

                            <div class="absolute left-3 top-3 flex gap-2">
                                <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $car->approval_status === 'approved' ? 'bg-lime-400 text-black' : 'bg-yellow-400 text-black' }}">
                                    {{ $car->approval_status }}
                                </span>
                                @if($isOccupied)
                                    <span class="rounded-full bg-red-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">Occupied</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <div class="mb-3 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-base font-bold text-white">{{ $car->brand }}</p>
                                    <p class="truncate text-sm text-gray-400">{{ $car->model }}</p>
                                </div>
                                <p class="shrink-0 text-right text-sm font-bold text-white">
                                    &#8369;{{ number_format($car->price) }}
                                    <span class="block text-[10px] font-normal text-gray-400">/ {{ $car->rent_unit }}</span>
                                </p>
                            </div>

                            <div class="mb-4 space-y-1 text-xs text-gray-400">
                                <p class="truncate"><i class="fa-solid fa-user mr-2 w-4 text-gray-500"></i>{{ $car->user?->full_name ?: $car->user?->username }}</p>
                                <p><i class="fa-solid fa-gas-pump mr-2 w-4 text-gray-500"></i>{{ $car->fuel_type }}</p>
                                <p><i class="fa-solid fa-gear mr-2 w-4 text-gray-500"></i>{{ $car->transmission }}</p>
                            </div>

                            <button type="button"
                                    onclick="document.getElementById('delete-modal-admin-car-{{ $car->id }}').classList.remove('hidden')"
                                    class="relative z-20 mt-auto w-full rounded-xl bg-red-500 px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-white transition hover:bg-red-400">
                                Delete Post
                            </button>
                        </div>

                        <x-modals.car_modal :car="$car" />
                    </div>

                    {{-- Delete confirmation for this posted car --}}
                    <x-modals.delete_confirmation
                        rentalId="admin-car-{{ $car->id }}"
                        :route="route('admin.cars.destroy', $car)"
                        method="DELETE"
                        title="Delete Post"
                        message="Are you sure you want to delete this posted car?"
                        confirmText="Yes, Delete" />
                @empty
                    <div class="col-span-full rounded-2xl border border-white/5 bg-[#1a1a1a] py-24 text-center">
                        <p class="font-medium text-gray-500">No posted cars found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $cars->links() }}
            </div>
        </div>
    </div>
</x-layout>
