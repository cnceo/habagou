<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================
namespace bjphp\vendor\log;

//注意：功能比较简单，适合在开发环境使用，生产环境建议关闭LOG功能或用其它的LOG类代替
class Log
{
	//写日志
	//level：级别  0:debug 1:info 2:warn 3:error
	public function write($msg, $level = 0)
	{
		$mode = bjconfig('site')['mode'];
		if( $mode == 'debug' )
		{
			$t = time();
			$file = $this->get_config()['logpath'];
			if( !file_exists($file) ) mkdir(iconv("UTF-8", "GBK", $file),0777,true);
			$file .= '/'.date('Ymd',$t).'log.txt';
			list($usec, $sec) = explode(" ", microtime());
			
			$str = "\r\n[infor]".date('Y-m-d H:i:s',time()).'.'.(intval(1000*$usec)).' '.$msg;
			error_log($str,3,$file);
		}
	}
	
	private function get_config()
	{
		return ['logpath'=> INDEX_PATH . '/log'];//暂时不用配置文件
	}
}

