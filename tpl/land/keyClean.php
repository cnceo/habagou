<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />		<!-- iphone safri 全屏 -->
	    <meta name="apple-mobile-web-app-status-bar-style" content="black" />	<!-- iphone safri 状态栏的背景颜色 -->
	    <meta name="apple-mobile-web-app-title" content="一文鸡">		<!-- iphone safri 添加到主屏界面的显示标题 -->
	    <meta name="format-detection" content="telphone=no, email=no" />	<!-- 禁止数字识自动别为电话号码 -->
	    <meta name="renderer" content="webkit">				<!-- 启用360浏览器的极速模式(webkit) -->
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">	
	    <meta name="HandheldFriendly" content="true">		<!-- 是针对一些老的不识别viewport的浏览器，列如黑莓 -->
	    <meta name="MobileOptimized" content="320">			<!-- 微软的老式浏览器 -->
	    <meta http-equiv="Cache-Control" content="no-siteapp" />	<!-- 禁止百度转码 -->
	    <meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
	    <meta name="browsermode" content="application">		<!-- UC应用模式 -->
	    <meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
	    <meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
	    <meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
	    <meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->
	    <meta name="msapplication-tap-highlight" content="no">
	    <meta name="msapplication-TileColor" content="#000"/> 		<!-- Windows 8 磁贴颜色 -->   
		<meta name="msapplication-TileImage" content="icon.png"/>	<!-- Windows 8 磁贴图标 -->
	    <link rel="Shortcut Icon" href="favicon.ico">		<!-- 浏览器tab图标 -->
	    <link rel="apple-touch-icon" href="assets/images/icon.jpg" />				<!-- iPhone 和 iTouch，默认 57x57 像素，必须有 -->
	    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/icon.jpg" />	<!-- iPad，72x72 像素  -->
	    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/icon.jpg" />	<!-- Retina iPhone 和 Retina iTouch，114x114 像素 -->

		<title>一起来养狗</title>
		<link rel="stylesheet" type="text/css" href="../../res/css/hui_main.css"/>
		<style type="text/css">
			html {  
			  height:100%;  
			}  
			body {  
			  background:url(../../res/image/farm/farmBg1.png) repeat-y center center;
			  background-size: 100% 100%;  
			 /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
			} 
		</style>
	</head>
	<body style="height: 100%;overflow: hidden;">
		<div id="page">
			<div id="notice">
				<span class="laba">(点击查看完整公告)</span>
				<p class="noticeText">规则更新通知:规则更知:则知:则</p>
			</div>
			<div id="contain">
				<div class="account">
					<img src="../../res/image/index/face/1.png" class="face"></img>
					<span class="name">会员名称</span>
					<span class="numA">300</span>
					<span class="numB">200</span>
				</div>
				<div class="backBox">
					<a href="/user/index" class="back"></a>
					<span class="refresh"></span>
				</div>
				<div class="dogFarm">
					<ul class="cleanDogUl">
						{{#each landinfo}}
						<li class="type{{type}}" dog-flag="{{dog}}">
							{{#if dog == 1}}
								<img class="img img1" src="../../res/image/farm/dog/0.png" />  
						    {{#end}}
						    {{#if dog == 2}}
								<img class="img img1" src="../../res/image/farm/dog/0.png" />
								<img class="img img2" src="../../res/image/farm/dog/0.png" />   
						    {{#end}}
						    {{#if dog == 3}}
								<img class="img img1" src="../../res/image/farm/dog/0.png" />
								<img class="img img2" src="../../res/image/farm/dog/0.png" /> 
								<img class="img img3" src="../../res/image/farm/dog/0.png" />   
						    {{#end}}
							<div class="dogText">
								<div class="totalNum">小狗总数:<em class="totalNumA"></em></div>
								<div class="todayNum">当天狗仔:<em class="todayNumA"></em></div>
								<div class="oldNum">历史狗仔:<em class="oldNumA"></em></div>
							</div>
							{{#if dog==0}}
							    <div class="kaigouChang" data-flag="{{dog}}"></div>
							{{#end}}
							{{#if dog!=0}}
							    <div class="zengyangOpacity"></div>
							{{#end}}
							{{#if dog!=0}}
							    <div class="weiyangOpacity"></div>
							{{#end}}
							{{#if dog!=0}}
							    <div class="shouhuo"></div>
							{{#end}}
							<div class="paopaoDog paopao{{pop}}"></div>
							<div  class="langan{{@root.level}}" id="langan"></div>
							<!--<div class="click"></div>-->
							<div class="jiahao"></div>
						</li>
						{{#end}}
	
					</ul>
				</div>
			</div>
			<div id="oneKeyCleans" class="cleanDog1"></div>
       </div>
        
		
	
		<!--提示框-->
		<div id="infoBox">
			<span class="close8"></span>
			<div class="infoText">提示信息</div>
		</div>
		<!--遮罩-->
        <div id="screen"></div>
        <script src="../../res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../../uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
        <script src="../../res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
        <script src="../../res/js/hui_landhome.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
        	
        </script>
	</body>
</html>