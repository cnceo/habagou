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

class Http 
{
	private $request;
	private $response;
	
	public function __construct()
	{
		$this->request = bjcreate('bjphp.vendor.http.Request');
		$this->response = bjcreate('bjphp.vendor.http.Response');
	}
	
	public function initEnv()
	{
		date_default_timezone_set('Asia/Shanghai');
		error_reporting(E_ALL);
		register_shutdown_function(function () {
			if (error_get_last()) bjhttp()->handleException(null);
		});
	}
	
	//完整的http响应流程
	public function run()
	{
		
		
		
		
		//执行路由
		try {

			//环境初始化
			$this->initEnv();
			
			//系统初始化
		foreach(bjconfig('system')['init'] as $p) bjstaticcall($p);
		
			$obj = bjcreate(bjconfig('vendor')['router']);
			$obj->Run();
			
			
		} catch (\BjException $ex) {
			
			//write_log('=== BjException:'.$ex->err_type);
			$exh = bjconfig('system.exception');
			
			
			if( is_array($exh) && isset($exh[$ex->err_type]) )
			{
				
				$handler_func = $exh[$ex->err_type];
				
				if( is_array($handler_func) )
				{
					foreach($handler_func as $lh)
					{
						if( bjstaticcall($lh) ) break;
					}
				}
				else
				{
					bjstaticcall($handler_func);
				}
				
			}
			else
				$this->handleException($ex);

		} catch (\Exception $e) {
			
			//全局异常处理
			$this->handleException($e);
		}
		
		//系统清理
		foreach(bjconfig('system')['clean'] as $p) bjstaticcall($p);
	}
	
	public function handleException($e)
	{
		$error_context = [];

		$error_context['mode'] = bjconfig("site")["mode"];
		
		$ex = error_get_last();
		$sErr = $e ? $e->getMessage() : ($ex ? $ex['message'] : '未知原因');
		$error_context["message"] = $sErr;
		
		
		bjwriteLog('fatal error:' . $sErr . ' in ' . $ex['file'] . ' line:' . $ex['line'],2);
		
		$site_type = "pc";
		if( isset(bjconfig('site')['sitetype']) ) $site_type=bjconfig('site')['sitetype'];
		if( $site_type != 'pc' && $site_type != 'response' )
		{
			ob_clean();
			echo $sErr;
		}
		else {
			$error_context["file"] = $ex["file"];
			$error_context["line"] = $ex["line"];
			
			
			$title = $e ? "应用程序错误" : '严重错误';
			$error_context["title"] = $title;
			
			$sStack = [];
			if ($e && property_exists($e, 'bj_call_stack')) $sStack = $e->bj_call_stack;
			else if ($e) $sStack = debug_backtrace();
			
			$error_context["stack"] = $sStack;
			
			ob_clean();
			$temp = bjconfig("http");
			
			if( is_array($temp) && isset($temp['template']) && isset($temp['template']['exception']) )
				$temp_file = $temp['template']['exception'];
			else
				$temp_file = bjpathJoin( CLASS_ROOT, "/bjphp/vendor/http/exception.html");//缺省的模板
			include $temp_file;
		}
	}
	
	//得到request对象
	public function request()
	{
		return $this->request;
	}
	
	//得到response对象
	public function response()
	{
		return $this->response;
	}
	
	public function e404($path)
	{
		bjwriteLog('file not found:'.$path);
		header('HTTP/1.1 404 Not Found');
		header("status: 404 Not Found");

		$temp = bjconfig("http");
	
		if( is_array($temp) && isset($temp['template']) && isset($temp['template']['404']) )
			$temp_file = $temp['template']['404'];
		else
			$temp_file = bjpathJoin( CLASS_ROOT, "/bjphp/vendor/http/404.html");//缺省的模板
		include $temp_file;

		exit;
	}
	
	public function e301($path)
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$path}");
		exit;
	}
	
}
