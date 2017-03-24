<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/5
 * Time: 18:22
 */
require_once 'ChuanglanSmsHelper/ChuanglanSmsApi.php';
class send{
    public static function sendCode($phone,$content){
        $clapi  = new ChuanglanSmsApi();
        $result = $clapi->sendSMS($phone, '【哈巴狗科技】'.$content);
        $result = $clapi->execResult($result);
        return $result;
    }
}