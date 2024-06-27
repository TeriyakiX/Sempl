<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        return CategoryResource::collection($categories);
    }

    public function create(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $category = new Category([
            'name' => $data['name'],
        ]);
        if (isset($data['parent_id'])) {
            $parentCategory = Category::find($data['parent_id']);
            if (!$parentCategory) {
                return response()->json(['error' => 'Родительская категория не найдена'], 404);
            }
            $category->parent()->associate($parentCategory);
        }
        $category->save();

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $category->load('subcategories');
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    public function searchProductsByCategories(Request $request)
    {
        $categoryIds = $request->input('category_ids');

        $products = Product::whereHas('category', function ($query) use ($categoryIds) {
            $query->whereIn('id', $categoryIds);
        })->get();

        return ProductResource::collection($products);
    }

}
