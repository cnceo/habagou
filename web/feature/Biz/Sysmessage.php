<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:55
 */
namespace feature\Biz;
class Sysmessage{
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }

    public function add($title){

        return bjfeature('primary.Sysmessage')->add([
            'accid'=>$this->user->getAccid(),
            'title'=>$title,
            'sendtime'=>time()
        ]);
    }

    public function loadAll(){
        return bjfeature('primary.Sysmessage')->loadAll($this->user);
    }


}