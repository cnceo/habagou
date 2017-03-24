<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================
namespace bjphp\vendor\file;

class File
{
	public static function formatBytes($size) {
		$units = array(' B', ' KB', ' MB', ' GB', ' TB');
		$p = $size;
		for ($i = 0; $p >= 1024 && $i < 4; $i++) $p = $p / 1024;
		
		return round($p, 2).$units[$i];
	}
}