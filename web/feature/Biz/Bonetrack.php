<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 0:13
 */
namespace feature\Biz;
class Bonetrack{
    private $user;
    /**
     * Bonetrack constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    //判断是否是当天第一次进入狗场
    public function judgeTodayFirst(){
         return bjfeature('primary.Bonetrack')->judgeTodayFirst($this->user);
    }

    /*
     * 是否弹出泡泡
     */
    public function judgePop($land){
       $bonetrack=bjfeature('primary.Bonetrack')->judgePop($this->user,$land->landindex);
        $land->pop=0;
       if($bonetrack!=null&&(int)$bonetrack->get==0){
           $land->pop=1;
       }
       return $land;
    }

    /*
     * 对用户批量插入骨头记录
     */
    public function addbatch($lands){
        //这里需要计算用户实际的利率
        $arrrate=bjfeature('Biz.Rate')->loadYesterdayRate($this->user);
        //echo '实际应该的加速比例为:'.$rate;exit;
        $arrs=[];
        foreach($lands as $v){
            $start = date('Y-m-d',(int)$v->starttime);
            $today = date('Y-m-d');
            if($start==$today)continue;//如果当天注册开户的押金是不产生利息的
            $arrs[]=[
                'accid' =>$this->user->getAccid(),
                'num'   =>(int)$v->wealth*$arrrate['total']/100,
                'basic' =>$arrrate['basic'],
                'breeder' =>$arrrate['breeder'],
                'level' =>$arrrate['level'],
                'total' =>$arrrate['total'],
                'get'   =>0,
                'landindex' =>$v->landindex,
                'bonedate'  =>time()
            ];
        }
        bjfeature('primary.Bonetrack')->addbatch($arrs);
    }

    public function loadgrowthrecord($input){
        return bjfeature('primary.Bonetrack')->loadgrowthrecord($this->user,$input);
    }
}