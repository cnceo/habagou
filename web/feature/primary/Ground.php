<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:58
 */
namespace feature\primary;
bjload('feature.primary.Wealth');
class Ground extends Wealth{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Wealth');
    }

    public function getTodayHatchWealth($user){
        $targetid=$user->accid;
        $condition = bjcond("t.accid","accid",$targetid );
        $condition = $condition->_and(bjcond("date(from_unixtime(t.hatchtime))=date(now())",'',''));
        //$condition=$condition->_or( bjcond("","","") );
        //当天已经孵化的小狗总数
        $totle = (int)bjcreate(
            "bjphp.vendor.db.Dao",
            "select sum([num]) as c from {m1} t " . $condition->where(),
            $condition->fill([ "m1" => bjmeta("meta.Hatchrecord")->TableName() ]));
        return $totle;
    }

    /*
     * 添加地面财富
     */
    public function add($user,$num){
             $accid=$user->accid;
             bjcreate(
                "bjphp.vendor.db.Dao",
                "update {m1} t set t.[ground]= t.[ground]+{deposit1}, t.[total] = t.[total]+{deposit2} where t.{accid=}",
                [
                   "m1"    =>  $this->meta->TableName(),
                   "deposit1"   =>  $num,
                   "deposit2"  =>  $num,
                   "accid" =>  $accid
                 ]
                );

    }

    /*
     * 减少地面财富
     */
    public function reduce($user,$num){
        //更新数据
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "update {m1} t set t.[total]=t.[total]-{param1},t.[ground]=t.[ground]-{param2} where {accid=}",
            [ 
                "m1" => $this->meta->TableName(),
                "param1" => $num,
                "param2" => $num,
                "accid" => $user->accid
            ]
        );
    }


}