<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
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
		    	// 接收数据
		    	$xml_str = file_get_contents('php://input');
		    	//记录日志
		    	file_put_contents('wx_wvent.log', $xml_str);
		    	//将xml文本转为
		    	// $data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
		     //   	dd($data);
		        echo "";
		        die;

		    }else{
		        echo '';
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
	//redis 缓存coken 值
	public function rediscoken(){
		 $key = 'wx:access_token';
		 // echo $key;die;
		 $token = Redis::get($key);
		 if($token){
		 	echo "有缓存";echo '</br>';
		 }else{
		 	echo "无缓存";echo '</br>';
			 $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSEC')."";
			 $response = file_get_contents($url);
			 // echo $response;die;
			 $data = json_decode($response,true);
			 $token = $data['access_token'];

			 Redis::set($key,$token);
			 // echo $key;die;
			 Redis::expire($key,24*60*60);	
		 }

		
		 // echo "access_token: ".$token;
		 
	}

}
