<?php

use App\Http\Controllers\ProductController;
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

Route::get('/products', 'ProductViewController@index')->name('products.index');
Route::get('/products/create', 'ProductViewController@create')->name('products.create');
Route::get('/products/{product}', 'ProductViewController@show')->name('products.show');
Route::get('/products/{product}/edit', 'ProductViewController@edit')->name('products.edit');
