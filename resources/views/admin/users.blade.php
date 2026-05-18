<x-layout>
    <div class="min-h-screen bg-[#121212] px-4 py-12 sm:px-6 lg:px-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="mx-auto max-w-7xl">
            {{-- Page title and total users --}}
            <div class="mb-8 flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-[11px] font-black uppercase tracking-[0.22em] text-lime-400">Admin</p>
                    <h1 class="text-3xl font-black uppercase tracking-tight text-white md:text-4xl">Users</h1>
                    <p class="mt-2 text-sm text-gray-400">View registered users and remove accounts that should no longer be active.</p>
                </div>

                <div class="rounded-lg border border-white/10 bg-black px-5 py-4 text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Accounts</p>
                    <p class="text-2xl font-black text-white">{{ $users->total() }}</p>
                </div>
            </div>

            {{-- User search --}}
            <form action="{{ route('admin.users') }}" method="GET" class="mb-6 max-w-xl">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search users by name, username, email, or contact..."
                        class="w-full rounded-2xl border border-gray-800 bg-[#1a1a1a] px-6 py-4 pr-14 text-sm text-white outline-none transition-all focus:border-transparent focus:ring-2 focus:ring-lime-400">

                    <button
                        type="submit"
                        class="input-action-button right-3 h-9 w-9 rounded-xl bg-lime-400 text-black transition-colors hover:bg-lime-300"
                        title="Search">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>
            </form>

            {{-- Users table --}}
            <div class="overflow-hidden rounded-lg border border-white/10 bg-[#1a1a1a]">
                <div class="hidden grid-cols-[1.4fr_1fr_120px_120px_120px] border-b border-white/10 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-gray-500 lg:grid">
                    <span>User</span>
                    <span>Contact</span>
                    <span>Cars</span>
                    <span>Rentals</span>
                    <span class="text-right">Action</span>
                </div>

                @forelse($users as $user)
                    <div class="grid gap-4 border-b border-white/5 px-5 py-4 last:border-b-0 lg:grid-cols-[1.4fr_1fr_120px_120px_120px] lg:items-center">
                        <div class="min-w-0">
                            <a
                                href="{{ route('user.profile', $user->id) }}"
                                wire:navigate
                                data-nav-navigate
                                class="flex items-center gap-3 rounded-lg transition hover:bg-white/5">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full border border-white/10 bg-[#242424] text-xs font-black uppercase text-white">
                                    @if($user->profile_picture)
                                        <img
                                            src="{{ asset('storage/' . $user->profile_picture) }}"
                                            alt="{{ $user->username }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        {{ substr($user->username, 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-white">
                                        @ {{ $user->username }}
                                    </p>
                                    <p class="truncate text-xs text-gray-500">{{ $user->full_name ?: $user->username }}</p>
                                </div>
                            </a>
                        </div>

                        <div class="min-w-0 text-xs text-gray-400">
                            <p class="truncate">{{ $user->email }}</p>
                            <p class="truncate text-gray-600">{{ $user->contact_number ?: 'No contact number' }}</p>
                        </div>

                        <div class="text-sm font-bold text-white">
                            {{ $user->cars_count }}
                            <span class="ml-1 text-[10px] uppercase tracking-widest text-gray-500 lg:hidden">cars</span>
                        </div>

                        <div class="text-sm font-bold text-white">
                            {{ $user->rentals_count }}
                            <span class="ml-1 text-[10px] uppercase tracking-widest text-gray-500 lg:hidden">rentals</span>
                        </div>

                        <button type="button"
                                onclick="document.getElementById('delete-modal-admin-user-{{ $user->id }}').classList.remove('hidden')"
                                class="w-full rounded-lg border border-red-500/30 px-4 py-2.5 text-[11px] font-black uppercase tracking-widest text-red-300 transition hover:bg-red-500 hover:text-white">
                            Delete
                        </button>

                        {{-- Delete confirmation for this account --}}
                        <x-modals.delete_confirmation
                            rentalId="admin-user-{{ $user->id }}"
                            :route="route('admin.users.destroy', $user)"
                            method="DELETE"
                            title="Delete Account"
                            message="Are you sure you want to permanently delete this user account?"
                            confirmText="Yes, Delete" />
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-sm font-black uppercase tracking-widest text-white">No users found</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layout>
