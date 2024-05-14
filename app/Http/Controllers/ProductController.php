<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function create(CreateProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function getProductReviews(Request $request, $product)
    {
        $product = Product::findOrFail($product);
        $perPage = $request->has('per_page') ? intval($request->per_page) : 10;
        $reviews = $product->reviews()->paginate($perPage);

        return ReviewResource::collection($reviews);
    }

}
