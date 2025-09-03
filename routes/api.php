<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Product Routes
Route::get('/products/categories', [ProductController::class, 'categories']);

Route::get('/products', [ProductController::class, 'index']);        
Route::post('/products', [ProductController::class, 'store']);        
Route::get('/products/{product}', [ProductController::class, 'show']); 
Route::put('/products/{product}', [ProductController::class, 'update']); 
Route::delete('/products/{product}', [ProductController::class, 'destroy']);

// Video Routes
Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index']);
    Route::get('/categories', [VideoController::class, 'categories']);
    Route::get('/{video}', [VideoController::class, 'show']);
});