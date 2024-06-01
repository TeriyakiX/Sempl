<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\CreateProductRequest;
use App\Models\ProductUserLike;
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

    public function getTestedProducts(Request $request)
    {
        $user = auth()->user();
        $testedProducts = Product::whereHas('reviews', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('is_tested', true)->get();

        return ProductResource::collection($testedProducts);
    }

    public function like(Product $product)
    {
        $user = auth()->user();
        $existingLike = $product->userLikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            if ($existingLike->like) {
                return response()->json(['error' => 'You have already liked this product.'], 400);
            } else {
                $existingLike->like = true;
                $existingLike->save();
            }
        } else {
            ProductUserLike::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'like' => true
            ]);
        }

        return response()->json(['message' => 'Product liked successfully.']);
    }

    public function dislike(Product $product)
    {
        $user = auth()->user();
        $existingLike = $product->userLikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            if (!$existingLike->like) {
                return response()->json(['error' => 'You have already disliked this product.'], 400);
            } else {
                $existingLike->like = false;
                $existingLike->save();
            }
        } else {
            ProductUserLike::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'like' => false
            ]);
        }

        return response()->json(['message' => 'Product disliked successfully.']);
    }

}
