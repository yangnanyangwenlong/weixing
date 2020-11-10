<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\OpenModel;
use App\Model\MediaModel;

class liaisonController extends Controller
{
    // 微信接口
    public function wx(Request $request)
    {
        $echostr = $request->echostr;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = env('WX_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        // 获取到微信推送过来post数据（xml格式）
        $postArr = file_get_contents("php://input");
        // 处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string($postArr);

        if(!empty(strtolower($postObj))){
            $toUser   = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            //判断该数据包是否是订阅的事件推送
            if(strtolower($postObj->MsgType) == 'event') {
                // 关注
                if(strtolower($postObj->Event == 'subscribe')){
                    //回复用户消息(纯文本格式)
                    $msgType  = 'text';
                    $content  = '欢迎关注微信公众账号';
                    // 获取用户的信息
                    $token = $this->access_token();
                    $uri = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$toUser."&lang=zh_CN";
                    file_put_contents('laravel-access.log',$uri);
                    $uri_json = file_get_contents($uri);
                    $uri_json = json_decode($uri_json,true);
                    //                     file_put_contents('laravel-access.log1',$uri_json.'\r\n',FILE_APPEND);
                                        // die;
                    $subscribe = OpenModel::where('wx_open_id',$uri_json['openid'])->first();
                    if(!empty($subscribe)){
                        $content  = '欢迎回来';
                    }else{
                        $userInfo = [
                            'wx_open_id' =>$uri_json['openid'],
                            'nickname' =>$uri_json['nickname'],
                            'sex' =>$uri_json['sex'],
                            'city' =>$uri_json['city'],
                            'headimgurl' =>$uri_json['headimgurl'],
                            'subscribe_time' =>$uri_json['subscribe_time'],
                        ];
                        OpenModel::insert($userInfo);
                    }
                    // 发送信息
                    $result = $this->text($toUser,$fromUser,$content);
                    return $result;
                }
                // 取消关注
                if(strtolower($postObj->Event == 'unsubscribe')){
                    // 消除用户的信息
                }
            }
            // 被动回复用户文本
            if(strtolower($postObj->MsgType)=='text')
            {
                file_put_contents('laravel-access.log',$postObj);
                switch ($postObj->Content) {
                    case '签到':
                        $content  = '签到成功';
                        $result = $this->text($toUser,$fromUser,$content);
                        return $result;
                        break;
                    case '时间':
                        $content  = date('Y-m-d H:i:s',time());
                        $result = $this->text($toUser,$fromUser,$content);
                        return $result;
                        break;
                    case '天气':
                        $key = '2a7f0f742a944fddb748bedb7919802e';
                        $uri = "https://devapi.qweather.com/v7/weather/now?location=101010100&key=".$key."&gzip=n";
                        $api = file_get_contents($uri);
                        $api = json_decode($api,true);
                        $content = "天气状态：".$api['now']['text'].'风向：'.$api['now']['windDir'];
                        $result = $this->text($toUser,$fromUser,$content);
                        return $result;
                        break;


                }
            // 被动回复用户文本。
            if(strtolower($postObj->MsgType)=='image'){
                $media = MediaModel::where('media_url',$postObj->PicUrl)->first();
                if(empty($media)){
                    $data = [
                        'media_url'     =>$postObj->PicUrl,
                        'media_type'    =>'image',
                        'add_time'      =>time(),
                        'openid'        =>$postObj->FromUserName,
                    ];
                    MediaModel::insert($data);
                    $content = '已将此图片记录素材库';
                }else{
                    $content = '素材库已存在';
                }
                $result = $this->text($toUser,$fromUser,$content);
                return $result;
            }
        }
    }
        // 接口测试
        if( $tmpStr == $signature ){
                echo $echostr;die;
            }else{
                return false;
            }

    }
    // 获取 access_token
    public function access_token()
    {
        // {"access_token":"38_t8wL-9vVIOgIEPuD0NKA6xUgJXHAQiL-DwcY-hSVwr1hSO7WviLBfo2y415VlM7NXbWletxGTZLlHjQaOM7e1Ti1BbbD77SNef7LN8dK1fOyLOBP-BefcTKxxKAbXWRfKZCjdxDO3EoslM6TBXZdABAGCE","expires_in":7200}
        $key = 'access_token';
        if(empty(Redis::get($key))){
            $uri = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSEC');
            $api = file_get_contents($uri);
            $api = json_decode($api);
            /*            $api = json_decode($api);
                       if(is_object($api)){
                            $api = $api->toArray();
                        }*/
            $value = $api->access_token;
            $time = $api->expires_in;
            // 存 access_token
            Redis::setex($key,$time-3600,$value);
//        dd(Redis::get($key));
        }

        $access_token = Redis::get($key);
        return $access_token;
    }
    // 新增临时素材
    public function media_insert(Request $request)
    {
        // 类型
        $type = $request->type;
//        $type = 'image';

        // 获取token
        $token = $this->access_token();

        // 接口
        $api = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token."&type=".$type;

        // 素材链接
//        $fileurl = "http://wyxxx.xyz/1.jpg";
        $fileurl = $request->fileurl;
        $this->media_add($api,$fileurl);
    }
    // 调用接口上传临时素材
    private function media_add($api,$fileurl)
    {
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_SAFE_UPLOAD,true);

        $data = ['media'    => new \CURLFile($fileurl)];

        curl_setopt($curl,CURLOPT_URL,$api);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
        print_r(json_decode($result,true));
    }

    // 1 回复文本消息
    private function text($toUser,$fromUser,$content)
    {
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
        $info = sprintf($template, $toUser, $fromUser, time(), 'text', $content);
        return $info;
    }




    // 测试
    public function test()
    {
        echo $this->access_token();
    }
}
// 自定义菜单
/*{
    "button": [
        {
            "type": "click",
            "name": "测试1",
            "key": "V1001_TODAY_MUSIC"
        },
        {
            "name": "测试2",
            "sub_button": [
                {
                    "type": "view",
                    "name": "百度",
                    "url": "https://www.baidu.com/"
                }
            ]
        }
    ]
}*/
