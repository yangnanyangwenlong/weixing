<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeixinController extends Controller
{
	
 //    private function checkSignature()
	// {
	//     $signature = $_GET["signature"];
	//     $timestamp = $_GET["timestamp"];
	//     $nonce = $_GET["nonce"];
		
	//     $token = 123;
	//     $tmpArr = array($token, $timestamp, $nonce);
	//     sort($tmpArr, SORT_STRING);

	//     $tmpStr = implode( $tmpArr );
	//     $tmpStr = sha1( $tmpStr );
	    
	//     if( $tmpStr == $signature ){
	//         return true;
	//     }else{
	//         return false;
	//     }
	// }
	//接口测试/
		public function wx(){
		    $signature = $_GET["signature"];
		    $timestamp = $_GET["timestamp"];
		    $nonce = $_GET["nonce"];
			
		    $token = env('WX_TOKEN');
		    $tmpArr = array($token, $timestamp, $nonce);
		    sort($tmpArr, SORT_STRING);
		    $tmpStr = implode( $tmpArr );
		    $tmpStr = sha1( $tmpStr );
		    
		    if( $tmpStr == $signature ){
		        echo $_GET['echostr'];
		    }else{
		        echo '123';
		    }		
		}
	//接口配置
	private function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
		
	    $token = 123123;
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

	//连接
	public function cs1(){
		$token = request()->get('echostr','');
		if(!empty($token) && $this->checkSignature()){
			echo $token;
		}
	}



}
