<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)->group(function () {
    Route::post('/user/register', 'add')->middleware('guest');
    Route::post('/user/login', 'login')->middleware('guest');
    Route::post('/user/forgot-password', 'forgotPassword')->middleware('guest');
    Route::post('/user/reset-password', 'resetPassword')->middleware('guest');

    Route::get('/user/profile', 'getProfile')->middleware('auth:sanctum');
    Route::post('/user/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/user/profile/basic', 'updateBasic')->middleware('auth:sanctum');
    Route::post('/user/profile/billing-address', 'updateBillingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/shipping-address', 'updateShippingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/password', 'changePassword')->middleware('auth:sanctum');


    Route::get('/user', 'list')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
});

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload/avatar', 'uploadAvatar')->middleware('auth:sanctum');
    Route::post('/upload/product', 'uploadProduct')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'listProduct')->middleware('auth:sanctum');
    Route::get('/product/{id}', 'getProduct')->middleware('auth:sanctum');

    Route::post('/product', 'addProduct')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
    Route::post('/product/{id}', 'editProduct')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
    Route::delete('/product/{id}', 'deleteProduct')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'listCategory')->middleware('auth:sanctum');
    Route::post('/category', 'addCategory')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
    Route::post('/category/{id}', 'editCategory')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
    Route::delete('/category/{id}', 'deleteCategory')->middleware(['auth:sanctum', EnsureUserIsAdmin::class]);
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/review', 'newReview')->middleware('auth:sanctum');
    Route::get('/review', 'listReview')->middleware('auth:sanctum');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'listCart')->middleware('auth:sanctum');
    Route::post('/cart', 'addToCart')->middleware('auth:sanctum');
    Route::post('/cart/{id}', 'editCartItem')->middleware('auth:sanctum');
    Route::delete('/cart/{id}', 'removeCartItem')->middleware('auth:sanctum');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/order/checkout', 'checkout')->middleware('auth:sanctum');
});

Route::controller(CouponController::class)->group(function () {
    Route::post('/coupon/validate', 'validate')->middleware('auth:sanctum');
});

