<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\ProductDetailsController;
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\SliderController;
use App\Models\ProductOrderModel;

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


Route::post('/loginByMobile', [OTPController::class, 'loginByMobile']);


Route::get('/CategoryListData', [CategoryController::class, 'CategoryListData']);
Route::get('/SendSliderInfo', [SliderController::class, 'sliderInfo']);
Route::get('/categoryGetData', [CategoryController::class, 'categoryGetData']);
Route::get('/SendSiteInfo', [SiteInfoController::class, 'GetSiteInfoDetails']);


Route::get('/ProductShortListByRemark/{remark}', [ProductListController::class, 'productListByRemark']);
Route::get('/ProductDetailsByCode/{code}', [ProductDetailsController::class, 'productDetails']);

Route::post('/addToCart', [ProductCartController::class, 'addToCart']);
Route::get('/CartCount/{mobile}', [ProductCartController::class, 'CartCount']);
Route::get('/CartDetails/{mobile}', [ProductCartController::class, 'CartDetails']);
Route::get('/CartItemPlus/{id}/{quantity}/{price}', [ProductCartController::class, 'CartItemPlus']);
Route::get('/CartItemMinus/{id}/{quantity}/{price}', [ProductCartController::class, 'CartItemMinus']);
Route::get('/RemoveCartList/{id}', [ProductCartController::class, 'RemoveCartList']);


Route::post('/Order', [ProductCartController::class, 'PostOrder']);
Route::get('/OrderListByUser/{mobile}', [ProductOrderController::class, 'OrderListByUser']);

Route::post('/addFav/{code}/{mobile}', [FavouriteController::class, 'addFav']);
Route::get('/favList/{mobile}', [FavouriteController::class, 'favList']);
