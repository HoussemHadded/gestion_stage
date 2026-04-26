@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col items-center justify-center space-y-4 my-8">
        
        {{-- Results Summary --}}
        <div class="text-sm font-medium text-secondary dark:text-secondary text-center">
            Affichage de <span class="font-bold text-text dark:text-surface">{{ $paginator->firstItem() }}</span> 
            à <span class="font-bold text-text dark:text-surface">{{ $paginator->lastItem() }}</span> 
            sur <span class="font-bold text-text dark:text-surface">{{ $paginator->total() }}</span> résultats
        </div>

        {{-- Pagination Links --}}
        <ul class="flex items-center justify-center space-x-2">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span aria-disabled="true" aria-label="Précédent" class="flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 rounded-full text-secondary bg-black/5 dark:bg-black/5 dark:text-secondary cursor-not-allowed opacity-60">
                        <i class="bi bi-chevron-left md:mr-2"></i>
                        <span class="hidden md:inline font-medium">Précédent</span>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Précédent" class="group flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 rounded-full text-secondary bg-surface border border-black/5 shadow-sm hover:bg-secondary/10 hover:text-text hover:border-secondary/20 dark:bg-black/5 dark:border-black/5 dark:text-secondary dark:hover:bg-secondary/80 dark:hover:border-secondary transition-all duration-300 transform hover:-translate-x-1">
                        <i class="bi bi-chevron-left md:mr-2 transition-transform group-hover:-translate-x-1"></i>
                        <span class="hidden md:inline font-medium">Précédent</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="hidden sm:block">
                        <span aria-disabled="true" class="flex items-center justify-center w-10 h-10 rounded-full text-secondary dark:text-secondary bg-transparent">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="hidden sm:block" aria-current="page">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold shadow-md transform scale-110">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="hidden sm:block">
                                <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 rounded-full text-secondary bg-surface border border-black/5 hover:bg-black/[0.02] hover:text-secondary dark:bg-black/5 dark:border-black/5 dark:text-secondary dark:hover:bg-secondary/80 dark:hover:text-secondary transition-all duration-200 hover:scale-105 shadow-sm">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Suivant" class="group flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 rounded-full text-secondary bg-surface border border-black/5 shadow-sm hover:bg-secondary/10 hover:text-text hover:border-secondary/20 dark:bg-black/5 dark:border-black/5 dark:text-secondary dark:hover:bg-secondary/80 dark:hover:border-secondary transition-all duration-300 transform hover:translate-x-1">
                        <span class="hidden md:inline font-medium">Suivant</span>
                        <i class="bi bi-chevron-right md:ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </li>
            @else
                <li>
                    <span aria-disabled="true" aria-label="Suivant" class="flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 rounded-full text-secondary bg-black/5 dark:bg-black/5 dark:text-secondary cursor-not-allowed opacity-60">
                        <span class="hidden md:inline font-medium">Suivant</span>
                        <i class="bi bi-chevron-right md:ml-2"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif

