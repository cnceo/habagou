<?php
//include bjpathJoin(CLASS_ROOT,"/vendor/253/send.php");
class Index
{
	//缺省显示
	/*public function display()
	{
		//echo '===';
		/*bjview("user.login")->display([
		]);
	}*/

	//添加页面
	public function add()
	{

		bjview("user.add")->display([
			"action_url"	=>	"/user/add_submit",
			"user" => [],
			]);
	}

	//添加页面 之 提交
	public function add_submit()
	{
		/*
		$name = bjhttp()->request()->getParam("name");
		$age = bjhttp()->request()->getParam("age");
		*/
		//简洁的写法
		$user = bjmeta("user.user")->getFormMeta();
		//因为例子中没有pwd字段，而表结构中要求有此字段，所以手工加入，作为演示
		//$user->pwd = "test";
		//$user->regtime = date("Y-m-d H:i:s");
		bjapi("user.User.add",$user);
		//保存到数据库
		//bjmeta("user.user")->add($user);
		bjview("user.ok")->display([]);
	}

	//删除页面
	public function remove()
	{
		bjapi("user.User.remove",["id"=>4]);
		echo "remove ok";
	}

	//修改页面
	public function edit($id)
	{
		$user = bjmeta("user.user")->load(["id"=>4]);//and
		bjview("user.add")->display([
			"user" => $user,
			"action_url" => "/user/edit_submit/$id"
			]);
	}

	//修改页面 之 提交
	public function edit_submit($id)
	{
		//简洁的写法
		$user = bjmeta("user.user")->getFormMeta();
		bjmeta("user.user")->update($user,["id"=>$id]);
		echo "update ok!";
	}

	public function test(){
		bjapi("user.User.myWarehouse",[]);
		//$result=\send::sendCode('15975596745','test');
		//var_dump($result);
		//echo 'aaa';
		//bjsession()->set('accid',1);
		//$feeder=bjstaticcall('feature.Biz.Feeder.myWarehouse',5);
		//var_dump($feeder);
		//$propspeed=(double)$feeder->rate;
	}

	public function import()
	{
		$xls = bjcreate("bjphp.vendor.excel.Excel");
		$xls->importFromFile("D:/6.xlsx",true,function($row){
			echo $row['name'].'==='.$row['phone'].'==='.$row['tphone'].'==='.$row['num']."<br>";
			/*$base = [
					"perday"	=>	$row['日期'],
					'cycle'		=>	$row['周期'],
					"n1"		=>	$row['n1'],
					"n2"		=>	$row['n2'],
					"n3"		=>	$row['n3'],
					"n4"		=>	$row['n4'],
					"n5"		=>	$row['n5'],
			];*/
			bjapi('user.User.import',$row);
		});
		echo "导入成功！";
	}
}
