<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Request;

class ProductViewController extends Controller
{
    protected $apiProductController;

    public function __construct(ProductController $apiProductController)
    {
        $this->apiProductController = $apiProductController;
    }

    public function index()
    {
        $products = Product::all();

        $productsList = $products->toArray();

        return view('products.index', ['products' => $productsList]);
    }

    public function show(Product $product)
    {
        $product = $this->apiProductController->show($product);
        $productData = $product->toArray(request());

        return view('products.show', ['product' => $productData['data']]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $apiRequest = new CreateProductRequest();
        $apiRequest->replace($request->all());

        // Добавляем файл, если он есть
        if ($request->hasFile('photo')) {
            $apiRequest->files->set('photo', $request->file('photo'));
        }

        $product = $this->apiProductController->create($apiRequest);
        $productData = $product->toArray($request);

        return redirect()->route('products.index')->with('success', 'Продукт успешно создан');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $apiRequest = new UpdateProductRequest();
        $apiRequest->replace($request->all());


        if ($request->hasFile('photo')) {
            $apiRequest->files->set('photo', $request->file('photo'));
        }

        $updatedProduct = $this->apiProductController->update($apiRequest, $product);
        $updatedProductData = $updatedProduct->toArray($request);

        return redirect()->route('products.index')->with('success', 'Продукт успешно обновлен');
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт успешно удалён');
    }
}
