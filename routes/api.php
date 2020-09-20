<?php

use Illuminate\Http\Request;

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

Route::post('/register', 'Api\UserController@register');
Route::post('/login', 'Api\UserController@login');
Route::resource('/news', 'Api\NewsController', ['only' => ['index', 'show']] );

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/profile', 'Api\UserController@profile');
    Route::post('/logout', 'Api\UserController@logout');
    Route::resource('/news', 'Api\NewsController', ['except' => ['index', 'show']]);
});

