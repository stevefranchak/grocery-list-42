<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', "HomeController@index");

Route::group(array('prefix' => Constant::get('API_PREFIX_URI')), function() {

    Route::post('register', array(
        'as' => 'register',
        'uses' => 'AuthController@register'
    ));

    Route::post('login', array(
        'as' => 'login',
        'uses' => 'AuthController@login'
    ));

    Route::group(array(
        'before' => 'auth.token'
    ), function() {

        Route::post('ping', array(
            'as' => 'ping',
            'uses' => 'AuthController@ping'
        ));

    });

});
