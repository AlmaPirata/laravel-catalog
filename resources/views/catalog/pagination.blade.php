Страница:
@if ($paginator->hasPages())
    <nav aria-label="Пагинация">
        @for ($page = 1; $page <= $paginator->lastPage(); $page++)
            @if ($page === $paginator->currentPage())
                <span aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endfor
    </nav>
@endif