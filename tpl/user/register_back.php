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
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=2"/>
		<style type="text/css">
			
			html {  
			  height:100%;  
			}  
			body {  
			  background:url(/res/image/index/indexbg.png) repeat-y center center;
			  background-size: 100% 100%;  
			 /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
			}
			input::-webkit-input-placeholder {
				font-size: 12px;
				     }
			input[type='text']{font-size:12px;line-height:30px;}
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
			<div id="neWDogField">
				<form id="neWDogFieldForm">
					{{#if edit == 0}}
						<input type="text" readonly="readonly" name="giveUser" class="giveuser nDFFormInput" placeholder="推荐人手机号"  value="{{account}}" />
				    {{#end}}
				    {{#if edit == 1}}
						<input type="text" name="giveUser" class="giveuser nDFFormInput" placeholder="推荐人手机号"  value="{{account}}" />
				    {{#end}}
					
					<input type="text" name="myUser" class="myuser nDFFormInput" placeholder="注册手机号"  />
					<input type="text" name="name" class="name nDFFormInput" placeholder="姓名"  />
					<div class="sexBox">
						性别&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" id="nba" checked="checked" name="sport" value="0">
						<label name="nba" class="checked" for="nba">男</label>
						<input type="radio" id="cba" name="sport" value="1">
						<label name="cba" for="cba">女</label>
					</div>
					<input type="text" name="phone" class="phone nDFFormInput" placeholder="确认手机号" />
					<p>所需初始小狗330</p>
					<div class="subBtn"></div>
				</form>
			</div>
			<!--提示框-->
			<div id="infoBox">
				<span class="close8"></span>
				<div class="infoText">提示信息</div>
			</div>
		</div>
		<script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_register.js" type="text/javascript" charset="utf-8"></script>
	    <script>
			//返回上一页
			$('.back').on('click',function(){
				window.location.href="/user/user/home";
			})
		</script>
	</body>
</html>
