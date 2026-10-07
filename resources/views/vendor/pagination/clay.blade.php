@if ($paginator->total() > 0)
    <nav class="clay-pagination-wrap" aria-label="Navigasi Halaman Data">
        <div class="clay-pagination-info">
            Menampilkan <span>{{ $paginator->firstItem() ?? 1 }}</span> - <span>{{ $paginator->lastItem() ?? $paginator->total() }}</span> dari <span>{{ $paginator->total() }}</span> data
        </div>

        @if ($paginator->hasPages())
            <ul class="clay-pagination-list">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" aria-disabled="true" aria-label="Halaman Sebelumnya">
                        <span aria-hidden="true"><i class="fas fa-chevron-left" style="font-size: 0.725rem;"></i></span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman Sebelumnya">
                            <i class="fas fa-chevron-left" style="font-size: 0.725rem;"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman Berikutnya">
                            <i class="fas fa-chevron-right" style="font-size: 0.725rem;"></i>
                        </a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true" aria-label="Halaman Berikutnya">
                        <span aria-hidden="true"><i class="fas fa-chevron-right" style="font-size: 0.725rem;"></i></span>
                    </li>
                @endif
            </ul>
        @endif
    </nav>
@endif
