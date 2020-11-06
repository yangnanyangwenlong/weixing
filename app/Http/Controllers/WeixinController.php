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

		    if(!empty($token) == $this->checkSignature()){
		    	echo $token;
		    }else{
		    	// 接收数据
		    	$xml_str = file_get_contents('php://input');
		    	//记录日志
		    	file_put_contents('wx_wvent.txt', $xml_str);
		       	// dd($data);
		     //    echo "<xml>
		     //    			<ToUserName><![CDATA[gh_92948588ea26]]></ToUserName>
							// <FromUserName><![CDATA[obzSIt32D35x2OPb8asBVv4V1Wk0]]></FromUserName>
							// <CreateTime>1604656805</CreateTime>
							// <MsgType><![CDATA[event]]></MsgType>
							// <Event><![CDATA[subscribe]]></Event>
							// <EventKey><![CDATA[]]></EventKey>
					  // </xml>";
				echo "";
		        die;
		    	//将xml文本转为
		    	$data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);

		    }		
		}


	//redis 缓存coken 值
	public function rediscoken(){
		 $key = 'wx:access_token';
		 
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











    public function sub()
    {
        $str = file_get_contents("php://input");
        Log::info('===='.$str);
        $array = simplexml_load_string($str);
        if ($array->MsgType == "event") {
            if ($array->Event == "subscribe") {
                $ToUserName = $array->FromUserName;
                $FromUserName = $array->ToUserName;
                $CreateTime = time();
                $MsgType = "text";
                $Content = "你好，欢迎关注";
                $res = '<xml>
                        <ToUserName><![CDATA['.$ToUserName.']]></ToUserName>
                        <FromUserName><![CDATA['.$FromUserName.']]></FromUserName>
                        <CreateTime>'.$CreateTime.'</CreateTime>
                        <MsgType><![CDATA['.$MsgType.']]></MsgType>
                        <Content><![CDATA['.$Content.']]><Content>
                   </xml>';
                echo $res;exit;
            }
            if ($array->Event == "CLICK") {
                $eventkey = $array->EventKey;
                switch($eventkey){
                    case 'V1001_TODAY_MUSIC':
                        $arrays = ['少年','拥抱春天'];
                        $content = $arrays[array_rand($arrays)];
                        $this->responseText($array,$content);
                        break;
                    case 'V1001_GOOD':
                        $count = Cache::add('good',1)?:Cache::increment('good');
                        $content = '点赞人数:'.$count;
                        $this->responseText($array,$content);
                        break;
                    default:
                        break;
                }
            }
        }elseif($array->MsgType=='text'){
            $msg = $array->Content;
            switch($msg){
                case '在吗':
                    $content = '客观您好，有什么帮助您的吗？';
                    $this->responseText($array,$content);
                    break;
                case '在':
                    $content = '客观您好，有什么帮助您的吗？';
                    $this->responseText($array,$content);
                    break;
                case '红包':
                    $content = '客观您好，天上有掉馅饼的事吗？';
                    $this->responseText($array,$content);
                    break;
                case '百度':
                    $this->responseNews($array);
                    break;
                case '图片':
                    $media_id="_3oGKn0BD19avk1VTPXLrr7r-t4dfhQFQ420Bvv1Mb7F3tv-nSC0VNLyn5NDwJ3h";
                    $this->img($array,$media_id);
                    break;
                default:
                    $content = '欢迎';
                    $this->responseText($array,$content);
                    break;
            }
        }
    }







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

}
