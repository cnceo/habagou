<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 20:49
 */
namespace feature\Biz;
class Sysnotice{
    public static function loadAll(){
        return bjfeature('primary.Sysnotice')->loadAll();
    }
}