<?php
namespace  Ts\wx_api;
/**
 * ts.php
 * ts 自动打卡
 * Created on 2019/11/6 17:14
 * Create by v_joseqiu
 */
class TsSDK
{
    private $api_host="http://mobile-app.hand-china.com";
    public $code;
    public $access_token='';
    public $uid;
    public $token;
    public $timestamp;
    public $project="516900";//项目id
    public $proxy;

    /** ts打卡的接口，已经写死了项目id、工作地址、打卡坐标
     * @return array
     */
    public function tsSave()
    {
        $url=$this->api_host."/hmap/api/l/timesheet_process/save_timesheet1?access_token={$this->access_token}";
        $postData=[
            'params'=>[
                'p_employee'=>$this->uid,
                'p_date'=>date('Ymd',time()),
                'p_project'=>$this->project,
                'p_description'=>'',
                'p_offbase_flag'=>"0",
                'p_base_flag'=>"0",
                'p_ext_charge'=>'1',
                'p_int_charge'=>'0',
                'p_address'=>'0',
                'p_flyback'=>'-1',
                'p_address_detail'=>'上海市上海市',
                'p_equipment_number'=>'微信填写',
                'p_operating_system'=>'Win32',
                'p_brand'=>'Mozilla/5.0 (Windows NT 6.1',
                'p_system_model'=>'Mozilla/5.0 (Windows NT 6.1',
                "p_longitude"=> 121.397785,
                "p_latitude"=> 31.165791,
                "p_sale_id"=> "",
                "p_language"=> "zh_CN",
                "p_opportunity_id"=> "",
                "p_address_id"=> "16672"
            ]
        ];
        $headers=[
            "loginName: {$this->uid}",
            "timestamp: {$this->timestamp}",
            "token: {$this->token}",
            "Proxy-Connection: keep-alive",
            "Accept: application/json, text/plain, */*",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.5;q=0.4",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat",
            "Origin: http://mobile-app.hand-china.com",
            "Host: mobile-app.hand-china.com",
            "Content-Type: application/json;charset=UTF-8",
        ];
        $proxy=$this->proxy;
        $ret=$this->supperCurl($url,json_encode($postData),null,$headers,$proxy);
        $head=$ret['header'];
        $rsp=$ret['body'];
        return json_decode($rsp,true);
    }

    /** token获取，目前有点问题，可能是包没抓全，提示缺失授权
     * @return array
     */
    public function getToken()
    {
        $url=$this->api_host."/hmap/oauth/token?client_id=wx_client&client_secret=secret&grant_type=password&username={$this->code}&password=123456&p_phone_no=111111";
        $post=[
            'params'=>[
                'client_id'=>'wx_client',
                'client_scret'=>'secret',
                'grant_type'=>'password',
                'username'=>$this->code,
                'password'=>'123456',
                'p_phone_no'=>'111111',
            ]
        ];
        $headers=[
            "Proxy-Connection: keep-alive",
            "Accept: application/json, text/plain, */*",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.5;q=0.4",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat",
            "Origin: http://mobile-app.hand-china.com",
            "Host: mobile-app.hand-china.com",
            "Content-Type: application/json;charset=UTF-8",
            "Content-Length: 0",
        ];

        $proxy=$this->proxy;
        $ret=$this->supperCurl($url,json_encode($post),null,$headers,$proxy);
        $head=$ret['header'];
        $rsp=$ret['body'];
        return json_decode($rsp,true);
    }

    /** 获取 项目详情
     * @return mixed
     */
    public function getProject()
    {
        $url=$this->api_host."/hmap/api/l/timesheet_process/fetch_projects?access_token={$this->access_token}";
        $headers=[
            "Proxy-Connection: keep-alive",
            "Accept: application/json, text/plain, */*",
            "Origin: http://mobile-app.hand-china.com",
            "Host: mobile-app.hand-china.com",
            "Content-Type: application/json;charset=UTF-8",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat",
        ];
        $post=[
            'params'=>[
                "p_employee"=>$this->uid,
                "p_date"=>date('Ymd',time()),
            ]
        ];
        $proxy=$this->proxy;
        $ret=$this->supperCurl($url,json_encode($post),null,$headers,$proxy);
        $head=$ret['header'];
        $rsp=$ret['body'];
        return json_decode($rsp,true);
    }

    /** 查询某天的ts打卡状态，默认不填的话自动传当天的日期
     * @param null $date
     * @return mixed
     */
    public function getTsStatus($date=null)
    {
        $url=$this->api_host."/hmap/api/l/api_timesheet/get_allowance_authority?access_token={$this->access_token}";
        $headers=[
            "loginName: {$this->uid}",
            "timestamp: {$this->timestamp}",
            "token: {$this->token}",
            "Proxy-Connection: keep-alive",
            "Accept: application/json, text/plain, */*",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.5;q=0.4",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat",
            "Origin: http://mobile-app.hand-china.com",
            "Host: mobile-app.hand-china.com",
            "Content-Type: application/json;charset=UTF-8",
        ];
        $post=[
          'params'=>[
              "p_employee_code"=>$this->uid,
              "p_record_date"=>$date?:date('Ymd',time()),
          ]
        ];
        $proxy=$this->proxy;
        $ret=$this->supperCurl($url,json_encode($post),null,$headers,$proxy);
        $head=$ret['header'];
        $rsp=$ret['body'];
        return json_decode($rsp,true);
    }

    public function supperCurl($url,$post=null,$cookie=null,$headers=[],$proxy=null)
    {
        $ch =curl_init();
        //以下代码设置代理服务器
        //代理服务器地址http://www.cnproxy.com/proxy1.html !!Hong Kong, China的速度比较好
        if($proxy){
            //以下代码设置代理服务器
            //代理服务器地址http://www.cnproxy.com/proxy1.html !!Hong Kong, China的速度比较好
            $proxy_type = explode('://', $proxy)[0];			// http, https, socks4, socks5
            $proxy_ip_port = explode('://', $proxy)[1];			// ip:port
            curl_setopt ( $ch, CURLOPT_HTTPPROXYTUNNEL, false );
            curl_setopt ( $ch, CURLOPT_PROXY, $proxy_ip_port );

            if ($proxy_type == "http") {
                curl_setopt ( $ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );			// http
            }
            elseif ($proxy_type == "https") {
                curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
                curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );				// https
            }
            elseif ($proxy_type == "socks4") {
                curl_setopt ( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4 );		// socks4
            }
            elseif ($proxy_type == "socks5") {
                curl_setopt ( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );		// socks5
            }
        }
        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7); //HTTp定向级别
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//6秒超时设置
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        }
        if($post){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($cookie){
            curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        }
        if($headers){
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }
        curl_setopt($ch, CURLOPT_HEADER, true);

        $result = curl_exec( $ch );
        // 获得响应结果里的：头大小
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // 根据头大小去获取头信息内容
        $header = substr($result, 0, $headerSize);
        curl_close( $ch );
        $body=substr($result,$headerSize-1);
        return ['header'=>$header,'body'=>$body];
    }
}
