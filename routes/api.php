<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
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

Route::group(['prefix' => 'v1'], function () {

    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login');
    //cart apis
    Route::apiResource('cart', CartController::class)->except(['show']);

    //get product list without authentication
    Route::get('products', [ProductController::class, 'index']);

    //auth group
    Route::group(['middleware' => 'auth:api'], function () {
        //product apis
        Route::apiResource('products', ProductController::class)->except(['index', 'update']);
        Route::get('category', [CategoryController::class, 'index']);
    });
});
