<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>狗场</title>
	<meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
	<meta name="browsermode" content="application">		<!-- UC应用模式 -->
	<meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
	<meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
	<meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
	<meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->

	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<link rel="stylesheet" type="text/css" href="/res/css/css.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/hui_land.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css" />
	<script src="/res/js/jquery-1.11.2.min.js" charset="utf-8"></script>
	<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/hui_visitfriend.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/fastclick.js" type="text/javascript" charset="utf-8"></script>
	<!--<script src="/res/js/newland.js" type="text/javascript" charset="utf-8"></script>-->
	<script src="/res/js/newland.js" type="text/javascript" charset="utf-8"></script>
	<style>
		.new_head ul{
			/*<!-- flex:2; -->*/
			-webkit-box-flex:2;
			-moz-box-flex:2;
			-ms-flex:2;
			width:80%;
			font-size:1.3rem;
		}
	</style>
</head>
<body>
<div id="page">
	<div id="gonggao" ><span><img src="/res/images/laba.png">点击查看完整公告</span><div><marquee scrollAmount=5>
				哈巴狗2017最新公告:
				{{#each notice}}
				{{#if @first}}
				{{title}}
				{{#end}}
				{{#end}}
			</marquee></div></div>
	<div class="new_content">
		<div class="new_head">
			<img src="{{headimg}}" class="pesron"/>
			<ul>
				<li class="li1"><img src="/res/images/xz.png"><span>{{account}}</span><img src="/res/images/xz.png"></li>
				<li class="li2"><span><img src="/res/images/g1.png"><em class="em1">{{bone}}</em></span><span class="span2"><img src="/res/images/g2.png"><em class="em2">{{total}}</em></span></li>
			</ul>
			<!--<div>
				<label id="myFriend"><img src="/res/images/myFriend.png"></label>
				<label class="myWareHouse"><img src="/res/images/myWareHouse.png"></label>
			</div>-->
		</div>
		<div class="back"><span class="back_span1 backLink"><img src="/res/images/fh.png"></span><span class="back_span2"><img src="/res/images/sx.png"></span></div>
		<div class="new_gc">
			<input type="hidden" id="zy_zl" value="{{level}}"/>
			<div class="new_gcn">
				<ul>
					{{#each landinfo}}
					<li {{#if type== 1}}class="b2" {{#end}}><label></label>
						<span class="li_click"></span>

						{{#if dog == 1}}
						<img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />
						{{#end}}
						{{#if dog == 2}}
						<img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />
						<img id="moveimg" class="right2left" src="/res/image/farm/01.gif" />
						{{#end}}
						{{#if dog == 3}}
						<img id="moveimg" class="left2right" src="/res/image/farm/dog/00.gif" />
						<img id="moveimg" class="right2left" src="/res/image/farm/dog/01.gif" />
						<img id="moveimg" class="left2right left2right1" src="/res/image/farm/dog/00.gif" />
						{{#end}}


					</li>
					{{#end}}

					<!--<li class="b2"><label></label></li>-->
				</ul>
			</div>
		</div>
	</div>
	<div class="bottom1">
		<div id="oneKeyCleans" class="cleanDog{{clean}}" clean_id = "{{clean}}" toAccid = "{{toAccid}}"></div>
	</div>

	<!--公告-->
	<div id="noticeBox">
		<span class="close13"></span>
		<ul>
			{{#each notice}}
			<li>
				<div class="top">
					<span class="text">{{title}}</span>
					<span class="time">{{sendtime}}</span>
				</div>
				<div class="contentBox">
					<span class="num">{{id}}</span>
					<p class="totalText">{{content}}</p>
				</div>
			</li>
			{{#end}}
		</ul>
	</div>
	<!--公告-->
    <!--清洗动画	-->
	<img id="cleanGif" src="/res/image/farm/clean.gif"/>
	<!--确定提示框-->
	<div id="okInfo">
		<span class="okInfoClose"></span>
		<p class="infoxiaoxi"></p>
		<div class="okInfoB"></div>
	</div>
	<!--提示框-->
	<div id="infoBox">
		<span class="close8"></span>
		<div class="infoText">提示信息</div>
	</div>

	<!--遮罩-->
	<div id="screen"></div>


</div>

</body>
</html>