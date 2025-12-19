@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center justify-center gap-3 py-4">
        
        {{-- Botones de navegación --}}
        <div class="flex items-center gap-3">
            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                   class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </a>
            @endif

            {{-- Indicador de página --}}
            <span class="px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 rounded-xl border border-blue-200">
                Página {{ $paginator->currentPage() }}
            </span>

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                   class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all">
                    Siguiente
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed">
                    Siguiente
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
