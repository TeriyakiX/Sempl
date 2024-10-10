<div class="container">
    <h1>Список продуктов</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Цена</th>
            <th>Фото</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->price }}</td>
            <td><img src="{{ $product->photo }}" alt="Фото продукта"></td>
            <td>
                <a href="{{ route('products.show', $product->id) }}">Просмотр</a>
                <a href="{{ route('products.edit', $product->id) }}">Редактировать</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
