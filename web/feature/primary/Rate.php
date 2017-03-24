<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 10:40
 */
namespace feature\primary;
class Rate{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.RateSet');
    }
    /*
     * 查询出8天,当天的就去掉
     */
    public function loadSevenday(){
        /*$today=strtotime(date('Y-m-d'));
        //strtotime(date('Y-m-d',strtotime('+1 day')))
        for($i=0;$i<365;$i++){

        }*/
        //select * from rateSet  where date_sub(curdate(), INTERVAL 7 DAY) <= date(from_unixtime(`ratedate`))
        $condition = bjcond("date_sub(curdate(), INTERVAL 8 DAY) <= date(from_unixtime(`ratedate`))",'','');
        $data = bjcreate(
            'bjphp.vendor.db.Dao',
            'select * from {m1} '.$condition->where().' order by `ratedate` asc',
            $condition->fill([
                'm1' => $this->meta->TableName()
            ])
        )->all();
        return $data;
    }


    public function loadBasic(){
        $condition = bjcond("date_sub(curdate(),interval 1 day) = date(from_unixtime(`ratedate`))",'','');
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select t.rate  from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }
}