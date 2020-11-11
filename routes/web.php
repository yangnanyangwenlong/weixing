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
    Route::get('/','WeachatController@wechat'); //接口测试
    Route::post('/wx','WeachatController@event'); //接受事件推送
    Route::get('/getaccesstoken','WeachatController@getaccesstoken'); //获取access_token
    Route::post('/createmenu','WeachatController@createmenu'); //接受事件推送

});
	
Route::get('/test1','YangnanController@test1'); //测试1
Route::get('/test2','YangnanController@test2'); //测试2
Route::get('/test3','YangnanController@test3'); //测试3
Route::post('/test4','YangnanController@test4'); //测试4
Route::get('/guzzleget','YangnanController@guzzleget'); //使用guzzle发起get请求
