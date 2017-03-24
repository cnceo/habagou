<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 0:13
 */
namespace feature\Biz;
class Rate{

    public function loadSevenday(){
        $rates=bjfeature('primary.Rate')->loadSevenday();
        return $rates;
    }

    /*
     * 获取用户昨天利率   影响利率的因素:基础等级+会员等级加速比例+饲养员加速比例
     */
    public function loadYesterdayRate($user){
        $yesterdayrate=bjfeature('primary.Rate')->loadBasic();
        if(is_null($yesterdayrate)){
            //说明没有设置，那么就调用通用的概率
            $generalrate=bjstaticcall('feature.Biz.Sysparameter.loadByName','generalrate');
            $basic=(double)$generalrate->value;
        }else{
            $basic=(double)$yesterdayrate->rate;
        }
        //echo '基础利率'.$basic;exit;
        $account=bjstaticcall('feature.Biz.Account.loadById',$user->getAccid());
        $levelspeed=0;
        if((int)$account->level>1&&(int)$account->level<=5){
            $level=(int)$account->level;//配合前端命名
            $speedlevel=bjstaticcall('feature.Biz.Sysparameter.loadByName','speedlevel'.$level);
            if($speedlevel!=null){
                $levelspeed=(double)$speedlevel->value;
            }
        }
        //echo '会员等级加速比例'.$levelspeed;exit;
        $feeder=bjstaticcall('feature.Biz.Prop.loadByName','feeder');
        $accprop=bjfeature('Biz.Accprop',$user)->loadSpeed($feeder);
        $propspeed=0;
        if($accprop!=null){
            $feeder=bjstaticcall('feature.Biz.Feeder.loadFeedBylevel',$accprop->level);
            if(!empty($feeder)){
                $propspeed=(double)$feeder->rate;
            }

        }
        $total=(double)$basic+$levelspeed+$propspeed;

        //echo  'basic'.$basic.'level'.$levelspeed.'breeder'.$propspeed.'total'.$total;
        return ['basic'=>$basic,'level'=>$levelspeed,'breeder'=>$propspeed,'total'=>$total];
    }
}