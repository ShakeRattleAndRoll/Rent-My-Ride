@forelse ($pendingCars as $car)
    {{-- Pending car row --}}
    <div
        class="mb-3 grid overflow-hidden rounded-lg border border-white/10 bg-[#1a1a1a] lg:grid-cols-[170px_1fr_170px]"
        data-admin-pending-car="{{ $car->id }}">
        <div class="aspect-[16/9] bg-gray-900 lg:aspect-auto">
            <img
                src="{{ asset('storage/' . $car->car_image) }}"
                alt="{{ $car->brand }} {{ $car->model }}"
                class="h-full w-full object-cover">
        </div>

        {{-- Car and owner details --}}
        <div class="p-4">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-yellow-400 px-2.5 py-0.5 text-[9px] font-black uppercase tracking-widest text-black">Pending</span>
                <span class="text-[10px] font-semibold text-gray-500">Submitted {{ $car->created_at->diffForHumans() }}</span>
            </div>

            <h2 class="text-lg font-black text-white">{{ $car->brand }} {{ $car->model }}</h2>
            <p class="mt-1 text-xs text-gray-400">
                Posted by {{ $car->user->full_name ?: $car->user->username }}
                <span class="text-gray-600">/</span>
                {{ $car->user->email }}
            </p>

            <div class="mt-3 grid gap-2 text-xs text-gray-300 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Price</p>
                    <p class="font-bold text-white">&#8369;{{ number_format($car->price) }} / {{ $car->rent_unit }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Owned</p>
                    <p class="font-bold text-white">{{ $car->date_owned->format('M j, Y') }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Fuel</p>
                    <p class="font-bold text-white">{{ $car->fuel_type }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Transmission</p>
                    <p class="font-bold text-white">{{ $car->transmission }}</p>
                </div>
            </div>

            @if($car->description)
                <p class="mt-3 line-clamp-1 text-xs leading-relaxed text-gray-400">{{ $car->description }}</p>
            @endif
        </div>

        {{-- Approval actions --}}
        <div class="flex flex-col justify-center gap-2 border-t border-white/10 p-4 lg:border-l lg:border-t-0">
            <form
                action="{{ route('admin.cars.approve', $car) }}"
                method="POST"
                class="w-full"
                data-livewire-form
                data-preserve-scroll>
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full rounded-lg bg-lime-400 px-4 py-2.5 text-[11px] font-black uppercase tracking-widest text-black transition hover:bg-lime-300">
                    Accept Post
                </button>
            </form>
            <button type="button"
                    onclick="document.getElementById('delete-modal-deny-car-{{ $car->id }}').classList.remove('hidden')"
                    class="w-full rounded-lg border border-red-500/30 px-4 py-2.5 text-[11px] font-black uppercase tracking-widest text-red-300 transition hover:bg-red-500 hover:text-white">
                Deny
            </button>
        </div>
    </div>

    {{-- Denial confirmation for this car --}}
    <x-modals.delete_confirmation
        rentalId="deny-car-{{ $car->id }}"
        :route="route('admin.cars.deny', $car)"
        method="DELETE"
        title="Deny Car Post"
        message="Are you sure you want to deny and remove this car post?"
        confirmText="Yes, Deny" />
@empty
    <div class="rounded-lg border border-white/10 bg-[#1a1a1a] py-20 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-lg bg-lime-400/10 text-lime-400">
            <i class="fa-solid fa-check text-xl"></i>
        </div>
        <p class="text-sm font-black uppercase tracking-widest text-white">No pending car posts</p>
        <p class="mt-2 text-sm text-gray-500">New submissions will appear here.</p>
    </div>
@endforelse

<div class="mt-8">
    {{ $pendingCars->links() }}
</div>
