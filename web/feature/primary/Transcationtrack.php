<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:15
 */
namespace feature\primary;
class Transcationtrack{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Transcationtrack');
    }

    public function add($transaction){
         $fee=(int)$transaction->getNum()*(double)$transaction->getFee();
         $this->meta->add([
             'fromid'=>$transaction->getFromUser()->getAccid(),
             'toid'  =>$transaction->getToUser()->getAccid(),
             'num'   =>$transaction->getNum(),
             'starttime'=>$transaction->getLaunchtime(),
             'endtime'  =>time(),
             'rate'     =>$transaction->getFee(),
             'fee'      =>$fee
         ]);
    }

}