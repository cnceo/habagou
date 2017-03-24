<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:59
 */
namespace feature\Biz;
//include bjpathJoin(CLASS_ROOT,"/vendor/sms/Send.php");
include bjpathJoin(CLASS_ROOT,"/vendor/253/send.php");
class User{
    public $accid;
    private $account;
    private $groundwealth;
    private $warehousewealth;
    private $bonewealth;
    private $accprop;
    private $land;
    private $square;

    public function __construct($accid)
    {
            $this->accid=$accid;
    }

    public static function getConfig()
    {
        return [
            'sale'	=>	[
                'Enter' => ['feature.Biz.User.enterSale'],
                'Leave' => ['feature.Biz.User.leaveSale']
            ],
            'superSale'=>  [
                'Enter' => 'feature.Biz.User.enterSale',
                'Leave' => ['feature.Biz.User.leaveSuperSale']
            ],
            'openAccount'	=>	[
                'Enter' => ['feature.Biz.User.enterOpenAccount'],
                'Leave' => ['feature.Biz.User.leaveOpenAccount']
            ],
            'clean'     =>[
                'Enter' => ['feature.Biz.User.enterClean'],
                'Leave' => ['feature.Biz.User.leaveClean']
            ],
            'upgradeFeeder'  =>[
                'Enter' => ['feature.Biz.User.enterUpgradeFeeder'],
                'Leave' => ['feature.Biz.User.leaveUpgradeFeeder']
            ],
            'buySuperclean'  =>[
                'Enter' => ['feature.Biz.User.enterBuySuperclean'],
                'Leave' => ['feature.Biz.User.leaveBuySuperclean']
            ],
            'superClean'   =>[
                'Enter' => ['feature.Biz.User.enterSuperclean'],
                'Leave' => ['feature.Biz.User.leaveSuperclean']
            ],
        ];
    }

    public static function create($input){
        //通过input验证，插入account表，然后才有id
        return bjstaticcall("feature.Biz.Account.create",$input);
    }

    /**
     * @return mixed
     */
    public function getAccid()
    {
        return $this->accid;
    }

    /**
     * @param mixed $accid
     */
    public function setAccid($accid)
    {
        $this->accid = $accid;
    }



    public function getBonewealth(){
        if(is_null($this->bonewealth)){
            $this->bonewealth=bjfeature('Biz.Bone',$this);
        }
        return $this->bonewealth;
    }

    public function getGroundWealth(){
       if(is_null($this->groundwealth)){
           $this->groundwealth=bjfeature('Biz.Ground',$this);
       }
        return $this->groundwealth;
    }


    public function getWarehouseWealth(){
        if(is_null($this->warehousewealth)){
            $this->warehousewealth=bjfeature('Biz.Warehouse',$this);
        }
        return $this->warehousewealth;
    }

    public function getAccount(){
        if(is_null($this->account)){
            $this->account=bjfeature('Biz.Account',$this);
        }
        return $this->account;
    }

    /*
     * 判断是否是自己的好友
     */
    public function judgeFriend($toUser){
        return bjfeature('Biz.Account',$this)->judgeFriend($toUser);
    }

    public static function enterClean($_this,$in_args){
        $toUser=$in_args[0];
        //要判断下是不是自己的好友
        $toAccount=$_this->judgeFriend($toUser);
        if(is_null($toAccount))bjerror('您要打扫的好友的不存在');
        if($toAccount->recommendid!=$_this->accid)bjerror('请不要打扫别人的狗屋');
        $commsion = (int)$toUser->getGroundWealth()->getTodayHatchWealth();
        if($commsion==0)bjerror('您的好友今天还有孵化小狗');
        //今天是否有打扫过
        $TouserCleanrecord=bjfeature('Biz.Cleanrecord',$toUser)->getTouserCleanrecord();
        if($TouserCleanrecord!=null)bjerror('您的好友今天已经被打扫过');
        $cleancharge=bjstaticcall('feature.Biz.Sysparameter.loadByName','cleancharge');
        $in_args[1]=$commsion;
        $in_args[2]=(double)$cleancharge->value;
        $in_args[3]=$toAccount->account;

        return $in_args;
    }

