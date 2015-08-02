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

/**
 * Main routes that do not need a specific access level (only if the repository
 * is private).
 */
Route::get('/', 'HomeController@getMain')->before('omen.privateRepo');
Route::get('search', 'HomeController@getSearch')->before('omen.privateRepo');
Route::get('keyword/{tag}', 'KeywordController@getKeywordSearch')->before('omen.privateRepo');
Route::get('profile/{username}', 'UserController@getProfile')->before('omen.privateRepo');
Route::get('project/{name}', 'ProjectController@getProject')->before('omen.privateRepo');

/**
 * Login and logout routes.
 */
Route::get('login', function () {
    return View::make('login');
})->before('guest');
Route::post('login', 'UserController@postLogin')->before('csrf');
Route::get('logout', function () {
    Auth::logout();

    return Redirect::to('/');
})->before('auth');

/**
 * User registration routes.
 */
Route::get('signup', function () {
    if (\Config::get('omen.private'))
        return View::make('nosignup');

    return View::make('signup');
})->before('guest');
Route::post('signup', 'UserController@postCreateNewAccount')->before('omen.csrf');
Route::get('activate/{code}', 'UserController@getActivateAccount')->before('omen.privateRepo');

/**
 * Password reset routes.
 */
Route::get('forgot_password', function () {
    return View::make('forgot_password');
})->before('guest');
Route::post('forgot_password', 'UserController@postForgotPassword')->before('csrf');

/**
 * User account and settings routes.
 */
Route::get('account', 'UserController@getAccount')->before('auth');
Route::post('account', 'UserController@postAccount')->before('auth');
Route::get('account/delete_token/{token}', 'UserController@deleteToken')->before('auth');

Route::group(['prefix' => 'admin', 'before' => 'auth.admin'], function () {
    Route::get('', function () {
        return Redirect::to('admin/dashboard');
    });

    Route::get('dashboard', "Admin\\MainController@getDashboard");
    Route::get('projects', "Admin\\MainController@getProjects");
    Route::get('keywords', "Admin\\MainController@getKeywords");
    Route::get('users', "Admin\\MainController@getUsers");

    Route::get('users/{id}/delete', "UserController@deleteUser");
    Route::get('users/{id}/makeAdmin', "UserController@makeAdmin");
    Route::get('users/{id}/makeActive', "UserController@makeActive");
    Route::get('users/{id}/reset', "UserController@resetPassword");

    Route::get('projects/{id}/delete', "UnpublishController@deleteProject");
});

/**
 * Omen API routes.
 */
Route::group(['prefix' => 'api/v1'], function () {
    Route::group(['prefix' => 'dependency'], function () {
        Route::post('check', 'DependencyController@postCheckAndBuild');
        Route::post('update', 'DependencyController@postCheckUpdateAndBuild');
        Route::get('download/{hash}', 'DependencyController@getDownloadPack');
        Route::get('extend/{hash}', 'ProjectController@getDownload');
    });

    Route::post('login', 'UserController@apiLogin');

    Route::group(['prefix' => 'publish', 'before' => 'auth.token'], function () {
        Route::post('project', 'PublishController@createApplication');
    });

    Route::group(['prefix' => 'unpublish', 'before' => 'auth.token'], function () {
        Route::delete('project', 'UnpublishController@deleteApplication');
        Route::delete('version', 'UnpublishController@deleteVersion');
    });
});
