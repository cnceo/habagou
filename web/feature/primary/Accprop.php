<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:16
 */
namespace feature\primary;
class Accprop{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('account.Accprop');
    }

    /*
     * 当前用户   超级打扫道具
     */
    public function validSuperClean($user,$prop){
        return $this->meta->load(["accid" =>$user->getAccid(),"proid"=>$prop->id]);
    }

    public function loadAccprop($user,$prop){
        return $this->meta->load(["accid" =>$user->getAccid(),"proid"=>$prop->id]);
    }

    public function updateLevel($fromUser,$Prop,$level){
          $this->meta->update(['level'=>$level],['accid'=>$fromUser->getAccid(),'proid'=>$Prop->id]);
    }

    public function loadProp($fromUser,$Prop){
        return $this->meta->load(['accid'=>$fromUser->getAccid(),'proid'=>$Prop->id]);
    }

    public function addProp($input){
        $this->meta->add($input);
    }
    /*
     * 更新道具
     */
    public function updateProp($fromUser,$Prop){
         $day=(int)$Prop->expiry;
         $overtime=strtotime("+$day day");
         $this->meta->update(['buytime'=>time(),'overtime'=>$overtime],['accid'=>$fromUser->getAccid(),'proid'=>$Prop->id]);
    }
}