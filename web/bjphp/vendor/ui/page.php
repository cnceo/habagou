<?php

/**
 * 前端页面的基础类
 * - 支持真静态（伪静态由router实现）
 * - 支持表单的防重复提交
 */
class vendor_ui_page extends intfPage
{
	//真静态化间隔时间 -1:不支持真静态化 0:缺省间隔（3600秒），>0：指定间隔时间（秒）
	//如果某个页面需要真静态化，则请在构造函数中调用make_static方法
	private $static_interval = -1;
	
	//action
	public $__action__='display';

	//-- 防重复提交
	private $anti_flush='';
	
	protected $view;
	
	public function __construct()
	{
		$this->view = bjcreate('vendor.ui.view');
	}
	
	//设置action -- 需要重载
	public function set_action($a)
	{
		//缺省的action是display
		//action一般是根据网址参数决定，每个页面请自行定义action
		$this->__action__ = $a;
	}
	
	//得到action -- 可以重载
	public function get_action()
	{
		return $this->__action__;
	}
	
	//缺省的action
	public function action_display()
	{
		//do nothing
	}
	
	//缺省的render
	public function render_display()
	{
		//do nothing
	}
	
	//action前置工作-- 可以重载
	public function before_action()
	{
		
	}
	
	//action后置工作-- 可以重载
	public function after_action()
	{

	}
	
	//render前置工作-- 可以重载
	public function before_render()
	{
		//do nothing
	}
	
	//render后置工作-- 可以重载
	public function after_render()
	{
		//do nothing
	}
	
	public function render()
	{
		$this->view->render();
	}
	//[工具函数] 模板赋值
	public function Assign($k,$v=null)
	{
		$resp = http()->Response();
		if( is_array($k))
		{
			foreach($k as $ak => $av) $resp->Assign($ak,$av);
			unset($ak);
			unset($av);
		}
		else $resp->Assign($k,$v);
	}
	
	//[工具函数] 文件引入
	public function import_css($block,$file_name)
	{
		$resp = http()->Response();
		
		//<< 页面依赖的css
		if( is_array($file_name) )
		{
			foreach($file_name as $f)
			{
				$url = fixurl($f);
				$resp->AddBlock($block,"\r\n<link rel='stylesheet' type='text/css' href='{$url}' />");
			}
			unset($f);
		}
		else{
			$url = fixurl($file_name);
			$resp->AddBlock($block,"\r\n<link rel='stylesheet' type='text/css' href='{$url}' />");
		}
	}
	
	//[工具函数] 文件引入
	public function import_js($block,$file_name,$charset=null)
	{
		$resp = http()->Response();
		
		//<< 页面依赖的css
		if( is_array($file_name) )
		{
			foreach($file_name as $f)
			{
				$url = fixurl($f);
				$ch='';
				if( $charset ) $ch=" charset='{$charset}'";
				$resp->AddBlock($block,"\r\n<script src='{$url}' type='text/javascript' {$ch}></script>");
			}
			unset($f);
		}
		else{
			$url = fixurl($file_name);
			$ch='';
			if( $charset ) $ch=" charset='{$charset}'";
			$resp->AddBlock($block,"\r\n<script src='{$url}' type='text/javascript' {$ch}></script>");
		}
	}
	
	//[工具函数] html/js 源码引入
	//file_name是本机绝对路径，不是网址！
	public function import_code($block,$file_name)
	{
		write_log("---->>> import_code file=".$file_name);
		$resp = http()->Response();
		
		//<< 页面依赖的css
		if( is_array($file_name) )
		{
			foreach($file_name as $f)
			{
				$this->import_code((string)$f);
			}
			unset($f);
		}
		else{
			$str = '';
			if( substr($file_name,-4) == '.php')
			{
				ob_start();
				require((string)$file_name);
				$str = ob_get_contents();
				ob_end_clean();
			}
			else
				$str = file_get_contents((string)$file_name);
			
			$resp->AddBlock($block,$str);
		}
	}
	
	//页面作为“能力提供者”所需的系统级初始化
	public static function system_init()
	{
		ob_start();
	}
	
	//页面作为“能力提供者”所需的系统级清理
	public static function system_clean()
	{
		ob_end_flush();
	}
	
