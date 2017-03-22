<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>开发新狗场</title>
	<meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
	<meta name="browsermode" content="application">		<!-- UC应用模式 -->
	<meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
	<meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
	<meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
	<meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<link rel="stylesheet" type="text/css" href="/res/css/css1.css?" />
	<link rel="stylesheet" type="text/css" href="/res/css/info.css" />
	<script src="/res/js/jquery-1.11.2.min.js" charset="utf-8"></script>
	<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/highcharts.js" charset="utf-8"></script>
	<script src="/res/js/hui_register.js" charset="utf-8"></script>
	<style type="text/css">
		/*----确定提示框------*/
			#okInfo3 {
			    position: absolute;
			    width: 100%;
			    height: 58vh!important;
			    height: 435px;
			    background: url(/res/image/farm/okInfo.png) no-repeat 0 0;
			    background-size: 100% 100%;
			    top: 10%;
			    z-index: 10000000;
			    text-align: center;
			    color: #815f0f;
			    font-size: 18px;
			    font-weight: bold;
			    display: none;
			}
			
			#okInfo3 .okInfoClose3 {
			    width: 31px;
			    height: 31px;
			    background: url(/res/image/close1.png) no-repeat 0 0;
			    background-size: 100% 100%;
			    position: absolute;
			    top: 24%;
			    right: 9%;
			}
			
			#okInfo3 .infoxiaoxi3 {
			    width: 60%;
			    margin: 28vh auto  !important;
			    margin: 200px auto;
			}
			
			#okInfo3 .okInfoB3 {
			    width: 62%;
			    height: 6vh!important;
			    height: 40px;
			    background: url(/res/image/farm/queding.png) no-repeat 0 0;
			    background-size: 100% 100%;
			    margin: -18vh auto  !important;
			    margin: 304px auto;
			}
			/*----确定提示框------*/
	</style>
</head>
<body>
<div id="page">
	<div id="top">
		<span><a href="/user/user/home">返回</a></span>
		<label><img src="{{headimg}}"></label>
	</div>
	<div id="reg_div" class="box-sizing">
		<div class="reg box-sizing">
			<ul class="reg_button">
				<li class="on"><a href="/user/user/register">开发新狗场</a></li>
				<li><a href="/user/user/sale">转赠小狗</a></li>
				<li><a href="/user/user/salelist">转赠记录</a></li>
			</ul>
			<div class="reg_content box-sizing">
				<div class="border box-sizing">
					<form class="form">
						<input type="text" class="giveuser" value="{{account}}" {{#if edit == 0}}readonly{{#end}}>
						<input type="text" class="myuser" placeholder="注册手机号">
						<input type="text" class="name" placeholder="姓名">
						<p>性别 <input type="radio" name="sport" value="0" checked/>男<input type="radio" value="1" name="sport" />女</p>
						<input type="text" class="phone" placeholder="确认手机号">
						<p>所需小狗数量330</p>
						<span class="botton subBtn">确认</span>
					</form>
				</div>
				<div class="t_bg"><img src="/res/images/t_bg.png"></div>
			</div>
		</div>
	</div>
	<!--提示框-->
	<div id="infoBox">
		<span class="close8"></span>
		<div class="infoText">提示信息</div>
	</div>
	<!--提示框-->
	<!--确定提示框-->
    <div id="okInfo3" >
        <span class="okInfoClose3"></span>
        <p class="infoxiaoxi3">1111111111</p>
        <div class="okInfoB3"></div>
    </div>
    <!--确定提示框-->
    <!--遮罩-->
    <div id="screen"></div>
</div>
</body>
</html>