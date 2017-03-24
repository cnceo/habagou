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
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=18"/>
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
				<img class="face" src="/res/image/index/face/1.png" />
			</div>
			<div id="zhuanRecord">
				<div class="tab">
					<a href="/user/user/buyList" class="leftTab"></a>
					<a href="" class="rightTab"></a>
				</div>
				<ul>
					{{#each datas}}
					<li>
						<span>{{account}}</span>
						<span>{{num}}</span>
						<!--<span>小狗</span>-->
						<span>{{launchtime}}</span>
						<span>
							{{#if status==1}}
							    <a href="/user/user/updataStatus/{{id}}/0/4">取消交易</a>
							{{#end}}
							{{#if status==2}}
							     <a href="/user/user/updataStatus/{{id}}/0/3">确认收米</a>
							{{#end}}
							{{#if status==3}}
							     交易完成
							{{#end}}
							{{#if status==4}}
							     交易取消
							{{#end}}
						</span>
					</li>
					{{#end}}
				</ul>
				<div class="fenye">
					<a href="{{prev_page}}"><span class="fenYeLeft"></span></a>
					<span class="fenshu"><em>{{pageindex}}</em>/{{totalpage}}</span>
					<a href="{{next_page}}"><span class="fenYeRight"></span></a>
				</div>
			</div>
		</div>
	</body>
</html>
