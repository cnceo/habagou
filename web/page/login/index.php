<?php

class Index
{
	//缺省显示
	public function display()
	{
		bjview("user.index")->display([
			"login_url"  =>  "/login/login",
		]);
	}
	//登录
	public function login()
	 {

	 	bjview("account.login")->display([
	 		   
	 		   "action_url" => "/login/login_submit",
	 		   ]);


	 }
	 	 public function login_submit()
	 {
	 	//取出表单数据 数据为对象
	 	$user = bjmeta("account.account")->getFormMeta();
	 	
	 	// var_dump($user);exit;
	 	//进入api层bjapi("分组，类，方法",要传递的值)
	 	bjapi("account.Account.login",$user);
	 	bjhttp()->response()->redirect("/sys");

	 }


}
