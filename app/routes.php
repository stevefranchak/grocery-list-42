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

    /***  Users  ***/
    Route::group(array('prefix' => 'users'), function() {

        Route::post('', array(
            'as' => 'register',
            'uses' => 'AuthController@register'
        ));

    });

    /***  Session  ***/
    Route::group(array('prefix' => 'session'), function() {

        Route::post('', array(
            'as' => 'login',
            'uses' => 'AuthController@login'
        ));

        Route::delete('', array(
            'as' => 'logout',
            'uses' => 'AuthController@logout',
            'before' => 'auth.token'
        ));

    });

    /*** Myself (ping)  ***/
    Route::group(array(
        'before' => 'auth.token',
        'prefix' => 'myself'
    ), function() {

        Route::get('', array(
            'as' => 'ping',
            'uses' => 'AuthController@ping'
        ));

    });

});
