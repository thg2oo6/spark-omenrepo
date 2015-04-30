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

Route::get('/', function () {
    return View::make('omen');
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::group(['prefix' => 'dependency'], function () {
        Route::post('check', 'DependencyController@postCheckAndBuild');
        Route::post('update', 'DependencyController@postCheckUpdateAndBuild');
        Route::get('download/{hash}', 'DependencyController@getDownloadPack');
    });

    Route::group(['prefix' => 'publish', 'before' => 'auth.once'], function () {
        Route::post('project', 'PublishController@createApplication');
    });
});