    /*
     * 普通打扫
     * param
     *  user : hhhh
     */
    public function clean($toUser,$commsion,$rate){
        $num=(double)$commsion*$rate;
        $this->getBonewealth()->add($num);
        return $num;
    }


    public static function leaveClean($_this,$in_args,$ret){
        //添加打扫记录
        bjstaticcall('feature.Biz.Cleanrecord.add',[
            'accid'=>$_this->getAccid(),
            'targetid'=>$in_args[0]->getAccid(),
            'num'=>$ret,
            'cleantime'=>time()
        ]);

        //插入日志记录
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($fromWealth->accid);
        $Wealthtrack->setScale($in_args[2]);
        $Wealthtrack->setValue($ret);
        $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse-$ret);
        $Wealthtrack->setAftervalue((double)$fromWealth->warehouse);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark('为'.$in_args[3].'清洗新增'.$ret.'狗仔');
        $Wealthtrack->add();

        return $ret;//返回获得的佣金
    }

    /*
     * 查询出我的所有下线
     */
    public function getCleanPartner(){
        return bjfeature('Biz.Account',$this)->getCleanPartner();
    }

    public static function enterSuperclean($_this,$in_args){
        //首先要查询判断道具表--->超级打扫
        $Accprop=bjfeature('Biz.Accprop',$_this)->validSuperClean();
        if(is_null($Accprop))bjerror('您还没有购买一键清洗');
        if((int)$Accprop->overtime<(int)time())bjerror('您购买的一键清洗已经过期');
        //查询出当前用户的好友列表
        $cleanUsers=$_this->getCleanPartner();
        //var_dump($cleanUsers);
        if(is_null($cleanUsers))bjerror('您没有清洗的好友');
        $newCleanUser=[];
        $totalToDayHatchre=0;
        foreach($cleanUsers as $k=>$toAccids){
            $toUser=bjfeature('Biz.User',$toAccids->id);
            //echo $toUser->getAccid();
             //toUser是否有孵化
             $ToDayHatchrecord=bjfeature('Biz.Hatchrecord',$toUser)->getToDayHatchrecord();
             //var_dump($ToDayHatchrecord);
             if(empty($ToDayHatchrecord))continue;
             //toUser是否被打扫过
             $TouserCleanrecord=bjfeature('Biz.Cleanrecord',$toUser)->getTouserCleanrecord();
             //var_dump($TouserCleanrecord);exit;
             if($TouserCleanrecord!=null)continue;
             $tmp=0;
             foreach($ToDayHatchrecord as $v){
                 $tmp=$tmp+(int)$v->num;
             }
            $totalToDayHatchre=$totalToDayHatchre+$tmp;
            $newCleanUser[]=['user'=>$toUser,'num'=>$tmp];
            //echo $toUser->getAccid().'==='.$tmp.'<br/>';
        }
        if(is_null($newCleanUser))bjerror('清洗了0只小狗');
        $cleancharge=bjstaticcall('feature.Biz.Sysparameter.loadByName','cleancharge');
        $in_args[0]=$newCleanUser;
        $in_args[1]=$totalToDayHatchre;
        $in_args[2]=(double)$cleancharge->value;
        //var_dump($newCleanUser);exit;
        //echo $totalToDayHatchre;
        return $in_args;
    }



    /*
     * 一键清洗
     */
    public function superClean($users,$total,$scale){
          $this->getBonewealth()->add($total*$scale);
    }

    //普通转赠后置方法
    public static function leaveSuperclean($_this,$in_args,$ret){
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $total=$in_args[1]*$in_args[2];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($fromWealth->accid);
        $Wealthtrack->setScale($in_args[2]);
        $Wealthtrack->setValue($total);
        $Wealthtrack->setBeforevalue((double)$fromWealth->bone-$total);
        $Wealthtrack->setAftervalue($fromWealth->bone);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(3);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark('一键清洗狗仔增加'.$total.'条小狗,佣金比例为'.$in_args[2]);
        $Wealthtrack->add();

        //添加清洗记录
        $arrs=$in_args[0];
        foreach($arrs as $v){
            bjstaticcall('feature.Biz.Cleanrecord.add',[
                'accid'=>$_this->getAccid(),
                'targetid'=>$v['user']->getAccid(),
                'num'=>$v['num']*$in_args[2],
                'cleantime'=>time()
            ]);
        }
        return $total;
    }

    public function getCancleanPartners(){
        return bjfeature('Biz.Account',$this)->getCancleanPartners();
    }

    public function getLandByIndex($index){
         return bjfeature('Biz.Land',$this,$index);
    }

    //通过账号查询用户
    public static function loadByAccount($account){
        return bjstaticcall("feature.Biz.Account.loadByAccount",$account);
    }

    //通过电话查询用户
    public static function loadByPhone($phone){
        return bjfeature('primary.Account')->loadByPhone($phone);
    }



    public static function login($input){
        $account=self::loadByPhone($input->phone);
        if(is_null($account))bjerror('手机号不存在');
        if((int)$account->frozen==1)bjerror('账号已被冻结');
        if($account->pwd!=md5($input->pwd))bjerror('账号信息不符');
        bjsession()->set('accid',$account->id);
        bjsession()->set('fromAccount',$account->account);
    }

    //我的伙伴
    public function getPartner(){
        return bjfeature('primary.Account')->getPartner($this);
    }

    /*
	 * 查询是否已经打算指定下线的狗屋
	 */
     public function isCeanPartner($toUser){
         return bjfeature('primary.Account')->isCeanPartner($this,$toUser);
     }

     //初始化指定用户的财富表
     public  static function initialization($toUser,$openFee){
         bjfeature('primary.Wealth')->initialization($toUser,$openFee);
     }

    /*
     *开户前置方法
     */
    public static function enterOpenAccount($_this,$in_args)
    {
        $input=$in_args[0];
        if($input->account!=$input->phone)bjerror('手机号不一致');
        $fromAccount=bjstaticcall("feature.Biz.User.loadByAccount",$input->fromAccount);
        if(is_null($fromAccount))bjerror('推荐用户账号不存在');
        bjwriteLog('推荐人ID'.$fromAccount->id);
        $fromUser =bjfeature('Biz.User',$fromAccount->id);
        $existAccount=bjstaticcall('feature.Biz.Account.loadByAccount',$input->account);
        if($existAccount!=null)bjerror('注册用户帐号已经存在');
        $existAccount=bjstaticcall('feature.Biz.Account.loadByPhone',$input->phone);
        if($existAccount!=null)bjerror('注册手机号已经存在');
        $openfee=bjstaticcall('feature.Biz.Sysparameter.loadByName','openfee');
        $charge=bjstaticcall('feature.Biz.Sysparameter.loadByName','opencharge');
        $in_args[1]=(int)$openfee->value;
        $in_args[2]=(double)$charge->value;
        $in_args[3]=$fromUser;
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $fee=$in_args[1]*(1+$in_args[2]);
        if($fee>(double)$fromWealth->warehouse)bjerror('仓库小狗数量不足');
        return $in_args;
    }

    /*
     * 开户
     */
    public function openAccount($input,$openFee,$charge,$tjUser){
         $fee=$openFee*(1+$charge);
         $input->recommendid=$tjUser->getAccid();
         unset($input->fromAccount);
         $input->pwd=md5('123456');
         $input->regtime=time();
         $toAccid=self::create($input);
         $this->getWarehouseWealth()->reduce($fee);
         $toUser =bjfeature('Biz.User',$toAccid);
         self::initialization($toUser,$openFee);
         return $toUser;
    }

    /*
    * 开户后置方法
    */
    public static function leaveOpenAccount($_this,$in_args,$ret){
        //每次开户完要对用户等级是否符合条件做变更
        $tjUser=$in_args[3];
        $tjUser->updateLevel();
        bjfeature('Biz.Land',$ret,0)->open();//自动开地
        //开户人仓库财富的变化
        $num=$in_args[1]*(1+$in_args[2]);
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($fromWealth->accid);
        $Wealthtrack->setScale($in_args[2]);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->warehouse);
        $Wealthtrack->setAftervalue((double)$fromWealth->warehouse-$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark('为'.$in_args[0]->account.'开发新狗场,仓库减少'.$num.'条小狗,手续费比例为'.$in_args[2]);
        $Wealthtrack->add();
        //添加收件箱记录
        bjfeature('Biz.Sysmessage',$ret)->add('注册获得'.$in_args[1].'小狗');
        $input=$in_args[0];
        bjfeature('Biz.Sysmessage',$_this)->add('为'.$input->phone.'注册消耗'.$num.'小狗');
        //\Send::regNotify($in_args[0]->phone);
        $result=\send::sendCode($in_args[0]->phone,'恭喜您注册成功，您的账号为您的注册电话，初始密码为123456。请尽快登入网站修改密码，感谢您的参与！');
        return $ret->getAccid();
    }

    public function updateLevel(){
        $account=bjstaticcall('feature.Biz.Account.loadById',$this->getAccid());
        if((int)$account->level<4){
            $toplevelvalue=bjstaticcall('feature.Biz.Sysparameter.loadByName','level'.(int)$account->level);
            $parter=bjfeature('Biz.Account',$this)->getCleanPartner();
            //echo count($parter).'==='.(int)$toplevelvalue->value.'<br>';
            if(is_array($parter)&&count($parter)>=(int)$toplevelvalue->value){
                //更新下会员等级
                $toplevel=(int)$account->level+1;
                bjfeature('Biz.Account',$this)->updateLevel($toplevel);
            }
        }
    }

    /*
     * 普通转赠前置
     */
    public static function enterSale($_this,$in_args)
    {

         $num=(int)$in_args[1];
         if($num%10!=0)bjerror('转赠数量必须为10的倍数');
         //转赠数量不能单笔限额
         $salelimit=bjstaticcall('feature.Biz.Sysparameter.loadByName','salelimit');
         if($num>(int)$salelimit->value)bjerror('转赠数量不能单笔限额');
         //查询是否超过仓库的额度
         $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
         if($num>(double)$fromWealth->warehouse)bjerror('转赠数量超过仓库的小狗数量');
         //查询出这块用户开了多少绿地和金地，然后根据今天转了多少额度算出还可以转多少额度
         $openLands=bjfeature('Biz.Square',$_this)->getOpenLand();
         $available=$_this->getQuota($openLands);
         if($num>$available)bjerror('单日转赠数量已经超额');
         $scale=0;
         if(count($openLands)<15){
             $salefee=bjstaticcall('feature.Biz.Sysparameter.loadByName','salefee');
             $scale=(double)$salefee->value;//转赠手续费
         }
         $in_args[2]=$scale;

         return $in_args;
    }

    public function getQuota($openLands){
        $available=0;
        $green=bjstaticcall('feature.Biz.Sysparameter.loadByName','green');
        $gold=bjstaticcall('feature.Biz.Sysparameter.loadByName','gold');
        foreach($openLands as $k=>$land){
             if((int)$land->landtype==0){
                 $available+=(int)$green->value;
             }elseif((int)$land->landtype==1){
                 $available+=(int)$gold->value;
             }
        }
        $Transaction=bjfeature('Biz.Transaction');
        $Transaction->setFromUser($this);
        $todaysale=$Transaction->loadTodaySale();
        $sale=0;
        if(is_array($todaysale)){
            foreach($todaysale as $sales){
                $sale+=(int)$sales->num;
            }
        }
        return $available-$sale;
    }

    //普通转赠
    public function sale($toUser,$num,$scale){
        $transactionId=bjstaticcall("feature.Biz.Transaction.create",$this,$toUser,$num,$scale);
        $Transaction=bjfeature('Biz.Transaction');
        $Transaction->setId($transactionId);
        $Transaction->setFromUser($this);
        $Transaction->setToUser($toUser);
        $Transaction->setNum($num);
        $Transaction->setFee($scale);
        $Transaction->start();
        return $Transaction;
    }

    /*
     * 普通转账后置方法
     */
    public static function leaveSale($_this,$in_args,$ret){
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $toUser=$in_args[0];
        //$num=(int)$in_args[1];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getAccid());
        $Wealthtrack->setScale($ret->getFee());
        $Wealthtrack->setValue($ret->getNum());
        $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse+((int)$ret->getNum()*(1+(double)$ret->getFee())));
        $Wealthtrack->setAftervalue($fromWealth->warehouse);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);
        $Wealthtrack->setRemark("向账号".$toUser->getAccount()->getAccount()."普通转账".$ret->getNum().'条小狗,手续费比例为'.$ret->getFee());
        $Wealthtrack->add();
        bjsession()->set('salecode','');
        return $ret;
    }

    //超级转赠
    public function superSale($toUser,$num,$scale){
        $transactionId=bjstaticcall("feature.Biz.Transaction.create",$this,$toUser,$num,$scale);
        $Transaction=bjfeature('Biz.Transaction');
        $Transaction->setId($transactionId);
        $Transaction->setFromUser($this);
        $Transaction->setToUser($toUser);
        $Transaction->setNum($num);
        $Transaction->setFee($scale);
        $Transaction->setLaunchtime(time());
        $Transaction->superSale();
        //更新status为3
        $Transaction->updateStatus(3);
        return $Transaction;
    }

    public static function leaveSuperSale($_this,$in_args,$ret){
        bjfeature('Biz.Transcationtrack')->add($ret);
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $toUser=$in_args[0];
        $totlefee=(int)$in_args[1]*(1+(double)$in_args[2]);
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getAccid());
        $Wealthtrack->setScale($in_args[2]);
        $Wealthtrack->setValue($in_args[1]);
        $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse+$totlefee);
        $Wealthtrack->setAftervalue($fromWealth->warehouse);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);
        $Wealthtrack->setRemark("向账号".$toUser->getAccount()->getAccount()."超级转账".$in_args[1].'条小狗,手续费比例为'.$in_args[2]);
        $Wealthtrack->add();

        $toWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$toUser->getAccid());
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($toUser->getAccid());
        $Wealthtrack->setScale($in_args[2]);
        $Wealthtrack->setValue($in_args[1]);
        $Wealthtrack->setBeforevalue((double)$toWealth->bone-(int)$in_args[1]);
        $Wealthtrack->setAftervalue((double)$toWealth->bone);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(3);
        $Wealthtrack->setRemark("账号".$_this->getAccount()->getAccount()."向我超级转账".$in_args[1].'条狗仔,手续费比例为'.$in_args[2]);
        $Wealthtrack->add();

        //添加收件箱记录
        bjfeature('Biz.Sysmessage',$_this)->add('获得'.$in_args[1].'狗仔');
        bjsession()->set('salecode','');
        return $ret;
    }

    public function getUserInfo(){
        $accountinfo=bjfeature('Biz.Account',$this)->getAccountinfo();
        $wealthinfo=bjstaticcall('feature.Biz.Wealth.loadByAccid',$this->getAccid());

        /*if($wealthinfo->enddate<time()){
            //表示骨头已经失效
            //这里要做更新操作
            // 当天的零点
            $dayBegin = strtotime(date('Y-m-d', time()));
            // 当天的24
            $dayEnd = $dayBegin + 24 * 60 * 60;
            bjfeature('Biz.Bone',$this)->updateBone(0,$dayEnd);//不孵化每天就清0操作

            //这里要添加清0记录
            $Wealthtrack=bjfeature('Biz.Wealthtrack');
            $Wealthtrack->setAccid($this->getAccid());
            $Wealthtrack->setScale(0);
            $Wealthtrack->setValue($wealthinfo->bone);
            $Wealthtrack->setBeforevalue((double)$wealthinfo->bone);
            $Wealthtrack->setAftervalue(0);
            $Wealthtrack->setTracktime(time());
            $Wealthtrack->setType(3);
            $Wealthtrack->setRemark("狗仔未当天喂食系统清0操作,狗仔减少".$feeder->fee."只小狗");
            $Wealthtrack->add();

            $wealthinfo->bone=0;
            $wealthinfo->enddate=$dayEnd;
        }*/

        $Prop=bjstaticcall('feature.Biz.Prop.loadByName','feeder');
        $accprop=bjfeature('Biz.Accprop',$this)->loadProp($Prop);
        $feederlevel=0;
        if(is_null($accprop)){
            bjfeature('Biz.Accprop',$this)->addProp($Prop);
        }else{
            $feederlevel=$accprop->level;
        }

        return ['accountinfo'=>$accountinfo,'wealthinfo'=>$wealthinfo,'feedlevel'=>$feederlevel];
    }


    public function isEdit(){
        $lands=bjfeature('Biz.Square',$this)->getLandInfo();
        $green=bjstaticcall('feature.Biz.Sysparameter.loadByName','regeditgreen');
        $gold=bjstaticcall('feature.Biz.Sysparameter.loadByName','regeditgold');
        $type0=0;
        $type1=0;
        $edit=0;

        foreach($lands as $v){
            if((int)$v->landtype==0){
                $type0++;
            }elseif((int)$v->landtype==1){
                $type1++;
            }
        }

        if($type0>=(int)$green->value&&$type1>=(int)$gold->value){
            $edit=1;
        }
        return $edit;
    }


    public static function enterUpgradeFeeder($_this,$in_args)
    {
          $level=(int)$in_args[0];
          if($level>=9)bjerror('已经是最高级别了');
          $NextFeeder=bjstaticcall('feature.Biz.Feeder.loadNextFeeder',$level);
          //var_dump($NextFeeder);
          $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
          //var_dump($fromWealth);
          if((double)$NextFeeder->fee>(double)$fromWealth->warehouse)bjerror('仓库小狗数量不足');
          $in_args[1]=$NextFeeder;
          $in_args[2]=$fromWealth;
          return $in_args;

    }

    public function upgradeFeeder($level,$feeder){
          //这里要判断概率
          $lottery=(int)$feeder->chance;
          $fail=100-$lottery;
          $prize_arr =array(1=>(int)$feeder->chance,2=>$fail);
          $result=bjcreate('vendor.tool.Lottery')->get_rand($prize_arr);
          $flag=0;
          if($result==1){
              $flag=1;
              $Prop=bjstaticcall('feature.Biz.Prop.loadByName','feeder');
              bjfeature('Biz.Accprop',$this)->updateLevel($Prop,$level);
          }
          $this->getWarehouseWealth()->reduce((int)$feeder->fee);
          return ['feeder'=>$feeder,'flag'=>$flag];
    }

    public static function leaveUpgradeFeeder($_this,$in_args,$ret){
        //添加记录
        $flag=$ret['flag'];
        $feeder=$ret['feeder'];
        $fromWealth=$in_args[2];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($feeder->fee);
        $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse);
        $Wealthtrack->setAftervalue($fromWealth->warehouse-(double)$feeder->fee);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);
        $Wealthtrack->setRemark("升级饲养员仓库减少".$feeder->fee."只小狗");
        $Wealthtrack->add();
        return ['level'=>$feeder->level,'flag'=>$flag];
    }

    /*
     * 我的伙伴
     */
    public function myFriend(){
        return bjfeature('Biz.Account',$this)->myFriend();
    }

    /*
     * 拜访好友狗屋，查询是否可以打扫
     */
    public function canClean(){
        $flag=0;
        $commsion = (int)$this->getGroundWealth()->getTodayHatchWealth();
        //echo $commsion;
        if($commsion>0){
            $TouserCleanrecord=bjfeature('Biz.Cleanrecord',$this)->getTouserCleanrecord();
            //var_dump($TouserCleanrecord);
            if(is_null($TouserCleanrecord))$flag=1;
        }
        //echo $flag;exit;
        return $flag;
    }

    /*
     *进入狗场时查询出我的道具
     */
    public function myProp(){
        //=========================饲养员=======================
        $Prop=bjstaticcall('feature.Biz.Prop.loadByName','feeder');
        $accprop=bjfeature('Biz.Accprop',$this)->loadProp($Prop);
        $feederlevel=0;
        if(is_null($accprop)){
            bjfeature('Biz.Accprop',$this)->addProp($Prop);
        }else{
            $feederlevel=$accprop->level;
        }
        //==========================一键清洗=========================
        $cleanProp=bjstaticcall('feature.Biz.Prop.loadByName','superclean');
        $cleanaccprop=bjfeature('Biz.Accprop',$this)->loadProp($cleanProp);
        $day=0;
        if($cleanaccprop!=null&&(int)$cleanaccprop->overtime>=time()){
            $overtime=(int)$cleanaccprop->overtime;
            $day=(int)(($overtime - (int)time()) / 86400);
        }
        $fee=$cleanProp->fee;
        return ['feederlevel'=>$feederlevel,'day'=>$day,'fee'=>$fee];
    }


    public static function enterBuySuperclean($_this,$in_args)
    {
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getAccid());
        $Prop=bjstaticcall('feature.Biz.Prop.loadByName','superclean');
        if((double)$fromWealth->warehouse<(double)$Prop->fee)bjerror("仓库小狗不足");
        $in_args[0]=$Prop;
        $in_args[1]=$fromWealth;
        return $in_args;
    }

    /*
     * 购买一键清洗
     */
    public function buySuperclean($prop){
         $cleanAccprop=bjfeature('Biz.Accprop',$this)->loadProp($prop);
         if(is_null($cleanAccprop)){
             bjfeature('Biz.Accprop',$this)->addProp($prop);
         }else{
             if((int)time()<=(int)$cleanAccprop->overtime)bjerror('一键清洗还没有到期');
             bjfeature('Biz.Accprop',$this)->updateProp($prop);
         }
         $this->getWarehouseWealth()->reduce((double)$prop->fee);
    }

    //普通转赠后置方法
    public static function leaveBuySuperclean($_this,$in_args,$ret){
        $prop=$in_args[0];
        $fromWealth=$in_args[1];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($prop->fee);
        $Wealthtrack->setBeforevalue((double)$fromWealth->warehouse);
        $Wealthtrack->setAftervalue($fromWealth->warehouse-(double)$prop->fee);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);
        $Wealthtrack->setRemark("购买一键清洗仓库减少".$prop->fee."只小狗");
        $Wealthtrack->add();
        return (int)$prop->expiry-1;//返回剩余的天数,除去今天

    }


    public function addMax($index){
        $land=$this->getLandByIndex($index)->load();
        $landset=bjfeature('Biz.Landset',$index)->getLandset();;
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$this->getAccid());
        $result=0;
        if((int)$landset->capacity>(int)$land->wealth){
            $result=(int)$landset->capacity-(int)$land->wealth;
            if($result>(double)$fromWealth->warehouse){
                $result=(int)$fromWealth->warehouse;
            }
        }
        return $result;
    }

    public function feedMax($index){
        $land=$this->getLandByIndex($index)->load();
        $landset=bjfeature('Biz.Landset',$index)->getLandset();;
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$this->getAccid());
        $result=0;
        if((int)$landset->capacity>(int)$land->wealth){
            $result=(int)$landset->capacity-(int)$land->wealth;
            if($result>(double)$fromWealth->bone){
                $result=(int)$fromWealth->bone;
            }
        }
        return $result;
    }


    public static function sendForgetpwdCode($phone){
        $code=bjsession()->get('code');
        //if($code!=null)bjerror('请不要频繁发送验证码');
        $account=bjstaticcall('feature.Biz.Account.loadByPhone',$phone);
        if(is_null($account))bjerror('手机号不存在');
        $code=rand(100000,999999);
        bjsession()->set('code',$code);
        //$result=\Send::sendSmscode($phone,$code);
        //var_dump($result);
        //if(!$result)bjerror('短信发送不成功');
        $result=\send::sendCode($phone,'您正在重置密码,您的短信验证码为'.$code);
        if($result[1]!=0)bjerror('发送失败');
    }

    public static function updateForgetpwd($input){
        $code=bjsession()->get('code');
        if(is_null($code))bjerror('请先获取验证码');
        $account=bjstaticcall('feature.Biz.Account.loadByPhone',$input->phone);
        if(is_null($account))bjerror('手机号不存在');
        if($code!=$input->code)bjerror('验证码不正确');
        $fromUser=bjfeature('Biz.User',$account->id);
        $fromUser->getAccount()->updatePwd($input->pwd);
        bjsession()->set('code','');//清空
    }


    public function sendSaleCode($input){
        //$code=bjsession()->get('salecode');
        //if(!empty($code))bjerror('请不要频繁发送验证码');
        $account=bjstaticcall('feature.Biz.Account.loadById',$this->getAccid());
        if(is_null($account))bjerror('手机号不存在');
        $code=rand(100000,999999);
        bjsession()->set('salecode',$code);
        //$phone,$account,$num,$code
        //$result=\Send::sendSaleCode($account->phone,$input->account,$input->num,$code);
        //if(!$result)bjerror('短信发送不成功');
        //bjwriteLog('验证码为:'.$code);
        $result=\send::sendCode($account->phone,'您的验证码：'.$code.'正在出售给［'.$input->account.'］'.$input->num.'只小狗，，HBG提醒您注意交易安全！');
        if($result[1]!=0)bjerror('发送失败');
    }

    public function loadSelfWealth(){
        return bjstaticcall('feature.Biz.Wealth.loadByAccid',$this->getAccid());
    }

    public function getSysmessage(){
        return bjfeature('Biz.Sysmessage',$this)->loadAll();
    }

    public function updateVideo($status){
        $Switchvideo=bjfeature('Biz.Switchvideo',$this)->load();
        if(is_null($Switchvideo)){
            bjfeature('Biz.Switchvideo',$this)->add($status);
        }else{
            bjfeature('Biz.Switchvideo',$this)->updateVideo($status);
        }
    }

    public function myVideo(){
        $Switchvideo=bjfeature('Biz.Switchvideo',$this)->load();
        $status=0;
        if(is_null($Switchvideo)){
            bjfeature('Biz.Switchvideo',$this)->add(1);//默认为开
            $status=1;
        }else{
            $status=$Switchvideo->status;
        }
        return $status;
    }

    public function updateHeadimg($headid){
        $this->getAccount()->updateHeadimg($headid);
    }



    public function updateLoginpwd($input){
        $account=bjstaticcall('feature.Biz.Account.loadById',$this->getAccid());
        //bjwriteLog('修改密码'.md5($input->oldpwd).'==='.$account->pwd);
        if(md5($input->oldpwd)!=$account->pwd)bjerror('旧密码错误');
        bjfeature('Biz.Account',$this)->updatePwd($input->pwd);
    }


    public static function import($input){
        $name=$input->name;
        $phone= trim($input->phone);
        $tphone=trim($input->tphone);
        $recommendid=0;
        $taccount=self::loadByPhone($tphone);
        if($taccount!=null){
            $recommendid=$taccount->id;
            //echo $recommendid;exit;
        }
        //新增用户
        $fromUserId=bjfeature('primary.Account')->create([
            'account'=>$phone,
            'name'   =>$name,
            'phone'  =>$phone,
            'pwd'    =>md5('123456'),
            'recommendid'=>$recommendid,
            'regtime'=>time()
        ]);
        $num=$input->num;
        //初始化用户财富
        $toUser=bjfeature('Biz.User',$fromUserId);
        self::initialization($toUser,$num);
        //更新用户等级
        if($recommendid!=0){
            $fromUser=bjfeature('Biz.User',$recommendid);
            $fromUser->updateLevel();
        }

    }


    /*
     * 单独写一个根据用户查询他对应的头像方法
     */
    public static function loadHeadimg($id){
         return bjfeature('primary.Account')->loadHeadimg($id);
    }

}