<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('yangnan')->group(function(){
	Route::get('/','YangnanController@index');
	Route::get('/table','YangnanController@table');
});
//练习
Route::prefix('a')->group(function() {
	Route::get('/','AController@index');
} );
Route::prefix('weixin')->group(function(){
	Route::get('/','WeixinController@checkSignature');
});