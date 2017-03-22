<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\secure;

class Auth
{
	//验证是否已登录
	//此处为缺省实现，不同业务系统请重载
	public static function isLogin()
	{
		return false;
	}
	
	//验证是否有相应权限
	//此处为缺省实现，不同业务系统请重载
	public static function hasRight($right_name)
	{
		return false;
	}
	
	//用户登录
	//此处为缺省实现，不同业务系统请重载
	public static function login()
	{
		bjerror('登录未实现');
	}
	
	//超级管理员登录
	//此处为缺省实现，建议重载
	public static function superLogin($super_name,$super_pwd)
	{
		$cfg = bjconfig('site')['superadmin'];
		if( !($super_name == $cfg['name'] && md5($super_pwd) == $cfg['pwd']) )
			bjerror('帐号或密码错误！');
		
		$session = bjsession();
		$session->Set('is_super',1);
		//$session->Set('userid',$super_name);
		$session->Set('alias','超级管理员');
		$session->Set('login_time',time());
	}
}

