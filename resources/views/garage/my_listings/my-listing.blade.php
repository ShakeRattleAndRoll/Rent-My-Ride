<x-layout>
<div class="bg-[#121212] min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <x-garage_header
        active="listing"
        title="My Listings"
        subtitle="Manage your posted cars"
    />

    {{-- Listings --}}
    <div data-owner-listings data-refresh-url="{{ route('garage.my-listing.items') }}">
        @include('garage.my_listings.partials.list', ['listings' => $listings])
    </div>

</div>
<script src="{{ asset('js/my-listings-live.js') }}"></script>
</x-layout>
