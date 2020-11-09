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
	Route::match(['get','post'],'wx','WeixinController@wx');//事件（推送

	// 获取 access_token
	Route::get('/access_token','WeixinController@access_token');
	// 测试
	Route::get('/test','WeixinController@test');


	// 新增临时素材
	Route::get('/insert','WeixinController@media_insert');
});

Route::prefix('wx')->group(function(){
	// Route::get('wx','WxController@wx');
	Route::get('/text1','WxController@text1');
	Route::post('/text2','WxController@text2');
});
