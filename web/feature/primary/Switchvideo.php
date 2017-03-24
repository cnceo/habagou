<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 10:45
 */
namespace feature\primary;

class Switchvideo{
	public $meta;
	public function __construct()
	{
		$this->meta = bjmeta('account.Switchvideo');
	}

	public function updateVideo($fromUser,$status){
        $this->meta->update(['status'=>$status],['accid'=>$fromUser->getAccid()]);
	}

	public function add($fromUser,$status){
		//var_dump($fromUser);echo '==='.$status;exit;
		$this->meta->add([
				'accid'=>$fromUser->getAccid(),
				'status'=>$status
		]);
	}

	public function load($fromUser){
		return $this->meta->load(['accid'=>$fromUser->getAccid()]);
	}
}