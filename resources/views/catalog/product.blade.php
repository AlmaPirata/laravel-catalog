<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $product->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <main class="container py-4">
        <nav aria-label="Хлебные крошки">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Главная</a></li>

                @foreach ($breadcrumbs as $group)
                    <li class="breadcrumb-item"><a href="{{ route('groups.show', $group) }}">{{ $group->name }}</a></li>
                @endforeach
            </ol>
        </nav>

        <h1 class="mb-3">{{ $product->name }}</h1>

        <div>Цена: {{ $product->price->price }} руб.</div>
    </main>
</body>
</html>
