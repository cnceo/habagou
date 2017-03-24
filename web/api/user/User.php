<?php

namespace api\user;

class User
{
	public static function Config()
	{
		static $config;
		if( ! $config )
		{
			$config = [
				"ModuleName"	=>	"用户管理",
				"Groups"	=>	["登录","开户","转赠小狗","超级转赠","打扫","查询账户","拜访好友",
						"用户主页","生长记录","打扫记录","喂食记录","增养记录","更新状态","查询下一级饲养员",
						"更新饲养员等级","增养最大值","喂食最大值",'我的仓库','购买一键清洗',
						'获取短信验证码','获取转赠验证码','查询财富','重置密码','更改音效状态','一键清洗',
						'更改用户头像','更改密码',"导入数据"],
				"Functions"	=>	[
					//[分组，权限]　权限取值范围：*:public -:login "":验证
					"register"			=>	["开户","-"],
					"login"		=>	["登录","*"],
					"sale"		=>	["转赠小狗","-"],
					"superSale"		=>	["超级转赠","-"],
					"clean"		=>	["打扫","-"],
					"loadSelfAccount"		=>	["查询账户","-"],
					'visitFriend'   =>  ["拜访好友","-"],
					'home'   =>  ["用户主页","-"],
					'growthrecord'=>["生长记录","-"],
					'cleanrecord'=>["打扫记录","-"],
					'feedrecord'=>["喂食记录","-"],
					'raiserecordcord'=>["增养记录","-"],
					'updataStatus'=>["更新状态","-"],
					'loadNextFeeder'=>["查询下一级饲养员","-"],
					'upgradeFeeder'=>["更新饲养员等级","-"],
					'addMax'=>["增养最大值","-"],
					'feedMax'=>["喂食最大值","-"],
					'myWarehouse'=>['我的仓库','-'],
					'buySuperclean'=>['购买一键清洗','-'],
					'superClean'=>['一键清洗','-'],
					'sendForgetpwdCode'=>['获取短信验证码','*'],
					'sendSaleCode'=>['获取转赠验证码','-'],
					'loadSelfWealth'=>['查询财富','-'],
					'updateForgetpwd'=>['重置密码','*'],
					'updateVideo'=>['更改音效状态','-'],
					'updateHeadimg'=>['更改用户头像','-'],
					'updateLoginpwd'=>['更改密码','-'],
					"import"		=>	["导入数据","*"]
				],
				"Policy"=>[
						[
							"Groups"	=>	["转赠小狗","超级转赠","打扫","查询账户","拜访好友","用户主页",
									"生长记录","打扫记录","喂食记录","增养记录","更新状态","查询下一级饲养员",
									"更新饲养员等级","增养最大值","喂食最大值",'我的仓库','购买一键清洗',
									'获取转赠验证码','查询财富','重置密码','更改音效状态','一键清洗','更改用户头像','更改密码','开户'],
							"Functions"=>[],
							"Enter"=>["policy.session.Filter.get_session"],//前置操作
							"Leave"=>[]//后置操作
						],
				],

			];
		}
		return $config;
	}

	/*
	 * 普通打扫
	 */
	public static function clean($input){
		$fromUser=bjfeature('Biz.User',$input->accid);
		$toUser=bjfeature('Biz.User',$input->toAccid);
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			$commission=$fromUser->clean($toUser);
			$connection->commit();
			return $commission;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}

	/*
	 * 超级打扫
	 */
	public static function superClean($input){
		$fromUser=bjfeature('Biz.User',$input->accid);
		return $fromUser->superClean([]);
	}

