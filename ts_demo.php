<?php
/**
 * ts_demo.php
 * sdk调用样例
 * Created on 2019/11/7 10:31
 * Create by v_joseqiu
 */
//access_token
define('ACCESS_TOKEN','你自己抓取到的access_token');
//工号
define('UID','你自己的工号');
//token
define('TOKEN','和access_token一起的在Header里面对应的token');
//timestamp
define('TIMESTAMP','和access_token一起在header里面对应的13位时间戳');

require_once 'TsSDK.php';
$obj=new \Ts\wx_api\TsSDK();
$obj->token=TOKEN;
$obj->access_token=ACCESS_TOKEN;
$obj->timestamp=TIMESTAMP;
$obj->uid=UID;
$obj->proxy='http://127.0.0.1:12639';//腾讯内部访问外网的代理，不需要就留空
$ret=$obj->tsSave();
var_dump($ret);
$obj->code="0aM1bWlgcktGWyUzBaANpJIAs_uEOi87fupVnXILWdI";//随机生成的唯一对应的授权码，一次性,目前还没找到是从哪生成的
$ret=$obj->getProject();
var_dump($ret);