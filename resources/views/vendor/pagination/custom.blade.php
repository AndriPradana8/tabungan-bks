<div class="flex flex-col gap-4 py-4 px-6 sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 dark:border-white/[0.05]">
    <div>
        <p class="text-gray-500 text-sm dark:text-gray-400">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
        </p>
    </div>
    @if ($paginator->hasPages())
        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="p-2 text-sm font-medium text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed dark:border-gray-700 dark:text-gray-500" disabled>
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="p-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-brand-500 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-brand-500">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed" disabled>{{ $element }}</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="px-4 py-2 text-sm font-medium text-brand-500 bg-brand-50 rounded-lg dark:bg-brand-500/15 dark:text-brand-500">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="p-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-brand-500 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-brand-500">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <button class="p-2 text-sm font-medium text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed dark:border-gray-700 dark:text-gray-500" disabled>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            @endif
        </div>
    @endif
</div>
