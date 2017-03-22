<?php
bjload("bjphp.vendor.ui.CachePage");
class user_clean_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<!DOCTYPE html>\r\n<html>\r\n\t<head>\r\n\t\t<meta charset=\"UTF-8\">\r\n\t\t<meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0\" />\r\n\t\t<meta name=\"apple-mobile-web-app-capable\" content=\"yes\" />\r\n\t\t<meta name=\"apple-touch-fullscreen\" content=\"yes\"  />\r\n\t\t<meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\" />\r\n\t\t<meta name=\"format-detection\" content=\"telephone=no\">\r\n\t\t<title>养狗日志</title>\r\n\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_main.css\"/>\r\n\t\t<style type=\"text/css\">\r\n\t\t\thtml {  \r\n\t\t\t  height:100%;  \r\n\t\t\t}  \r\n\t\t\tbody {  \r\n\t\t\t  background:url(../../res/image/index/indexbg.png) repeat-y center center;\r\n\t\t\t  background-size: 100% 100%;  \r\n\t\t\t /* min-heigth:min-height:100%;  height:100%; */\r\n\t\t\t overflow: hidden;\r\n\t\t\t} \r\n\t\t</style>\r\n\t</head>\r\n\t<body>\r\n\t\t<div id=\"page\" class=\"page dailyRecord clean\">\r\n\t\t\t<div class=\"link\">\r\n\t\t\t\t<a href=\"/log/record/growthrecord\"></a>\r\n\t\t\t\t<a href=\"/log/record/cleanrecord\"></a>\r\n\t\t\t\t<a href=\"/log/record/feedrecord\"></a>\r\n\t\t\t\t<a href=\"/log/record/raiserecordcord\"></a>\r\n\t\t\t</div>\r\n\t\t\t<div id=\"TopBox\">\r\n\t\t\t\t<span class=\"back\"><a href=\"/user/user/home\" style=\"color: #FFFFFF;\">返回</a></span>\r\n\t\t\t\t<img class=\"face\" src=\"../../res/image/index/face/1.png\" />\r\n\t\t\t</div>\r\n\t\t\t<ul>\r\n\t\t\t\t");
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
		$this->do_html("\r\n\t\t\t\t<li>\r\n\t\t\t\t\t<span>");
		$v5=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v5));
		$this->do_html("</span>\r\n\t\t\t\t\t<span>");
		$v6=( $this->get_prop($this->_this,"num") );
		$this->do_html($this->encode($v6));
		$this->do_html("</span>\r\n\t\t\t\t\t<span>");
		$v7=( $this->get_prop($this->_this,"cleantime") );
		$this->do_html($this->encode($v7));
		$this->do_html("</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v4;
		}
		$this->do_html("\r\n\r\n\t\t\t</ul>\r\n\t\t\t<div class=\"fenye\">\r\n\t\t\t\t<a href=\"");
		$v8=( $this->get_prop($this->_this,"prev_page") );
		$this->do_html($this->encode($v8));
		$this->do_html("\"><span class=\"fenYeLeft\"></span></a>\r\n\t\t\t\t<span class=\"fenshu\"><em>");
		$v9=( $this->get_prop($this->_this,"pageindex") );
		$this->do_html($this->encode($v9));
		$this->do_html("</em>/");
		$v10=( $this->get_prop($this->_this,"totalpage") );
		$this->do_html($this->encode($v10));
		$this->do_html("</span>\r\n\t\t\t\t<a href=\"");
		$v11=( $this->get_prop($this->_this,"next_page") );
		$this->do_html($this->encode($v11));
		$this->do_html("\"><span class=\"fenYeRight\"></span></a>\r\n\t\t\t</div>\r\n\t\t\t<ol>\r\n\t\t\t\t<li>\r\n\t\t\t\t\t<span>1.68</span>\r\n\t\t\t\t\t<div>清洗收益</div>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li>\r\n\t\t\t\t\t<span>3116.51</span>\r\n\t\t\t\t\t<div>总生产小狗</div>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li>\r\n\t\t\t\t\t<span>0.88</span>\r\n\t\t\t\t\t<div>喂食收益</div>\r\n\t\t\t\t</li>\r\n\t\t\t</ol>\r\n\t\t</div>\r\n\t</body>\r\n</html>");
	}
}
