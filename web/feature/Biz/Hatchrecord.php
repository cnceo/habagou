<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 14:53
 */
namespace feature\Biz;
class Hatchrecord{
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }
    public function  getToDayHatchrecord(){
        return bjfeature('primary.Hatchrecord')->getToDayHatchrecord($this->user);
    }

    public function loadhatchrecord($input){
        return bjfeature('primary.Hatchrecord')->loadhatchrecord($this->user,$input);
    }

    public static function add($input){
        return bjfeature('primary.Hatchrecord')->add($input);
    }
}