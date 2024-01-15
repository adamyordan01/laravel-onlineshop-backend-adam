<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


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
    });
