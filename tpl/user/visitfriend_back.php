<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes"  />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no">
	<title>一起来养狗</title>
	<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=889ffffffdd"/>
	<style type="text/css">
		html {
			height:100%;
		}
		body {
			background:url(/res/image/farm/farmBg1.png) repeat-y center center;
			background-size: 100% 100%;
			/* min-heigth:min-height:100%;  height:100%; */
			overflow: hidden;
		}
	</style>
</head>
<body style="height: 100%;overflow: hidden;">
<div id="page">
	<div id="gonggao" style="background: transparent;">
		<span class="laba"></span>
		<div class="wanzhengText">点击查看完整公告</div>
		<div class="warp" style="left: 20px;">
			{{#each notice}}
			{{#if @first}}
			<div class="movetext">{{title}}</div>
			{{#end}}
			{{#end}}
		</div>
	</div>
	<div id="contain">
		<div class="account">
			<img src="/res/image/index/face/1.png" class="face"></img>
			<span class="name">{{account}}</span>
			<span class="numA">{{bone}}</span>
			<span class="numB">{{total}}</span>
			<!--<span class="myFrieng"></span>
			<span class="myWareHouse"></span>-->
		</div>
		<div class="backBox">
			<a href="/land/land/home" class="back"></a>
			<span class="refresh"></span>
		</div>
		<div class="dogFarm">
			<ul>
				{{#each landinfo}}
				<li class="type{{type}}">
					{{#if dog == 1}}
					<img class="img img1" src="/res/image/farm/dog/0.png" />
					{{#end}}
					{{#if dog == 2}}
					<img class="img img1" src="/res/image/farm/dog/0.png" />
					<img class="img img2" src="/res/image/farm/dog/0.png" />
					{{#end}}
					{{#if dog == 3}}
					<img class="img img1" src="/res/image/farm/dog/0.png" />
					<img class="img img2" src="/res/image/farm/dog/0.png" />
					<img class="img img3" src="/res/image/farm/dog/0.png" />
					{{#end}}
					
					<div  class="langan{{@root.level}}" id="langan"></div>
				</li>
				{{#end}}
			</ul>
		</div>
	</div>
	<div id="oneKeyCleans" class="cleanDog{{clean}}" clean_id = "{{clean}}" toAccid = "{{toAccid}}"></div>
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
</div>
<img id="cleanGif" src="/res/image/farm/clean.gif"/>


<!--提示框-->
<div id="infoBox">
	<span class="close8"></span>
	<div class="infoText">提示信息</div>
</div>
<!--遮罩-->
<div id="screen"></div>
<script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
<script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
<script src="/res/js/hui_landhome.js" type="text/javascript" charset="utf-8"></script>
<script src="/res/js/hui_visitfriend.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

	 $(function(){
     	
     	//狗场list
		var list = $('.dogFarm ul').find('li');
		list.each(function(i){
			var index = i
			//创建狗移动
			var i = 0;
			//var images =  $(list[index]).find('.img');
			var timer1 = null;
			var timer2 = null;
			var timer3 = null;
	
			var ll = parseInt(Math.random()*20+2);
			var rr = parseInt(Math.random()*20+40);
	
			var img = $(list[index]).find('.img');
			move1(img)
			//运动
			function move1(obj){
	
				clearInterval(timer2)
				timer1 = setInterval(function(){
					i++
					//让i在 0到6之间变化
					i = i % 6;
					//名字变成字符串
					var name = i + "." + "png";
					//加上目录
					$(list[index]).find(obj).attr('src',"/res/image/farm/dog/" + name);
	
				}, 150);
				//var ss = parseInt(Math.random()*1500+3000);
				$(list[index]).find(obj).stop().animate({'left':2},4500,function(){
					clearInterval(timer1)
					timer2 = setInterval(function(){
						i++
						//让i在 0到6之间变化
						i = i % 6;
						//名字变成字符串
						var name = "0"+i + "." + "png";
						//加上目录
						$(list[index]).find(obj).attr('src',"/res/image/farm/dog/" + name);
					}, 150);
	
					$(list[index]).find(obj).stop().animate({'left':60},3500,function(){
						clearInterval(timer1)
						move1(obj)
					})
				});
			}
		})
	    
	  
    })
</script>
</body>
</html>