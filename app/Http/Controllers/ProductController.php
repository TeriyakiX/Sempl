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
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('products', $photoName, 'public');
            $validated['photo'] = $photoName;
        } else {
            $defaultImages = [
                'https://cdn.discordapp.com/attachments/956148678475776000/1251191124794540074/image.png?ex=666dae0a&is=666c5c8a&hm=ead55e6019a94a5174cd149cb4a0b41e02a03d0b3688868e04d605cf3b00c616&',
                'https://cdn.discordapp.com/attachments/956148678475776000/1251191370673033359/image.png?ex=666dae44&is=666c5cc4&hm=a8dfea379f6fe448d8049ce0525b8724809583251efd524cd3b54c4ed53d7e92&',
                'https://cdn.discordapp.com/attachments/956148678475776000/1251191508766425129/image.png?ex=666dae65&is=666c5ce5&hm=879a805f560cd378ad539c4f52dda3b8f732654318e33197e8931508323090a0&'
            ];

            $randomIndex = array_rand($defaultImages);
            $validated['photo'] = $defaultImages[$randomIndex];
        }

        $product = Product::create($validated);

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
