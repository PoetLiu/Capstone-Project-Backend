<?php

use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    Route::post('/user/profile/avatar', 'updateAvatar')->middleware('auth:sanctum');
    Route::post('/user/profile/basic', 'updateBasic')->middleware('auth:sanctum');
    Route::post('/user/profile/billing-address', 'updateBillingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/shipping-address', 'updateShippingAddress')->middleware('auth:sanctum');
    Route::post('/user/profile/password', 'changePassword')->middleware('auth:sanctum');
});

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload/avatar', 'uploadAvatar')->middleware('auth:sanctum');
    Route::post('/upload/product', 'uploadProduct')->middleware('auth:sanctum');

});