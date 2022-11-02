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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
/**
 * Login
 */
Route::post('/login', 'API\AuthController@login');

Route::group(['middleware' => 'auth:api'], function () {

    /**
     * Logout
     */
    Route::post('/logout', 'API\AuthController@logout');

    /**
     * Customer
     * Front end done
     */
    Route::get('/get-all-customer', 'API\CustomerController@index');
    Route::get('/get-all-customer-select', 'API\CustomerController@getAll');
    Route::get('/get-customer-byId/{id}', 'API\CustomerController@getItem');
    Route::post('/store-customer', 'API\CustomerController@store');
    Route::post('/edit-customer', 'API\CustomerController@edit');
    Route::get('/delete-customer/{id}', 'API\CustomerController@delete');
    Route::get('/get-previous-due/{id}', 'API\CustomerController@getPreviousDue');

    /**
     * Product
     */
    Route::get('/get-all-product', 'API\ProductController@index');
    Route::get('/get-all-product-raw', 'API\ProductController@getAllRaw');
    Route::get('/get-all-product-select', 'API\ProductController@getAll');
    Route::get('/get-product-byId/{id}', 'API\ProductController@getItem');
    Route::post('/store-product', 'API\ProductController@store');
    Route::post('/edit-product', 'API\ProductController@edit');
    Route::get('/delete-product/{id}', 'API\ProductController@delete');
    Route::get('/product-edit/{id}', 'API\ProductController@getProductById');

    /**
     * Unit
     * Front end done
     */
    Route::get('/get-all-unit-select', 'API\UnitController@getAll');
    Route::get('/get-all-unit', 'API\UnitController@index');
    Route::get('/get-unit-byId/{id}', 'API\UnitController@getItem');
    Route::post('/edit-unit', 'API\UnitController@update');
    Route::post('/store-unit', 'API\UnitController@store');
    Route::post('/unit-search', 'API\UnitController@search');
    Route::get('/delete-unit/{id}', 'API\UnitController@delete');

    /**
     * Account
     * Front end done
     */
    Route::get('/get-all-account', 'API\AccountController@index');
    Route::get('/get-all-account-select', 'API\AccountController@getAll');
    Route::get('/get-account-byId/{id}', 'API\AccountController@getItem');
    Route::get('/delete-account/{id}', 'API\AccountController@delete');
    Route::post('/create-account', 'API\AccountController@create');
    Route::post('/edit-account', 'API\AccountController@edit');
    Route::get('/get-total-balance', 'API\AccountController@getTotalBalance');
    Route::post('/add-amount', 'API\AccountController@addAmount');
    Route::post('/widthdraw-amount', 'API\AccountController@widthdrawAmount');
    Route::get('/get-all-transection', 'API\AccountController@getAllTransection');

    /**
     * Brand
     * Front end done
     */
    Route::get('/get-all-brand', 'API\BrandController@index');
    Route::post('/create-brand', 'API\BrandController@create');
    Route::post('/edit-brand', 'API\BrandController@edit');
    Route::get('/delete-brand/{id}', 'API\BrandController@delete');
    Route::get('/get-brand-byId/{id}', 'API\BrandController@getItem');
    Route::get('/get-all-brand-select', 'API\BrandController@getAll');

    /**
     * Category
     * Front-end done
     */
    Route::get('/get-all-category', 'API\CategoryController@index');
    Route::get('/get-all-category-select', 'API\CategoryController@getAll');
    Route::post('/create-category', 'API\CategoryController@create');
    Route::post('/edit-category', 'API\CategoryController@edit');
    Route::get('/delete-category/{id}', 'API\CategoryController@delete');
    Route::get('/get-category-byId/{id}', 'API\CategoryController@getItem');

    /**
     * Supplier
     */
    Route::get('/get-all-supplier', 'API\SupplierController@index');
    Route::get('/get-all-supplier-select', 'API\SupplierController@getAll');
    Route::post('/create-supplier', 'API\SupplierController@create');
    Route::post('/edit-supplier', 'API\SupplierController@edit');
    Route::get('/delete-supplier/{id}', 'API\SupplierController@delete');
    Route::get('/get-supplier-byId/{id}', 'API\SupplierController@getItem');

    /**
     * Purchase
     */
    Route::post('/make-purchase', 'API\PurchaseController@makePurchase');
    Route::get('/get-all-purchase', 'API\PurchaseController@index');
    Route::get('/get-purchase/{id}', 'API\PurchaseController@getById');

    /**
     * Purchase
     */
    Route::post('/sell', 'API\PosController@sell');
});
