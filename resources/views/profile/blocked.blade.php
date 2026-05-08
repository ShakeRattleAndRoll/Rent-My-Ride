<x-layout title="Profile Unavailable">
    <div class="bg-[#121212] min-h-screen text-white px-6 py-16" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-xl mx-auto text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-600/10 border border-red-600/30 flex items-center justify-center">
                <i class="fa-solid fa-ban text-red-500 text-3xl"></i>
            </div>

            <p class="text-red-500 text-[10px] font-black uppercase tracking-[0.35em] mb-3">
                Profile Unavailable
            </p>

            <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-4">
                You cannot view this profile
            </h1>

            <p class="text-gray-400 text-sm leading-6 mb-8">
                This profile is unavailable because one of you has blocked the other user.
                Messaging and profile details are restricted for this account.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <button
                    type="button"
                    onclick="history.back()"
                    class="px-6 py-3 rounded-full bg-yellow-400 hover:bg-yellow-300 text-black text-xs font-black uppercase tracking-widest transition"
                >
                    Go Back
                </button>

                <a
                    href="/messages"
                    wire:navigate
                    data-nav-navigate
                    class="px-6 py-3 rounded-full border border-white/10 hover:border-white/30 text-gray-300 hover:text-white text-xs font-black uppercase tracking-widest transition"
                >
                    Open Messages
                </a>
            </div>
        </div>
    </div>
</x-layout>
