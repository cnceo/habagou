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

class Request
{
	private $user_args = [];
	private $all_args;
	private $has_init = false;
	private $origin_url;//干净的原始文件网址，不含伪静态参数和查询参数
	
	private function init()
	{
		if ($this->has_init) return;
		$this->has_init = true;
		
		$this->all_args = array_merge($_POST,$_GET,$_COOKIE,$this->user_args);
	}
	
	//获取入参，如果$key所代表的参数没有传入，则返回缺省值 default_val
	public function getParam($key, $default_val = null)
	{
		if (is_int($key)) {
			if ($key < count($this->user_args)) return $this->user_args[$key];
		} else {
			
			//按优先顺序：用户级参数、POST、GET、Cookie
			if (isset($this->user_args[$key])) return $this->user_args[$key];
			if (isset($_POST[$key])) return $_POST[$key];
			if (isset($_GET[$key])) return $_GET[$key];
			
			if (isset($_COOKIE[$key])) return $_COOKIE[$key];
		}
		return $default_val;
	}
	
	//入参集合
	public function getParams()
	{
		$this->init();
		return $this->all_args;
	}
	
	//参数重载（主要用于单元测试）
	public function resetParam($arr)
	{
		$this->user_args = $arr;
		$this->all_args = [];
		$this->inited = true;
		return $this;
	}
	
	//手动添加参数
	public function addParam($arr)
	{
		if (is_array($arr))
			$this->user_args = array_merge($this->user_args, $arr);
		else
			$this->user_args[] = $arr;
		return $this;
	}
	
	//得到伪静态参数个数
	public function getUserParamCount(){ return count($this->user_args); }
	
	//按顺序获取伪静态参数
	public function getUserParam($index,$def=null)
	{
		if( $index < count($this->user_args) ) return $this->user_args[ $index ];
		return $def;
	}
	
	//原始网址，干净的不带参数的
	public function setOriginUrl($url)
	{
		$this->origin_url = $url;
	}
	public function getOriginUrl()
	{
		return $this->origin_url;
	}
	
	//构造URL
	public function makeUrl()
	{
		$arguments = func_get_args();
		$count = count($arguments);
		$str="";
		$url="";
		$url = $this->origin_url;
		if( substr($url,-1) != '/' ) $url .= '/';
		for($i=0;$i<$count;$i++)
		{
			if( $i > 0 ) $str .= "/";
			$str .= urlencode($arguments[$i]);
		}
		return bjfixUrl( $url . $str );
		
	}
	public function makeUrlJump()
	{
		$arguments = func_get_args();
		$count = count($arguments);
		$url="";
		if( $count > 0 ) $url = $arguments[0];
		if( substr($url,-1) != '/' ) $url .= '/';
		$str="";
		for($i=1;$i<$count;$i++)
		{
			if( $i > 1 ) $str .= "/";
			$str .= urlencode($arguments[$i]);
		}
		return bjfixUrl( $url . $str );
		
	}
	

	//[工具函数] 判断是否为移动端访问
	public function isMobile()
	{
		$regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
		$regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
		$regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
		$regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
		$regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320×320|240×320|176×220";
		$regex_match .= ")/i";
		return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
	}
	
	public function getip()
	{
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
			
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
