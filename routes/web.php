<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('api')->group(function () {

    Route::prefix('categories')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::post('', [CategoryController::class, 'store']);
    });

    Route::prefix('product')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::get('{id}', [ProductController::class, 'show']);
        Route::post('', [ProductController::class, 'store']);
    });

});

