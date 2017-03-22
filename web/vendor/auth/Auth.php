<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 18:02
 */
namespace vendor\auth;
class Auth{
    //验证是否已登录
    //此处为缺省实现，不同业务系统请重载
    public static function is_login()
    {
        if(bjsession()->get('accid')){
           return true;
        }
        return false;
    }

}