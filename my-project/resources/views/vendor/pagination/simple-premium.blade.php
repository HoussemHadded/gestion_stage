@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center justify-center space-y-4 my-8">
        <div class="flex items-center justify-center space-x-4">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center px-6 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 text-sm font-bold uppercase tracking-wider">
                    <i class="bi bi-chevron-left mr-2"></i> Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center px-6 py-2 rounded-xl bg-white text-gray-600 border border-gray-200 shadow-sm hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 premium-hover text-sm font-bold uppercase tracking-wider">
                    <i class="bi bi-chevron-left mr-2"></i> Précédent
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center px-6 py-2 rounded-xl bg-white text-gray-600 border border-gray-200 shadow-sm hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 premium-hover text-sm font-bold uppercase tracking-wider">
                    Suivant <i class="bi bi-chevron-right ml-2"></i>
                </a>
            @else
                <span class="flex items-center justify-center px-6 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 text-sm font-bold uppercase tracking-wider">
                    Suivant <i class="bi bi-chevron-right ml-2"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
