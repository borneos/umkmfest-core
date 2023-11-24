<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::middleware('auth')->group(function () {
        //Dashboard
        Route::get('/', function () {
            return view('home');
        });
        //Banners
        Route::get('/admin/banners', 'BannerController@index')->name('banners');
        Route::post('/admin/banners/store', 'BannerController@store')->name('banners.store');
        Route::get('/admin/banners/edit/{id}', 'BannerController@edit')->name('banners.edit');
        Route::post('/admin/banners/update', 'BannerController@update')->name('banners.update');
        Route::delete('/admin/banners/delete/{id}', 'BannerController@delete')->name('banners.delete');
        //Events
        Route::get('/admin/events', 'EventController@index')->name('events');
        Route::post('/admin/events/store', 'EventController@store')->name('events.store');
        Route::get('/admin/events/edit/{id}', 'EventController@edit')->name('events.edit');
        Route::post('/admin/events/update', 'EventController@update')->name('events.update');
        Route::delete('/admin/events/delete/{id}', 'EventController@delete')->name('events.delete');
        //Merchants
        Route::get('/admin/merchants', 'MerchantController@index')->name('merchants');
        Route::post('/admin/merchants/store', 'MerchantController@store')->name('merchants.store');
        Route::get('/admin/merchants/edit/{id}', 'MerchantController@edit')->name('merchants.edit');
        Route::post('/admin/merchants/update', 'MerchantController@update')->name('merchants.update');
        Route::delete('/admin/merchants/delete/{id}', 'MerchantController@delete')->name('merchants.delete');
        //Games
        Route::get('/admin/games', 'GameController@index')->name('games');
        Route::post('/admin/games/store', 'GameController@store')->name('games.store');
        Route::get('/admin/games/edit/{id}', 'GameController@edit')->name('games.edit');
        Route::post('/admin/games/update', 'GameController@update')->name('games.update');
        Route::delete('/admin/games/delete/{id}', 'GameController@delete')->name('games.delete');
    });
});
