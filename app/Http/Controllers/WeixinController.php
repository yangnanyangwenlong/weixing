<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Log;
class WeixinController extends Controller
{
	

	//接口测试/
		public function wx(){
		    $token = request()->get('echostr','');
		    $xml_str = file_get_contents('php://input');

		    if(!empty($token) == $this->checkSignature()){
		    	echo $token;
		    }else{
		    	//记录日志
		    	file_put_contents('wx_wvent.txt', $xml_str);

			
		    	//将xml文本转为 对象
		    	$data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);

		    	if($data->Event=="subscribe"){
		    		// file_put_contents('opopop');
		    		$content="滚，别来";
		    		echo $this->xiaoxi($data,$content);
 
		    	}
		    	// if($data->)
		    }		
		}


	//redis 缓存coken 值
	public function rediscoken(){
		 $key = 'wx:access_token';
		 
		 $token = Redis::get($key);
		 if($token){
		 	return true;
		 }else{
		 	return false;
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



	//关注回复
	function xiaoxi($data,$content){ //返回消息
        //我们可以恢复一个文本|图片|视图|音乐|图文列如文本
            //接收方账号
        $toUserName=$data->FromUserName;
           //开发者微信号
        $fromUserName=$data->ToUserName;
           //时间戳
        $time=time();
           //返回类型
        $msgType="text";

        $xml = "<xml>
					  <ToUserName><![CDATA[%s]]></ToUserName>
					  <FromUserName><![CDATA[%s]]></FromUserName>
					  <CreateTime>%s</CreateTime>
					  <MsgType><![CDATA[%s]]></MsgType>
					  <Event><![CDATA[%s]]></Event>
					  <EventKey><![CDATA[%s]]></EventKey>
					  <Ticket><![CDATA[%s]]></Ticket>
					</xml>";
                    // <xml>
                    //   <ToUserName><![CDATA[%s]]></ToUserName>
                    //   <FromUserName><![CDATA[%s]]></FromUserName>
                    //   <CreateTime>%s</CreateTime>
                    //   <MsgType><![CDATA[%s]]></MsgType>
                    //   <Content><![CDATA[%s]]></Content>
                    // </xml>
            //替换掉上面的参数用 sprintf
        echo sprintf($xml,$toUserName,$fromUserName,$time,$msgType,$content);


    }



}