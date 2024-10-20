<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\View\AuthViewController;
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


Route::get('/', [AuthViewController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthViewController::class, 'login']);

Route::middleware('ensure.token.is.valid')->group(function () {
    Route::get('/products', [ProductViewController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductViewController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductViewController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductViewController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductViewController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductViewController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductViewController::class, 'destroy'])->name('products.destroy');
});
