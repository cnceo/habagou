<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:01
 */
namespace feature\Biz;
class Land{
    private $bonewealth;//骨头财富
    private $landwealth;//地面财富
    private $index;//索引
    private $user;//用户
    private $id;//草地唯一编号
    public function __construct($user,$index)
    {
        $this->user=$user;
        $this->index=$index;
    }

    public static function getConfig()
    {
        return [
            'open'	=>	[
                'Enter' => ['feature.Biz.Land.enterOpen'],
                'Leave' => ['feature.Biz.Land.leaveOpen']
            ],
            'addOxygen'	=>	[
                'Enter' => ['feature.Biz.Land.enterAddOxygen'],
                'Leave' => ['feature.Biz.Land.leaveAddOxygen']
            ],
            'feed'	=>	[
                'Enter' => ['feature.Biz.Land.enterFeed'],
                'Leave' => ['feature.Biz.Land.leaveFeed']
            ],
            'harvest'=>	[
                'Enter' => ['feature.Biz.Land.enterHarvest'],
                'Leave' => ['feature.Biz.Land.leaveHarvest']
            ],
            'hitPop'=>	[
                'Enter' => ['feature.Biz.Land.enterHitPop'],
                'Leave' => ['feature.Biz.Land.leaveHitPop']
            ],
        ];
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

    public function getIndex(){
        return $this->index;
    }

    public function getUser(){
        return $this->user;
    }

    public function isOpened(){

    }

    //开地逻辑前置操作
    public static function enterOpen($_this,$in_args)
    {
        //首先要判断是否开过这块地
         $isopen=$_this->isOpen();
         if($isopen!=null)bjerror('请勿重复开地');
        //根据地面索引查询出地块的类型和押金
         $landset=bjfeature('Biz.Landset',$_this->index)->getLandset();
         $in_args[0]=$landset;
         $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getUser()->getAccid());
         if((double)$fromWealth->warehouse<(int)$in_args[0]->deposit)bjerror('仓库小狗数目不足');
         $in_args[1]=$fromWealth;
         return $in_args;
    }

    //判断用户是否开过这块地
    public function isOpen(){
         return bjfeature('primary.Land')->isOpen($this);

    }


    //开地逻辑
    public function open($landset){
        $deposit=(int)$landset->deposit;
         //仓库减
        $this->user->getWarehouseWealth()->reduce($deposit);
        //地面加
        $this->user->getGroundWealth()->add($deposit);
        //添加开地表
        $this->add($landset);
    }

    //开地后置方法
    public static function leaveOpen($_this,$in_args,$ret){
        //仓库财富的变化
        $num=(int)$in_args[0]->deposit;
        $fromWealth=$in_args[1];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getUser()->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->warehouse);
        $Wealthtrack->setAftervalue((double)$fromWealth->warehouse-$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("开".$_this->index."号地,仓库减少".$num.'条小狗');
        $Wealthtrack->add();

        //地面财富的变化
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getUser()->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->ground);
        $Wealthtrack->setAftervalue((double)$fromWealth->ground+$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(2);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("开".$_this->index."号地,地面新增".$num.'条小狗');
        $Wealthtrack->add();

        bjfeature('Biz.Sysmessage',$_this->getUser())->add('开地消耗'.$num.'小狗');

        return 1;//返回小狗数量
    }


    //新增地
    public function add($landset){
         bjfeature('primary.Land')->add($this->user,$this->index,$landset->type,$landset->deposit);
    }

