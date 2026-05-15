@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center justify-center w-full">
        
        {{-- Show results numbers --}}
        <div class="mb-4 text-center">
            <p class="text-sm text-gray-400 leading-5">
                {!! __('Showing') !!}
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <div class="mb-6 flex items-center gap-2">
            <label class="text-xs text-gray-500 uppercase font-bold tracking-widest">Jump to page:</label>
            <div class="relative">
                <input 
                    type="number" 
                    id="page-jumper"
                    min="1" 
                    max="{{ $paginator->lastPage() }}" 
                    placeholder="{{ $paginator->currentPage() }}"
                    class="w-16 bg-[#1a1a1a] border border-white/10 text-white text-sm rounded-lg px-2 py-1 focus:ring-1 focus:ring-lime-400 outline-none"
                >

            </div>
        </div>

        {{-- Button part --}}
        <div class="flex items-center justify-center shadow-sm">
            
            {{-- FIRST PAGE (<<) --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-gray-600 bg-[#1a1a1a] border border-white/10 rounded-l-xl cursor-default">&laquo;&laquo;</span>
            @else
                <a href="{{ $paginator->url(1) }}" class="px-3 py-2 text-white bg-[#1a1a1a] border border-white/10 rounded-l-xl hover:bg-lime-400 hover:text-black transition">&laquo;&laquo;</a>
            @endif

            {{-- PREVIOUS PAGE (<) --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-gray-600 bg-[#1a1a1a] border-y border-r border-white/10 cursor-default">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-white bg-[#1a1a1a] border-y border-r border-white/10 hover:bg-lime-400 hover:text-black transition">&laquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 text-gray-500 bg-[#1a1a1a] border-y border-r border-white/10 cursor-default">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 text-black bg-lime-400 border-y border-r border-lime-400 font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-white bg-[#1a1a1a] border-y border-r border-white/10 hover:bg-gray-800 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- NEXT PAGE (>) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-white bg-[#1a1a1a] border-y border-r border-white/10 hover:bg-lime-400 hover:text-black transition">&raquo;</a>
            @else
                <span class="px-4 py-2 text-gray-600 bg-[#1a1a1a] border-y border-r border-white/10 cursor-default">&raquo;</span>
            @endif

            {{-- LAST PAGE (>>) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="px-3 py-2 text-white bg-[#1a1a1a] border-y border-r border-white/10 rounded-r-xl hover:bg-lime-400 hover:text-black transition">&raquo;&raquo;</a>
            @else
                <span class="px-3 py-2 text-gray-600 bg-[#1a1a1a] border-y border-r border-white/10 rounded-r-xl cursor-default">&raquo;&raquo;</span>
            @endif

        </div>
    </nav>
@endif

{{-- Search part of pagination --}}
<script>
    function jumpToPage() {
        const page = document.getElementById('page-jumper').value;
        if (page && page >= 1 && page <= {{ $paginator->lastPage() }}) {
            let url = new URL(window.location.href);
            url.searchParams.set('page', page);
            window.location.href = url.href;
        } else {
            alert("Please enter a valid page number between 1 and {{ $paginator->lastPage() }}");
        }
    }
    document.getElementById('page-jumper')?.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            jumpToPage();
        }
    });
</script>