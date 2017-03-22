<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\router;

class Router 
{
	private $origin_url;//干净的原始文件网址，不含伪静态参数和查询参数
	
	//解析路由和入参，并引导到正确的页面进行运行，返回页面的执行结果
	public function run()
	{
		//= 原始请求路径
		$UrlPath = $_SERVER['REQUEST_URI'];
		
		//= 去掉虚拟目录名
		$vdname = bjconfig('site')['vdname'];
		if ('' != $vdname) {
			$vdname = '/' . $vdname;
			$UrlPath = substr($UrlPath, strlen($vdname));
		}
		
		
		//= 去掉 QueryString 参数
		$pos = strpos($UrlPath, '?');
		if ($pos !== false) {
			$UrlPath = substr($UrlPath, 0, $pos);
		}
		
		//= 虚拟路由（或称为预定义路径）
		if ($this->DoVirtual($UrlPath)) return;
		
		//= 真实文件及伪静态的处理
		$ext = strtolower(pathinfo($UrlPath, PATHINFO_EXTENSION));
		if ($ext == 'php')//真实文件
		{
			/*$file_path = INDEX_PATH . '/web' . $UrlPath;
			if (file_exists($file_path)) {
				require $file_path;//直接调用真实文件
			} else {
				bjhttp()->e404($UrlPath);
			}*/
			bjhttp()->e404($UrlPath);//禁止直接调用页面文件！
		} else if ($ext == '') //伪静态
		{
			$this->DoPseudoStatic($UrlPath);
		} else //其它的都当作404
		{
			bjhttp()->e404($UrlPath);
		}
	}
	
	//虚拟路由
	private function doVirtual($path)
	{
		//附件
		if (strpos($path, '/att') === 0) $this->DownloadAttachment($path);
		
		//apicall
		else if (strpos($path, '/bizcall') === 0) $this->DoApiCall($path);
		
		//文件上传
		else if (strpos($path, '/fileupload') === 0) $this->DoFileUpload($path);
		
		//微信回调
		else if (strpos($path, '/wxcb') === 0) $this->DoWeixin($path);
		
		else return false;
		
		return true;
	}
	
	//= 附件下载
	private function downloadAttachment($path)
	{
		$path = substr($path, strlen('/att'));
		bjcall('bjphp.vendor.file.download.run', $path);
	}
	
	//= api call
	private function doApiCall($path)
	{
		bjwriteLog("enter BizCall:".$path);
		try {
			$biz = str_replace('/','.',substr($path,strlen('/bizcall/')) );
			$user_func_data = bjapi($biz, bjobject( array_merge($_GET,$_POST) ) );
			if (is_null($user_func_data)) $user_func_data = 0;
			$ret = ['status' => 0, 'Data' => $user_func_data];
			
			echo json_encode($ret);
			
		} catch (\BjException $ex) {
			$code = $ex->getCode();
			if ((int)$code == 0) $code = 1;
			$ret = ['status' => $code, 'userexception' => 1, 'errortype' => $ex->err_type, 'message' => $ex->getMessage()];
			
			echo json_encode($ret);
		} catch (\Exception $e) {
			$code = $e->getCode();
			if ((int)$code == 0) $code = 1;
			$ret = ['status' => $code, 'userexception' => 0, 'message' => $e->getMessage()];
			
			echo json_encode($ret);
		}
		
	}
	
	//= 微信回调
	private function doWeixin($path)
	{
		bjcall(bjconfig('vendor')['weixin'].'.run');
		
	}
	
	//= 文件上传
	private function doFileUpload($path)
	{
		bjcall('bjphp.vendor.file.Upload.run');
	}
	