    /*
     * 增养前置操作
     */
    public static function enterAddOxygen($_this,$in_args)
    {
        //首先要判断是否开过这块地
        $toLand=$_this->isOpen();
        if(is_null($toLand))bjerror('请先开地');
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->user->accid);
        $num=(int)$in_args[0];
        if((double)$fromWealth->warehouse<$num)bjerror('仓库小狗数目不足');
        $landset=bjfeature('Biz.Landset',$_this->index)->getLandset();
        $surplus=(int)$landset->capacity-(int)$toLand->wealth;
        if($num>$surplus)bjerror('增养数量超过狗屋容量');
        $in_args[1]=(int)$landset->dognum;//每多少数量对应草地上一条狗
        $in_args[2]=(int)$toLand->wealth;//当前草地财富
        $in_args[3]=$fromWealth;
        $in_args[4]=$landset;
        return $in_args;
    }

    /*
     * 增养
     */
    public function addOxygen($num,$dognum,$wealth){
         //仓库减
        $this->user->getWarehouseWealth()->reduce($num);
         //地面加
        $this->user->getGroundWealth()->add($num);
        //当前草地财富加
        $this->addWealth($num);
        //返回增养后当前草地应该有多少条小狗
        $Remainder=($wealth+$num)%$dognum;
        if($Remainder==0){
            $ret=(int)(($wealth+$num)/$dognum);
        }else{
            $ret=(int)(($wealth+$num)/$dognum)+1;
        }

        /*echo '当前财富'.$log;
        echo '增养财富'.$num;
        echo '每条狗对应的数量'.$dognum;
        echo '狗的数量'.$ret;*/
        return $ret;
    }

    /*
     * 增养后置操作
     */
    public static function leaveAddOxygen($_this,$in_args,$ret)
    {

        //仓库财富的变化
        $num=$in_args[0];
        $fromWealth=$in_args[3];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->user->accid);
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->warehouse);
        $Wealthtrack->setAftervalue((double)$fromWealth->warehouse-$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("增养".$_this->index."号地,仓库减少".$num.'条小狗');
        $Wealthtrack->add();

        //地面财富的变化
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->user->accid);
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->ground);
        $Wealthtrack->setAftervalue((double)$fromWealth->ground+$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(2);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("增养".$_this->index."号地,地面新增".$num.'条小狗');
        $Wealthtrack->add();

        //增加增养记录
        bjstaticcall('feature.Biz.Raiserecord.add',[
            'accid'=>$_this->user->getAccid(),
            'num'  =>$num,
            'landindex' =>$_this->index,
            'type' =>$in_args['4']->type,
            'hatchtime'=>time()
        ]);

        return $ret;
    }

    /*
     * 孵化前置操作
     */
    public static function enterFeed($_this,$in_args)
    {
        $toLand=$_this->isOpen();
        if(is_null($toLand))bjerror('请先开地');
        $num=(int)$in_args[0];
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getUser()->getAccid());
        //if((int)$fromWealth->enddate<(int)time())bjerror('骨头已经失效');
        if((int)$fromWealth->bone<=0)bjerror('没有可驯化的狗崽');
        if((int)$fromWealth->bone<$num)bjerror('驯化的狗崽大于可用的狗崽');
        $landset=bjfeature('Biz.Landset',$_this->index)->getLandset();
        $surplus=(int)$landset->capacity-(int)$toLand->wealth;
        if($num>$surplus)bjerror('驯化的狗崽超过狗屋容量');
        $in_args[1]=$fromWealth;
        $Remainder=(((int)$toLand->wealth+$num)%(int)$landset->dognum);
        if($Remainder==0){
            $ret=(int)(((int)$toLand->wealth+$num)/(int)$landset->dognum);

        }else{
            $ret=(int)(((int)$toLand->wealth+$num)/(int)$landset->dognum)+1;
        }
        $in_args[2]=$ret;
        $in_args[3]=$landset;
        return $in_args;
    }

    /*
     * 孵化
     */

    public function feed($num){
         //骨头财富减
        $this->user->getBonewealth()->reduce($num);
        //地面财富加
        $this->user->getGroundWealth()->add($num);
        //当前地面当前财富增加
        $this->addWealth($num);
    }


    /*
     *孵化后置操作
     */
    public static function leaveFeed($_this,$in_args,$ret)
    {
        //仓库财富的变化
        $num=$in_args[0];
        $fromWealth=$in_args[1];
        //$in_args[2]=(int)$toLand->log;
        //$in_args[3]=(int)$landset->dognum;
        //地面财富的变化
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->user->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->ground);
        $Wealthtrack->setAftervalue((double)$fromWealth->ground+$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(2);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("孵化".$_this->index."号地,地面增加".$num.'条小狗');
        $Wealthtrack->add();

        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->bone);
        $Wealthtrack->setAftervalue((double)$fromWealth->bone-$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(3);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("孵化".$_this->index."号地,骨头减少".$num.'条狗仔');
        $Wealthtrack->add();

        //要添加孵化记录
        bjstaticcall('feature.Biz.Hatchrecord.add',[
            'accid'=>$_this->user->getAccid(),
            'num'=>$num,
            'landindex'=>$_this->index,
            'type'=>$in_args[3]->type,
            'hatchtime'=>time()
        ]);
        return $in_args[2];//返回当前草地显示小狗的数量
    }

    /*
     * 收获前置操作
     */
    public static function enterHarvest($_this,$in_args)
    {
        //首先要判断是否开过这块地
        $toLand=$_this->isOpen();
        if(is_null($toLand))bjerror('请先开地');
        $num=(int)$toLand->wealth-(int)$toLand->deposit;
        if($num==0)bjerror('没有可收获的小狗');
        $_this->setId((int)$toLand->id);
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->user->accid);
        $in_args[0]=$num;
        $in_args[1]=$fromWealth;
        return $in_args;
    }

    /*
     * 收获
     */
    public function harvest($num){
        //更新当前地面的财富值
        $this->reduceWealth($num);
         //地面财富减
        $this->user->getGroundWealth()->reduce($num);
        //仓库财富加
        $this->user->getWarehouseWealth()->add($num);
        return $num;
    }

    /*
     * 收获后置操作
     */
    public static function leaveHarvest($_this,$in_args,$ret)
    {
        //仓库财富的变化
        $num=$in_args[0];
        $fromWealth=$in_args[1];
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->user->accid);
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->warehouse);
        $Wealthtrack->setAftervalue((double)$fromWealth->warehouse+$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(1);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("收获".$_this->index."号地,仓库增加".$num.'条小狗');
        $Wealthtrack->add();

        //地面财富的变化
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->user->accid);
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($num);
        $Wealthtrack->setBeforevalue($fromWealth->ground);
        $Wealthtrack->setAftervalue((double)$fromWealth->ground-$num);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(2);//1仓库   2地面   3骨头
        $Wealthtrack->setRemark("收获".$_this->index."号地,地面减少".$num.'条小狗');
        $Wealthtrack->add();

        return $ret;//返回当前草地显示小狗的数量
    }

    public function load(){
        return bjfeature('primary.Land')->load($this);
    }

    public function addWealth($num){
        return bjfeature('primary.Land')->addWealth($this,$num);
    }

    public function reduceWealth($num){
        return bjfeature('primary.Land')->reduceWealth($this,$num);
    }

    public function getAllInfo(){
        return bjfeature('primary.Land')->getAllInfo($this->user);
    }

    public function popup(){
        //var_dump($this->getUser());echo $this->getUser()->getAccid();exit;
        $land=$this->load();
        if(empty($land))bjerror('改草地尚未开地');
        return bjfeature('primary.Land')->popup($this);
    }

    public static function enterHitPop($_this,$in_args)
    {
         $bonetrack=$_this->isPopget();
         if((int)$bonetrack->get==1)bjerror('该草地已经领取过小狗');
         $in_args[0]=$bonetrack;
         return $in_args;
    }

    public function hitPop($bonetrack){
        //骨头添加
        $this->getUser()->getBonewealth()->add((double)$bonetrack->num);
        bjfeature('primary.Bonetrack')->hitPop($this,$bonetrack);
        return (double)$bonetrack->num;
    }

    //点击后置方法
    public static function leaveHitPop($_this,$in_args,$ret){
        //添加骨头变化记录
        $fromWealth=bjstaticcall('feature.Biz.Wealth.loadByAccid',$_this->getUser()->getAccid());
        $Wealthtrack=bjfeature('Biz.Wealthtrack');
        $Wealthtrack->setAccid($_this->getUser()->getAccid());
        $Wealthtrack->setScale(0);
        $Wealthtrack->setValue($in_args[0]->num);
        $value=(double)$fromWealth->bone-(double)$in_args[0]->num;
        $Wealthtrack->setBeforevalue($value);
        $Wealthtrack->setAftervalue($fromWealth->bone);
        $Wealthtrack->setTracktime(time());
        $Wealthtrack->setType(3);
        $Wealthtrack->setRemark('点击'.$in_args[0]->landindex.'号地泡泡获得'.$in_args[0]->num.'狗仔');
        $Wealthtrack->add();
        return $ret;
    }

    public function isPopget(){
        return bjfeature('primary.Bonetrack')->isPopget($this);
    }

}