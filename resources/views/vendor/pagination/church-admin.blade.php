@if ($paginator->hasPages())
    <nav class="flex justify-end" role="navigation" aria-label="Navigasi halaman">
        <div class="inline-flex flex-wrap gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center rounded-l-md border border-white/10 bg-church-surface px-3 py-1.5 text-sm text-slate-500" aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1 rounded-l-md border border-church-gold/40 bg-church-navy-mid px-3 py-1.5 text-sm font-medium text-white hover:bg-church-navy" aria-label="Halaman sebelumnya"><i class="fa-solid fa-angles-left" aria-hidden="true"></i></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center border border-white/10 bg-church-card px-3 py-1.5 text-sm text-slate-400">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center border border-church-gold/50 bg-church-gold px-3 py-1.5 text-sm font-semibold text-church-navy">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center border border-white/10 bg-church-card px-3 py-1.5 text-sm font-medium text-church-gold hover:bg-white/10">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1 rounded-r-md border border-church-gold/40 bg-church-navy-mid px-3 py-1.5 text-sm font-medium text-white hover:bg-church-navy" aria-label="Halaman berikutnya"><i class="fa-solid fa-angles-right" aria-hidden="true"></i></a>
            @else
                <span class="inline-flex cursor-not-allowed items-center rounded-r-md border border-white/10 bg-church-surface px-3 py-1.5 text-sm text-slate-500" aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>
            @endif
        </div>
    </nav>
@endif
