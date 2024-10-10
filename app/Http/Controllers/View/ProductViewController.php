<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;

class ProductViewController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create(Request $request)
    {
        return view('products.create');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


}
