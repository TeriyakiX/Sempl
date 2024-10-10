
<h1>Product {{ $product->name }}</h1>

<p>Price: {{ $product->price }}</p>

<p>Photo: <img src="{{ asset('storage/' . $product->photo) }}" alt="Product photo"></p>
