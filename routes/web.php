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

Route::view('/upload-image', 'create_product'); // Ваш маршрут для отображения формы

Route::post('/upload-photo', [UserController::class, 'uploadPhoto'])->name('upload.photo'); // Маршрут для обработки загрузки фотографии
