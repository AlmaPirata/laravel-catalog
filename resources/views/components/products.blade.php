@props(['products'])

<div class="sort mb-3">Сортировать по:
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc', 'page' => 1]) }}">По цене ↑</a> |
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc', 'page' => 1]) }}">По цене ↓</a> |
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc', 'page' => 1]) }}">По названию ↑</a> |
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc', 'page' => 1]) }}">По названию ↓</a>
</div>

<div class="items">
    @if ($products->isNotEmpty())
        @foreach ($products as $product)
            <div class="mb-2">
                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a> <span class="price ms-1">{{ $product->price->price }} - руб.</span>
            </div>
        @endforeach
    @endif
</div>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
    <div class="pagination">
        {{ $products->links('catalog.pagination') }}
    </div>

    <div class="text-sm-end">
        Выводить по:
        <a href="{{ request()->fullUrlWithQuery(['per_page' => 6, 'page' => 1]) }}">6</a> |
        <a href="{{ request()->fullUrlWithQuery(['per_page' => 12, 'page' => 1]) }}">12</a> |
        <a href="{{ request()->fullUrlWithQuery(['per_page' => 18, 'page' => 1]) }}">18</a>
    </div>
</div>
