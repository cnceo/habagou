<?php
bjload("bjphp.vendor.ui.CachePage");
class user_index_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n\t<meta charset=\"utf-8\" />\r\n\t<title>哈巴狗</title>\r\n\t<meta name=\"screen-orientation\" content=\"portrait\">\t<!-- uc强制竖屏 -->\r\n\t<meta name=\"browsermode\" content=\"application\">\t\t<!-- UC应用模式 -->\r\n\t<meta name=\"full-screen\" content=\"yes\">\t\t\t\t<!-- UC强制全屏 -->\r\n\t<meta name=\"x5-orientation\" content=\"portrait\">\t\t<!-- QQ强制竖屏 -->\r\n\t<meta name=\"x5-fullscreen\" content=\"true\">\t\t\t<!-- QQ强制全屏 -->\r\n\t<meta name=\"x5-page-mode\" content=\"app\">\t\t\t<!-- QQ应用模式 -->\r\n\r\n\t<meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\">\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/css1.css?\" />\r\n\t<style>\r\n\t\t/*-----提示面板-------*/\r\n\t\t@media (min-width:600px) {\r\n\t\t\t#infoBox{width:600px;margin:0px auto;width:calc(100vh*9/16)!important;height:calc(100vh*375/736);}\r\n\t\t}\r\n\t\t#infoBox {\r\n\t\t\tposition: absolute;\r\n\t\t\twidth: 100%;\r\n\t\t\theight: 375px;\r\n\t\t\tbackground: url(/res/image/infoBg.png) no-repeat center center;\r\n\t\t\tbackground-size: 100% 100%;\r\n\t\t\tposition: absolute;\r\n\t\t\tz-index: 100000001;\r\n\t\t\ttop: 12%;\r\n\t\t\tleft: 0;\r\n\t\t\ttext-align: center;\r\n\t\t\tdisplay: none;\r\n\t\t}\r\n\r\n\t\t#infoBox .close8 {\r\n\t\t\twidth: 31px;\r\n\t\t\theight: 31px;\r\n\t\t\tbackground: url(/res/image/close1.png) no-repeat center center;\r\n\t\t\tbackground-size: 100% 100%;\r\n\t\t\tposition: absolute;\r\n\t\t\ttop: 23%;\r\n\t\t\tright: 9%;\r\n\t\t}\r\n\r\n\t\t#infoBox .infoText {\r\n\t\t\twidth: 100%;\r\n\t\t\tcolor: #815f0f;\r\n\t\t\tfont-size: 18px;\r\n\t\t\tfont-weight: bold;\r\n\t\t\tmargin-top: 190px;\r\n\t\t}\r\n\t\t/*-----提示面板-------*/\r\n\t</style>\r\n\t<script src=\"/res/js/jquery-1.11.2.min.js\" charset=\"utf-8\"></script>\r\n\t<script src=\"/uilib/bjui.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/highcharts.js\" charset=\"utf-8\"></script>\r\n\t<script src=\"/res/js/index.js\" charset=\"utf-8\"></script>\r\n    <script type=\"text/javascript\">\n    \t//".'$'."(\".tjt_div\").css(\"height\", ".'$'."(\"#page\").height() - ".'$'."(\".bottom\").height() - ".'$'."(\".new_content\").height() - 120);\n    </script>\r\n</head>\r\n<body>\r\n<div id=\"page\">\r\n\t<div id=\"gonggao\"><span><img src=\"/res/images/laba.png\">点击查看完整公告</span><div>\r\n\t<marquee scrollAmount=5>\r\n\t");
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
		$this->do_html("\r\n\t\t");
		$v5=( $this->get_prop($this->_this,"@first") );
		if( $this->is_true($v5) ){
		$this->do_html("\r\n\t\t\t");
		$v6=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v6));
		$this->do_html("\r\n\t\t");
		}
		$this->do_html("\r\n\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v4;
		}
		$this->do_html("\r\n\t</marquee></div></div>\r\n\t<div class=\"new_content\">\r\n\t\t<div class=\"new_head\">\r\n\t\t\t<span class=\"pesron\"><img src=\"");
		$v7=( $this->get_prop($this->_this,"image") );
		$this->do_html($this->encode($v7));
		$this->do_html("\"></span>\r\n\t\t\t<ul>\r\n\t\t\t\t<li class=\"li1\"><img src=\"/res/images/xz.png\"><span>");
		$v8=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v8));
		$this->do_html("</span><img src=\"/res/images/xz.png\"></li>\r\n\t\t\t\t<li class=\"li2\"><span><img src=\"/res/images/g1.png\">");
		$v9=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v9));
		$this->do_html("</span><span class=\"span2\"><img src=\"/res/images/g2.png\">");
		$v10=( $this->get_prop($this->_this,"total") );
		$this->do_html($this->encode($v10));
		$this->do_html("</span></li>\r\n\t\t\t</ul>\r\n\t\t\t<div>\r\n\t\t\t\t<label id=\"music_set\"><img src=\"/res/images/shezhi.png\"></label>\r\n\t\t\t\t<label id=\"email_set\"><img src=\"/res/images/dogEmail.png\"></label>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"tjt_div\">\r\n\t\t<img src=\"/res/images/topTitle.png\" class=\"img1\">\r\n\t\t<img src=\"/res/images/ding1.png\" class=\"img2\">\r\n\t\t<img src=\"/res/images/ding2.png\" class=\"img3\">\r\n\t\t<div class=\"tjt\">\r\n\t\t\t<div id=\"tjt\">\r\n\t\t\t\t<div id=\"tjt_pic\"></div>\r\n\t\t\t</div>\r\n\t\t\t<a href=\"/land/land/home\"><span class=\"go_div\">进入狗崽乐园</span></a>\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"bottom\">\r\n\t\t<a href=\"/user/user/register\"><span alt=\"1\"><img src=\"/res/images/shopCenter.png\"></span></a>\r\n\t\t<span id=\"home\"><img src=\"/res/images/dogShop.png\"></span>\r\n\t\t<a href=\"/log/record/growthrecord\"><span alt=\"3\"><img src=\"/res/images/dogDaily.png\"></span></a>\r\n\t\t<span id=\"rule\"><img src=\"/res/images/gameRule.png\"></span>\r\n\t</div>\r\n\r\n\t<div id=\"screen\"></div>\r\n\r\n\t<div id=\"game_RulePanel\" class=\"rule_div\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<ul>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<em>1</em>\r\n\t\t\t\t\t<img src=\"/res/images/icon1.png\">\r\n\t\t\t\t\t<span>    关于开狗场：注册成功系统自动开普通狗场（普通狗场300），后期开金色狗场：点击开狗场按钮，点击开发的狗场地块，只要仓库小狗数量够就开狗场成功，否则失败。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/icon2.png\">\r\n\t\t\t\t\t<em>2</em><span>    关于增养：增养就是把我的仓库里面的小狗增养到任意一块狗场。增养流程：点击增养按钮，出现一闪一闪的阴影狗场，点击然后弹出对话框以后，输入数量，系统默认增养该狗场最大值，点击「确定」，该狗场增养完成。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/m3.png\">\r\n\t\t\t\t\t<em>3</em><span>    关于喂食：每天登入界面收集骨头，点击喂食按钮，出现一闪一闪阴影狗场，选择任意开放的狗场，弹出对话框，输入数量，系统默认喂养最大值，点击「确定」，喂养完成。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/icon4.png\">\r\n\t\t\t\t\t<em>4</em><span>    关于收获：点击收获按钮，出现一闪一闪阴影狗场，点击任意狗场，该狗场除了最低限度外其他小狗都会收回到您的仓库，收获完成。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/icon5.png\">\r\n\t\t\t\t\t<em>5</em><span>    关于转赠：转赠分为超级转赠和普通转赠，超级转赠一步到位，超级转赠按钮打开以后，小狗转赠立即到对方仓库，对方进去增养即可（适用于熟人之间转赠）普通转赠（关闭超级按钮即是普通转赠），普通转赠流程：输入转赠数量，对方电话，姓名，收到验证码以后，点击确定。普通转赠适用于陌生会员之间转赠。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<em>6</em>\r\n\t\t\t\t\t<img src=\"/res/images/icon6.png\">\r\n\t\t\t\t\t<span>    关于清洗：进入我的好友，选择任意好友，点击进入，会有帮助小狗清洗的动画，如果好友喂食了，为好友清洗获得相应狗崽回到仓库，返回页面增养步骤即可。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/icon7.png\">\r\n\t\t\t\t\t<em>7</em><span>    关于饲养员：饲养员设置9个等级，通过用小狗购买饲养员可以提升每日狗崽增长数量，饲养员有年轻饲养员，中年饲养员，老年饲养员，每种饲养员三个形象，衣服上对应星星，月亮，太阳三个形象对应级别。</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t<li style=\"position: relative;\">\r\n\t\t\t\t\t<img src=\"/res/images/icon8.png\">\r\n\t\t\t\t\t<em>8</em><span>    关于骨头泡泡：系统每次拆分完后，会根据每个狗场的狗崽数量产生相应骨头，当天狗场上方出现骨头泡泡。每天您只需点击泡泡让骨头进入仓库，喂食小狗。否则当天产生的骨头没有喂食的话不参与第二天的拆分，第二天骨头会被清零，所以大家一定记得喂食狗崽哦。</span>\r\n\t\t\t\t</li>\r\n\t\t\t</ul>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"gong_gao\" class=\"rule_div\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<div class=\"gonggao_div\">\r\n\t\t\t\t");
		$v11=( $this->get_prop($this->_this,"notice") );
		if( $this->can_each($v11) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v14=$this->_eachobj;
		foreach($v11 as $v12=>$v13){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v13;
			$this->_key=$v12;
		$this->do_html("\r\n\t\t\t\t<dl>\r\n\t\t\t\t\t<dd><span><img src=\"/res/images/textIcon.png\">");
		$v15=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v15));
		$this->do_html("</span><label>");
		$v16=( $this->get_prop($this->_this,"sendtime") );
		$this->do_html($this->encode($v16));
		$this->do_html("</label></dd>\r\n\t\t\t\t\t<dt>");
		$v17=( $this->get_prop($this->_this,"content") );
		$this->do_html($this->encode($v17));
		$this->do_html("</dt>\r\n\t\t\t\t</dl>\r\n\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v14;
		}
		$this->do_html("\r\n\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"in_email\" class=\"rule_div\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<ul>\r\n\t\t\t\t");
		$v18=( $this->get_prop($this->_this,"message") );
		if( $this->can_each($v18) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v21=$this->_eachobj;
		foreach($v18 as $v19=>$v20){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v20;
			$this->_key=$v19;
		$this->do_html("\r\n\t\t\t\t<li>\r\n\t\t\t\t\t<img src=\"/res/images/icon4.png\">\r\n\t\t\t\t\t<span class=\"getDogNum\">");
		$v22=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v22));
		$this->do_html("</span>\r\n\t\t\t\t\t<span class=\"date\">");
		$v23=( $this->get_prop($this->_this,"sendtime") );
		$this->do_html($this->encode($v23));
		$this->do_html("</span>\r\n\t\t\t\t</li>\r\n\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v21;
		}
		$this->do_html("\r\n\t\t\t</ul>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"in_head\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<ul class=\"faceUl\">\r\n\t\t\t\t");
		$v24=( $this->get_prop($this->_this,"images") );
		if( $this->can_each($v24) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v27=$this->_eachobj;
		foreach($v24 as $v25=>$v26){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v26;
			$this->_key=$v25;
		$this->do_html("\r\n\t\t\t\t<li data_id=\"");
		$v28=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v28));
		$this->do_html("\">\r\n\t\t\t\t\t<img src=\"");
		$v29=( $this->get_prop($this->_this,"image") );
		$this->do_html($this->encode($v29));
		$this->do_html("\">\r\n\t\t\t\t\t<span class=\"duigou\"></span>\r\n\t\t\t\t</li>\r\n\t\t\t\t");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v27;
		}
		$this->do_html("\r\n\t\t\t</ul>\r\n\t\t\t<span class=\"go_chage\">确认选择</span>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"game_music\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<div class=\"m_div1\">\r\n\t\t\t\t<span>音效</span>\r\n\t\t\t\t<div class=\"slideThree\">\r\n\t\t\t\t\t<input type=\"checkbox\" value=\"");
		$v30=( $this->get_prop($this->_this,"status") );
		$this->do_html($this->encode($v30));
		$this->do_html("\" id=\"slideThree\"  name=\"check\"\r\n\t\t\t\t\t\t   checked=\"checked\"/>\r\n\t\t\t\t\t<label for=\"slideThree\"></label>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t\t<a href=\"/user/user/loginout\"><span class=\"loginout\">注销登录</span></a>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"dog_home\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<div style=\"overflow:hidden;\">\r\n\t\t\t\t<div class=\"home_intro box\">\r\n\t\t\t\t\t<img src=\"/res/images/laba.png\">敬请期待\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div id=\"edit_password\">\r\n\t\t<div style=\"position:relative;\">\r\n\t\t\t<span class=\"close5\"></span>\r\n\t\t\t<div style=\"overflow:hidden;\">\r\n\t\t\t\t<div class=\"box\">\r\n\t\t\t\t\t<form>\r\n\t\t\t\t\t\t<input type=\"password\" class=\"oldPassword\"  placeholder=\"旧密码\">\r\n\t\t\t\t\t\t<input type=\"password\" class=\"newPassword\" placeholder=\"新密码\">\r\n\t\t\t\t\t\t<input type=\"password\" class=\"PasswordAgain\" placeholder=\"确认密码\">\r\n\t\t\t\t\t\t<span class=\"botton okBtn\">确认修改</span>\r\n\t\t\t\t\t</form>\r\n\t\t\t\t</div>\r\n\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\t<div id=\"infoBox\">\r\n\t\t<span class=\"close8\"></span>\r\n\t\t<div class=\"infoText\">提示信息</div>\r\n\t</div>\r\n</div>\r\n\r\n\r\n\r\n<script type=\"text/javascript\">\r\n    \r\n\t".'$'."(function () {\r\n\r\n\t\t//关闭提示信息\r\n\t\t".'$'."('.close8').on('click',function(){\r\n\t\t\t".'$'."('#infoBox').hide();\r\n\t\t})\r\n\t\t//最新利率\r\n\t\tvar arr1 = [1.0, 2.12, 1.86, 1.9, 1.95, 2.01, 5.2];\r\n\t\t//公共利率\r\n\t\tvar arr2 = [2.5, 2.12, 1.86, 1.8, 1.95, 2.01, 1.88];\r\n\r\n\t\t".'$'."('#tjt_pic').highcharts({\r\n\t\t\tchart: {\r\n\t\t\t\ttype: 'spline',\r\n\t\t\t\tmargin: [0, 0,170, 0],\r\n\t\t\t\tbackgroundColor: 'rgba(0,0,0,0)'\r\n\t\t\t},\r\n\r\n\t\t\ttitle: {\r\n\t\t\t\ttext: null,\r\n\t\t\t\tx:-100,\r\n\t\t\t\ty:100\r\n\t\t\t},\r\n\r\n\t\t\tsubtitle: {\r\n\t\t\t\ttext: null,\r\n\t\t\t\ty:100\r\n\t\t\t},\r\n\r\n\t\t\txAxis: {\r\n\t\t\t\ttype: 'datetime',\r\n\t\t\t\ttickInterval: 24 * 36e5, // one week\r\n\t\t\t\t// lineColor : '#000',\r\n\t\t\t\tlabels: {\r\n\t\t\t\t\tformat: '{value: %m-%d}',\r\n\t\t\t\t\talign: 'right',\r\n\t\t\t\t\trotation: -30\r\n\t\t\t\t},\r\n\r\n\t\t\t},\r\n\r\n\t\t\tyAxis: {\r\n\r\n\t\t\t\ttitle: {\r\n\t\t\t\t\ttext: null\r\n\t\t\t\t}\r\n\t\t\t},\r\n\r\n\t\t\tlegend: {\r\n\t\t\t\tfloating:true,\r\n\t\t\t\tlayout: 'vertical',\r\n\t\t\t\tx:-70,\r\n\t\t\t\ty:-300\r\n\t\t\t},\r\n\r\n\t\t\tplotOptions: {\r\n\t\t\t\tspline: {\r\n\t\t\t\t\tdataLabels: {\r\n\t\t\t\t\t\tenabled: true //开启数据标签\r\n\t\t\t\t\t},\r\n\t\t\t\t\tenableMouseTracking: true\r\n\t\t\t\t}\r\n\t\t\t},\r\n\r\n\t\t\tcredits: {\r\n\t\t\t\tenabled:false\r\n\t\t\t},\r\n\r\n\t\t\ttooltip: {\r\n\t\t\t\tenabled: false\r\n\t\t\t},\r\n\r\n\t\t\tseries: [{\r\n\t\t\t\tname: '最新利率:',\r\n\t\t\t\tdata: arr1,\r\n\t\t\t\tpointInterval: 24 * 36e5,\r\n\t\t\t\tpointStart: Date.UTC(2017,1, 30)\r\n\t\t\t}, {\r\n\t\t\t\tname: '公共利率:',\r\n\t\t\t\tdata: arr2,\r\n\t\t\t\tpointInterval: 24 * 36e5,\r\n\t\t\t\tpointStart: Date.UTC(2017, 1, 30)\r\n\t\t\t}],\r\n\t\t});\r\n\r\n\r\n\r\n\r\n\t\t//密码验证\r\n\t\tfunction checkpass(){\r\n\t\t\tif( ".'$'."('.newPassword').val().length > 5 && ".'$'."('.newPassword').val().length < 13 ){\r\n\t\t\t\treturn true\r\n\t\t\t}\r\n\t\t}\r\n\r\n\t\tvar okBtn = ".'$'."('.okBtn');\r\n\t\tokBtn.on('click',function(){\r\n\t\t\tvar oldpwd = ".'$'."('.oldPassword').val();\r\n\t\t\tvar newpwd = ".'$'."('.newPassword').val();\r\n\t\t\tvar confirmpwd = ".'$'."('.PasswordAgain').val();\r\n\t\t\t//var super = ".'$'."('input[type=\"radio\"]:checked').attr('value')\r\n\r\n\t\t\tif( oldpwd == \"\" ){\r\n\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t".'$'."('#infoBox').find('.infoText').text('请输入旧密码');\r\n\t\t\t}else if( newpwd == \"\" ){\r\n\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t".'$'."('#infoBox').find('.infoText').text('请输入新密码');\r\n\t\t\t}else if( confirmpwd == \"\" ){\r\n\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t".'$'."('#infoBox').find('.infoText').text('请再次输入密码');\r\n\t\t\t}else{\r\n\t\t\t\tif( !checkpass() ){\r\n\t\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t\t".'$'."('#infoBox').find('.infoText').text('密码必须是5到12位');\r\n\t\t\t\t}else{\r\n\t\t\t\t\tif( newpwd != confirmpwd ){\r\n\t\t\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t\t\t".'$'."('#infoBox').find('.infoText').text('确认密码不一致');\r\n\t\t\t\t\t}else{\r\n\t\t\t\t\t\tbjui_config.error_report=function(userexception, src, param, msg, fnFail){\r\n\t\t\t\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t\t\t\t".'$'."('#infoBox').find('.infoText').text(msg);\r\n\t\t\t\t\t\t}\r\n\t\t\t\t\t\t//alert(oldpwd+'==='+newpwd);\r\n\t\t\t\t\t\tBizCall(\r\n\t\t\t\t\t\t\t\t\"user.User.updateLoginpwd\",\r\n\t\t\t\t\t\t\t\t{\r\n\t\t\t\t\t\t\t\t\t\"oldpwd\"\t:  oldpwd,\r\n\t\t\t\t\t\t\t\t\t\"pwd\"   :  newpwd\r\n\t\t\t\t\t\t\t\t},\r\n\t\t\t\t\t\t\t\tfunction(data){\r\n\t\t\t\t\t\t\t\t\t".'$'."('#infoBox').show();\r\n\t\t\t\t\t\t\t\t\t".'$'."('#infoBox').find('.infoText').text('修改密码成功');\r\n\t\t\t\t\t\t\t\t\t\r\n//\t\t\t\t\t\t\t\t\tsetTimeout(function(){\r\n//\t\t\t\t\t\t\t\t\t\t".'$'."('#infoBox').hide();\r\n//\t\t\t\t\t\t\t\t\t\t".'$'."(\"#edit_password\").hide();\r\n//                                      ".'$'."(\"#screen\").hide();\r\n//\t\t\t\t\t\t\t\t\t},1000)\r\n\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t});\r\n\t\t\t\t\t}\r\n\t\t\t\t}\r\n\r\n\t\t\t}\r\n\t\t})\r\n\r\n\t});\r\n</script>\r\n</body>\r\n</html>");
	}
}
