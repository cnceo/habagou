<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 16:51
 */
namespace feature\primary;
class Wealth{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Wealth');
    }

    public function initialization($toUser,$openFee){
        $this->meta->add([
             'accid'=>$toUser->getAccid(),
             'total'=>$openFee,
             'warehouse'=>$openFee,
             'ground'=>0,
             'bone'=>0,
             'enddate'=>time()
        ]);
    }

    public static function loadByAccid($accid){
        $condition =  bjcond("t.{accid=}","accid",$accid);
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => bjmeta('wealth.Wealth')->TableName()
            ])
        )->first();
        return $data;
    }

}