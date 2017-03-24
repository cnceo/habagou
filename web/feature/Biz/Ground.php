<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:55
 */
namespace feature\Biz;
bjload("feature.Biz.Wealth");
class Ground extends Wealth{
    private $user;
    public function __construct($user)
    {
         $this->user=$user;
    }

    public function getTodayHatchWealth(){
         return bjfeature('primary.Hatchrecord')->getTodayHatchWealth($this->user);
    }

    public function add($num){
        return bjfeature('primary.Ground')->add($this->user,$num);
    }

    public function reduce($num){
        return bjfeature('primary.Ground')->reduce($this->user,$num);
    }
}