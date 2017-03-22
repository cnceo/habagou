<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

//=== 框架全局常量
define( 'BJPHP', 		true );				//框架标志
define( 'BJPHP_VER', 		'1.2' );			//框架版本
define( 'BJPHP_PUBDATE', 	'2017.2.20' );			//框架发布日期

//数组转对象（轻对象，即只有属性没有方法的对象）
function bjobject($arr)
{
	if( is_array($arr) )
	{
		foreach($arr as $k=>$v)
		{
			if( is_array($v) ) $arr[$k] = bjobject($v);
		}
		return (object)$arr;
	}
	else if( is_object($arr) )
		return $arr;
	else
		return bjobject([$arr]);
}

//对象转数组
function bjarray($obj)
{
	$arr = (array)$obj;
	foreach($arr as $k=>$v)
	{
		if( is_object($v) ) $arr[$k] = bjarray($v);
	}
	return $arr;
}

//路径
function bjpathJoin($path,$str)
{
	$last = substr($path,-1);
	$first = substr($str,0,1);
	if( $last == '/' )
	{
		if( $first == '/' ) return $path . substr($str,1);
		else return $path . $str;
	}else{
		if( $first == '/' ) return $path . $str;
		else return $path . '/' . $str;
	}
}

//读取配置信息
function bjconfig($key)
{
	static $config;
	if( !$config )
	{
		$config = require( bjpathJoin(INDEX_PATH , '/config/appconfig.php') );
	}
	
	$root = $config;
	while(true)
	{
		$pos = strpos($key,'.');
		if( $pos === false )
		{
			if( isset($root[$key]) ) return $root[$key];
			else break;
		}
		$k = substr($key,0,$pos);
		if( isset($root[$k]) ) 
		{
			$root = $root[$k];
			$key = substr($key,$pos+1);
			if( ! is_array($root) ) break;
		}
		else break;
	}

	return "";
}

//用户级异常
class BjException extends \Exception {
	//预定义错误类型
	//约定 0:未分类 1024:未登录 1025:未授权的API调用
	public $err_type=0;
	public $bj_call_stack;
	public function __construct($message, $err_type=0,$code = 0) {
		parent::__construct($message, $code);
		$this->err_type = $err_type;
		$this->bj_call_stack = debug_backtrace();
	}
	
	public function __toString() {
		return __CLASS__.':['.$this->code.']:'.$this->message;
	}
}

//用户级错误
function bjerror($msg,$err_type=0,$code=1)
{
	throw new BjException($msg,$err_type,$code);
}

function bjerrorLogin($msg)
{
	bjerror($msg,1024,1);
}

function bjerrorAuth($msg)
{
	bjerror($msg,1025,1);
}

//加载类文件而不实例化
function bjload($cls)
{
	
	static $all_loaded;
	if( !$all_loaded ) $all_loaded = [];
	
	$key = str_replace(["..","/","\\"], "", $cls);
	if( isset($all_loaded[$key]) ) return $key;//已经加载了
	
	$class_file = bjpathJoin(CLASS_ROOT,str_replace('.','/',$key).'.php');
	$class_file = str_replace("\\",'/',$class_file);
	
	$all_loaded[ $key ] = $class_file;
	if( !file_exists($class_file) ) bjerror('class file not found:'.$cls);
	require_once $class_file;
	return $key;
}


//实例化对象
function bjcreate()
{
	$arguments = func_get_args();
	$path = array_shift($arguments);
	
	$path = bjload($path);
	
	$class_str = "\\".str_replace('.',"\\",$path);
	
	$class = new ReflectionClass($class_str);
	$obj = $class->newInstanceArgs($arguments);
	if( strpos($path,"feature.") === 0 ){
		if( isset($vendor['feature_proxy']) )
		{
			$feature_proxy = $vendor['feature_proxy'];
			if( $feature_proxy != '' )
			{
				$oProxy = bjcreate($feature_proxy,$obj,$path,$arguments);
				$obj = $oProxy;
			}
		}
	}
	return $obj;
}

//成员方法调用
function bjcall()
{
	$arguments = func_get_args();
	$path = array_shift($arguments);
	$path = str_replace(["..","/","\\"], "", $path);
	
	$names = explode('.',$path);
	$func = array_pop($names);
	
	bjload(implode('.',$names));

	$cls_name = "\\".implode("\\",$names);
	
	$class = new ReflectionClass($cls_name);
	$obj = $class->newInstanceArgs();
	
	$reflectionMethod = new ReflectionMethod($cls_name, $func);
	return $reflectionMethod->invokeArgs($obj, $arguments);
}

