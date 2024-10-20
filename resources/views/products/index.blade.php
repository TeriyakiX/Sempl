<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список товаров</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h1>Все продукты</h1>

    <!-- Таблица с продуктами -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название товара</th>
            <th>Описание</th>
            <th>Фотография</th>
            <th>Категория</th>
            <th>Подкатегория</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td class="truncate">{{ $product['description'] }}</td>
                <td>
                    <img src="{{ $product['photo'] }}" alt="Фото продукта" width="100">
                </td>
                <td>{{ $product['category']['name'] ?? 'Без категории' }}</td>
                <td>
                    @if(isset($product['subcategory']['name']))
                        {{ $product['subcategory']['name'] }}
                    @else
                        Без подкатегории
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-info">Редактировать</a>
                    <a href="{{ route('products.create') }}" class="btn btn-info">Добавить</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
