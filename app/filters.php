<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});


App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }
});

Route::filter('auth.admin', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }

    if (!Auth::user()->isAdmin)
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::to('/');
        }
});


Route::filter('auth.basic', function () {
    if (Auth::guest())
        return Auth::basic();
});

/**
 * Filter used for the authentication through the API for the CLI tool.
 */
Route::filter('auth.once', function () {
    if (!Auth::guest())
        return false;

    $user = [
        'email'    => Input::get('user'),
        'password' => Input::get('pass')
    ];

    if (!Auth::once($user)) {
        return Response::json(["status" => "error", "message" => "Invalid user or password"], 500);
    }

});

/**
 * Filter used for the authentication through the API for the CLI tool.
 */
Route::filter('auth.token', function () {
    if (!Auth::guest())
        return false;

    $token = Token::where('uuid', Input::get('token'))->first();
    if (is_null($token)) {
        return Response::json(["status" => "error", "message" => "Invalid token, please relogin"], 500);
    }

    if (!Auth::onceUsingId($token->user_id)) {
        return Response::json(["status" => "error", "message" => "Invalid user or password"], 403);
    }

    $token->save();

});

/**
 * Filters to check if the application has been set as a private
 * one or not. Disables the registration feature if the repository
 * is a private one.
 */
Route::filter('omen.privateRepo', function () {
    if (Auth::guest() && \Config::get('omen.private'))
        return Redirect::to('login')->with('omen_notice', 'This repository is a private one!');

});
Route::filter('omen.csrf', function ($route, $request) {
    if (Auth::guest() && \Config::get('omen.private'))
        return Redirect::to('login')->with('omen_notice', 'User registration has been disabled!');

    Route::callRouteFilter('csrf', [], $route, $request);
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
//        throw new Illuminate\Session\TokenMismatchException;
        return Redirect::to('login')->with('omen_error', 'You must login through our website!');
    }
});
