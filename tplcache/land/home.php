<?php
bjload("bjphp.vendor.ui.CachePage");
class land_home_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <title>狗崽乐园</title>\r\n    <meta name=\"screen-orientation\" content=\"portrait\">\t<!-- uc强制竖屏 -->\r\n    <meta name=\"browsermode\" content=\"application\">\t\t<!-- UC应用模式 -->\r\n    <meta name=\"full-screen\" content=\"yes\">\t\t\t\t<!-- UC强制全屏 -->\r\n    <meta name=\"x5-orientation\" content=\"portrait\">\t\t<!-- QQ强制竖屏 -->\r\n    <meta name=\"x5-fullscreen\" content=\"true\">\t\t\t<!-- QQ强制全屏 -->\r\n    <meta name=\"x5-page-mode\" content=\"app\">\t\t\t<!-- QQ应用模式 -->\r\n\r\n    <meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/css.css?t=4\" />\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_land.css\" />\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_main.css\" />\r\n    <script src=\"/res/js/jquery-1.11.2.min.js\" charset=\"utf-8\"></script>\r\n    <script src=\"/uilib/bjui.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n    <script src=\"/res/js/hui_tool.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n    <script src=\"/res/js/fastclick.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n    <!--<script src=\"/res/js/newland.js\" type=\"text/javascript\" charset=\"utf-8\"></script>-->\r\n    <script src=\"/res/js/newland.min.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\r\n    <style>\r\n        /*------------------加号-----------------*/\r\n        .new_gcn ul li .jiahao {\r\n            width: 34px;\r\n            height: 34px;\r\n            position: absolute;\r\n            background: url(/res/image/farm/jiahao.png) no-repeat left bottom;\r\n            background-size: 70% 70%;\r\n            bottom: 35px;\r\n            left: 25px;\r\n            z-index: 20000000;\r\n            color: #ffffff;\r\n            text-align: right;\r\n            font-size: 18px;\r\n            font-weight: bold;\r\n            -webkit-text-stroke-width: 1px;\r\n            -webkit-text-stroke-color: #e1e732;\r\n            display: none;\r\n        }\r\n        /*------------------加号-----------------*/\r\n        /*----确定提示框------*/\r\n        #okInfo1 {\r\n            position: absolute;\r\n            width: 100%;\r\n            height: 58vh!important;\r\n            height: 435px;\r\n            background: url(/res/image/farm/okInfo.png) no-repeat 0 0;\r\n            background-size: 100% 100%;\r\n            top: 10%;\r\n            z-index: 10000000;\r\n            text-align: center;\r\n            color: #815f0f;\r\n            font-size: 18px;\r\n            font-weight: bold;\r\n            display: none;\r\n        }\r\n\r\n        #okInfo1 .okInfoClose1 {\r\n            width: 31px;\r\n            height: 31px;\r\n            background: url(/res/image/close1.png) no-repeat 0 0;\r\n            background-size: 100% 100%;\r\n            position: absolute;\r\n            top: 24%;\r\n            right: 9%;\r\n        }\r\n\r\n        #okInfo1 .infoxiaoxi1 {\r\n            width: 60%;\r\n            margin: 28vh auto  !important;\r\n            margin: 200px auto;\r\n        }\r\n\r\n        #okInfo1 .okInfoB1 {\r\n            width: 62%;\r\n            height: 6vh!important;\r\n            height: 40px;\r\n            background: url(/res/image/farm/queding.png) no-repeat 0 0;\r\n            background-size: 100% 100%;\r\n            margin: -18vh auto  !important;\r\n            margin: 304px auto;\r\n        }\r\n        /*----确定提示框------*/\r\n        \r\n\r\n     \r\n    </style>\r\n</head>\r\n<body>\r\n<div id=\"page\">\r\n    <div id=\"gonggao\" style=\"background: transparent\"><span><img src=\"/res/images/laba.png\">点击查看完整公告</span><div>\r\n            <marquee  scrollAmount=5>\r\n               哈巴狗2017最新公告:");
		$v1=( $this->get_prop($this->_this,"topnotice") );
		$this->do_html($this->encode($v1));
		$this->do_html("\r\n            </marquee>\r\n        </div></div>\r\n    <div class=\"new_content\">\r\n        <div class=\"new_head\">\r\n            <img src=\"");
		$v2=( $this->get_prop($this->_this,"image") );
		$this->do_html($this->encode($v2));
		$this->do_html("\" class=\"pesron\"/>\r\n            <ul>\r\n                <li class=\"li1\"><img src=\"/res/images/xz.png\"><span>");
		$v3=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v3));
		$this->do_html("</span><img src=\"/res/images/xz.png\"></li>\r\n                <li class=\"li2\"><span><img src=\"/res/images/g1.png\"><em class=\"em1\">");
		$v4=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v4));
		$this->do_html("</em></span><span class=\"span2\"><img src=\"/res/images/g2.png\"><em class=\"em2\">");
		$v5=( $this->get_prop($this->_this,"total") );
		$this->do_html($this->encode($v5));
		$this->do_html("</em></span></li>\r\n            </ul>\r\n            <div>\r\n                <label id=\"myFriend\"><img src=\"/res/images/myFriend.png\"></label>\r\n                <label class=\"myWareHouse\"><img src=\"/res/images/myWareHouse.png\"></label>\r\n            </div>\r\n        </div>\r\n        <div class=\"back\"><a href=\"/user/user/home\"><span class=\"back_span1\"><img src=\"/res/images/fh.png\"></span></a><span class=\"back_span2\"><img src=\"/res/images/sx.png\"></span></div>\r\n        <div class=\"new_gc\">\r\n            <div class=\"new_gcn\">\r\n                <input type=\"hidden\" id=\"djyy\" value=\"");
		$v6=( $this->get_prop($this->_this,"yy") );
		$this->do_html($this->encode($v6));
		$this->do_html("\"/>\r\n                <input type=\"hidden\" id=\"zyyy\" value=\"");
		$v7=( $this->get_prop($this->_this,"zyyy") );
		$this->do_html($this->encode($v7));
		$this->do_html("\"/>\r\n                <input type=\"hidden\" id=\"zy_zl\" value=\"");
		$v8=( $this->get_prop($this->_this,"level") );
		$this->do_html($this->encode($v8));
		$this->do_html("\"/>\r\n                <ul>\r\n                    <!-- <li>\r\n                         <img src=\"/res/images/01.gif\" style=\"left:10px;bottom:20px;\">\r\n                         <label></label>\r\n                         <span class=\"paopao\"></span>\r\n                     </li>-->\r\n                    ");
		$v9=( $this->get_prop($this->_this,"landinfo") );
		if( $this->can_each($v9) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v12=$this->_eachobj;
		foreach($v9 as $v10=>$v11){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v11;
			$this->_key=$v10;
		$this->do_html("\r\n                    <li ");
		$v13=( ($this->get_prop($this->_this,"type")) == (1) );
		if( $this->is_true($v13) ){
		$this->do_html("class=\"b2\" ");
		}
		$this->do_html(">\r\n                        <label></label>\r\n                        <span class=\"li_click\"></span>\r\n                        ");
		$v14=( ($this->get_prop($this->_this,"pop")) == (1) );
		if( $this->is_true($v14) ){
		$this->do_html("\r\n                        <span class=\"paopao\"></span>\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v15=( ($this->get_prop($this->_this,"dog")) == (1) );
		if( $this->is_true($v15) ){
		$this->do_html("\r\n                        <img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/00.gif\" />\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v16=( ($this->get_prop($this->_this,"dog")) == (2) );
		if( $this->is_true($v16) ){
		$this->do_html("\r\n                        <img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/00.gif\" />\r\n                        <img id=\"moveimg\" class=\"right2left\" src=\"/res/image/farm/01.gif\" />\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v17=( ($this->get_prop($this->_this,"dog")) == (3) );
		if( $this->is_true($v17) ){
		$this->do_html("\r\n                        <img id=\"moveimg\" class=\"left2right\" src=\"/res/image/farm/00.gif\" />\r\n                        <img id=\"moveimg\" class=\"right2left\" src=\"/res/image/farm/01.gif\" />\r\n                        <img id=\"moveimg\" class=\"left2right left2right1\" src=\"/res/image/farm/00.gif\" />\r\n                        ");
		}
		$this->do_html("\r\n                        <div class=\"dogText\">\r\n                            <div class=\"totalNum\">小狗总数:<em class=\"totalNumA\"></em></div>\r\n                            <div class=\"todayNum\">当天狗仔:<em class=\"todayNumA\"></em></div>\r\n                            <div class=\"oldNum\">历史狗仔:<em class=\"oldNumA\"></em></div>\r\n                        </div>\r\n                        ");
		$v18=( ($this->get_prop($this->_this,"dog")) == (0) );
		if( $this->is_true($v18) ){
		$this->do_html("\r\n                        <div class=\"kaigouChang\" data-flag=\"");
		$v19=( $this->get_prop($this->_this,"dog") );
		$this->do_html($this->encode($v19));
		$this->do_html("\"></div>\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v20=( ($this->get_prop($this->_this,"dog")) != (0) );
		if( $this->is_true($v20) ){
		$this->do_html("\r\n                        <div class=\"zengyangOpacity\" zengyang-flag=\"");
		$v21=( $this->get_prop($this->_this,"dog") );
		$this->do_html($this->encode($v21));
		$this->do_html("\"></div>\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v22=( ($this->get_prop($this->_this,"dog")) != (0) );
		if( $this->is_true($v22) ){
		$this->do_html("\r\n                        <div class=\"weiyangOpacity\"></div>\r\n                        ");
		}
		$this->do_html("\r\n                        ");
		$v23=( ($this->get_prop($this->_this,"dog")) != (0) );
		if( $this->is_true($v23) ){
		$this->do_html("\r\n                        <div class=\"shouhuo\"></div>\r\n                        ");
		}
		$this->do_html("\r\n                        <div class=\"jiahao\"></div>\r\n                    </li>\r\n                    ");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v12;
		}
		$this->do_html("\r\n\r\n                    <!--<li class=\"b2\"><label></label></li>-->\r\n                </ul>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class=\"bottom1\">\r\n        <span class=\"mu1\" alt=\"1\"><img src=\"/res/images/m1.png\"><b>开狗场</b></span>\r\n        <span class=\"mu2\" alt=\"2\"><img src=\"/res/images/m2.png\"><b>增养</b></span>\r\n        <span class=\"mu3\" alt=\"3\"><img src=\"/res/images/m3.png\"><b>驯化狗崽</b></span>\r\n        <span class=\"mu4\" alt=\"4\"><img src=\"/res/images/m4.png\"><b>收获</b></span>\r\n    </div>\r\n    <!--饲养员-->\r\n\r\n    <div id=\"drag\" class=\"feederLever");
		$v24=( $this->get_prop($this->_this,"feederlevel") );
		$this->do_html($this->encode($v24));
		$this->do_html("\"></div>\r\n\r\n    <!--公告-->\r\n    <div id=\"noticeBox\">\r\n        <span class=\"close13\"></span>\r\n        <ul>\r\n            ");
		$v25=( $this->get_prop($this->_this,"notice") );
		if( $this->can_each($v25) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v28=$this->_eachobj;
		foreach($v25 as $v26=>$v27){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v27;
			$this->_key=$v26;
		$this->do_html("\r\n            <li>\r\n                <div class=\"top\">\r\n                    <span class=\"text\">");
		$v29=( $this->get_prop($this->_this,"title") );
		$this->do_html($this->encode($v29));
		$this->do_html("</span>\r\n                    <span class=\"time\">");
		$v30=( $this->get_prop($this->_this,"sendtime") );
		$this->do_html($this->encode($v30));
		$this->do_html("</span>\r\n                </div>\r\n                <div class=\"contentBox\">\r\n                    <span class=\"num\">");
		$v31=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v31));
		$this->do_html("</span>\r\n                    <p class=\"totalText\">");
		$v32=( $this->get_prop($this->_this,"content") );
		$this->do_html($this->encode($v32));
		$this->do_html("</p>\r\n                </div>\r\n            </li>\r\n            ");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v28;
		}
		$this->do_html("\r\n        </ul>\r\n    </div>\r\n    <!--公告-->\r\n    <!--好友列表-->\r\n    <div id=\"friendList\">\r\n        <span class=\"friendListClose\"></span>\r\n        <ul>\r\n            ");
		$v33=( $this->get_prop($this->_this,"friend") );
		if( $this->can_each($v33) ){
		$this->_index_stack[] = $this->_index;
		$this->_index=0;
		$this->_key_stack[] = $this->_key;
		$this->_key=null;
		$v36=$this->_eachobj;
		foreach($v33 as $v34=>$v35){
			$this->_this_stack[] = $this->_this;
			$this->_this=$v35;
			$this->_key=$v34;
		$this->do_html("\r\n            <!--<li>\r\n                <div class=\"top\">\r\n                    <img src=\"../../res/image/index/face/1.png\"/>\r\n                    <div class=\"vipBox\">\r\n                        <span class=\"VipName\">");
		$v37=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v37));
		$this->do_html("</span>\r\n                        <span class=\"num\" style=\"font-size: 12px\">");
		$v38=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v38));
		$this->do_html("</span>\r\n                    </div>\r\n                    <a href=\"/user/user/visitFriend/");
		$v39=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v39));
		$this->do_html("\"><div class=\"btn\" style=\"width: 25%\">去拜访</div></a>\r\n                </div>\r\n                <div class=\"bottom\">\r\n                    <span class=\"name\" style=\"width: 48px;margin-right: 0px;margin-left: -10px;\">");
		$v40=( $this->get_prop($this->_this,"name") );
		$this->do_html($this->encode($v40));
		$this->do_html("</span>\r\n                    <span class=\"phone\">");
		$v41=( $this->get_prop($this->_this,"phone") );
		$this->do_html($this->encode($v41));
		$this->do_html("</span>\r\n                </div>\r\n            </li>-->\r\n            <!--<li style=\"background:#f7edbd;\">\r\n                <div style=\"overflow:hidden;padding:5px;\">\r\n                    <span style=\"display:block;float:left;margin-right:5px;\"><img src=\"/res/image/index/face/1.png\" style=\"width:50px;height:50px;\"></span>\r\n                    <p style=\"float:left;\">");
		$v42=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v42));
		$this->do_html("<span style=\"position:relative;border-top:solid 2px #6e5118;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 10px 0px 30px;line-height:25px;\"><img src=\"/res/images/g2.png\" style=\"width:30px;height:30px;position:absolute;left:-5px;top:-5px;\">");
		$v43=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v43));
		$this->do_html("</span></p>\r\n                    <a href=\"/user/user/visitFriend/");
		$v44=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v44));
		$this->do_html("\" style=\"display:block;float:right;background:url(http://www.habagou.com/res/image/farm/btn.png) no-repeat 0px 0px;width:50px;height:30px;line-height:30px;text-align:center;color:#fff;background-size:100% 100%\">去拜访</a>\r\n                </div>\r\n                <div style=\"border-top:solid 2px #fff;overflow:hidden;padding:10px 0px;\">\r\n                    <span style=\"color:#fff;text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;-webkit-text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;float:left;line-height:25px;padding-left:10px;\">个人信息</span>\r\n                    <span style=\"position:relative;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:10px;\">");
		$v45=( $this->get_prop($this->_this,"name") );
		$this->do_html($this->encode($v45));
		$this->do_html("</span>\r\n                    <span style=\"position:relative;background:url(/res/images/tel.png) no-repeat 5px center #e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:10px;background-size:auto 100%;padding-left:30px;\">");
		$v46=( $this->get_prop($this->_this,"phone") );
		$this->do_html($this->encode($v46));
		$this->do_html("</span>\r\n                </div>\r\n            </li>-->\r\n            <li style=\"background:#f7edbd;border:solid 2px #6e5118;border-radius:10px 0px 10px 0px;overflow:hidden;\">\r\n                <div style=\"overflow:hidden;padding:5px;\">\r\n                    <span style=\"display:block;float:left;margin-right:5px;\"><img src=\"");
		$v47=( $this->get_prop($this->_this,"image") );
		$this->do_html($this->encode($v47));
		$this->do_html("\" style=\"width:50px;height:50px;\"></span>\r\n                    <p style=\"float:left;\">");
		$v48=( $this->get_prop($this->_this,"account") );
		$this->do_html($this->encode($v48));
		$this->do_html("<span style=\"position:relative;border-top:solid 2px #6e5118;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 10px 0px 30px;line-height:25px;\"><img src=\"/res/images/g2.png\" style=\"width:30px;height:30px;position:absolute;left:-5px;top:-5px;\">");
		$v49=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v49));
		$this->do_html("</span></p>\r\n                    <a href=\"/user/user/visitFriend/");
		$v50=( $this->get_prop($this->_this,"id") );
		$this->do_html($this->encode($v50));
		$this->do_html("\" style=\"display:block;float:right;background:url(/res/image/farm/btn.png) no-repeat 0px 0px;width:50px;height:30px;line-height:30px;text-align:center;color:#fff;background-size:100% 100%\">去拜访</a>\r\n                </div>\r\n                <div style=\"border-top:solid 2px #fff;overflow:hidden;padding:10px 0px;\">\r\n                    <span style=\"color:#fff;text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;-webkit-text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;float:left;line-height:25px;padding-left:3px;\">个人信息</span>\r\n                    <span style=\"position:relative;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:5px;\">");
		$v51=( $this->get_prop($this->_this,"name") );
		$this->do_html($this->encode($v51));
		$this->do_html("</span>\r\n                    <span style=\"position:relative;background:url(/res/images/tel.png) no-repeat 5px center #e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px 0px 20px;line-height:25px;float:left;margin-left:5px;background-size:auto 100%;\">");
		$v52=( $this->get_prop($this->_this,"phone") );
		$this->do_html($this->encode($v52));
		$this->do_html("</span>\r\n                </div>\r\n            </li>\r\n            ");
			$this->_this=array_pop($this->_this_stack);
			$this->_index++;
			}
		$this->_index=array_pop($this->_index_stack);
		$this->_key=array_pop($this->_key_stack);
		$this->_eachobj=$v36;
		}
		$this->do_html("\r\n\r\n        </ul>\r\n        <div class=\"bottomBtn\">\r\n            <span class=\"onekeyclean\"></span>\r\n            <span class=\"yaoqing\"></span>\r\n        </div>\r\n    </div>\r\n\r\n    <!--我的仓库-->\r\n    <div id=\"wareHouse\">\r\n        <span class=\"wareHouseClose\"></span>\r\n        <ul>\r\n            <li>\r\n                <img src=\"../../res/image/index/face/xiaogou.png\"/>\r\n                <div class=\"vipBox\">\r\n                    <span class=\"type\">小狗</span>\r\n                    <span id=\"xiaogoushu\" class=\"num\">");
		$v53=( $this->get_prop($this->_this,"warehouse") );
		$this->do_html($this->encode($v53));
		$this->do_html("</span>\r\n                </div>\r\n                <div id=\"zengyangBtn\" class=\"btn\">去增养</div>\r\n            </li>\r\n            <li>\r\n                <img src=\"../../res/image/index/face/dogFace.png\"/>\r\n                <div class=\"vipBox\">\r\n                    <span class=\"type\">狗崽</span>\r\n                    <span id=\"bone\" class=\"num\">");
		$v54=( $this->get_prop($this->_this,"bone") );
		$this->do_html($this->encode($v54));
		$this->do_html("</span>\r\n                </div>\r\n                <div id=\"wYangBtn\" class=\"btn\">去喂食</div>\r\n            </li>\r\n            <li>\r\n                <img id=\"langanlevel\" src=\"../../res/image/index/face/langan");
		$v55=( $this->get_prop($this->get_prop($this->_this,"@root"),"level") );
		$this->do_html($this->encode($v55));
		$this->do_html(".png\"/>\r\n                <div class=\"vipBox\">\r\n                    <span class=\"type\" style=\"margin-left: 5px;\">大栅栏</span>\r\n                    <p class=\"text\">每邀请10位好友可 获得/升级</p>\r\n                </div>\r\n                <a href=\"/user/user/register\"><div class=\"btn\">获得</div></a>\r\n            </li>\r\n            <li>\r\n                <img src=\"../../res/image/index/face/shaoba.png\"/>\r\n                <div class=\"vipBox\">\r\n                    <span class=\"type\" style=\"margin-left: 5px;\">一键清洗</span>\r\n                    <p class=\"text\">清洗所有好友小友期限30天</p>\r\n                </div>\r\n                <input type=\"button\" name=\"buy\"  id=\"buy\" value=\"购买\" class=\"btn\" />\r\n                <!--<div id=\"buy\" class=\"btn\">购买</div>-->\r\n            </li>\r\n            <li>\r\n                <img src=\"../../res/image/index/face/siyangyuan.png\"/>\r\n                <div class=\"vipBox\">\r\n                    <span id=\"feederlevel\" class=\"type\" feederlevel = \"");
		$v56=( $this->get_prop($this->_this,"feederlevel") );
		$this->do_html($this->encode($v56));
		$this->do_html("\" style=\"margin-left: 5px;\">饲养员Lv.<em id=\"dengji\">");
		$v57=( $this->get_prop($this->_this,"feederlevel") );
		$this->do_html($this->encode($v57));
		$this->do_html("</em></span>\r\n                    <p class=\"text\">等级越高，小狗的等级越高</p>\r\n                </div>\r\n                <div id=\"shengji\" class=\"btn\">升级</div>\r\n            </li>\r\n        </ul>\r\n    </div>\r\n    <!--输入喂养-->\r\n    <div id=\"feed\" class=\"weiyang\" style=\"display: none;\">\r\n        <span class=\"close12\"></span>\r\n        <span class=\"text\">输入喂食数量</span>\r\n        <input type=\"number\" name=\"numner\" class=\"numberFeed\" />\r\n        <div class=\"pan\"></div>\r\n        <div id=\"weiyangBtn\" class=\"queding\"></div>\r\n    </div>\r\n    <!--输入增养-->\r\n    <div id=\"feed\" class=\"zengyang\" style=\"display: none;\">\r\n        <span class=\"close12\"></span>\r\n        <span class=\"text\">输入增养数量</span>\r\n        <input type=\"number\" name=\"numner\" class=\"numberAdd\" />\r\n        <div class=\"pan\"></div>\r\n        <div id=\"addyang\" class=\"queding\"></div>\r\n    </div>\r\n    <!--确定提示-->\r\n    <div id=\"okInfo\">\r\n        <span class=\"okInfoClose\"></span>\r\n        <p class=\"infoxiaoxi\"></p>\r\n        <div class=\"okInfoB\"></div>\r\n    </div>\r\n\r\n    <!--确定提示框-->\r\n    <div id=\"okInfo1\" >\r\n        <span class=\"okInfoClose1\"></span>\r\n        <p class=\"infoxiaoxi1\"></p>\r\n        <div class=\"okInfoB1\"></div>\r\n    </div>\r\n    <!--确定提示框-->\r\n    <!--提示-->\r\n    <div id=\"infoBox\">\r\n        <span class=\"close8\"></span>\r\n        <div class=\"infoText\">提示信息</div>\r\n    </div>\r\n    <!--收获-->\r\n    <div id=\"harvest\">\r\n        <span class=\"close10\"></span>\r\n        <p class=\"text\">恭喜收获<span class=\"harvestNum\"></span>条小狗</p>\r\n    </div>\r\n    <!--遮罩-->\r\n    <div id=\"screen\"></div>\r\n    <div id=\"screen1\"></div>\r\n   \r\n    <audio audio-flag =\"");
		$v58=( $this->get_prop($this->_this,"status") );
		$this->do_html($this->encode($v58));
		$this->do_html("\" id=\"audio\" src=\"/res/image/farm/gougou.mp3\" preload=\"auto\"  style=\"position: absolute;top: 2px;left: 2px\">\r\n       <!-- Your browser does not support the audio element.-->\r\n    </audio>\r\n\r\n    <div id=\"visitFriend\"></div>\r\n\r\n</div>\r\n\r\n</body>\r\n</html>");
	}
}
