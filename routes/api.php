<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)->group(function () {
    Route::post('/user/register', 'register')->middleware('guest');
    Route::post('/user/login', 'login')->middleware('guest');
    Route::post('/user/forgot-password', 'forgotPassword')->middleware('guest');
    Route::post('/user/reset-password', 'resetPassword')->middleware('guest');

    Route::get('/user/profile', 'getProfile')->middleware('auth:sanctum');
    Route::post('/user/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/user/profile/basic', 'updateBasic')->middleware('auth:sanctum');
    Route::post('/user/profile/billing-address', 'updateBillingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/shipping-address', 'updateShippingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/password', 'changePassword')->middleware('auth:sanctum');
});

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload/avatar', 'uploadAvatar')->middleware('auth:sanctum');
    Route::post('/upload/product', 'uploadProduct')->middleware('auth:sanctum');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'listProduct')->middleware('auth:sanctum');
    Route::get('/product/{id}', 'getProduct')->middleware('auth:sanctum');
    Route::post('/product', 'addProduct')->middleware('auth:sanctum');
    Route::post('/product/{id}', 'editProduct')->middleware('auth:sanctum');
    Route::delete('/product/{id}', 'deleteProduct')->middleware('auth:sanctum');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'listCategory')->middleware('auth:sanctum');
    Route::post('/category', 'addCategory')->middleware('auth:sanctum');
    Route::post('/category/{id}', 'editCategory')->middleware('auth:sanctum');
    Route::delete('/category/{id}', 'deleteCategory')->middleware('auth:sanctum');
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