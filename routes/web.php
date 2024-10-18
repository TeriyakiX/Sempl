<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\View\ProductViewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductViewController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductViewController::class, 'create'])->name('products.create');
Route::get('/products/{product}', [ProductViewController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductViewController::class, 'edit'])->name('products.edit');
Route::delete('/products/{product}', [ProductViewController::class, 'destroy'])->name('products.destroy');
