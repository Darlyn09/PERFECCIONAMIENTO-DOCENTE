@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center justify-center gap-4 py-4">
        
        {{-- Info de resultados --}}
        <p class="text-sm text-slate-500">
            Mostrando 
            <span class="font-semibold text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span>
            a 
            <span class="font-semibold text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span>
            de 
            <span class="font-semibold text-blue-600">{{ $paginator->total() }}</span>
            resultados
        </p>

        {{-- Botones de paginación --}}
        <div class="flex items-center gap-1">
            
            {{-- Botón Primera Página --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->url(1) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all"
                   title="Primera página">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </a>
            @endif

            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all"
                   title="Anterior">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            @endif

            {{-- Números de página --}}
            <div class="flex items-center gap-1 mx-2">
                @foreach ($elements as $element)
                    {{-- Separador "..." --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-10 h-10 text-slate-400 text-sm font-medium">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Links de páginas --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Página actual --}}
                                <span class="inline-flex items-center justify-center w-10 h-10 text-white text-sm font-bold bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-lg shadow-blue-500/30">
                                    {{ $page }}
                                </span>
                            @else
                                {{-- Otras páginas --}}
                                <a href="{{ $url }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 text-slate-600 text-sm font-medium bg-white border border-slate-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all"
                   title="Siguiente">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif

            {{-- Botón Última Página --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all"
                   title="Última página">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>

        {{-- Indicador de página actual (móvil) --}}
        <p class="text-xs text-slate-400 sm:hidden">
            Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
        </p>
    </nav>
@endif