//静态方法调用
function bjstaticcall()
{
	$arguments = func_get_args();
	$path = array_shift($arguments);
	$path = str_replace(["..","/","\\"], "", $path);
	
	$names = explode('.',$path);
	$func = array_pop($names);
	
	bjload(implode('.',$names));
	
	return call_user_func_array("\\" . implode("\\",$names) . '::' . $func, $arguments );
}

//创建meta实例（name不需要带meta）
function bjmeta($name)
{
	return bjstaticcall('meta.'.$name. '.get_meta');
}

//创建功能层对象
function bjfeature()
{
	$arguments = func_get_args();
	$path = "feature.".array_shift($arguments);
	$vendor = bjconfig('vendor');

	if( is_object($path) )
	{
		if( isset($vendor['feature_proxy']) )
		{
			$feature_proxy = $vendor['feature_proxy'];
			if( $feature_proxy != '' )
			{
				$obj = $path;
				$oProxy = bjcreate($feature_proxy,$obj);
				return $oProxy;
			}
		}
		bjerror('缺少feature_proxy的配置！');
	}
	$path = bjload($path);


	$class_str = "\\".str_replace('.',"\\",$path);
	
	$class = new ReflectionClass($class_str);
	$obj = $class->newInstanceArgs($arguments);

	
	if( isset($vendor['feature_proxy']) )
	{
		$feature_proxy = $vendor['feature_proxy'];
		if( $feature_proxy != '' )
		{
			$oProxy = bjcreate($feature_proxy,$obj,$path,$arguments);
			$obj = $oProxy;
		}
	}
	
	return $obj;
}

//-------- 简化代码的工具函数 [BEGIN] ------------------------------------------
//得到http实例
function bjhttp()
{
	static $http;
	if( !$http )
		$http = bjcreate( bjconfig('vendor')['http'] );
	return $http;
}

//写日志
function bjwriteLog($msg,$level=0)
{
	static $log;
	if( ! $log ) $log = bjcreate( bjconfig('vendor')['log'] );
	$log->write($msg,$level);
}

//会话
function bjsession()
{
	static $s;
	if( !$s )
		$s = bjcreate( bjconfig('vendor')['session'] );
	return $s;
}

//SQL条件
function bjcond($sql,$key="",$val="")
{
	return bjcreate("bjphp.vendor.db.Cond",$sql,$key,$val);
}

//视图
//参数个数：[] [path] [path,alias] [path,alias,context]
function bjview()
{
	static $view;
	if( !$view ) $view = bjcreate("bjphp.vendor.ui.View");
	$args = func_get_args();
	$count = count($args);
	if( $count < 1 ) return $view;
	$path = $args[0];
	$alias = "";
	if( $count > 1 ) $alias = $args[1];
	$view->block($alias,$path,$count > 2 ? $args[2] : null);
	return $view;
}

