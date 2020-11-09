<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WxController extends Controller
{	

	// public function wx(){
	// 	//获取微信推送post数据 xml格式
	// 	$ojg = file_get_contents('php:/input');
	// 	//处理消息类型 设置回复内容和类型
	// 	$postojg = simplexml_load_string($ojg)
	// 	if(!empty())
	// }







	// //接入
	private function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
		
	    $token = env('WX_TOKEN');
	    $tmpArr = array($token, $timestamp, $nonce);
	    sort($tmpArr, SORT_STRING);
	    $tmpStr = implode( $tmpArr );
	    $tmpStr = sha1( $tmpStr );
	    
	    if( $tmpStr == $signature ){
	        return true;
	    }else{
	        return false;
	    }
	}




























	//get
    public function text1(){
    	echo "no";
    	echo '<pre>';print_r($_GET);echo '</pre>';
    }
    //post
    public function text2(){
    	echo '<pre>';print_r($_POST);echo '</pre>';
    }
}
