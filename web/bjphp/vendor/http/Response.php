<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================
namespace bjphp\vendor\http;


class Response 
{
	//输出内容
	public function write($str)
	{
		echo $str;
	}
	
	//跳转
	public function redirect($url)
	{
		ob_clean();
		header( 'location:'.bjfixUrl($url) );
	}
	
}
