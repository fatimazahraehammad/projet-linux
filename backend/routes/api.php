<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FavoriteController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'SAYIF Collection API',
    ]);
});

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::post('/orders', [OrderController::class, 'store']);

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);