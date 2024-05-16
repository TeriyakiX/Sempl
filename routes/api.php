<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('check.admin.creation')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/create-admin', [UserController::class, 'createAdmin']);
    });
});

Route::middleware(['auth:api', 'admin'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('users')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'store']);
        Route::get('/{id}/show', [UserController::class, 'show']);
        Route::put('/{id}/update', [UserController::class, 'update']);
        Route::delete('/{id}/delete', [UserController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/index', [ProductController::class, 'index']);
        Route::post('/create', [ProductController::class, 'create']);
        Route::get('/{product}/show', [ProductController::class, 'show']);
        Route::put('/{product}/update', [ProductController::class, 'update']);
        Route::delete('/{product}/delete', [ProductController::class, 'destroy']);
        Route::get('/{product}/reviews', [ProductController::class, 'getProductReviews']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategoryController::class, 'index']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::get('/{category}/show', [CategoryController::class, 'show']);
        Route::put('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/index', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'store']);
        Route::get('/{order}/show', [OrderController::class, 'show']);
        Route::put('/{order}/update', [OrderController::class, 'update']);
        Route::delete('/{order}/delete', [OrderController::class, 'destroy']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/index', [ReviewController::class, 'index']);
        Route::post('/create', [ReviewController::class, 'store']);
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::put('/{review}/update', [ReviewController::class, 'update']);
        Route::delete('/{review}/delete', [ReviewController::class, 'destroy']);
        Route::post('/{review}/media/upload', [ReviewController::class, 'uploadMedia'])->name('reviews.media.upload');
    });

});
