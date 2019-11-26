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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'UserController@login');

Route::middleware('jwt.verify')->prefix('product')->group(function() {
    Route::get('all', 'ProductController@all');
    Route::get('detail/{id}', 'ProductController@detail');
    Route::post('create', 'ProductController@create');
    Route::post('update/{id}', 'ProductController@update');
    Route::post('delete/{id}', 'ProductController@delete');
});
