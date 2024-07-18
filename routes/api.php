<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SampleRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductFeedbackController;
use App\Http\Controllers\ProductController;

    Route::prefix('auth')->group(function () {
        Route::post('/send-code', [AuthController::class, 'sendVerificationCode']); // Send verification code
        Route::post('/verify-code', [AuthController::class, 'verifyCode']); // Verify code
        Route::post('/verify-code-and-register', [AuthController::class, 'completeRegistration']); // Complete registration
        Route::post('/send-code-auth', [AuthController::class, 'sendVerificationCodeAuth']); // Send code for authentication
        Route::post('/verify-code-and-auth', [AuthController::class, 'loginWithVerificationCode']); // Verify code and authenticate
        Route::post('/login', [AuthController::class, 'login']); // Login
    });

    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::prefix('products')->group(function () {
            Route::post('/create', [ProductController::class, 'create']); // Create a new product
            Route::post('/{product}/update', [ProductController::class, 'update']); // Update a product
            Route::delete('/{product}/delete', [ProductController::class, 'destroy']); // Delete a product
        });

        Route::prefix('categories')->group(function () {
            Route::post('/create', [CategoryController::class, 'create']); // Create a new category
            Route::put('/{category}/update', [CategoryController::class, 'update']); // Update a category
            Route::delete('/{category}/delete', [CategoryController::class, 'destroy']); // Delete a category
        });
    });

    Route::middleware('check.admin.creation')->group(function () {
        Route::prefix('users')->group(function () {
            Route::post('/create-admin', [UserController::class, 'createAdmin']); // Create admin user
        });
    });

    Route::middleware(['auth:api'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']); // Logout

        Route::prefix('users')->group(function () {
            Route::get('/index', [UserController::class, 'index']); // Get all users
            Route::post('/create', [UserController::class, 'store']); // Create a new user
            Route::get('/CurrentUser', [AuthController::class, 'getCurrentUser']); // Get current user
            Route::get('/{id}/show', [UserController::class, 'show']); // Get user by ID
            Route::put('/{id}/update', [UserController::class, 'update']); // Update user by ID
            Route::put('/{id}/profile', [UserController::class, 'profile']); // Update user profile
            Route::delete('/{id}/delete', [UserController::class, 'destroy']); // Delete user by ID
            Route::post('/profile/update', [UserController::class, 'updateProfile']); // Update current user profile
            Route::get('/orders', [UserController::class, 'userOrders']); // Get user orders
        });

        Route::prefix('products')->group(function () {
            Route::get('/index', [ProductController::class, 'index']); // Get all products
            Route::get('/tested-products', [ProductController::class, 'getTestedProducts']); // Get tested products
            Route::get('/{product}/show', [ProductController::class, 'show']); // Get product by ID
            Route::post('/{product}/like', [ProductController::class, 'like']); // Like a product
            Route::post('/{product}/dislike', [ProductController::class, 'dislike']); // Dislike a product
            Route::get('/{product}/feedbacks', [ProductFeedbackController::class, 'getProductReviews']); // Get product feedbacks
        });

        Route::prefix('categories')->group(function () {
            Route::get('/index', [CategoryController::class, 'index']); // Get all categories
            Route::get('/{category}/show', [CategoryController::class, 'show']); // Get category by ID
            Route::post('/search', [CategoryController::class, 'searchProductsByCategories']); // Search products by categories
        });

        Route::prefix('orders')->group(function () {
            Route::get('/index', [OrderController::class, 'index']); // Get all orders
            Route::post('/create', [OrderController::class, 'store']); // Create a new order
            Route::get('/userOrders', [OrderController::class, 'userOrders']); // Get user's orders
            Route::get('/{order}/show', [OrderController::class, 'show']); // Get order by ID
            Route::put('/{order}/update', [OrderController::class, 'update']); // Update order by ID
            Route::delete('/{order}/delete', [OrderController::class, 'destroy']); // Delete order by ID
        });

        Route::prefix('reviews')->group(function () {
            Route::get('/index', [ReviewController::class, 'index']); // Get all reviews
            Route::post('/create', [ReviewController::class, 'store']); // Create a new review
            Route::get('/{review}', [ReviewController::class, 'show']); // Get review by ID
            Route::post('/{review}/like', [ReviewController::class, 'like']); // Like a review
            Route::post('/{review}/dislike', [ReviewController::class, 'dislike']); // Dislike a review
            Route::put('/{review}/update', [ReviewController::class, 'update']); // Update review by ID
            Route::delete('/{review}/delete', [ReviewController::class, 'destroy']); // Delete review by ID
            Route::post('/{review}/media/upload', [ReviewController::class, 'uploadMedia'])->name('reviews.media.upload'); // Upload media for review
        });

        Route::prefix('sample-requests')->group(function () {
            Route::get('/', [SampleRequestController::class, 'index']); // Get all sample requests
            Route::post('/', [SampleRequestController::class, 'store']); // Create a new sample request
            Route::get('/userSamples', [SampleRequestController::class, 'userSamples']); // Get user's sample requests
            Route::put('/update-delivery-status', [SampleRequestController::class, 'updateDeliveryStatusForExpiredOrders']); // Update delivery status for expired orders
            Route::get('/{sampleRequest}', [SampleRequestController::class, 'show']); // Get sample request by ID
            Route::put('/{sampleRequest}', [SampleRequestController::class, 'update']); // Update sample request by ID
            Route::delete('/{sampleRequest}', [SampleRequestController::class, 'destroy']); // Delete sample request by ID
        });

        Route::prefix('questions')->group(function () {
            Route::get('/', [QuestionController::class, 'index']); // Get all questions
            Route::post('/', [QuestionController::class, 'store']); // Create a new question
            Route::get('/{question}', [QuestionController::class, 'show']); // Get question by ID
            Route::put('/{question}', [QuestionController::class, 'update']); // Update question by ID
            Route::delete('/{question}', [QuestionController::class, 'destroy']); // Delete question by ID
        });

        Route::get('/token-ttl', [AuthController::class, 'getTokenTTL']); // Get token TTL

        Route::get('sample-requests/expected', [SampleRequestController::class, 'expectedSamples']); // Get expected sample requests
        Route::get('sample-requests/completed', [SampleRequestController::class, 'completedSamples']); // Get completed sample requests

        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'viewCart']); // View cart
            Route::post('/add', [CartController::class, 'addToCart']); // Add to cart
            Route::delete('/{id}', [CartController::class, 'removeFromCart']); // Remove from cart
            Route::post('/checkout', [CartController::class, 'checkout']); // Checkout
        });


        Route::get('/admin/orders', [AdminController::class, 'index']);
        Route::put('/admin/orders/{purchase}', [AdminController::class, 'updateStatus']);

        Route::post('product-feedbacks/{product_feedback}/like', [ProductFeedbackController::class, 'like']);
        Route::post('product-feedbacks/{product_feedback}/dislike', [ProductFeedbackController::class, 'dislike']);



        Route::prefix('product-feedbacks')->group(function () {
            Route::get('/', [ProductFeedbackController::class, 'index']); // Get all feedbacks
            Route::post('/', [ProductFeedbackController::class, 'store']); // Create a new feedback
            Route::get('/{product_feedback}', [ProductFeedbackController::class, 'show']); // Get feedback by ID
            Route::post('/{product_feedback}/like', [ProductFeedbackController::class, 'like']); // Like a feedback
            Route::post('/{product_feedback}/dislike', [ProductFeedbackController::class, 'dislike']); // Dislike a feedback
            Route::put('/{product_feedback}/update', [ProductFeedbackController::class, 'update']); // Update feedback by ID
            Route::delete('/{product_feedback}/delete', [ProductFeedbackController::class, 'destroy']); // Delete feedback by ID
        });



        Route::prefix('questions')->group(function () {
            Route::get('/', [QuestionController::class, 'index']); // Получение всех вопросов
            Route::post('/create', [QuestionController::class, 'store']); // Создание нового вопроса
        });

        Route::get('/product/{product}/feedback-questions', [ProductController::class, 'getFeedbackQuestions']); // Получение вопросов для отзыва о продукте
    });

