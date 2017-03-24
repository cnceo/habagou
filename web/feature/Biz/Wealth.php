<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:51
 */
namespace feature\Biz;

class Wealth{
    private $user;

    /**
     * Wealth constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    //通过ACCID查询用户用户财富
    public static function loadByAccid($accid){
        return bjfeature('primary.Wealth')->loadByAccid($accid);
    }



}