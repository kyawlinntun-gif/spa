<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// // Get all Products
// Route::get('/product', [ProductController::class, 'index']);
// // Store the product
// Route::post('/product', [ProductController::class, 'store']);
// // Show the product
// Route::get('/product/{product}', [ProductController::class, 'show']);
// // Put or Patch the product
// Route::match(['put', 'patch'], '/product/{product}', [ProductController::class, 'update']);
// // Delete the product
// Route::delete('product/{product}', [ProductController::class, 'destroy']);

// Product resource
Route::apiResource('/product', ProductController::class);
