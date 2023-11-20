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
        Route::get('/admin/banners', 'BannersController@index')->name('banners');
        Route::post('/admin/banners/store', 'BannersController@store')->name('banners.store');
        Route::get('/admin/banners/edit/{id}', 'BannersController@edit')->name('banners.edit');
        Route::post('/admin/banners/update', 'BannersController@update')->name('banners.update');
        Route::delete('/admin/banners/delete/{id}', 'BannersController@delete')->name('banners.delete');
    });
});
