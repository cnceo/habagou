<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:11
 */
namespace feature\Biz;
class Sysparameter{
     public static function loadByName($name){
         return bjfeature('primary.Sysparameter')->loadByName($name);
     }
}