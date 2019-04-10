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

        Route::post('sign-in', 'LoginController@login');

        Route::middleware('auth:api')->post('logout', 'LoginController@logout');

        Route::middleware('fb.user')->group(function () {

            Route::post('fb/sign-up', 'RegisterFbController');

            Route::post('fb/sign-in', 'LoginFbController');

        });

        Route::post('password/forgot', 'ForgotPasswordController');

        Route::post('password/reset', 'ResetPasswordController');

    });

    Route::middleware('auth:api')->group(function () {

        Route::middleware('signup.step')->match(['get', 'put'], 'sign-up/stepper', 'SignUpStepController');

        Route::middleware('signup.done')->group(function () {

            Route::match(['get', 'put'], 'profile', 'ProfileController');

            Route::resource('friends', 'FriendController')->only(['index']);

            Route::get('events/friends/{event?}', 'EventController@friends');

            Route::resource('events', 'EventController')->except(['edit', 'create']);

            Route::post('invited-events/accept', 'AcceptInviteEventController');

            Route::resource('updates', 'UpdateController')->only(['index', 'show']);

            Route::resource('materials', 'MaterialController')->only(['index', 'show']);

            Route::get('location', 'LocationController');

            Route::resource('connect', 'ConnectController')->only(['index', 'store', 'update']);

            Route::resource('friend-requests', 'FriendRequestController')->only(['index', 'store', 'update']);

        });

    });

});
