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
	
	Route::get('access_token','YangnanController@access_token');//推送消息回复
	
	Route::match(['get','post'],'/','YangnanController@wx');//推送消息回复

});
//练习

// Route::prefix('weixin')->group(function(){
// 	Route::match(['get','post'],'wx','WeixinController@wx');//事件（推送
//      // 测试
//     Route::get('/test','WeixinController@test');

// 	// 获取 access_token
// 	Route::get('/access_token','WeixinController@access_token');

//     Route::get('/turing','WeixinController@turing');
// 	// 新增临时素材
// 	Route::get('/media/insert','WeixinController@media_insert');
// });

