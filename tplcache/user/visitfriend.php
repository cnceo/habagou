<?php
bjload("bjphp.vendor.ui.CachePage");
class user_visitfriend_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n\t<meta charset=\"utf-8\" />\r\n\t<title>狗场</title>\r\n\t<meta name=\"screen-orientation\" content=\"portrait\">\t<!-- uc强制竖屏 -->\r\n\t<meta name=\"browsermode\" content=\"application\">\t\t<!-- UC应用模式 -->\r\n\t<meta name=\"full-screen\" content=\"yes\">\t\t\t\t<!-- UC强制全屏 -->\r\n\t<meta name=\"x5-orientation\" content=\"portrait\">\t\t<!-- QQ强制竖屏 -->\r\n\t<meta name=\"x5-fullscreen\" content=\"true\">\t\t\t<!-- QQ强制全屏 -->\r\n\t<meta name=\"x5-page-mode\" content=\"app\">\t\t\t<!-- QQ应用模式 -->\r\n\r\n\t<meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\">\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/css.css\" />\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_land.css\" />\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_main.css\" />\r\n\t<script src=\"/res/js/jquery-1.11.2.min.js\" charset=\"utf-8\"></script>\r\n\t<script src=\"/uilib/bjui.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/hui_tool.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/hui_visitfriend.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/fastclick.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<!--<script src=\"/res/js/newland.js\" type=\"text/javascript\" charset=\"utf-8\"></script>-->\r\n\t<script src=\"/res/js/newland.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<style>\r\n\t\t.new_head ul{\r\n\t\t\t/*<!-- flex:2; -->*/\r\n\t\t\t-webkit-box-flex:2;\r\n\t\t\t-moz-box-flex:2;\r\n\t\t\t-ms-flex:2;\r\n\t\t\twidth:80%;\r\n\t\t\tfont-size:1.3rem;\r\n\t\t}\r\n\t</style>\r\n</head>\r\n<body>\r\n<div id=\"page\">\r\n\t<div id=\"gonggao\" ><span><img src=\"/res/images/laba.png\">点击查看完整公告</span><div><marquee scrollAmount=5>\r\n\t\t\t\t哈巴狗2017最新公告:\r\n\t\t\t\t");
		$v1=( $this->get_prop($this->_this,"notice") );
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
		$this->do_html("\r\n\t\t\t\t");
		$v5=( $this->get_prop($this->_this,"@first") );
		if( $this->is_true($v5) ){
		$this->do_html("\r\n\t\t\t\t");
		$v6=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v6));
		$this->do_html("\r\n\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v4;
		}
		$this->do_html("\r\n\t\t\t</marquee></div></div>\r\n\t<div class=\"new_content\">\r\n\t\t<div class=\"new_head\">\r\n\t\t\t<img src=\"");
		$v7=( $this->get_prop($this->_this,"headimg") );
		$this->do_html($this->encode($v7));
		$this->do_html("\" class=\"pesron\"/>\r\n\t\t\t<ul>\r\n\t\t\t\t<li class=\"li1\"><img src=\"/res/images/xz.png\"><span>");
		$v8=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v8));
		$this->do_html("</span><img src=\"/res/images/xz.png\"></li>\r\n\t\t\t\t<li class=\"li2\"><span><img src=\"/res/images/g1.png\"><em class=\"em1\">");
		$v9=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v9));
		$this->do_html("</em></span><span class=\"span2\"><img src=\"/res/images/g2.png\"><em class=\"em2\">");
		$v10=( $this->get_prop($this->_this,"total") );
		$this->do_html($this->encode($v10));
		$this->do_html("</em></span></li>\r\n\t\t\t</ul>\r\n\t\t\t<!--<div>\r\n\t\t\t\t<label id=\"myFriend\"><img src=\"/res/images/myFriend.png\"></label>\r\n\t\t\t\t<label class=\"myWareHouse\"><img src=\"/res/images/myWareHouse.png\"></label>\r\n\t\t\t</div>-->\r\n\t\t</div>\r\n\t\t<div class=\"back\"><span class=\"back_span1 backLink\"><img src=\"/res/images/fh.png\"></span><span class=\"back_span2\"><img src=\"/res/images/sx.png\"></span></div>\r\n\t\t<div class=\"new_gc\">\r\n\t\t\t<input type=\"hidden\" id=\"zy_zl\" value=\"");
		$v11=( $this->get_prop($this->_this,"level") );
		$this->do_html($this->encode($v11));
		$this->do_html("\"/>\r\n\t\t\t<div class=\"new_gcn\">\r\n\t\t\t\t<ul>\r\n\t\t\t\t\t");
		$v12=( $this->get_prop($this->_this,"landinfo") );
		if( $this->can_each($v12) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v15=$this->_eachobj;
		foreach($v12 as $v13=>$v14){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v14;
			$this->_key=$v13;
		$this->do_html("\r\n\t\t\t\t\t<li ");
		$v16=( ($this->get_prop($this->_this,"type")) == (1) );
		if( $this->is_true($v16) ){
		$this->do_html("class=\"b2\" ");
		}
		$this->do_html("><label></label>\r\n\t\t\t\t\t\t<span class=\"li_click\"></span>\r\n\r\n\t\t\t\t\t\t");
		$v17=( ($this->get_prop($this->_this,"dog")) == (1) );
		if( $this->is_true($v17) ){
		$this->do_html("\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/00.gif\" />\r\n\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t");
		$v18=( ($this->get_prop($this->_this,"dog")) == (2) );
		if( $this->is_true($v18) ){
		$this->do_html("\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/00.gif\" />\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"right2left\" src=\"/res/image/farm/01.gif\" />\r\n\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\t\t\t\t\t\t");
		$v19=( ($this->get_prop($this->_this,"dog")) == (3) );
		if( $this->is_true($v19) ){
		$this->do_html("\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/dog/00.gif\" />\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"right2left\" src=\"/res/image/farm/dog/01.gif\" />\r\n\t\t\t\t\t\t<img id=\"moveimg\" class=\"left2right left2right1\" src=\"/res/image/farm/dog/00.gif\" />\r\n\t\t\t\t\t\t");
		}
		$this->do_html("\r\n\r\n\r\n\t\t\t\t\t</li>\r\n\t\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v15;
		}
		$this->do_html("\r\n\r\n\t\t\t\t\t<!--<li class=\"b2\"><label></label></li>-->\r\n\t\t\t\t</ul>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"bottom1\">\r\n\t\t<div id=\"oneKeyCleans\" class=\"cleanDog");
		$v20=( $this->get_prop($this->_this,"clean") );
		$this->do_html($this->encode($v20));
		$this->do_html("\" clean_id = \"");
		$v21=( $this->get_prop($this->_this,"clean") );
		$this->do_html($this->encode($v21));
		$this->do_html("\" toAccid = \"");
		$v22=( $this->get_prop($this->_this,"toAccid") );
		$this->do_html($this->encode($v22));
		$this->do_html("\"></div>\r\n\t</div>\r\n\r\n\t<!--公告-->\r\n\t<div id=\"noticeBox\">\r\n\t\t<span class=\"close13\"></span>\r\n\t\t<ul>\r\n\t\t\t");
		$v23=( $this->get_prop($this->_this,"notice") );
		if( $this->can_each($v23) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v26=$this->_eachobj;
		foreach($v23 as $v24=>$v25){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v25;
			$this->_key=$v24;
		$this->do_html("\r\n\t\t\t<li>\r\n\t\t\t\t<div class=\"top\">\r\n\t\t\t\t\t<span class=\"text\">");
		$v27=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v27));
		$this->do_html("</span>\r\n\t\t\t\t\t<span class=\"time\">");
		$v28=( $this->get_prop($this->_this,"sendtime") );
		$this->do_html($this->encode($v28));
		$this->do_html("</span>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"contentBox\">\r\n\t\t\t\t\t<span class=\"num\">");
		$v29=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v29));
		$this->do_html("</span>\r\n\t\t\t\t\t<p class=\"totalText\">");
		$v30=( $this->get_prop($this->_this,"content") );
		$this->do_html($this->encode($v30));
		$this->do_html("</p>\r\n\t\t\t\t</div>\r\n\t\t\t</li>\r\n\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v26;
		}
		$this->do_html("\r\n\t\t</ul>\r\n\t</div>\r\n\t<!--公告-->\r\n    <!--清洗动画\t-->\r\n\t<img id=\"cleanGif\" src=\"/res/image/farm/clean.gif\"/>\r\n\t<!--确定提示框-->\r\n\t<div id=\"okInfo\">\r\n\t\t<span class=\"okInfoClose\"></span>\r\n\t\t<p class=\"infoxiaoxi\"></p>\r\n\t\t<div class=\"okInfoB\"></div>\r\n\t</div>\r\n\t<!--提示框-->\r\n\t<div id=\"infoBox\">\r\n\t\t<span class=\"close8\"></span>\r\n\t\t<div class=\"infoText\">提示信息</div>\r\n\t</div>\r\n\r\n\t<!--遮罩-->\r\n\t<div id=\"screen\"></div>\r\n\r\n\r\n</div>\r\n\r\n</body>\r\n</html>");
	}
}
