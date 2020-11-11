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

//练习

Route::prefix('weixin')->group(function(){
	//微信开发者服务器接入(即支持get又支持post)
	Route::match(['get','post'],'/wx','WeixinController@checkSignature');
	//上传素材
	Route::get('/guzzle2','WeixinController@guzzle2');
	//获取access_token
	Route::get('/access_token','WeixinController@access_token');
	//天气(780)
	Route::get('/weather1','WeixinController@weather1');
	//自定义菜单
	//
	Route::get('/create_menu','WeixinController@create_menu');


	//测试1
	Route::get('/weather','WeixinController@weather');
	//测试2
	Route::get('/test','WeixinController@test');
	//测试3(postman)
	Route::get('test2','WeixinController@test2');//get
	Route::post('test3','WeixinController@test3');//post(form-data)
	Route::post('test4','WeixinController@test4');//post(raw)
});
	
Route::get('/test1','YangnanController@test1'); //测试1
Route::get('/test2','YangnanController@test2'); //测试2
Route::get('/test3','YangnanController@test3'); //测试3
Route::post('/test4','YangnanController@test4'); //测试4
Route::get('/guzzleget','YangnanController@guzzleget'); //使用guzzle发起get请求
