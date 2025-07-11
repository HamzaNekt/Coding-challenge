<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);      // GET /api/products
    Route::post('/', [ProductController::class, 'store']);     // POST /api/products
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);     // GET /api/categories
});