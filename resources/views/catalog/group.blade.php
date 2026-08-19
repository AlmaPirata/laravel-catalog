<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $group->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <main class="container py-4">
        <div class="row g-4">
            <aside class="col-12 col-md-4">
                <div class="catalog border p-3 h-100">
                    @if ($groups->isNotEmpty())
                        <x-catalog
                            :groups="$groups"
                            :product-counts="$productCounts"
                            :group-path-ids="$groupPathIds"
                        />
                    @else
                        <p>Подгрупп нет.</p>
                    @endif
                </div>
            </aside>

            <section class="col-12 col-md-8">
                <div class="products border p-3 h-100">
                    <x-products :products="$products" />
                </div>
            </section>
        </div>
    </main>
</body>
</html>
