<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/18
 * Time: 14:18
 */
namespace feature\Biz;
class Square{
    private $user;//用户
    private $land;
    public function __construct($user)
    {
        $this->user=$user;
    }

    public static function getConfig()
    {
        return [
            'getAllInfo'	=>	[
                'Leave' => ['feature.Biz.Square.leaveGetAllInfo']
            ],
        ];
    }

    public function getAllInfo(){
        $lands=bjfeature('primary.Land')->getAllInfo($this->user);
        if($lands!=null){
            foreach($lands as $k=>$v){
                bjfeature('Biz.Bonetrack',$this->user)->judgePop($v);
                if((int)$v->dognum==0){
                    $v->dog=0;
                }else{
                    $remainder=(int)$v->wealth%(int)$v->dognum;
                    if($remainder==0){
                        $dog=(int)((int)$v->wealth/(int)$v->dognum);
                    }else{
                        $dog=(int)((int)$v->wealth/(int)$v->dognum)+1;
                    }
                    $v->dog=$dog;
                }
            }
            return $lands;
       }
    }

    public static function leaveGetAllInfo($_this,$in_args,$lands){
        //这里要做判断，是否是当天第一次进入需要插入骨头记录表
        if(!empty($lands)){
            $result=bjfeature('Biz.Bonetrack',$_this->user)->judgeTodayFirst();
            //var_dump($result);exit;
            if(empty($result)){
                //这里需要做插入骨头记录表操作
                bjfeature('Biz.Bonetrack',$_this->user)->addbatch($lands);
            }
            foreach($lands as $k=>$v){
                bjfeature('Biz.Bonetrack',$_this->user)->judgePop($v);
                if((int)$v->dognum==0){
                    $v->dog=0;
                }else{
                    $remainder=(int)$v->wealth%(int)$v->dognum;
                    if($remainder==0){
                        $dog=(int)((int)$v->wealth/(int)$v->dognum);
                    }else{
                        $dog=(int)((int)$v->wealth/(int)$v->dognum)+1;
                    }
                    $v->dog=$dog;
                }
            }
            //这里还需要做一个判断，过滤一下是否加入泡泡pop
            //$lands=bjfeature('Biz.Bonetrack',$_this->user)->judgePop($ret);
            return $lands;
        }
    }

    public function getFriendInfo(){
        $lands=bjfeature('primary.Land')->getAllInfo($this->user);

        foreach($lands as $k=>$v){
            if((int)$v->dognum==0){
                $v->dog=0;
            }else{
                $Remainder=(int)$v->wealth%(int)$v->dognum;
                if($Remainder==0){
                    $dog=(int)((int)$v->wealth/(int)$v->dognum);
                }else{
                    $dog=(int)((int)$v->wealth/(int)$v->dognum)+1;
                }
                $v->dog=$dog;
            }
        }

        return $lands;
    }

    public function getOpenLand(){
        return bjfeature('primary.Land')->getOpenLand($this->user);
    }


    public function getLandInfo(){
        return bjfeature('primary.Land')->getLandInfo($this->user);
    }

    /*
     * 拜访好友获取好友开地信息
     */
    public function getToUserLandInfo(){
        $lands=bjfeature('primary.Land')->getAllInfo($this->user);

    }

}