	/*
	 * 开户
	 */
    public static function register($input){
		$superUser=bjfeature('Biz.User',$input->accid);
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			$toUserId=$superUser->openAccount($input);
			$connection->commit();
			return $toUserId;
		}catch(\Exception $ex){
			$connection->rollback();
			throw $ex;
		}
	}

    /*
     * 登录
     */
	public static function login($input){
		return bjstaticcall("feature.Biz.User.login",$input);
	}

	/*
	 * 我的伙伴
	 */
	public static function partner($input){
		$user=bjcreate('feature.Biz.User',$input->accid);
		return $user->getPartner($input);
	}


	public static function validSale(&$input){
		$code=bjsession()->get('salecode');
		if(empty($code))bjerror('请先获取验证码');
		if($input->code!=$code)bjerror('验证码错误，请重新输入');
		if($input->fromAccount==$input->toAccount)bjerror('不能向同账号转赠');
		$fromAccount=bjstaticcall('feature.Biz.User.loadByAccount',$input->fromAccount);
		if(is_null($fromAccount))bjerror('转出账号不存在');
		$toAccount=bjstaticcall('feature.Biz.User.loadByAccount',$input->toAccount);
		if(is_null($toAccount))bjerror('目标账号不存在');
		if($toAccount->name!=$input->toName)bjerror('目标账号和目标姓名不匹配');
		$input->fromAccount=$fromAccount;
		$input->toAccount=$toAccount;
	}

	//转赠小狗
	public static function sale($input){
		$fromAccount=bjstaticcall('feature.Biz.Account.loadById',$input->accid);
		$input->fromAccount=$fromAccount->account;
        self::validSale($input);
		$fromUser =bjfeature('Biz.User',$input->accid);
		$fromUser =bjfeature('Biz.User',$input->accid);
		$toUser =bjfeature('Biz.User',$input->toAccount->id);
		$fromUser->getAccount()->setAccount($input->fromAccount->account);//把转赠的账号传递过去
		$toUser->getAccount()->setAccount($input->toAccount->account);//把要转赠的账号传递过去
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			$fromUser->sale($toUser,(int)$input->num);
			$connection->commit();
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}

	//超级转赠
	public static function superSale($input){
		$fromAccount=bjstaticcall('feature.Biz.Account.loadById',$input->accid);
		$input->fromAccount=$fromAccount->account;
		self::validSale($input);
		$fromUser =bjfeature('Biz.User',$input->accid);
		$toUser =bjfeature('Biz.User',$input->toAccount->id);
		$fromUser->getAccount()->setAccount($input->fromAccount->account);//把转赠的账号传递过去
		$toUser->getAccount()->setAccount($input->toAccount->account);//把要转赠的账号也传递过去
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			$result=$fromUser->superSale($toUser,(int)$input->num,$input->code);
			$connection->commit();
			return $result;
		}catch(\Exception $ex)
		{

			$connection->rollback();
			throw $ex;
		}

	}

	public static function loadSelfAccount($input){
		$fromAccount=bjstaticcall('feature.Biz.Account.loadById',$input->accid);
		$edit=bjfeature('Biz.User',$fromAccount->id)->isEdit();
        //头像
		$headimg=bjstaticcall('feature.Biz.User.loadHeadimg',$input->accid);
		//var_dump($headimg);exit;
		return ['account'=>$fromAccount->account,'edit'=>$edit,'headimg'=>$headimg->image];
	}

	public static function loadSelfWealth($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$wealth=$fromUser->loadSelfWealth();
		//头像
		$headimg=bjstaticcall('feature.Biz.User.loadHeadimg',$input->accid);
		return ['warehouse'=>$wealth->warehouse,'headimg'=>$headimg->image];
	}

	public static function visitFriend($input){
		$notices=bjstaticcall("feature.Biz.Sysnotice.loadAll");
		$toUser = bjfeature("Biz.User",$input->toUserId);
		//用户的基本信息
		$userinfo=$toUser->getUserInfo();
		$landinfo=bjfeature('Biz.Square',$toUser)->getFriendInfo();
		//传递一个标志，是否可以打扫
		$flag=$toUser->canClean();
		return ['notice'=>$notices,'userinfo'=>bjarray($userinfo),'landinfo'=>bjarray($landinfo),'clean'=>$flag];
	}

    /*
     * 升级饲养员
     */
	public static function upgradeFeeder($input){
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			$fromUser = bjfeature("Biz.User",$input->accid);
			$result=$fromUser->upgradeFeeder((int)$input->level);
			$connection->commit();
			return $result;
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}

	}

    /*
     * 查询下一个等级的成功率以及消耗成本
     */
	public static function loadNextFeeder($input){
		$level=(int)$input->level;
		if($level>=9)bjerror('已经是最高级别了');
		$NextFeeder=bjstaticcall('feature.Biz.Feeder.loadNextFeeder',$level);
		return bjarray($NextFeeder);
	}


	public static function home($input){
		$notices=bjstaticcall("feature.Biz.Sysnotice.loadAll");
		$user = bjfeature("Biz.User",$input->accid);
		//用户的基本信息
		$userinfo=$user->getUserInfo();
		//收件箱信息
		$sysmessage=$user->getSysmessage();
		$swithchvideo=$user->myVideo();
		$headimg=bjstaticcall('feature.Biz.Headimg.loadAll');
		return ['notice'=>$notices,'userinfo'=>bjarray($userinfo),'sysmessage'=>$sysmessage,'status'=>$swithchvideo,'headimg'=>$headimg];
	}
    /*
     * 生长记录
     */
	public static function growthrecord($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return bjfeature('Biz.Bonetrack',$fromUser)->loadgrowthrecord($input);
	}

	/*
     * 打扫记录
     */
	public static function cleanrecord($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return bjfeature('Biz.Cleanrecord',$fromUser)->loadcleanrecord($input);
	}

	/*
     * 喂食记录
     */
	public static function feedrecord($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return bjfeature('Biz.Hatchrecord',$fromUser)->loadhatchrecord($input);
	}


	/*
	 * 增养记录
	 */
	public static function raiserecordcord($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return bjfeature('Biz.Raiserecord',$fromUser)->loadraiserecord($input);
	}
    /*
     * 根据id更新状态
     */
	public static function updataStatus($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$Transaction=bjfeature('Biz.Transaction');
		$Transaction->setFromUser($fromUser);
		$Transaction->updataStatus($input);
	}

	/*
	 * 购买一键清洗
	 */
	public static function buySuperclean($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return $fromUser->buySuperclean();
	}


	public static function addMax($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return $fromUser->addMax($input->index);
	}

	public static function feedMax($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		return $fromUser->feedMax($input->index);
	}

    /*
     * 点击我的仓库
     */
	public static function myWarehouse($input) {
		$fromUser = bjfeature("Biz.User",$input->accid);
		$userinfo=bjarray($fromUser->getUserInfo());
		//饲养员等级以及是否购买一键清洗---》如果购买了，还有多少天可以用
		$prop=$fromUser->myProp();
		//var_dump($prop);exit;
		return ['warehouse'=>sprintf("%.2f",$userinfo['wealthinfo']['warehouse']),
				'bone'=>sprintf("%.2f",$userinfo['wealthinfo']['bone']),
				'level'=>$userinfo['accountinfo']['level'],
				'feederlevel'=>$prop['feederlevel'],
			    'day'=>$prop['day'],
			    'fee'=>$prop['fee'],
		];
	}

    /*
     *忘记密码获取验证码
     */
    public static function sendForgetpwdCode($input){
		bjstaticcall('feature.Biz.User.sendForgetpwdCode',$input->phone);
	}

	/*
     *转赠获取验证码
     */
	public static function sendSaleCode($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$fromUser->sendSaleCode($input);
	}

	/*
	 * 忘记密码-->修改密码
	 */
	public static function updateForgetpwd($input){
		bjstaticcall('feature.Biz.User.updateForgetpwd',$input);
	}

	/*
	 * 更改音效设置
	 */
	public static function  updateVideo($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$fromUser->updateVideo($input->status);
	}


	/*
	 * 更换用户头像
	 */
	public static function updateHeadimg($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$fromUser->updateHeadimg($input->headid);
	}

	/*
	 * 更改密码
	 */
	public static function updateLoginpwd($input){
		$fromUser = bjfeature("Biz.User",$input->accid);
		$fromUser->updateLoginpwd($input);
	}

	public static function import($input){
		$connection = bjstaticcall("bjphp.vendor.db.Connection.master");
		try
		{
			$connection->beginTrans();
			bjstaticcall('feature.Biz.User.import',$input);
			$connection->commit();
		}catch(\Exception $ex)
		{
			$connection->rollback();
			throw $ex;
		}
	}
}