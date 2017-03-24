<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:58
 */
namespace feature\primary;
bjload('feature.primary.Wealth');
class Warehouse extends Wealth{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Wealth');
    }
    /*
     * 添加仓库值
     */
    public function add($user,$num){
        bjcreate(
            "bjphp.vendor.db.Dao",
            "update {m1} t set t.[warehouse] = t.[warehouse]+{deposit1} , t.[total] = t.[total]+{deposit2} where t.{accid=}",
            [
                "m1"	=>	$this->meta->TableName(),
                "deposit1"	=>	$num,
                "deposit2"	=>	$num,
                "accid"	=>	$user->accid
            ]
        );
    }

    /*
     * 减仓库值
     */
    public function reduce($user,$num){
        bjcreate(
            "bjphp.vendor.db.Dao",
            "update {m1} t set t.[warehouse] = t.[warehouse]-{deposit1} ,t.[total] = t.[total]-{deposit2} where t.{accid=}",
            [
                "m1"	=>	$this->meta->TableName(),
                "deposit1"	=>	$num,
                "deposit2"	=>	$num,
                "accid"	=>	$user->getAccid()
            ]
        );
    }
}