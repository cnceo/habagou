<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\session;

class Session
{
	public static function system_init()
	{
		session_start();
		session_name("bjssnid");
	}
	
	public static function system_clean()
	{
		//
	}
	
	//清空并销毁
	public function destroy()
	{
		session_unset();
		session_destroy();
	}
	
	//按组管理 key-value ，避免冲突
	//key:值  group:所属组
	public function set($key, $val)
	{
		$_SESSION[ $key ] = $val;
	}
	
	//获取值
	public function get($key)
	{
		if( isset($_SESSION[ $key ] ) ) return $_SESSION[$key];
		return '';
	}
}
