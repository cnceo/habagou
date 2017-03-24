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

class CachePage
{
	protected $_cbs=[];
	protected $_funcs=[];

	protected $_key=null;
	protected $_key_stack=[];

	protected $_index=0;
	protected $_index_stack=[];

	protected $_this=null;
	protected $_this_stack=[];

	protected $_root=null;
	protected $_eachobj=null;

	public function register_cb($name,$func)
	{
		$this->_cbs[$name] = $func;
	}
	public function register_func($name,$func)
	{
		$this->_funcs[$name] = $func;
	}

	public function run($uicontext)
	{
		//需要被重载
	}

	protected function is_true($obj)
	{
		if( is_bool($obj) ) return $obj;
		if( is_array($obj) ) return count($obj) > 0;
		if( is_string($obj) ) return $obj !== "";
		if( is_int($obj) ) return (int)$obj != 0;
		if( is_float($obj) ) return (float)$obj != 0;
		if( is_object($obj) ) return $obj;
		return false;
	}
	
	protected function is_last($index,$ev)
	{
		if( is_array($ev) ) 
			return $index == count($ev) - 1;
		else 
		{
			$arr = bjarray($ev);
			return $index == count($arr) - 1;
		}
	}
	protected function do_html($text)
	{
		$f = $this->_cbs["do_html"];
		return $f($text);
	}
	protected function do_block($text)
	{
		$f = $this->_cbs["do_block"];
		return $f($text);
	}
	protected function do_jscode($text)
	{
		$f = $this->_cbs["do_jscode"];
		return $f($text);
	}
	protected function do_csscode($text)
	{
		$f = $this->_cbs["do_csscode"];
		return $f($text);
	}
	protected function encode($text)
	{
		return htmlentities($text);
	}
	protected function can_each($obj)
	{
		return is_array($obj);
	}
	protected function get_prop($obj,$iden)
	{
		if( $iden == "@this" ) return $this->_this;
		if( $iden == "@key" ) return $this->_key;
		if( $iden == "@index" ) return $this->_index;
		if( $iden == "@first" ) return $this->_index==0;
		if( $iden == "@last" ) return $this->_index == count($this->_eachobj)-1;
		if( $iden == "@root" ) return $this->_root;
		if( $iden == "@parent" ) 
		{
			if( $obj == $this->_this )
				return $this->_this_stack[ count($this->_this_stack) - 1];
			$count = count($this->_this_stack);
			for($i=$count-1;$i>=0;$i--)
			{
				if( $this->_this_stack[$i] == $obj )
				{
					if( $i > 0 ) return $this->_this_stack[$i-1];
					else break;
				}
			}
			return null;
		}

		if( is_array($obj) )
		{
			if( isset($obj[$iden]) ) return $obj[$iden];
			return "";
		}
		else if( is_object($obj) )
		{
			if( property_exists($obj,$iden) ) return $obj->{$iden};
			return "";
		}
		else bjerror("属性不存在:$iden");
		return null;
	}
}

