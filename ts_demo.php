<?php
/**
 * ts_demo.php
 * sdk调用样例
 * Created on 2019/11/7 10:31
 * Create by v_joseqiu
 */
//access_token
define('ACCESS_TOKEN','你自己抓取到的access_token');
require_once 'TsSDK.php';
$uid=null;//可以不填，然后由sdk自己获取，也可以直接引入，不由api来获取。
$proxy='http://127.0.0.1:12639';//腾讯内部访问外网的代理，不需要就填Null
$obj=new \Ts\wx_api\TsSDK(ACCESS_TOKEN,$uid,$proxy);
$ret=$obj->tsSave();
var_dump($ret);