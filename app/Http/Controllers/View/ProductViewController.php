<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class ProductViewController extends Controller
{
    protected $apiProductController;

    public function __construct(ProductController $apiProductController)
    {
        $this->apiProductController = $apiProductController;
    }

    public function index()
    {
        $products = Product::with('category')->get();

        $categories = Category::with('subcategories')->get();

        return view('products.index', ['products' => $products, 'categories' => $categories]);
    }

    public function show(Product $product)
    {
        $product = $this->apiProductController->show($product);
        $productData = $product->toArray(request());

        return view('products.show', ['product' => $productData['data']]);
    }

    public function create()
    {
        $categories = Category::with('subcategories')->get();
        return view('products.create', compact('categories'));
    }

    public function store(CreateProductRequest $request)
    {
        $validatedData = $request->validated();

        $product = $this->createProduct($validatedData);

        return redirect()->route('products.index')->with('success', 'Продукт успешно добавлен');
    }

    protected function createProduct($validatedData)
    {

        $product = Product::create($validatedData);

        if (request()->hasFile('photo')) {
            $photo = request()->file('photo');

            $path = $photo->store('products', 'public');

            $product->photo = asset('storage/' . $path);
        }
        $product->save();

        return $product;
    }

    public function edit(Product $product)
    {
        $categories = Category::with('subcategories')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $path = $photo->store('products', 'public');
            $validatedData['photo'] = asset('storage/' . $path);

            if (filter_var($product->photo, FILTER_VALIDATE_URL) === false && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
        }

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Продукт успешно обновлен');
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт успешно удалён');
    }
}
