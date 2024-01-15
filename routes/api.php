<?php

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\RegisterController;


Route::post('auth/login', LoginController::class);
Route::post('auth/register', RegisterController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', LogoutController::class);

    // get user profile
    Route::get('auth/me', function (Request $request) {
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $request->user()
        ], 200);
    });

    // categories
    Route::get('categories', [CategoryController::class, 'index']);

    // products
    Route::get('products', [ProductController::class, 'index']);
    // getProductByCategory
    Route::get('products/category/{id}', [ProductController::class, 'getProductByCategory']);
});
