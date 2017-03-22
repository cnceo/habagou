<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 15:03
 */
namespace feature\Biz;
class Cleanrecord{
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }

    public static function add($cleanrecord){
        return bjfeature("primary.Cleanrecord")->add($cleanrecord);
    }

    public  function getTouserCleanrecord(){
        return bjfeature("primary.Cleanrecord")->getTouserCleanrecord($this->user);
    }

    public function loadcleanrecord($input){
        return bjfeature('primary.Cleanrecord')->loadcleanrecord($this->user,$input);
    }
}