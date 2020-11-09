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

Route::prefix('weixin')->group(function(){
	Route::post('/wx','WeixinController@wx');//事件（推送
	// Route::get('/rediscoken','WeixinController@rediscoken');//测试1
	// Route::get('/sub','WeixinController@sub');//测试1
	// Route::get('/api','WeixinController@createParam');//spi

});

Route::prefix('wx')->group(function(){
	Route::get('/text1','WxController@text1');
	Route::get('/text2','WxController@text2');
});