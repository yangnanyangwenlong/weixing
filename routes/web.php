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
	//Route::get('/Token','test\TestController@token');//测试接入
	Route::post('/wx','WeixinController@wxEvent');//测试接入、
	Route::get('/token',"WeixinController@getAccressToken");//获取access_token
	Route::get('/create_menu','WeixinController@create_menu');//添加菜单

 
});
	