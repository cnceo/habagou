<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>转赠小狗</title>
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
	<script src="/res/js/highcharts.js" charset="utf-8"></script>
	<!--<script src="/res/js/hui_sale.js" charset="utf-8"></script>-->
	<script src="/res/js/hui_sale_change.js" charset="utf-8"></script>
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
				<li><a href="/user/user/register">开发新狗场</a></li>
				<li class="on"><a href="/user/user/sale">转赠小狗</a></li>
				<li><a href="/user/user/salelist">转赠记录</a></li>
			</ul>
			<div class="reg_content box-sizing">
				<div class="border box-sizing ">
					<form class="form">
						<div class="f_label">狗仔库存数量<label>{{warehouse}}</label><br>
							转赠须要收10%手续费</div>
						<input type="text" class="giveuser" value="" placeholder="转赠数量必须是10的倍数">
						<input type="text" class="myuser" placeholder="目标手机号">
						<input type="text" class="name" placeholder="目标姓名">
						<div class="on_off">
							<div class="slideThree">
								<input type="checkbox" value="1" id="slideThree" name="check" />
								<label for="slideThree"></label>
							</div>超级转增
						</div>
						<span class="botton subBtn">确认</span>
					</form>
				</div>
				<div class="t_bg"><img src="/res/images/t_bg.png"></div>
			</div>
		</div>
	</div>
	<!-------验证码框------->
	<div id="checkCodeBox">
		<span class="checkCodeBoxclose"></span>
		<input type="text" name="checkcode" id="checkcode" class="yZCode" placeholder="输入验证码" />
		<input type="button" id="btn" disabled-id="0" value="获取验证码" class="getCodeBtn getCodeBtn0" />
		<div class="checkBtn"></div>
	</div>
	<!-------验证码框------->

	<!--提示框-->
	<div id="infoBox">
		<span class="close8"></span>
		<div class="infoText">提示信息</div>
	</div>
	<!--提示框-->

</div>
</body>
</html>