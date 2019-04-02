<?php

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
    return view('auth.login');
});

Auth::routes();

Route::get('reset/password/api', 'Auth\ApiResetPassword')->name('api.reset.password');

Route::group(['namespace'=>'Admin', 'middleware'=>['auth']], function () {
    Route::get('/home',         'AdminController@index')->name('home');
    Route::resource('updates',  'UpdatesController');
    Route::get('/data/updates', 'UpdatesController@data')->name('data_updates');
});
