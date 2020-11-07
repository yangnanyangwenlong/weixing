<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Log;
class WeixinController extends Controller
{
	

	//接口测试/
	public function wx()
	{
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
		    		$content="关注成功。";
		    		echo $this->xiaoxi($data,$content);
 
		    	}else
		    	if($data->unsubscribe){
		    		$console = "取消关注成功";
		    		echo $this->qx($data,$console);
		    	}
		    }		
	}


	//redis 缓存coken 值
	public function rediscoken()
	{
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
	//接口
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
	function xiaoxi($data,$content)
	{ //返回消息
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
                      <Content><![CDATA[%s]]></Content>
                    </xml>";

            //替换掉上面的参数用 sprintf
        echo sprintf($xml,$toUserName,$fromUserName,$time,$msgType,$content);


    }

    function qx($data,$console)
    {
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
				  <Content><![CDATA[%s]]></Content>
				</xml>";
		echo sprintf($xml,$toUserName,$fromUserName,$time,$msgType,$content);
    }


public function GetShow(){
	$token = $this->token;
	//将token、timestamp、nonce三个参数进行字典序排序
	$arr = [$token,$_GET["timestamp"],$_GET['nonce']];
	sort($arr);
	$str = implode($arr);
	//加密
	$GetShow = sha1($str);
	//加密后的字符串与signature对比
	if($GetShow == $_GET["signature"]){
	echo $_GET['echostr'];die;
	}
}

/*用户关注授权获取信息*/
public function responseMsg(){
	if(@!empty($_GET["echostr"])){
		file_put_contents("1.txt",json_encode($_GET));
		$this->GetShow();
	}
	$data = file_get_contents("php://input");
	$this->res = (array)simplexml_load_string($data,"SimpleXMLElement",LIBXML_NOCDATA);
	//判断是否首次关注
	if ($this->res['MsgType'] == 'event') 
	{
		if ($this->res['Event'] == 'subscribe') 
		{
			$this->sendText("欢迎您关注我们，更多了解，敬请期待！");
		}
		if ($this->res['Event'] == 'unsubscribe') 
		{
			echo "取消关注";die;
		}
	}
}
//公共号首次关注推送消息
public function sendText($content){
	echo "<xml>
	<ToUserName><![CDATA[".$this->res['FromUserName']."]]></ToUserName>
	<FromUserName><![CDATA[".$this->res['ToUserName']."]]></FromUserName>
	<CreateTime>".time()."</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[".$content."]]></Content>
	</xml>";
}




}