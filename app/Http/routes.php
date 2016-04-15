<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('auth', 'Auth\AuthController@authenticate');
Route::get('user/me', 'UserController@getUser');



Route::group(['prefix' => '/api'],function(){
Route::group(['prefix' => 'v1'],function(){
    
    

});
});

