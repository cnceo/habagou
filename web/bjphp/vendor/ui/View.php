<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\ui;

class View
{
	public function __construct()
	{
		$this->registers=[];
		$this->blocks = [];
		$this->jsfiles=[];
		$this->cssfiles=[];
		$this->jscodes=[];
		$this->csscodes=[];
		$this->html = [];
	}

	public function register($name,$f)
	{
		$this->registers[ $name ] = $f;
	}
	public function block($name,$templ_file_path,$_context)
	{
		$this->blocks[ $name ] = [$templ_file_path,$_context];
	}
	
	public function render()
	{
		$this->do_block("");

		$text = implode("",$this->html);
		
		
		//js files
		$_jsfiles = [];
		foreach($this->jsfiles as $v) $_jsfiles[] = $v[1];
		
		$text = str_replace("<!-- @@==jsfile==@@ -->",implode("",$_jsfiles),$text);
		
		
		//css files
		$_cssfiles = [];
		foreach($this->cssfiles as $v) $_cssfiles[] = $v[1];
		$text = str_replace("<!-- @@==cssfile==@@ -->",implode("",$_cssfiles),$text);
		
		//css codes
		$_csscodes = implode("",$this->csscodes);
		if( $_csscodes != "" ) $_csscodes = "<style type='text/css'>" . $_csscodes . "</style>";
		$text = str_replace("<!-- @@==csscode==@@ -->",$_csscodes,$text);

		
		//js codes
		$_jscodes = implode("",$this->jscodes);
		if( $_jscodes != "" ) $_jscodes = "<script type='text/javascript'>" . $_jscodes . "</script>";
		$text = str_replace("<!-- @@==jscode==@@ -->",$_jscodes,$text);
		
		bjhttp()->response()->write($text);
	}

	public function display($context=null)
	{
		if( !isset($this->blocks[ "" ]) ) bjerror("请先设置主模板");
		
		if( !is_null($context) )
		{
			$this->block("",$this->blocks[ "" ][0],$context);
		}
			
		$this->render();
	}

	public function do_html($text)
	{
		$this->html[] = (string)$text;
	}
	private function do_jscode($code)
	{
		$this->jscodes[] = $code;
	}
	private function do_csscode($code)
	{
		$this->csscodes[] = $code;
	}
	private function do_block($name)
	{
		if( ! isset($this->blocks[$name]) ) bjerror( "模板模块" . $name . "未定义" );

		$arr = $this->blocks[ $name ];
		$templ_file_path = $arr[0];
		$_context = $arr[1];

		
		if( $templ_file_path != "" )
		{
			
			$runner = $this->get_templ($templ_file_path);
			
			//echo("runner:".gettype($runner)); exit;

			$this->init_runner($runner);
			$runner->run( $_context );
		}
	}
	private function jsfile($filename,$charset="")
	{
		if( isset($this->jsfiles[$filename]) ) return "";

		$ch = "";
		if( $charset != "" ) $ch = "charset='" . $charset . "'";
		$src = bjfixUrl($filename);
		$code="<script type='text/javascript' src='$src' $ch></script>";
		$this->jsfiles[] = [$filename,$code];
		return "";
	}

	private function cssfile($filename,$charset="")
	{
		if( isset($this->cssfiles[$filename]) ) return "";

		$ch = "";
		if( $charset != "" ) $ch = "charset='" . $charset ."'";
		$src = bjfixUrl($filename);
		$code="<link rel='stylesheet' href='$src' $ch />";
		$this->cssfiles[] = [$filename,$code];
		return "";
	}

	private function get_templ($templ_file_path)
	{
		$templ_file_path  = str_replace(".","/",$templ_file_path);
		
		$cls = str_replace("/","_",$templ_file_path)."_cache";

		$file_tpl = bjpathJoin(INDEX_PATH,"/tpl");
		$file_tpl = bjpathJoin($file_tpl,$templ_file_path) . ".php";

		$file_cache = bjpathJoin(INDEX_PATH,"/tplcache");
		$file_cache = bjpathJoin($file_cache,$templ_file_path) . ".php";

		//echo "e1 - ";
		$need_compile = false;
		if( file_exists($file_cache) )
		{
			$time_tpl = filemtime($file_tpl);
			$time_cache = filemtime($file_cache);
			if( $time_cache < $time_tpl ) $need_compile = true;
		}
		else
		{
			$need_compile = true;
		}

		if( $need_compile )
		{
			//echo "need compile - ";
			$str = file_get_contents($file_tpl);
			$engine = bjcreate("bjphp.vendor.ui.Engine");
			//echo " engine:".gettype($engine);
			$content = $engine->compile($str,$cls);
			

			$s1 = strrchr($file_cache,'/');
			$dir = substr($file_cache,0,strlen($file_cache)-strlen($s1));
			if( !file_exists($dir) ) mkdir(iconv("UTF-8", "GBK", $dir),0777,true);

			file_put_contents($file_cache,$content);
		}

		//echo "require";
		require_once($file_cache);
		$class = new \ReflectionClass($cls);
		return $class->newInstanceArgs([]);
	}

	private function init_runner($runner)
	{
		//注册缺省的函数
		$runner->register_cb("do_html",function($s){ $this->do_html($s); });
		$runner->register_cb("do_jscode",function($s){ $this->do_jscode($s); });
		$runner->register_cb("do_csscode",function($s){ $this->do_csscode($s); });
		$runner->register_cb("do_block",function($name){ $this->do_block($name); });

		$runner->register_func("jsfile",function($filename,$ch=""){ $this->jsfile($filename,$ch); });
		$runner->register_func("cssfile",function($filename,$ch=""){ $this->cssfile($filename,$ch); });
		$runner->register_func("count",function($arr){ return count($arr); });
		$runner->register_func("to_json",function($arr){ return json_encode($arr); });
		
		$runner->register_func("fixurl",function($url){ return bjfixUrl($url); });
		$runner->register_func("add",function($a,$b){
			if( is_string($a) ) return $a.$b;
			if( is_array($a) ) {
				$a[] = $b;
				return $a;
			}
			return $a + $b;
		});
		$runner->register_func("arrayget",function($arr,$index){ return isset($arr[$index]) ? $arr[$index] : null; });
		$runner->register_func("pagerange",function($pageindex,$pagecount){
			if( $pagecount < 1 ) return [];
			$start = ($pageindex > 4 ? $pageindex - 4 : 1);
			$end = ($pageindex + 4 <= $pagecount ? $pageindex + 4 : $pagecount);
			$arr=[];
			if( $end >= $start )
			{
				for($index=$start;$index<=$end;$index++)
					$arr[] =  $index;
			}
			return $arr;
		});

		//再注册用户自定义回调函数
		foreach($this->registers as $k=>$v)
		{
			$runner->register_func($k,$v);
		}
	}

	private $blocks;
	private $jsfiles;
	private $cssfiles;
	private $jscodes;
	private $csscodes;
	private $html;
	private $registers;//注册的函数
}
