@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center sm:justify-end gap-1 select-none">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-400 dark:text-slate-500 bg-slate-100/70 dark:bg-slate-800/50 rounded-lg cursor-not-allowed">
                <i class="bi bi-chevron-left text-sm"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 dark:hover:bg-indigo-950/60 dark:hover:text-indigo-300 dark:hover:border-indigo-800 shadow-sm transition duration-150">
                <i class="bi bi-chevron-left text-sm"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-400 dark:text-slate-500">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-white bg-indigo-600 dark:bg-indigo-500 rounded-lg shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 dark:hover:bg-indigo-950/60 dark:hover:text-indigo-300 dark:hover:border-indigo-800 shadow-sm transition duration-150">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 dark:hover:bg-indigo-950/60 dark:hover:text-indigo-300 dark:hover:border-indigo-800 shadow-sm transition duration-150">
                <i class="bi bi-chevron-right text-sm"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-400 dark:text-slate-500 bg-slate-100/70 dark:bg-slate-800/50 rounded-lg cursor-not-allowed">
                <i class="bi bi-chevron-right text-sm"></i>
            </span>
        @endif
    </nav>
@endif
