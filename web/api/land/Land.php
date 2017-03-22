<?php

namespace api\land;

class Land
{
	public static function Config()
	{
		static $config;
		if( ! $config )
		{
			$config = [
				"ModuleName"	=>	"狗屋管理",
				"Groups"	=>	["开地","增养","孵化","收获","狗屋中心","产蛋记录","点击泡泡"],
				"Functions"	=>	[
					//[分组，权限]　权限取值范围：*:public -:login "":验证
					"create"		=>	["开地","-"],
					"addOxygen"   =>   ["增养","-"],
					"feed"         =>   ["孵化","-"],
					"harvest"      =>   ["收获","-"],
					"home"         =>   ["狗屋中心","-"],
					"popup"         =>   ["产蛋记录","-"],
					"hitPop"       =>    ["点击泡泡","-"]
				],
				"Policy"=>[
					[
							"Groups"	=>	["开地","增养","孵化","收获","狗屋中心","产蛋记录","点击泡泡"],
							"Functions"=>[],
							"Enter"=>["policy.session.Filter.get_session"],//前置操作
							"Leave"=>[]//后置操作
					]
				],
			];
		}
		return $config;
	}
	
	private static function module()
	{
		return bjcreate('feature.Biz.land');
	}
	
	//=== 读取
	public static function load($input)
	{
		return self::module()->load(["id"=>$input->id]);
	}
	
	//=== 开地
	public static function create($input)
	{
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		$user =bjfeature("Biz.User",$input->accid);
		try
		{
			$connection->beginTrans();
			$ret=$user->getLandByIndex($input->index)->open([]);
			$connection->commit();
			return $ret;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}

	}

	//增氧
	public static function addOxygen($input)
	{
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		$user =bjfeature("Biz.User",$input->accid);
		try
		{
			$connection->beginTrans();
			$ret=$user->getLandByIndex($input->index)->addOxygen($input->num);
			$connection->commit();
			return $ret;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}
	
	//孵化--->把骨头放到地方的过程
	public static function feed($input){
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		$user =bjfeature("Biz.User",$input->accid);
		try
		{
			$connection->beginTrans();
			$ret=$user->getLandByIndex($input->index)->feed($input->num);
			$connection->commit();
			return $ret;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}

	//收获
	public static function harvest($input){
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		$user =bjfeature("Biz.User",$input->accid);
		try
		{
			$connection->beginTrans();
			$ret=$user->getLandByIndex($input->index)->harvest();
			$connection->commit();
			return $ret;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}

	/*
	 * 进入狗场
	 */
	public static function home($input){
		//系统公告
		$notices=bjstaticcall("feature.Biz.Sysnotice.loadAll");
		$fromUser = bjfeature("Biz.User",$input->accid);
		//用户的基本信息
		$userinfo=$fromUser->getUserInfo();
		//用户的开地信息
		$landinfo=bjfeature('Biz.Square',$fromUser)->getAllInfo();
		//我的伙伴
		$friend=$fromUser->myFriend();
		//饲养员等级以及是否购买一键清洗---》如果购买了，还有多少天可以用
		//$prop=$user->myProp();
		$swithchvideo=$fromUser->myVideo();
		//exit;
		return ['notice'=>$notices,'userinfo'=>bjarray($userinfo),'landinfo'=>bjarray($landinfo),'friendinfo'=>$friend,'status'=>$swithchvideo];

	}

    /*
     * 拜访朋友狗屋
     */
	public static function partnerIndex($input){
		$fromUser = bjcreate("feture.Biz.User",$input->accid);
		$toUser = bjcreate("feture.Biz.User".$input->parterid);
		//用户的基本信息
		$userinfo=$toUser->getAccount()->getAccountInfo();
		exit;
		//用户的开地信息
		$landinfo=$toUser->getSquare()->getAllInfo();

		//这里还查询返回今天是否已经打扫
		$isClean=$fromUser->isCeanPartner($toUser);
		return ['userinfo'=>$userinfo,'landinfo'=>$landinfo,'clean'=>$isClean];
	}


	/*
	 * 用户点击单个草地弹出的框
	 */
	public static function popup($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		//var_dump($fromUser);
        $info=bjfeature("Biz.Land",$fromUser,$input->index)->popup();
		//var_dump(bjarray($info));
		return bjarray($info);
	}

	/*
	 * 点击下方增氧  孵化  优化异步查询操作
	 */
	public static function queryLand($input){
		$user = bjfeature("Biz.User",$input->accid);
		$landinfo=bjfeature('Biz.Square',$user)->getAllInfo();
		return $landinfo;
	}

	public static function hitPop($input){
		$user = bjfeature("Biz.User",$input->accid);
		$num=bjfeature('Biz.Land',$user,$input->index)->hitPop([]);
		return $num;
	}

	public static function test(){

	}


}