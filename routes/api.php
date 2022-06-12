<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWT\JWTController;
use App\Http\Controllers\API\Admin\HomeController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductStockController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\SellerController;
use App\Http\Controllers\API\UnitController;

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    // Route::post('/profile', [JWTController::class, 'profile']);

    /* -------- Product Related Routes -------- */
    Route::get('product', [ProductController::class, 'index']);
    Route::get('product/{id}', [ProductController::class, 'getById']);
    Route::get('product/delete/{id}', [ProductController::class, 'delete']);
    Route::post('product/store', [ProductController::class, 'store']);
    Route::post('product/update', [ProductController::class, 'update']);

    /* -------- Unit Related Routes  -------- */
    Route::get('unit', [UnitController::class, 'index']);
    Route::get('unit/{id}', [UnitController::class, 'getById']);
    Route::post('unit/update', [UnitController::class, 'update']);
    Route::post('unit/store', [UnitController::class, 'store']);
    Route::delete('unit/delete/{id}', [UnitController::class, 'delete']);

    /* -------- Customer Related Routes -------- */
    Route::get('customer', [CustomerController::class, 'index']);
    Route::get('customer/{id}', [CustomerController::class, 'getById']);
    Route::post('customer/store', [CustomerController::class, 'store']);
    Route::post('customer/update', [CustomerController::class, 'update']);
    Route::delete('customer/delete/{id}', [CustomerController::class, 'delete']);

    /* -------- Product Stock Related Routes -------- */
    Route::get('productstock', [ProductStockController::class, 'index']);
    Route::get('productstock/{id}', [ProductStockController::class, 'getById']);
    Route::post('productstock/store', [ProductStockController::class, 'store']);
    Route::post('productstock/update', [ProductStockController::class, 'update']);
    Route::delete('productstock/delete/{id}', [ProductStockController::class, 'delete']);

    /* -------- Seller Related Routes -------- */
    Route::get('seller', [SellerController::class, 'index']);
    Route::get('seller/{id}', [SellerController::class, 'getById']);
    Route::post('seller/store', [SellerController::class, 'store']);
    Route::post('seller/update', [SellerController::class, 'update']);
    Route::delete('seller/delete/{id}', [SellerController::class, 'delete']);

    /* ------- Other routes -------- */
    Route::post('product-create', [ProductController::class, 'productSave']);
    Route::post('product-purchase', [PurchaseController::class, 'purchase']);
});

/**
 * Protected routes
 */

Route::group(['middleware' => ['jwt.verify']], function () {

    //     Route::post('logout', 'AuthController@logout');
    //     Route::post('refresh', 'AuthController@refresh');
    Route::post('/profile', [JWTController::class, 'profile']);
});

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
