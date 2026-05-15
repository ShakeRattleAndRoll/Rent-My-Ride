<x-layout>
    <div class="min-h-screen bg-[#121212] px-4 py-12 sm:px-6 lg:px-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-[11px] font-black uppercase tracking-[0.22em] text-lime-400">Admin</p>
                    <h1 class="text-3xl font-black uppercase tracking-tight text-white md:text-4xl">Pending Car Posts</h1>
                    <p class="mt-2 text-sm text-gray-400">Review new car posts and accept the ones that can go public.</p>
                </div>

                <div class="rounded-lg border border-white/10 bg-black px-5 py-4 text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Waiting</p>
                    <p class="text-2xl font-black text-white" data-admin-pending-count>{{ $pendingCars->total() }}</p>
                </div>
            </div>

            <div data-admin-pending-cars data-refresh-url="{{ route('admin.cars.pending.items') }}">
                @include('admin.partials.pending-cars-list', ['pendingCars' => $pendingCars])
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-pending-cars.js') }}"></script>
</x-layout>
