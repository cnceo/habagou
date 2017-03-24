<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:55
 */
namespace feature\Biz;
class Switchvideo{
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }

    public function updateVideo($status){
         bjfeature('primary.Switchvideo')->updateVideo($this->user,$status);
    }

    public function add($status){
        bjfeature('primary.Switchvideo')->add($this->user,$status);
    }

    public function load(){
        return bjfeature('primary.Switchvideo')->load($this->user);
    }


}