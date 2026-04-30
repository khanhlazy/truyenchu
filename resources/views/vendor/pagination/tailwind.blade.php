@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
            <div class="flex flex-wrap items-center justify-center gap-2">
                @if ($paginator->onFirstPage())
                    <span class="btn-secondary cursor-default opacity-50">Đầu</span>
                    <span class="btn-secondary cursor-default opacity-50">Trước</span>
                @else
                    <a href="{{ $paginator->url(1) }}" class="btn-secondary">Đầu</a>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-secondary">Trước</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-3 py-2 text-sm text-[color:var(--ui-muted)]">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex min-w-[40px] items-center justify-center rounded-2xl bg-[color:var(--ui-primary)] px-3 py-2 text-sm font-semibold text-white sm:min-w-[48px] sm:px-4 sm:py-3">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="btn-secondary min-w-[40px] justify-center px-3 py-2 sm:min-w-[48px] sm:px-4 sm:py-3">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-secondary">Sau</a>
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="btn-secondary">Cuối</a>
                @else
                    <span class="btn-secondary cursor-default opacity-50">Sau</span>
                    <span class="btn-secondary cursor-default opacity-50">Cuối</span>
                @endif
            </div>
        </nav>
    </div>
@endif
