<?php

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

/**
 * Unit
 */
Route::get('/get-all-unit','API\UnitController@index');

/**
 * Account
 */
Route::get('/get-all-account','API\AccountController@index');
Route::get('/get-account-byId/{id}','API\AccountController@getItem');
Route::get('/delete-account/{id}','API\AccountController@delete');
Route::post('/create-account','API\AccountController@create');
Route::post('/edit-account','API\AccountController@edit');
Route::post('/edit-account','API\AccountController@edit');