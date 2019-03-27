<?php

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

Route::namespace('API')->group(function () {

    Route::namespace('Auth')->prefix('auth')->group(function () {

        Route::post('sign-up', 'RegisterController');

    });

    Route::middleware('auth:api')->group(function () {

        Route::middleware('signup.step')->match(['get', 'put'], 'sign-up/stepper', 'SignUpStepController');

    });

});
