<?php

use App\Http\Controllers\OrderController;
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
        Route::post('/create', [UserController::class, 'create']);
        Route::put('/{id}/update', [UserController::class, 'update']);
        Route::delete('/{id}/delete', [UserController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/index', [ProductController::class, 'index']);
        Route::post('/create', [ProductController::class, 'create']);
        Route::put('/{product}/update', [ProductController::class, 'update']);
        Route::delete('/{product}/delete', [ProductController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategoryController::class, 'index']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::put('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/index', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'store']);
        Route::put('/{order}/update', [OrderController::class, 'update']);
        Route::delete('/{order}/delete', [OrderController::class, 'destroy']);
    });

});
