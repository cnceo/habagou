<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:00
 */
namespace feature\Biz;
class Accprop{
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }

    public function validSuperClean(){
         $superClean=bjfeature('Biz.Prop')->getSuperClean();
         return bjfeature('primary.Accprop')->validSuperClean($this->user,$superClean);
    }


    /*
     * 根据当前用户查询升级下一级对应的概率
     */
    public function loadChance(){
        $level=0;//当前等级默认为0级

    }


    public function upgrade(){

    }

    public function loadSpeed($feeder){
        $accprop=bjfeature('primary.Accprop')->loadAccprop($this->user,$feeder);
        return $accprop;
    }
    /*
     * 升级等级
     */
    public function updateLevel($Prop,$level){
        $level++;
        bjfeature('primary.Accprop')->updateLevel($this->user,$Prop,$level);
    }
    /*
     * 查询出我的饲养员等级
     */
    public function loadProp($Prop){
        return bjfeature('primary.Accprop')->loadProp($this->user,$Prop);
    }

    public function addProp($Prop){
        $day=(int)$Prop->expiry;
        $overtime=strtotime("+$day day");
        bjfeature('primary.Accprop')->addProp(
            [
            'accid'=>$this->user->getAccid(),
            'proid'=>$Prop->id,
            'level'=>0,
            'scale'=>0,
            'buytime'=>time(),
            'overtime'=>$overtime,
            ]);
    }

    public function updateProp($Prop){
        bjfeature('primary.Accprop')->updateProp($this->user,$Prop);
    }


}