<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================
namespace bjphp\vendor\featureproxy;

class Proxy 
{
	private $obj;
	private $cls_path;
	private $args;
	private $config;
	
	public function __construct($obj,$cls_path,$args)
	{
		$this->obj = $obj;
		$this->cls_path = $cls_path;
		$this->args = $args;
		if( method_exists($obj,"getConfig") )
			$this->config = bjstaticcall($cls_path . ".getConfig");
	}
	
	public function __call($fn, $inargs)
	{
		if( $this->config )
		{
			if( isset($this->config[$fn]) && isset($this->config[$fn]['Enter']) )
			{
				$enter = $this->config[$fn]['Enter'];
				$enter_funcs=[];
				if( is_string($enter) ) $enter_funcs[] = $enter;
				else if( is_array($enter) ) $enter_funcs = $enter;

				foreach($enter_funcs as $f)
				{
					$ret_enter = bjstaticcall($f,$this->obj,$inargs);
					if( !is_null($ret_enter) ) $inargs = $ret_enter;
				}
			}
		}

		$ret = call_user_func_array(array($this->obj, $fn), $inargs);

		if( $this->config )
		{
			if( isset($this->config[$fn]) && isset($this->config[$fn]['Leave']) )
			{
				$leave = $this->config[$fn]['Leave'];
				$leave_funcs=[];
				if( is_string($leave) ) $leave_funcs[] = $leave;
				else if( is_array($leave) ) $leave_funcs = $leave;

				foreach($leave_funcs as $f)
				{
					$ret = bjstaticcall($f,$this->obj,$inargs,$ret);
				}
			}
		}
		return $ret;
	}
	
}

/*
示例类：
namespace feature\Biz;
class index
{
	public static function getConfig()
	{
		return [
			'open'	=>	[
					'Enter' => ['feature.Biz.index.EnterOpen'],
					'Leave' => ['feature.Biz.index.LeaveOpen']
				]
			];
	}

	public static function EnterOpen($_this,$in_args)
	{
		//$in_args是原始参数打包后的数组
		//如果需要对$in_args有修改，则需要修改后的数组，示例：
		//$in_args[0] = 5;
		//return $in_args;
	}

	public static function LeaveOpen($_this,$in_args,$ret)
	{

		//必须返回一个值，如果不修改返回值，则照原样返回
		return $ret;
	}

	public function open()
	{
	}
}

在其它类的成员方法中：
class XXX
{
	public function bar()
	{
		$_this = bjfeature("Biz.index");
		$ret = $_this->open();//在调用这句前
		//会调用EnterOpen，完成后再调用LeaveOpen
		return $ret;//此处返回的$ret其实是LeaveOpen处理过的$ret
	}
}

*/