<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\CreateProductRequest;
use App\Models\ProductUserLike;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $product = Product::create($validated);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $path = $photo->store('products', 'public');

            $product->photo = asset('storage/' . $path);
        }

        $product->save();

        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('products', $photoName, 'public');
            $validated['photo'] = $photoPath;


            if (filter_var($product->photo, FILTER_VALIDATE_URL) === false && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
        }

        $product->update($validated);

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

    public function getSecretProducts()
    {
        $secretProducts = Product::where('is_secret', true)->get();
        return ProductResource::collection($secretProducts);
    }

    public function getSecretProductDetail($id)
    {
        $product = Product::where('is_secret', true)->findOrFail($id);
        return new ProductResource($product);
    }

    public function makeSecret($id)
    {
        $product = Product::findOrFail($id);
        $product->is_secret = true;
        $product->save();

        return response()->json(['message' => 'Product marked as secret successfully.']);
    }

    // Метод для снятия статуса секретного продукта
    public function makeNotSecret($id)
    {
        $product = Product::findOrFail($id);
        $product->is_secret = false;
        $product->save();

        return response()->json(['message' => 'Product unmarked as secret successfully.']);
    }


    public function getPopularProducts()
    {
        $popularProducts = Product::where('is_popular', true)->get();
        return ProductResource::collection($popularProducts);
    }

    public function getPopularProductDetail($id)
    {
        $popularProduct = Product::where('is_popular', true)->findOrFail($id);
        return new ProductResource($popularProduct);
    }

    public function getSpecialProducts()
    {
        $specialProducts = Product::where('is_special', true)->get();
        return ProductResource::collection($specialProducts);
    }

    public function getSpecialProductDetail($id)
    {
        $specialProduct = Product::where('is_special', true)->findOrFail($id);
        return new ProductResource($specialProduct);
    }

    public function makePopular($id)
    {
        $product = Product::findOrFail($id);
        $product->is_popular = true;
        $product->save();

        return response()->json(['message' => 'Product marked as popular successfully.']);
    }

    public function makeNotPopular($id)
    {
        $product = Product::findOrFail($id);
        $product->is_popular = false;
        $product->save();

        return response()->json(['message' => 'Product unmarked as popular successfully.']);
    }

    public function makeSpecial($id)
    {
        $product = Product::findOrFail($id);
        $product->is_special = true;
        $product->save();

        return response()->json(['message' => 'Product marked as special successfully.']);
    }

    public function makeNotSpecial($id)
    {
        $product = Product::findOrFail($id);
        $product->is_special = false;
        $product->save();

        return response()->json(['message' => 'Product unmarked as special successfully.']);
    }



}
