<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 10:45
 */
namespace feature\primary;

class Sysmessage{
	public $meta;
	public function __construct()
	{
		$this->meta = bjmeta('sys.Sysmessage');
	}
	 /*
     * 添加骨头
     */
    public function add($input){
    	$this->meta->add($input);
    }


	public function loadAll($fromUser){
		$condition = bjcond("t.{accid=}","accid",$fromUser->getAccid());
		$data = bjcreate(
				"bjphp.vendor.db.Dao",
				"select * from {m1} t ".$condition->where().' order by t.id desc' ,
				$condition->fill([
						"m1" => $this->meta->TableName()
				])
		)->all();
		return $data;
	}

}