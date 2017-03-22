<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 11:27
 */
namespace api\transaction;
class Transaction{
    public static function Config()
    {
        static $config;
        if( ! $config )
        {
            $config = [
                "ModuleName"	=>	"交易",
                "Groups"	=>	["交易记录"],
                "Functions"	=>	[
                    //[分组，权限]　权限取值范围：*:public -:login "":验证
                    "record"			=>	["交易记录","-"],
                ],
                "Policy"=>[
                    [
                        "Groups"	=>	["交易记录"],
                        "Functions"=>[],
                        "Enter"=>["policy.session.Filter.get_session"],//前置操作
                        "Leave"=>[]//后置操作
                    ],
                ],

            ];
        }
        return $config;
    }

    /*
     * 转赠记录
     */
    public static function record($input){
        if($input->type==1){//购买记录
            $input->receiveid=$input->accid;
        }elseif($input->type==2){//转赠记录
            $input->launchid=$input->accid;
        }
        return bjfeature('Biz.Transaction')->record($input);
    }

}