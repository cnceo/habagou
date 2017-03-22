<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 14:57
 */
namespace feature\Biz;
class Raiserecord{
     private $user;
     public function __construct($user)
     {
         $this->user=$user;
     }

    public function loadraiserecord($input){
        return bjfeature('primary.Raiserecord')->loadraiserecord($this->user,$input);
    }

    public static function add($input){
        return bjfeature('primary.Raiserecord')->add($input);
    }

}