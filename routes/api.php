<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\TestedProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::prefix('auth')->group(function () {
    Route::post('/send-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/verify-code-and-register', [AuthController::class, 'completeRegistration']);
    Route::post('/send-code-auth', [AuthController::class, 'sendVerificationCodeAuth']);
    Route::post('/verify-code-and-auth', [AuthController::class, 'loginWithVerificationCode']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::prefix('products')->group(function () {
        Route::post('/create', [ProductController::class, 'create']);
        Route::put('/{product}/update', [ProductController::class, 'update']);
        Route::delete('/{product}/delete', [ProductController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::post('/create', [CategoryController::class, 'create']);
        Route::put('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
    });
});

Route::middleware('check.admin.creation')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/create-admin', [UserController::class, 'createAdmin']);
    });
});

Route::middleware(['auth:api'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('users')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'store']);
        Route::get('/CurrentUser', [AuthController::class, 'getCurrentUser']);
        Route::get('/{id}/show', [UserController::class, 'show']);
        Route::put('/{id}/update', [UserController::class, 'update']);
        Route::delete('/{id}/delete', [UserController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/index', [ProductController::class, 'index']);
        Route::get('/tested-products', [ProductController::class, 'getTestedProducts']);
        Route::get('/{product}/show', [ProductController::class, 'show']);
        Route::post('/{product}/like', [ProductController::class, 'like']);
        Route::post('/{product}/dislike', [ProductController::class, 'dislike']);
        Route::get('/{product}/reviews', [ProductController::class, 'getProductReviews']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategoryController::class, 'index']);
        Route::get('/{category}/show', [CategoryController::class, 'show']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/index', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'store']);
        Route::get('/userOrders', [OrderController::class, 'userOrders']);
        Route::get('/{order}/show', [OrderController::class, 'show']);
        Route::put('/{order}/update', [OrderController::class, 'update']);
        Route::delete('/{order}/delete', [OrderController::class, 'destroy']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/index', [ReviewController::class, 'index']);
        Route::post('/create', [ReviewController::class, 'store']);
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::post('/{review}/like', [ReviewController::class, 'like']);
        Route::post('/{review}/dislike', [ReviewController::class, 'dislike']);
        Route::put('/{review}/update', [ReviewController::class, 'update']);
        Route::delete('/{review}/delete', [ReviewController::class, 'destroy']);
        Route::post('/{review}/media/upload', [ReviewController::class, 'uploadMedia'])->name('reviews.media.upload');
    });

    Route::prefix('sample-requests')->group(function () {
        Route::get('/', [SampleRequestController::class, 'index']);
        Route::post('/', [SampleRequestController::class, 'store']);
        Route::get('/userSamples', [SampleRequestController::class, 'userSamples']);
        Route::put('/update-delivery-status', [SampleRequestController::class, 'updateDeliveryStatusForExpiredOrders']);
        Route::get('/{sampleRequest}', [SampleRequestController::class, 'show']);
        Route::put('/{sampleRequest}', [SampleRequestController::class, 'update']);
        Route::delete('/{sampleRequest}', [SampleRequestController::class, 'destroy']);
    });

    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::get('/{question}', [QuestionController::class, 'show']);
        Route::put('/{question}', [QuestionController::class, 'update']);
        Route::delete('/{question}', [QuestionController::class, 'destroy']);
    });

    Route::get('/token-ttl', [AuthController::class, 'getTokenTTL']);

    Route::get('sample-requests/expected', [SampleRequestController::class, 'expectedSamples']);
    Route::get('sample-requests/completed', [SampleRequestController::class, 'completedSamples']);

});
