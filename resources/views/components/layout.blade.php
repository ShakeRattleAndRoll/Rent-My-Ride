<!DOCTYPE html>
<html>
<head>

    @livewireStyles
    @livewireScripts

    <title>{{ $title ?? 'Rent My Ride' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Rent-My-Ride-Logo.png') }}" sizes="100x64">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
</head>

<body class="bg-gray-700">

    @if (!request()->is('login') && !request()->is('register'))
        <x-nav />
    @endif

    {{-- Pwede ni himoan og blade --}}
    <div class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
        {{-- Success --}}
        @if(session('success'))
            <div id="alert-success" class="bg-lime-500 text-black px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-lime-400 animate-in fade-in slide-in-from-top-4 duration-300">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-['Montserrat'] font-semibold uppercase text-[11px] tracking-widest leading-none pt-[1px]">
                        {{ session('success') }}
                    </span>
                </div>
                <button onclick="document.getElementById('alert-success').remove()" class="text-black/40 hover:text-black transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Error --}}
        @if(session('error'))
            <div id="alert-error" class="bg-red-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-red-500 animate-in fade-in slide-in-from-top-4 duration-300">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-['Montserrat'] font-semibold uppercase text-[11px] tracking-widest leading-none pt-[1px]">
                        {{ session('error') }}
                    </span>
                </div>
                <button onclick="document.getElementById('alert-error').remove()" class="text-white/40 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <main>
        {{ $slot }}
    </main>

    @if (!request()->is('login') && !request()->is('register'))
        <x-footer />
    @endif

    <script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</body>
</html>