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

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'blogs'], function () {
        Route::get('/', 'BlogController@get_blogs');
        Route::get('/{slug}', 'BlogController@detail_blogs');
    });
    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });
    Route::group(['prefix' => 'merchants'], function () {
        Route::get('/', 'MerchantController@get_merchants');
        Route::get('/{slug}', 'MerchantController@detail_merchants');
    });
    Route::group(['prefix' => 'events'], function () {
        Route::get('/', 'EventController@get_events');
        Route::get('/{slug}', 'EventController@detail_events');
        Route::post('/', 'EventController@store_log_events');
    });
    Route::group(['prefix' => 'log-event-histories'], function () {
        Route::get('/', 'LogEventHistoriesController@get_event_histories');
    });
});
