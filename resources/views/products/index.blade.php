<div class="container">
    <h1>Список продуктов</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Лайки</th>
            <th>Дизлайки</th>
            <th>Ожидается</th>
            <th>Фото</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['description'] }}</td>
                <td>{{ $product['likes'] }}</td>
                <td>{{ $product['dislikes'] }}</td>
                <td>{{ $product['expected'] }}</td>
                <td>
                    <img src="{{ $product['photo'] }}" alt="Фото продукта" width="100">
                </td>
                <td>
                    <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info">Просмотр</a>
                    <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-warning">Редактировать</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
