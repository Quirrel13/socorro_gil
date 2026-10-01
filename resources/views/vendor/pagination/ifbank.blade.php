@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-6 text-sm" role="navigation" aria-label="Paginação">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg border border-ifb-line text-ifb-dim opacity-40">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-ifb-line text-ifb-soft transition-all hover:border-ifb-primary hover:text-white">Anterior</a>
        @endif

        <span class="font-mono text-xs text-ifb-dim">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-ifb-line text-ifb-soft transition-all hover:border-ifb-primary hover:text-white">Próxima</a>
        @else
            <span class="px-3 py-1.5 rounded-lg border border-ifb-line text-ifb-dim opacity-40">Próxima</span>
        @endif
    </nav>
@endif