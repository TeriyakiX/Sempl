<h1>Product {{ $product->name }}</h1>

<p>Описание: {{ $product->description }}</p>
<p>Цена: {{ $product->price }}</p>
<p>Лайки: {{ $product->likesCount() }}</p>
<p>Дизлайки: {{ $product->dislikesCount() }}</p>
<p>Ожидается: {{ $product->expected ? 'Да' : 'Нет' }}</p>
<p>Фото: <img src="{{ asset('storage/' . $product->photo) }}" alt="Product photo"></p>