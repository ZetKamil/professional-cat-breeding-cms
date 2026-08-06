{{--
    Editorial Luxury Pagination Component — Apple / Polène / Porsche Minimalist Finish
--}}

@if ($paginator->hasPages())
    <nav class="editorial-pagination" role="navigation" aria-label="Nawigacja stronami">
        <div class="editorial-pagination__controls">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-btn--disabled" aria-disabled="true">
                    <i data-lucide="chevron-left" aria-hidden="true"></i>
                    <span>Poprzednia</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="Poprzednia strona">
                    <i data-lucide="chevron-left" aria-hidden="true"></i>
                    <span>Poprzednia</span>
                </a>
            @endif

            {{-- Numbered Page Links --}}
            <div class="editorial-pagination__pages">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-number pagination-number--ellipsis" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-number pagination-number--active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number" aria-label="Przejdź do strony {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="Następna strona">
                    <span>Następna</span>
                    <i data-lucide="chevron-right" aria-hidden="true"></i>
                </a>
            @else
                <span class="pagination-btn pagination-btn--disabled" aria-disabled="true">
                    <span>Następna</span>
                    <i data-lucide="chevron-right" aria-hidden="true"></i>
                </span>
            @endif
        </div>

        {{-- Results Counter --}}
        <div class="editorial-pagination__info">
            Pokazano {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} z {{ $paginator->total() }} wyników
        </div>
    </nav>
@endif