	public function Run()
	{
		//write_log('>>>> ------<< enter run!');
		$site_mode = bjconfig('site')['mode'];
		//是否支持静态化
		if ($site_mode != 'debug' && $this->static_interval > 0) {
			$filename = $this->get_static_file_name();
			
			if (time() - filemtime($filename) < $this->static_interval) {//在静态化缓存期内，直接返回静态内容
				readfile($filename);
				return;
			}
		}
		
	
		//页面逻辑，按action分流
		//页面逻辑的主要内容是：调用api进行业务处理，注入模板所需数据
		$this->before_action();
		$this->{'action_' . $this->get_action()}();
		$this->after_action();
		
		//页面渲染，按action分流
		//页面渲染的主要内容是根据业务情况，注入模板及区块(block)
		$this->before_render();
		$this->{'render_' . $this->get_action()}();
		$this->after_render();
		
		
		//是否支持静态化
		if ($site_mode != 'debug' && $this->static_interval > 0) {
			$filename = $this->get_static_file_name();
			$this->ensure_folder(dirname($filename));
			file_put_contents($filename, ob_get_contents());//生成静态文件
		}

		//write_log('------!! after run!');
	}
	
	
	
	//真静态化 interval:静态化文件有效期
	public function make_static($interval = 0)
	{
		$this->static_interval = $interval;
		if ($this->static_interval == 0) $this->static_interval = 3600;
	}
	
	//[工具函数] 判断是否为移动端访问
	public function is_mobile()
	{
		$regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
		$regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
		$regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
		$regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
		$regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320×320|240×320|176×220";
		$regex_match .= ")/i";
		return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
	}
	
	//[工具函数] 确保目录存在，不存在就创建
	public function ensure_folder($path)
	{
		if (!file_exists($path)) {
			if (mkdir(iconv("UTF-8", "GBK", $path), 0777, true)) {
				chmod(iconv("UTF-8", "GBK", $path), 0777);
			}
		}
	}
	
	//得到静态化文件
	private function get_static_file_name()
	{
		if ($this->static_interval > 0) {
			$filename = INDEX_PATH . '/static' . http()->Request()->MakeUrl();
			if (strrchr($filename, '/') == '/') $filename .= 'index.html';
			else $filename .= '.html';
			return $filename;
		}
		return '';
	}
	
	//[工具函数] 初始化页面参数
	//arr 按参数顺序指定缺省值，如：['id'=>'','page_index'=>1]
	public function init_param($arr)
	{
		http()->Request()->InitParam($this,$arr);
	}
	
	//[工具函数] 分页工具函数：生成页码范围
	public function make_page_range($page_index,$page_count)
	{
		if( $page_count < 1 ) return [];
		$start = ($page_index > 4 ? $page_index - 4 : 1);
		$end = ($page_index + 4 <= $page_count ? $page_index + 4 : $page_count);
		$arr=[];
		if( $end >= $start )
			for($index=$start;$index<=$end;$index++)
				$arr[] = $index;
		return $arr;
	}
	
	//跳转
	public function redirect($url)
	{
		ob_clean();
		header('location:'.$url);
	}
	
	//防重复提交（注入）
	public function anti_flush()
	{
		if( is_null(http()->Response()->GetValue("anti_flush")) ) {
			$this->Assign("anti_flush", function () {
				if( $this->anti_flush == '' )
					$this->anti_flush = bjstaticcall('vendor.db.model.genid');
				session()->Set("anti_flush", $this->anti_flush, 'sys');
				
				return "<input type='hidden' name='anti_flush' value='{$this->anti_flush}'/>";
			});
		}
	}
	//防重复提交（验证）
	public function verify_flush()
	{
		$session_anti_id = session()->Get('anti_flush','sys');
		$anti_flush = http()->Request()->Param('anti_flush','');
		if( $anti_flush != $session_anti_id ) {
			bjerror('表单已过期！');
		}
		//验证通过后就清空
		session()->Set("anti_flush", '', 'sys');
	}
	
	//将元数据的值注入到模板变量中
	public function fill_meta($obj,$form_name='')
	{
		$obj = bjarray($obj);
		foreach($obj as $k=>$v)
		{
			$this->Assign($form_name.'.'.$k,$v);
		}
	}
}

