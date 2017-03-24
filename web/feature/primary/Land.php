<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:09
 */
namespace feature\primary;
class Land{

	public $meta;
	public function __construct()
	{
		$this->meta = bjmeta('wealth.Land');
	}



	//对land表新增一条记录
	public function add($user,$index,$type=0,$commision){
		//获得草地类型
		//添加Land数据

		$this->meta->save([
			'accid' => $user->getAccid(),
			'landindex' => $index,
			'landtype' => $type,
			'deposit' => $commision,
			'wealth' => $commision,
			'starttime' => time()
		]);
		return bjstaticcall("bjphp.vendor.db.Connection.master")->lastID();
	}

	public function addWealth($land,$num){
		 $m=$this->meta;
    	 bjcreate(
    		"bjphp.vendor.db.Dao",
    		"update {m1} t set t.[wealth]=t.[wealth]+{deposit} where t.{accid=} and t.{landindex=}",
    		[
    		   "m1"         =>  $m->TableName(),
    		   "deposit"   =>  $num,
    		   "accid"     =>  $land->getUser()->accid,
               "landindex"=>$land->getIndex()
    		]
		 );
	}

	public function reduceWealth($land,$num){
		 $id=$land->getId();
    	 bjcreate(
    		"bjphp.vendor.db.Dao",
    		"update {m1} t set t.[wealth]=t.[wealth]-{deposit} where t.{id=}",
    		[
    		   "m1"      =>  $this->meta->TableName(),
    		   "deposit"   =>  $num,
    		   "id"   =>  $id
    		]
    	);
	}

	public function load($land){
        $accid=$land->getUser()->getAccid();
		$index=$land->getIndex();
		//根据草地索引以及会员accid
		$condition = bjcond("t.{accid=}","accid",$accid);
		$condition = $condition->_and( bjcond( "t.{landindex=}","landindex",$index));
		$data = bjcreate(
			"bjphp.vendor.db.Dao",
			"select * from {m1} t".$condition->where(),
			$condition->fill([
				"m1" => $this->meta->TableName()
			])
		)->first();
		return $data;
	}

	public function getAllInfo($user){
		$condition = bjcond('t.{accid=}','accid',$user->getAccid());
		$data = bjcreate(
				'bjphp.vendor.db.Dao',
				'select t.landindex,t.wealth,l.type,l.deposit,l.capacity,l.dognum,t.starttime from {m1} t JOIN landset l ON t.landindex=l.index'.$condition->where(),
				$condition->fill([
						'm1' => $this->meta->TableName()
				])
		)->all();
		return $data;
	}

	public function isOpen($land){
         return $this->meta->load(['accid'=>$land->getUser()->getAccid(),'landindex'=>$land->getIndex()]);
	}

	public function getOpenLand($user){
		return $this->meta->loadAll(['accid'=>$user->getAccid()]);
	}

	public function popup($land){
		//当前草地财富
        $wealth=$this->meta->load(['accid'=>$land->getUser()->getAccid(),'landindex'=>$land->getIndex()],['wealth']);
		//var_dump($wealth);
		$Bonetrack=bjmeta('wealth.Bonetrack');
		//历史产蛋
		$history=0;
		$condition = bjcond('t.{accid=}','accid',$land->getUser()->getAccid());
		$condition = $condition->_and(bjcond( "t.{landindex=}","landindex",$land->getIndex()));
		$condition = $condition->_and(bjcond( "t.{get=}","get",1));
		$bones = bjcreate(
				'bjphp.vendor.db.Dao',
				'select sum([num]) as c from {m1} t '.$condition->where(),
				$condition->fill([
						'm1' => $Bonetrack->TableName()
				])
		)->first();
		if($bones!=null)$history=(double)$bones->c;
		//本日产蛋
		$today=0;
		$condition = $condition->_and(bjcond("date(from_unixtime(t.bonedate))=date(now())",'',''));
		$bone = bjcreate(
				'bjphp.vendor.db.Dao',
				'select sum([num]) as c from {m1} t '.$condition->where(),
				$condition->fill([
						'm1' => $Bonetrack->TableName()
				])
		)->first();
		if($bone!=null)$today=(double)$bone->c;
		return ['wealth'=>$wealth->wealth,'history'=>$history,'today'=>$today];
	}

	public function getLandInfo($user){
		$condition = bjcond('t.{accid=}','accid',$user->getAccid());
		$lands = bjcreate(
				'bjphp.vendor.db.Dao',
				'select *  from {m1} t '.$condition->where(),
				$condition->fill([
						'm1' => $this->meta->TableName()
				])
		)->all();
		return $lands;
	}


}