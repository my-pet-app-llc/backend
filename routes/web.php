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
    Route::get('/home',             'AdminController@index')->name('home');

    Route::resource('updates',      'UpdatesController');
    Route::get('/data/updates',     'UpdatesController@data')->name('data_updates');

    Route::resource('materials',    'MaterialsController');
    Route::get('/data/materials',   'MaterialsController@data')->name('data_materials');

    Route::get('/users',            'UsersController@index')->name('users.index');
    Route::get('/users/{user}',     'UsersController@show')->name('users.show');
    Route::get('/data/users',       'UsersController@data')->name('data_users');
    Route::get('/users/ban/{user}', 'UsersController@userBan')->name('users.ban');

    Route::get('/tickets',          'TicketsController@index')->name('tickets.index');
    Route::get('/tickets/{ticket}', 'TicketsController@show')->name('tickets.show');
    Route::post('/tickets/messages/{room}', 'TicketsController@messages');
    Route::post('/tickets/messages/send/{room}', 'TicketsController@sendMessage');
    Route::put('/tickets/status/{ticket}', 'TicketsController@changeStatus');
    Route::get('/data/tickets',     'TicketsController@data')->name('data_tickets');
});
