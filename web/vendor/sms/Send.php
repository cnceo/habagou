<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 17:42
 */
include "TopSdk.php";
class Send{
     /*
      * 忘记密码验证码
      */
     public static function sendSmscode($phone,$code){
         $c = new TopClient;
         $c->appkey = '23657723';
         $c->secretKey = '18a04f7cbf53e03346d943e31140ca54';
         $req = new AlibabaAliqinFcSmsNumSendRequest;
         $req->setExtend("123456");
         $req->setSmsType("normal");
         $req->setSmsFreeSignName("哈巴狗科技");
         $req->setSmsParam("{\"code\":\"$code\"}");
         $req->setRecNum(''.$phone);
         $req->setSmsTemplateCode("SMS_51925001");
         $resp = $c->execute($req);
         return  $resp->result->success;
     }

    /*
     * 开户验证码
     */
    public static function regNotify($phone){
        $c = new TopClient;
        $c->appkey = '23657723';
        $c->secretKey = '18a04f7cbf53e03346d943e31140ca54';
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("哈巴狗科技");
        //$req->setSmsParam("{\"code\":\"$code\"}");
        $req->setRecNum($phone);
        $req->setSmsTemplateCode("SMS_51725069");
        $resp = $c->execute($req);
        //var_dump($resp);
        return  $resp->result->success;
    }

    /*
     * 发送转赠验证码  您正在向好友${account}转赠${num}只小狗，您的确认验证码为${code}
     */
    public static function sendSalecode($phone,$account,$num,$code){
        $c = new TopClient;
        $c->appkey = '23657723';
        $c->secretKey = '18a04f7cbf53e03346d943e31140ca54';
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("哈巴狗科技");
        $req->setSmsParam("{\"account\":\"$account\",\"num\":\"$num\",\"code\":\"$code\"}");
        $req->setRecNum($phone);
        $req->setSmsTemplateCode("SMS_52245055");
        $resp = $c->execute($req);
        return  $resp->result->success;
   }


}
