<x-layout title="Messages">
    <div class="bg-[#121212] min-h-screen text-white p-6 md:p-10" style="font-family: 'Montserrat', sans-serif;">
        <div class="max-w-6xl mx-auto flex gap-6 h-[85vh]">

            @include('message.sidebar')

            @include('message.chat_panel')

        </div>
    </div>

    <script src="{{ asset('js/messages.js') }}"></script>
</x-layout>