	//= 伪静态
	private function doPseudoStatic($UrlPath)
	{
		//初始化
		$filepath = bjpathJoin(CLASS_ROOT , '/page');
		$filepath = str_replace('\\','/',$filepath);//windows下的目录符替换
		$filepath = str_replace("..","",$filepath);//禁止使用 '..'

		//实际请求的文件
		$filename = '';
		$this->origin_url = '';
		
		
		$path_names = explode('/', $UrlPath, 128);//防止请求地址超长，最多128个
		$name_count = count($path_names);
		
		$isfile=false;
		$isback=false;
		for ($index = 1; $index < $name_count; $index++) {
			
			$name = urldecode( $path_names[$index] );
			//write_log("finding index=".$index." name=".$name);
			if ($index == 1 && "" == $name) break;//default: index.php

			if (strpos($name, ".") !== false) {//带后缀名的文件必须是可以直接访问的，不能当成伪静态
				bjhttp()->e404($UrlPath);
				return;
			}

			if ("" == $name) {
				if (!file_exists($filepath . '/index.php')) {
					//再后退判断上一层有没有index.php
					
				
					if( file_exists($sPath))
					{
						$isback = true;
						//$index--;
						break;
					}
					$sPath = $filepath . '/index.php';
					bjhttp()->e404($UrlPath);
					return;
				} else {
					
					//$filename = "index.php";
				}
				break;
			}
			$sPath = $filepath . '/' . $name;
			if (file_exists($sPath)) {
				if( is_dir($sPath) ){//目录存在
					$filepath .= '/' . $name;
					$this->origin_url .= '/' . $name;
					continue;
				}
				bjhttp()->e404($UrlPath);
				return;
			}
			
			$sPath .= '.php';
			if (file_exists($sPath)) {
				
				$filename = $name . '.php';
				$this->origin_url .= '/' . $name;
				$isfile = true;
				break;
			} else {
				
				//再后退判断上一层有没有index.php
				$sPath = $filepath . '/index.php';
				
				if( file_exists($sPath))
				{
					$isback = true;
					//$index--;
					break;
				}
				bjhttp()->e404($UrlPath);
				return;
			}
		}
		
		$req = bjhttp()->request();
		
		if( $this->origin_url == '' ) $this->origin_url = '/';
		$req->setOriginUrl( $this->origin_url );
		
		$class_name = "";
		for($i=1;$i<($isfile ? $index+1:$index);$i++) $class_name = $path_names[$i];
		
		if ($filename == "")
		{
			$filename = 'index.php';
			$class_name = "index";
		}

		
		$file = $filepath . '/' . $filename;
		
		
		
		$method_name = "display";
		$method_param=[];
		//打包参数
		$param_index_start = ($isback ? $index : $index + 1);
		for ($i = $param_index_start; $i < $name_count; $i++) {
			$param = urldecode($path_names[$i]);
			if( $i == $param_index_start ) $method_name = $param;
			else $method_param[] = $param;
			//write_log('route:add param='.$param);
			$req->addParam( $param );
		}
		//echo("file:".$file."<br>");
		if (file_exists($file)) {
			require_once($file);
			//echo('cls:'.$class_name); exit;
			$class_exists = class_exists("\\".$class_name);
			if( !class_exists("\\".$class_name) ){
				$class_name = "_".$class_name;//class_name刚好是关键字时，须在前面加下划线
				$class_exists = class_exists("\\".$class_name);
			} 

			if( $class_exists ){

				$class = new \ReflectionClass("\\".$class_name);
				$page = $class->newInstanceArgs([]);
				
				if( !method_exists($page,$method_name) )
				{
					array_unshift($method_param,$method_name);
					$method_name='display';
				}
				
				if( method_exists($page,$method_name) )
				{
					
					$filter = bjconfig('route_filter');
					if( is_array($filter))
					{
						$before = bjconfig('route_filter')['before'];
						foreach($before as $bfunc) bjstaticcall($bfunc);
					}
					
					call_user_func_array([$page,$method_name],$method_param);
					
					if( is_array($filter))
					{
						$after = bjconfig('route_filter')['after'];
						foreach($after as $afunc) bjstaticcall($afunc);
					}
					
				}
				else
					bjerror('missing action ['.$method_name.'] in class ['.$class_name.']',1026);
			}
			else bjerror('class not found:'.$class_name,1027);
			
		} else {
			bjhttp()->e404($UrlPath);
		}
	}
	
}


