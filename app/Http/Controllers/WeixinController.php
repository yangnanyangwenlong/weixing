<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeixinController extends Controller
{
	//接口配置
    private function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
		
	    $token = 123;
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
	//接口测试
	public function wx(){
		$token= request()->get("echostr");
		if(!empty($token) && $this->checkSignature()){
			echo $token;
		}
	}

}
