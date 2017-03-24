<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 10:45
 */
namespace feature\primary;

class Bone{
	public $meta;
	public function __construct()
	{
		$this->meta = bjmeta('wealth.Wealth');
	}
	 /*
     * 添加骨头
     */
    public function add($user,$num){
    	$accid=$user->accid;
    	$m=$this->meta;
    	bjcreate(
    		"bjphp.vendor.db.Dao",
    		"update {m1} t set t.[bone]=t.[bone]+{deposit} where t.{accid=}",
    		[
    		   "m1"      =>  $m->TableName(),
    		   "deposit"   =>  $num,
    		   "accid"   =>  $accid
    		]
    	);
    }

    public function reduce($user,$num){
    	$data = bjcreate(
    		"bjphp.vendor.db.Dao",
    		"update {m1} t set t.[bone]=t.[bone]-{param} where {accid=}",
    		[
    			"m1" => $this->meta->TableName(),
    			"param" => $num,
    			"accid" => $user->accid
    		]
    	);
    }

	public function updateBone($user,$bone,$enddate){
	     $this->meta->update(['bone'=>$bone,'enddate'=>$enddate],['accid'=>$user->getAccid()]);
    }
}