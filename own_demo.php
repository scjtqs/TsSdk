<?php
/**
 * ts_demo.php
 * sdk自用版本
 * Created on 2019/11/7 10:31
 * Create by v_joseqiu
 */
//access_token
define('ACCESS_TOKEN','XXXXXXXXXXXXXXXXXXXXXXXXXXXXX');//改成你自己的
//工号
define('UID','YYYYY');//改成你自己的

require_once 'TsSDK.php';
@$refresh_timesheet=file_get_contents('refresh_timesheet.json');
$info=[];
if(!empty($refresh_timesheet)){
    $refresh_timesheet=json_decode($refresh_timesheet,true)['timesheet'];
    foreach ($refresh_timesheet as $v)
    {
        if(date('Ymd',time())==$v['each_day']){
            $info=$v;
        }
    }
}
$uid=UID;
//$proxy='http://127.0.0.1:12639';//如果你是在公司内部电脑上跑，就放开这个注释。
$proxy=null;
$obj=new \Ts\wx_api\TsSDK(ACCESS_TOKEN,$uid,$proxy);
if(!empty($info) && $info['allowance']!=='0'){
  $isSave=true;
}elseif (empty($info)){
    $isSave=true;
}else{
    $isSave=false;
}
if($isSave==true){
    $ret=$obj->tsSave();
    if(empty($ret['status']) || empty($ret['result']) || empty($ret['refresh_status']) || empty($ret['refresh_message']))
    {
        //延迟600秒重试一波
        sleep(600);
        $ret=$obj->tsSave();
    }

    if(isset($ret['status']) && isset($ret['result']) && isset($ret['refresh_status']) && isset($ret['refresh_message'])){
        $post['content']="TS打卡成功，time=".date('Y-m-d');
        $refresh_timesheet=$ret['refresh_timesheet'];
        file_put_contents('refresh_timesheet.json',json_encode($refresh_timesheet));
    }else{
        $post['content']='授权验证失败，需要重新抓包token,time='.date('Y-m-d');
    }
}else{
    $post['content']='今日如果不是节假日，请重新抓包刷新token,time='.date('Y-m-d');
}
//$ret=$obj->getProject();
//var_dump($ret);//exit;
$post['cqq']=444208787;//改成你自己的qq号。同时，请加qq'3069219849'为好友，便于接受推送通知。

$url="https://wx.scjtqs.com/qq/push/singlePush";
$obj->supperCurl($url,$post);
