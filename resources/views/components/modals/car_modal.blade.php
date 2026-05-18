<div
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.self="open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
    style="font-family: 'Montserrat', sans-serif;"
    x-cloak
>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="bg-[#1e1e1e] rounded-2xl w-full max-w-xl p-6 relative border border-white/10 shadow-2xl"
    >
        {{-- Close Button --}}
        <button @click="open = false"
            class="absolute -top-3 -right-3 bg-[#1e1e1e] border border-white/10
                    w-8 h-8 flex items-center justify-center rounded-full
                    text-white hover:text-red-400 transition text-lg z-50">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Top Section --}}
        <div class="flex gap-6 mb-6">

            {{-- Car Image --}}
            <div class="w-48 h-40 rounded-xl overflow-hidden bg-gray-800 shrink-0">
                <img src="{{ asset('storage/' . $car->car_image) }}"
                    alt="{{ $car->brand }}"
                    class="w-full h-full object-cover">
            </div>

            {{-- Left Content --}}
            <div class="flex-1 flex flex-col justify-between">

                {{-- BRAND + PRICE --}}
                <div class="flex justify-between items-start gap-x-6 mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-white text-2xl font-black leading-tight uppercase tracking-tight break-words">
                            {{ $car->brand }}
                        </h2>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-widest mt-1">
                            {{ $car->model }}
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col items-end justify-start pt-1">
                        <div class="text-lime-400 font-black text-2xl leading-none">
                            ₱{{ number_format($car->price, 0) }}
                        </div>
                        <div class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1 border-t border-white/10 pt-1 w-full text-right">
                            {{ $car->rent_unit }}
                        </div>
                    </div>
                </div>

                {{-- SPECS --}}
                <div class="mt-3 space-y-2 text-sm text-gray-300">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-gray-500 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($car->date_owned)->format('F j, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-gas-pump text-gray-500 w-4"></i>
                        <span>{{ $car->fuel_type }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-gear text-gray-500 w-4"></i>
                        <span>{{ $car->transmission }}</span>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="mt-5 flex gap-3">
                    @if(auth()->check() && auth()->user()->is_admin)
                        <button type="button"
                                @click="open = false"
                                onclick="document.getElementById('delete-modal-admin-modal-car-{{ $car->id }}').classList.remove('hidden')"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-red-500 hover:bg-red-400 text-white text-xs font-bold rounded-full transition">
                            <i class="fa-solid fa-trash"></i>
                            Delete Post
                        </button>
                    @elseif(auth()->id() !== $car->user_id)
                        <a href="{{ route('messages.index', $car->user->id) }}" wire:navigate data-message-navigate @guest data-auth-required @endguest
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-lime-400 hover:bg-lime-300 text-black text-xs font-bold rounded-full transition">
                            <i class="fa-solid fa-message"></i>
                            Message Owner
                        </a>
                    @else
                        <div class="flex-1 flex items-center justify-center py-2.5 bg-gray-700 text-gray-400 text-xs font-bold rounded-full cursor-not-allowed">
                            Your Listing
                        </div>
                    @endif

                    @unless(auth()->check() && auth()->user()->is_admin)
                        <form method="POST" action="/cart/add" class="flex-1" data-livewire-form @guest data-auth-required @endguest>
                            @csrf
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-lime-400 hover:bg-lime-300 text-black text-xs font-bold rounded-full transition">
                                <i class="fa-solid fa-cart-plus"></i>
                                Add to Cart
                            </button>
                        </form>
                    @endunless
                </div>

            </div>
        </div>

        <div class="border-t border-white/5 mb-5"></div>

        {{-- OWNER INFO --}}
        <div class="grid grid-cols-2 gap-y-5 mb-6 text-sm">
            <div>
                <p class="text-white font-bold mb-1">Car Owner</p>
                <a href="{{ route('user.profile', $car->user->id) }}" wire:navigate data-nav-navigate
                class="inline-flex items-center gap-3
                        border border-transparent hover:border-white/30
                        rounded-xl px-3 py-2 -mx-3
                        transition-all duration-300">

                    <img src="{{ $car->user->profile_picture
                        ? asset('storage/' . $car->user->profile_picture)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($car->user->username) }}"
                        class="w-12 h-12 rounded-full object-cover border border-white/10"
                        alt="Owner">

                    <div class="flex flex-col leading-tight">
                        <p class="text-xs text-gray-500 font-semibold">
                            {{ $car->user->username }}
                        </p>
                        <p class="text-white font-semibold">
                            {{ $car->user->full_name }}
                        </p>
                    </div>

                </a>
            </div>

            <div>
                <p class="text-white font-bold mb-1">Owner Email</p>
                <a href="mailto:{{ $car->user->email }}" class="text-blue-400 hover:underline text-xs break-all">
                    {{ $car->user->email }}
                </a>
            </div>

            <div>
                <p class="text-white font-bold mb-1">Address</p>
                <p class="text-gray-400">{{ $car->user->address ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-white font-bold mb-1">Owner Contact Number</p>
                <p class="text-gray-400">{{ $car->user->contact_number ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="border-t border-white/5 mb-4"></div>

        {{-- DESCRIPTION --}}
        <p class="text-center text-gray-400 text-sm font-semibold mb-3">Description</p>
        <div class="bg-[#2a2a2a] border border-white/5 rounded-xl p-4 min-h-[80px]">
            <p class="text-gray-400 text-sm">
                {{ $car->description ?? 'No description provided.' }}
            </p>
        </div>

    </div>
</div>

@if(auth()->check() && auth()->user()->is_admin)
    <x-modals.delete_confirmation
        rentalId="admin-modal-car-{{ $car->id }}"
        :route="route('admin.cars.destroy', $car)"
        method="DELETE"
        title="Delete Post"
        message="Are you sure you want to delete this posted car?"
        confirmText="Yes, Delete" />
@endif
