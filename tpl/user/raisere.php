<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-touch-fullscreen" content="yes"  />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no">
		<title>养狗日志</title>
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css"/>
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
		<div id="page" class="page dailyRecord breeding">
			<div class="link">
				<a href="/log/record/growthrecord"></a>
				<a href="/log/record/cleanrecord"></a>
				<a href="/log/record/feedrecord"></a>
				<a href="/log/record/raiserecordcord"></a>
			</div>
			<div id="TopBox">
				<span class="back"><a href="/user/user/home" style="color: #FFFFFF;">返回</a></span>
				<img class="face" src="/res/image/index/face/1.png" />
			</div>
			<ul>
				{{#each datas}}
				<li>
					<span>{{num}}</span>
					<span>{{landindex}}</span>
					<span>{{type}}</span>
					<span>{{hatchtime}}</span>
				</li>
				{{#end}}

			</ul>
			<div class="fenye">
				<a href="{{prev_page}}"><span class="fenYeLeft"></span></a>
				<span class="fenshu"><em>{{pageindex}}</em>/{{totalpage}}</span>
				<a href="{{next_page}}"><span class="fenYeRight"></span></a>
			</div>
			<ol>
				<li>
					<span>1.68</span>
					<div>清洗收益</div>
				</li>
				<li>
					<span>3116.51</span>
					<div>总生产小狗</div>
				</li>
				<li>
					<span>0.88</span>
					<div>喂食收益</div>
				</li>
			</ol>
			
		</div>
	</body>
</html>