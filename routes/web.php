<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


    Route::get('/', function () {
        return view('auth.login');
    })->middleware('guest');

    Route::get('/home', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('auth');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register')->middleware('guest');

    // group middleware
    Route::group(['middleware' => ['auth']], function () {
        // group prefix category
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::post('/', [CategoryController::class, 'store'])->name('category.store');
            Route::post('/get-category-detail', [CategoryController::class, 'getCategoryDetail'])->name('category.getCategoryDetail');
            Route::patch('/update', [CategoryController::class, 'update'])->name('category.update');
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        });

        // group prefix product
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::post('/', [ProductController::class, 'store'])->name('product.store');
            Route::post('/get-product-detail', [ProductController::class, 'getProductDetail'])->name('product.getProductDetail');
            Route::patch('/update', [ProductController::class, 'update'])->name('product.update');
            Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        });
    });
