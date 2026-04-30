@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center justify-center space-y-4 my-10">
        
        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white text-gray-600 border border-gray-200 shadow-sm hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 premium-hover">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden sm:flex items-center space-x-1 sm:space-x-2">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="w-10 h-10 flex items-center justify-center text-gray-400">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 border border-indigo-600">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-600 border border-gray-200 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Mobile Current Page Indicator --}}
            <div class="flex sm:hidden items-center px-4 font-bold text-gray-700">
                Page {{ $paginator->currentPage() }}
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white text-gray-600 border border-gray-200 shadow-sm hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 premium-hover">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>

        {{-- Results Summary --}}
        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
            Affichage de <span class="text-gray-700">{{ $paginator->firstItem() }}</span> à <span class="text-gray-700">{{ $paginator->lastItem() }}</span> sur <span class="text-gray-700">{{ $paginator->total() }}</span> résultats
        </div>
    </nav>
@endif
