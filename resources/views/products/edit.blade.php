<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать продукт</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h1>Редактировать продукт: {{ $product->name }}</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Название продукта</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Описание продукта</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="photo">Фото продукта</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            <small>Текущее фото:</small><br>
            <img src="{{ $product->photo }}" alt="Текущее фото продукта" width="100"><br>
        </div>

        <div class="form-group">
            <label for="category_id">Категория</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="subcategory_id">Подкатегория</label>
            <select class="form-control" id="subcategory_id" name="subcategory_id">
                <option value="">Выберите подкатегорию</option>
                @foreach($categories as $category)
                    @foreach($category->subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $product->subcategory_id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Обновить</button>
    </form>
</div>
</body>
</html>
