<h1>Edit Product {{ $product->name }}</h1>

<form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{{ $product->name }}">

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" value="{{ $product->price }}">

    <label for="photo">Photo:</label>
    <input type="file" id="photo" name="photo">

    <button type="submit">Update</button>
</form>
