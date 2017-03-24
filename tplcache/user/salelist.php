<?php
bjload("bjphp.vendor.ui.CachePage");
class user_salelist_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n\t<meta charset=\"utf-8\" />\r\n\t<title>转赠记录</title>\r\n\t<meta name=\"screen-orientation\" content=\"portrait\">\t<!-- uc强制竖屏 -->\r\n\t<meta name=\"browsermode\" content=\"application\">\t\t<!-- UC应用模式 -->\r\n\t<meta name=\"full-screen\" content=\"yes\">\t\t\t\t<!-- UC强制全屏 -->\r\n\t<meta name=\"x5-orientation\" content=\"portrait\">\t\t<!-- QQ强制竖屏 -->\r\n\t<meta name=\"x5-fullscreen\" content=\"true\">\t\t\t<!-- QQ强制全屏 -->\r\n\t<meta name=\"x5-page-mode\" content=\"app\">\t\t\t<!-- QQ应用模式 -->\r\n\r\n\t<meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\">\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/css1.css?\" />\r\n\t<script src=\"/res/js/jquery-1.11.2.min.js\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/highcharts.js\" charset=\"utf-8\"></script>\r\n\t<script>\r\n\t\t".'$'."(document).ready(function () {\r\n\t\t\t".'$'."(\".tjt_div\").css(\"height\", ".'$'."(\"#page\").height() - ".'$'."(\".bottom\").height() - ".'$'."(\".new_content\").height() - 100);\r\n\t\t});\r\n\t</script>\r\n</head>\r\n<body>\r\n<div id=\"page\">\r\n\t<div id=\"top\">\r\n\t\t<span><a href=\"/user/user/home\">返回</a></span>\r\n\t\t<label><img src=\"/res/images/tx.png\"></label>\r\n\t</div>\r\n\t<div id=\"reg_div\" class=\"box-sizing\">\r\n\t\t<div class=\"reg box-sizing margin75\">\r\n\t\t\t<ul class=\"reg_button\">\r\n\t\t\t\t<li><a href=\"/user/user/register\">开发新狗场</a></li>\r\n\t\t\t\t<li><a href=\"/user/user/sale\">转赠小狗</a></li>\r\n\t\t\t\t<li class=\"on\"><a href=\"/user/user/salelist\">转赠记录</a></li>\r\n\t\t\t</ul>\r\n\t\t\t<div class=\"salelist box-sizing\">\r\n\t\t\t\t<ul class=\"salelist_ul flex\"><li class=\"box-sizing on\"><a href=\"/user/user/buylist\">购买记录</a></li><li class=\"box-sizing\"><a href=\"/user/user/salelist\">转赠记录</a></li></ul>\r\n\t\t\t\t<div class=\"border box-sizing\">\r\n\t\t\t\t\t<dl class=\"flex on\">\r\n\t\t\t\t\t\t<dt>接收人</dt><dd>数量</dd><dd>时间</dd><dt>状态</dt>\r\n\t\t\t\t\t</dl>\r\n\t\t\t\t\t");
		$v1=( $this->get_prop($this->_this,"datas") );
		if( $this->can_each($v1) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v4=$this->_eachobj;
		foreach($v1 as $v2=>$v3){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v3;
			$this->_key=$v2;
		$this->do_html("\r\n\t\t\t\t\t<dl class=\"flex\">\r\n\t\t\t\t\t\t<dt>");
		$v5=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v5));
		$this->do_html("</dt>\r\n\t\t\t\t\t\t<dd>");
		$v6=( $this->get_prop($this->_this,"num") );
		$this->do_html($this->encode($v6));
		$this->do_html("</dd>\r\n\r\n\t\t\t\t\t\t<dd>");
		$v7=( $this->get_prop($this->_this,"launchtime") );
		$this->do_html($this->encode($v7));
		$this->do_html("</dd>\r\n\t\t\t\t\t\t<dt>\r\n\t\t\t\t\t\t\t");
		$v8=( ($this->get_prop($this->_this,"status")) == (1) );
		if( $this->is_true($v8) ){
		$this->do_html("\r\n\t\t\t\t\t\t\t<a href=\"/user/user/updataStatus/");
		$v9=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v9));
		$this->do_html("/0/4\">取消交易</a>\r\n\t\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t\t");
		$v10=( ($this->get_prop($this->_this,"status")) == (2) );
		if( $this->is_true($v10) ){
		$this->do_html("\r\n\t\t\t\t\t\t\t<a href=\"/user/user/updataStatus/");
		$v11=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v11));
		$this->do_html("/0/3\">确认收米</a>\r\n\t\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t\t");
		$v12=( ($this->get_prop($this->_this,"status")) == (3) );
		if( $this->is_true($v12) ){
		$this->do_html("\r\n\t\t\t\t\t\t\t交易完成\r\n\t\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t\t");
		$v13=( ($this->get_prop($this->_this,"status")) == (4) );
		if( $this->is_true($v13) ){
		$this->do_html("\r\n\t\t\t\t\t\t\t交易取消\r\n\t\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t</dt>\r\n\t\t\t\t\t</dl>\r\n\t\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v4;
		}
		$this->do_html("\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"t_bg\"><img src=\"/res/images/t_bg.png\"></div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"page box flex\"><a href=\"");
		$v14=( $this->get_prop($this->_this,"prev_page") );
		$this->do_html($this->encode($v14));
		$this->do_html("\" class=\"p_left\" title=\"上一页\"></a><span>");
		$v15=( $this->get_prop($this->_this,"pageindex") );
		$this->do_html($this->encode($v15));
		$this->do_html("/");
		$v16=( $this->get_prop($this->_this,"totalpage") );
		$this->do_html($this->encode($v16));
		$this->do_html("</span><a href=\"");
		$v17=( $this->get_prop($this->_this,"next_page") );
		$this->do_html($this->encode($v17));
		$this->do_html("\" class=\"p_right\" title=\"下一页\"></a></div>\r\n\t\t</div>\r\n\t</div>\r\n\r\n</div>\r\n</body>\r\n</html>");
	}
}
