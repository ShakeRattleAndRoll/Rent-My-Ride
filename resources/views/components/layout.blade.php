<!DOCTYPE html>
<html>
<head>

    @livewireStyles

    <title>{{ $title ?? 'Rent My Ride' }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/Rent-My-Ride-Logo.png') }}" sizes="100x64">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-700">

    @if (!request()->is('login') && !request()->is('register') && !request()->is('register/verify-email') && !request()->is('forgot-password*') && !request()->is('reset-password*'))
        <x-nav />
    @endif

    {{-- Pwede ni himoan og blade --}}
    {{-- Uses alpine.js --}}
    <div class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4 pointer-events-none">
    {{-- Success Alert --}}
        @if(session('success'))
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 1000)" 
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="pointer-events-auto bg-lime-500 text-black px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-lime-400 mb-3"
            >
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-['Montserrat'] font-semibold uppercase text-[11px] tracking-widest leading-none pt-[1px]">
                        {{ session('success') }}
                    </span>
                </div>
                <button @click="show = false" class="text-black/40 hover:text-black transition-colors ml-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 1000)" 
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="pointer-events-auto bg-red-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-red-500 mb-3"
            >
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-['Montserrat'] font-semibold uppercase text-[11px] tracking-widest leading-none pt-[1px]">
                        {{ session('error') }}
                    </span>
                </div>
                <button @click="show = false" class="text-white/40 hover:text-white transition-colors ml-4">
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

    @guest
        <div
            id="auth-required-modal"
            class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/75 px-4 backdrop-blur-sm"
            style="font-family: 'Montserrat', sans-serif;"
            aria-modal="true"
            role="dialog"
        >
            <div class="w-full max-w-md rounded-2xl border border-white/10 bg-[#111] p-7 text-center shadow-2xl">
                <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-lime-400/10 text-lime-400">
                    <i class="fa-solid fa-lock text-lg"></i>
                </div>

                <h2 class="mb-3 text-2xl font-black text-white">Log in or Create an account</h2>
                <p class="mb-6 text-sm leading-relaxed text-gray-400">
                    You need an account to continue with this action.
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('login') }}" wire:navigate class="flex-1 rounded-lg bg-lime-400 px-5 py-3 text-sm font-black uppercase text-black transition hover:bg-lime-300">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" wire:navigate class="flex-1 rounded-lg border border-lime-400/40 px-5 py-3 text-sm font-black uppercase text-lime-300 transition hover:bg-lime-400 hover:text-black">
                        Sign Up
                    </a>
                </div>

                <button
                    type="button"
                    data-auth-modal-close
                    class="mt-5 text-xs font-bold uppercase tracking-widest text-gray-500 transition hover:text-white"
                >
                    Maybe Later
                </button>
            </div>
        </div>
    @endguest

    @if(session('car_submitted_for_approval'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="show = false"
            class="fixed inset-0 z-[130] flex items-center justify-center bg-black/75 px-4 backdrop-blur-sm"
            style="font-family: 'Montserrat', sans-serif;"
        >
            <div
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-md rounded-2xl border border-white/10 bg-[#111] p-7 text-center shadow-2xl"
            >
                <button
                    type="button"
                    @click="show = false"
                    class="absolute -right-3 -top-3 flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-[#1e1e1e] text-white transition hover:text-red-400"
                    title="Close"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-xl bg-yellow-400/10 text-yellow-400">
                    <i class="fa-solid fa-car-side text-xl"></i>
                </div>

                <h2 class="mb-3 text-2xl font-black text-white">Car Uploaded</h2>
                <p class="mb-6 text-sm leading-relaxed text-gray-400">
                    Your car post is waiting for admin approval. You will get a notification once it is accepted.
                </p>

                <button
                    type="button"
                    @click="show = false"
                    class="w-full rounded-lg bg-yellow-400 px-5 py-3 text-sm font-black uppercase text-black transition hover:bg-yellow-300"
                >
                    Got It
                </button>
            </div>
        </div>
    @endif

    @livewireScripts

    @if (!request()->is('login') && !request()->is('register') && !request()->is('register/verify-email') && !request()->is('forgot-password*') && !request()->is('reset-password*'))
        <x-footer />
    @endif

    <script src="{{ asset('js/auth/visiblepass.js') }}"></script>

</body>
</html>
