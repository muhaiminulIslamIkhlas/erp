<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWT\JWTController;
use App\Http\Controllers\API\Admin\HomeController;
use App\Http\Controllers\API\ProductController;

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    // Route::post('/profile', [JWTController::class, 'profile']);
    Route::get('product', [ProductController::class, 'index']);
    Route::get('product/{id}', [ProductController::class, 'getById']);
    Route::get('product/delete/{id}', [ProductController::class, 'delete']);
    Route::post('product/store', [ProductController::class, 'store']);
    Route::post('product/update', [ProductController::class, 'update']);
});

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