//api调用
function bjapi($biz_path,$ar)
{
	$is_debug = (bjconfig('site.mode') === 'debug');
	$debugger = null;
	if( $is_debug ) {
		$debugger = bjcreate("bjphp.vendor.debug.Debug");
		$debugger->timeStart();
	}

	static $api_calling;
	if( $api_calling == 1 ) bjerror("嵌套的API调用");
	$api_calling = 1;
	
	$biz_path = str_replace(["..","/","\\"], "", $biz_path);
	
	//bjwriteLog('call api:'.$biz_path.' args='.json_encode($ar));
	
	//--- 传入参数
	$input = bjobject($ar);
	
	$names = explode('.',$biz_path,20);//防止API路径太深
	$func = array_pop($names);
	if( empty($func) ) bjerror('错误的API调用，缺少函数名：'.$biz_path);
	if( count($names) < 1 ) bjerror("错误的API调用，缺少类名：".$biz_path);
	
	$class_name = "\\api\\".implode("\\",$names);
	$class_file = "api.".implode('.',$names);
	
	bjload($class_file);
	
	$config = call_user_func($class_name . '::Config');
	
	$functions = $config["Functions"];
	
	//--- 没有做配置，则表示禁止调用
	if( !isset($functions[$func]) )
	{
		bjerrorAuth($biz_path .' 被禁止调用！');
	}
	
	$group = $functions[$func][0];
	$auth_cat = $functions[$func][1];
	
	//--- 校验函数所属分组是否存在（每个函数必须属于某个分组！）
	if( !in_array($group,$config['Groups']) ) bjerror('接口函数('.$biz_path.')缺少分组');
	
	//--- 权限验证
	if( $auth_cat != '*' )//需要验证登录或有权限
	{
		//先验证登录
		$login_checker = bjconfig('auth')['is_login'];
		if( ! bjcall($login_checker) )
		{
			bjerrorLogin('请先登录');
		}
		
		if( $auth_cat != '-' )
		{
			//再验证权限
			$auth_checker = bjconfig('auth')['has_right'];
			$right_name = $config["ModuleName"] . "_" . $group;
			if( ! bjcall($auth_checker,$right_name) ) bjerrorAuth('未授权的操作：' . $biz_path);
		}
	}
	
	
	//--- 前置策略
	if( isset($config['Policy']) )
	{
		$policy_arr = [];
		foreach($config['Policy'] as $p)
		{
			$hit_enter = false;
			$funcs = $p['Functions'];
			if( is_array($funcs) && in_array($func,$funcs) )
			{
				$hit_enter = true;
			}
			else if( is_string($funcs) && (string)$funcs == $func ) $hit_enter = true;
			
			if( $hit_enter )
			{
				if( is_array($p['Enter']) ) $policy_arr = array_merge($policy_arr,$p['Enter']);
				else $policy_arr[] = (string)$p['Enter'];
				continue;
			}
			
			$groups = $p['Groups'];
			if( is_array($groups) && in_array($group,$groups) ) $hit_enter = true;
			else if( is_string($groups) && (string)$groups == $group ) $hit_enter = true;
			
			if( $hit_enter )
			{
				if( is_array($p['Enter']) ) $policy_arr = array_merge($policy_arr,$p['Enter']);
				else $policy_arr[] = (string)$p['Enter'];
			}
		}
		foreach($policy_arr as $p)
		{
			if( empty($p) ) continue;
			$arr = explode('.',$p);
			$enter_func = array_pop($arr);
			
			bjcreate(implode('.',$arr))->{$enter_func}($biz_path,$input);
			
		}
	}
	
	//调用API
	$user_func_data = call_user_func( $class_name . "::" . $func, $input );
	
	//后置策略
	if( isset($config['Policy']) )
	{
		$policys = [];
		foreach($config['Policy'] as $p)
		{
			$funcs = $p['Functions'];
			$hit_leave = false;
			if( is_array($funcs) && in_array($func,$funcs) )
			{
				$hit_leave = true;
			}
			else if( is_string($funcs) && (string)$funcs == $func ) $hit_leave = true;
			
			if( $hit_leave )
			{
				if( is_array($p['Leave']) ) $policys = array_merge($policys,$p['Leave']);
				else $policys[] = (string)$p['Leave'];
				continue;
			}
			
			$groups = $p['Groups'];
			if( is_array($groups) && in_array($group,$groups) ) $hit_leave = true;
			else if( is_string($groups) && (string)$groups == $group ) $hit_leave = true;
			
			if( $hit_leave )
			{
				if( is_array($p['Leave']) ) $policys = array_merge($policys,$p['Leave']);
				else $policys[] = (string)$p['Leave'];
			}
		}
		foreach($policys as $p)
		{
			if( empty($p) ) continue;
			$arr = explode('.',$p);
			$leave_func = array_pop($arr);
			
			bjcreate(implode('.',$arr))->{$leave_func}($biz_path,$input,$user_func_data);
		}
	}
	
	//返回API结果
	$api_calling = 0;

	if( $is_debug ) {
		$time_used = $debugger->timeEnd();
		bjwriteLog("[DEBUG] calling api (".$biz_path.") used time: ".$time_used."s");
	}

	return $user_func_data;
}

//修正url
function bjfixUrl($url)
{
	if( substr($url,0,1)== '/' )
	{
		$vdname = bjconfig('site')['vdname'];
		if( '' == $vdname ) return $url;
		else return '/' . $vdname . $url;
	}
	return $url;
}
//-------- 简化代码的工具函数 [END] --------------------------------------------