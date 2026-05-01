@props(['active' => 'listing', 'title', 'subtitle'])

<div class="px-10 pt-10 pb-6">
    <div class="flex items-center justify-between mb-1">
        <div>
            <h1 class="text-white text-2xl font-bold tracking-tight">{{ $title }}</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $subtitle }}</p>
        </div>
    </div>
</div>