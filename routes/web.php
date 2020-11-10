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
	Route::post('/wx','weixinController@checkSignature');  //接口微信
	Route::get('/wx/token','weixinController@token');  //access_token
	//Route::get('/tell','weixinController@tell');  //postman测试
	//Route::post('/tell2','weixinController@tell2');  //postman测试
	Route::get('/custom','weixinController@custom');  //自定义菜单

	//TEST 路由分组
	//Route::prefix('/text')get()->group(function (){
	//
	//});
	Route::get('getweather','weixinController@getweather');
	Route::get('/guzzle',"weixinController@guzzle");  //guzzle 测试  GET
	Route::get('/guzzle2',"weixinController@guzzle2");  //guzzle 测试  POST
});

