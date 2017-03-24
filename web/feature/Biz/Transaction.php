<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:05
 */
namespace feature\Biz;
class Transaction{

     private $id;//交易id
     private $fromUser;//发起人
     private $toUser;//购买人
     private $num;//交易金额
     private $fee;
     private $launchtime;

     public static function getConfig()
     {
          return [
              'updataStatus'	=>	[
                  'Enter' => ['feature.Biz.Transaction.enterUpdataStatus'],
                  'Leave' => ['feature.Biz.Transaction.leaveUpdataStatus']
              ]
           ];
     }

     /**
      * @return mixed
      */
     public function getLaunchtime()
     {
          return $this->launchtime;
     }

     /**
      * @param mixed $launchtime
      */
     public function setLaunchtime($launchtime)
     {
          $this->launchtime = $launchtime;
     }


     /**
      * @return mixed
      */
     public function getFee()
     {
          return $this->fee;
     }

     /**
      * @param mixed $fee
      */
     public function setFee($fee)
     {
          $this->fee = $fee;
     }


     public function getId(){
          return $this->id;
     }

     /**
      * @param mixed $id
      */
     public function setId($id)
     {
          $this->id = $id;
     }

     public function setNum($num){
          $this->num=$num;
     }
     public function getNum(){
          return $this->num;
     }

     public function setFromUser($fromUser){
         $this->fromUser=$fromUser;
     }
     public function setToUser($toUser){
          $this->toUser=$toUser;
     }
     public function getFromUser(){
          return $this->fromUser;
     }
     public function getToUser(){
          return $this->toUser;
     }


     public static function create($fromUser,$toUser,$num,$scale){
          //这里做插入
          return bjfeature('primary.Transaction')->create($fromUser,$toUser,$num,$scale);
     }

     //前置开始方法
     public static function enterStart($_this,$in_args)
     {

     }
     //交易状态(1   卖家已经发起   2买家已经付款  3卖家已经确认收款   4  卖家取消交易)
     public function start(){
          //出售人仓库财富减
          $num=(int)$this->num*(1+(double)$this->fee);
          $this->getFromUser()->getWarehouseWealth()->reduce($num);
          return $num;//返回扣的本金加手续费
     }


     //购买人确认付款
     public function toConfirm(){
          return bjfeature('primary.Transaction')->updateStatus($this);
     }

     //发起人确认收款
     public function fromConfirm(){
          //购买人骨头添加
          $this->getToUser()->getBonewealth()->add($this->num);
     }


     //更新交易状态
     public function updateStatus($status){
          bjfeature('primary.Transaction')->updateStatus($this,$status);
     }




     //发起人取消交易
     public function toCancel(){
          //修改状态为4
          $this->getFromUser()->getWarehouseWealth()->add($this->num);
          return bjfeature('primary.Transaction')->toCancel($this);
     }

     public  function loadTodaySale(){
          return bjfeature('primary.Transaction')->loadTodaySale($this);
     }

     /*
      * 超级转赠
      */
     public function superSale(){
          $this->start();
          $this->fromConfirm();
     }


     public function record($input){
          return bjfeature('primary.Transaction')->record($input);
     }


     public static function enterUpdataStatus($_this,$in_args)
     {
           $id=$in_args[0]->id;
           $type=$in_args[0]->type;
           $accid=$in_args[0]->accid;
           $status=(int)$in_args[0]->status;
           $transaction=self::loadById($id);
           if($status<=(int)$transaction->status)bjerror('非法操作');
           if($type==0){//卖
               if((int)$transaction->launchid!=$accid)bjerror('请不要修改不属于自己的交易');
           }elseif($type==1){//买
                if((int)$transaction->receiveid!=$accid)bjerror('请不要修改不属于自己的交易');
           }
          $in_args[1]=$transaction;
          return $in_args;
     }


     // id   type  status
     public function updataStatus($input,$transaction){
          //var_dump($input).'==='.var_dump($transaction);exit;
          $id=$input->id;
          $status=$input->status;
          bjfeature('primary.Transaction')->updataStatus($id,$status);
          if($status==3){
               $toUser=bjfeature('Biz.User',$transaction->receiveid);
               $toUser->getBonewealth()->add($transaction->num);
          }elseif($status==4){
               $fromUser=bjfeature('Biz.User',$transaction->launchid);
               $fromUser->getWarehouseWealth()->add((int)$transaction->num*(1+(double)$transaction->fee));
          }

     }

     //普通转赠后置方法
     public static function leaveUpdataStatus($_this,$in_args,$ret){
          $status=(int)$in_args[0]->status;
          if($status==3){
               $toUser=bjfeature('Biz.User',(int)$in_args[1]->receiveid);
               $toWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$toUser->getAccid());
               $Wealthtrack=bjfeature('Biz.Wealthtrack');
               $Wealthtrack->setAccid($toWealth->accid);
               $Wealthtrack->setScale($in_args[1]->fee);
               $Wealthtrack->setValue($in_args[1]->num);
               $Wealthtrack->setBeforevalue((double)$toWealth->bone-(double)$in_args[1]->num);
               $Wealthtrack->setAftervalue((double)$toWealth->bone);
               $Wealthtrack->setTracktime(time());
               $Wealthtrack->setType(3);//1仓库   2地面   3骨头
               $Wealthtrack->setRemark('用户'.bjsession()->get('fromAccount').'向我普通转账'.$in_args[1]->num.'狗仔');
               $Wealthtrack->add();
          }elseif($status==4){
               $fromUser=bjfeature('Biz.User',(int)$in_args[1]->launchid);
               $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$fromUser->getAccid());
               $Wealthtrack=bjfeature('Biz.Wealthtrack');
               $Wealthtrack->setAccid($fromWealth->accid);
               $Wealthtrack->setScale($in_args[1]->fee);
               $Wealthtrack->setValue($in_args[1]->num);
               $total=(int)$in_args[1]->num*(1+(double)$in_args[1]->fee);
               $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse-$total);
               $Wealthtrack->setAftervalue((double)$fromWealth->warehouse);
               $Wealthtrack->setTracktime(time());
               $Wealthtrack->setType(1);//1仓库   2地面   3骨头
               $toAccount=bjstaticcall('feature.Biz.Account.loadById',(int)$in_args[1]->receiveid);
               $Wealthtrack->setRemark('取消向用户'.$toAccount->account.'普通转账'.$in_args[1]->num.'小狗,仓库新增'.$total.'小狗');
               $Wealthtrack->add();
          }


     }

     public static function loadById($id){
         return bjfeature('primary.Transaction')->loadById($id);
     }

}