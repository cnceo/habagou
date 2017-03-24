<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-touch-fullscreen" content="yes"  />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no">
		<title>交易中心</title>
		<link rel="stylesheet" type="text/css" href="../../res/css/hui_main.css?t=20"/>
		<style type="text/css">
			
			html {  
			  height:100%;
			}  
			body {  
			  background:url(../../res/image/index/indexbg.png) no-repeat center bottom;
			  background-size: 100% 100%;  
			 /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
				height:700px;
			}
			input[type='text']{font-size:14px;line-height:44px;}
		</style>
	</head>
	<body>
		<div id="page">
			<div class="shopCenterLink">
				<a href="/user/user/register"></a>
				<a href="/user/user/sale"></a>
				<a href="/user/user/salelist"></a>
			</div>
			<div id="houseTop">
				<a href="/user/user/home" class="back">返回</a>
				<img class="face" src="../../res/image/index/face/1.png" />
			</div>
			<div id="giveDog">
				<form id="giveDogForm">
					<p>狗仔库存数量<span class="num">{{warehouse}}</span></p>
					<p>转赠须要收10%手续费</p>
					<input type="text" name="giveUser"  class="giveuser nDFFormInput" placeholder="转赠数量必须是10的倍数"  />  
				   
					<input type="text" name="myUser" class="myuser nDFFormInput" placeholder="目标手机号"  />
					<input type="text" name="name" class="name nDFFormInput" placeholder="目标姓名"  />
					<div class="switchBox">
						<span id="switch" class="switchOn"></span>
						<span class="infotext">超级转赠</span>
					</div>
					<div class="subBtn"></div>
				</form>
			</div>

			<!--验证码框-->
			<div id="checkCodeBox">
				<span class="checkCodeBoxclose"></span>
				<input type="number" name="checkcode" id="checkcode" class="yZCode" placeholder="输入验证码" />
				<input type="button" id="btn" disabled-id="0" value="获取验证码" class="getCodeBtn getCodeBtn0" />
				<div class="checkBtn"></div>
			</div>


			<!--提示框-->
			<div id="infoBox">
				<span class="close8"></span>
				<div class="infoText">提示信息</div>
			</div>
		</div>
		<script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_sale.js" type="text/javascript" charset="utf-8"></script>
	</body>
</html>
