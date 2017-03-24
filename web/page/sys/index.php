<?php
/**
 *
 * 功能：
 */

class Index
{	
	//缺省显示
	public function display()
	{
		//显示默认模版
		bjview("sys.index")->display([
			"sysnotice_url" => "/sys/showSysnotice/1",
			"sysmessage_url" => "/sys/showSysmessage/1"
			]);
	}

	//显示公告
	public function showSysnotice($sign)
	{	
		//接入api
		$data = bjapi("sys.Sys.showSysnotice",$sign);

		//将数据渲染到页面
		bjview("sys.announce")->display(["data"=>$data]);
	}

	//显示个人邮箱的所有信息
	public function showSysmessage($sign)
	{
		$data = bjapi("sys.Sys.showSysmessage",$sign);

		//将数据渲染到页面
		bjview("sys.sysmessage")->display(["data"=>$data]);
	}
	public function mima()
	 {

        $id=bjsession()->get('accid');
	 	bjview("sys.mima")->display([
	 		   
	 		   "action_url" => "/sys/mima_submit/$id",
	 		   
	 		   ]);
	 }
	 public function mima_submit($id)
	 {

	 	//取出表单数据 数据为对象
	 	$user = bjmeta("account.account")->getFormMeta();
        $user->pwd1 = md5(bjhttp()->request()->getParam("pwd1"));
        $user->pwd2 = md5(bjhttp()->request()->getParam("pwd2"));
	 	$user->id = $id;
	 	// var_dump($user);exit;
	 	//进入api层bjapi("分组，类，方法",要传递的值)
	 	bjapi("account.Account.mima",$user);
	 	bjhttp()->response()->redirect("/login/login");

	 }
}