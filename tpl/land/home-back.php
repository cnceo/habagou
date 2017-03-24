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
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=2"/>
		<style type="text/css">
			html {  
			  height:650px;  
			}  
			body {  
			  background:url(/res/image/farm/farmBg1.png) no-repeat center center;
			  background-size: 100% 100%;  
			 /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
			}
			/*------移动狗------*/
			.left2right{
				width: 25px;
				height: auto;
				position: absolute;
				left: 5%;
				bottom: 30%;
				z-index: 2;
			}

			.right2left{
				width: 30px;
				height: auto;
				position: absolute;
				left: 80%;
				bottom: 20%;
				z-index: 2;
			}
			.left2right1{
				width: 35px;
				height: auto;
				position: absolute;
				left: 18%;
				bottom: 10%;
				z-index: 2;
			}
			/*------移动狗------*/
			/*阻止层*/
			#contain .dogFarm ul li .zuZhiCeng {
				width: 100%;
				height: 100%;
				position: absolute;
				left: 0;
				top: 0;
				background: black;
				opacity: 0;
				z-index: 18;
			}
			/*邀请好友*/
			#visitFriend{
				width: 100%;
				height: 28%;
				background: url(../../res/image/farm/yaoqingfriend.png) no-repeat center center;
				background-size: 100% 100%;
				position: absolute;
				top: 10%;
				z-index: 40;
				display: none;
			}
		</style>
	</head>
	<body style="overflow: hidden;">
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
			<div id="contain" style="margin-bottom:80px;">
				<div class="account">
					<img src="../../res/image/index/face/1.png" class="face"></img>
					<span class="name">{{account}}</span>
					<span class="numA">{{bone}}</span>
					<span class="numB">{{total}}</span>
					<span class="myFrieng"></span>
					<span class="myWareHouse"></span>
				</div>
 				<div class="backBox">
					<a class="back"></a>
					<span class="refresh"></span>
				</div>
				<div class="dogFarm">
					<ul>
						{{#each landinfo}}
						<li class="type{{type}}" dog-flag="{{dog}}">
							{{#if dog == 1}}
							<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />
							{{#end}}
							{{#if dog == 2}}
							<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />
							<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />
							{{#end}}
							{{#if dog == 3}}
							<img id="moveimg" class="left2right" src="../../res/image/farm/dog/00.gif" />
							<img id="moveimg" class="right2left" src="../../res/image/farm/dog/01.gif" />
							<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/dog/00.gif" />
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
							    <div class="zengyangOpacity" zengyang-flag="{{dog}}"></div>
							{{#end}}
							{{#if dog!=0}}
							    <div class="weiyangOpacity"></div>
							{{#end}}
							{{#if dog!=0}}
							    <div class="shouhuo"></div>
							{{#end}}
							<div class="paopaoDog paopao{{1}}"></div>

                            <div class="zuZhiCeng"></div>
							<div  class="langan{{@root.level}}" id="langan"></div>
							<div class="click"></div>
							<div class="jiahao"></div>
						</li>
						{{#end}}
					</ul>
				</div>
			</div>
			<div id="footNav">
				<span class="openFarm">开狗场</span>
				<span class="add">增养</span>
				<span class="feed">喂食</span>
				<span class="harvest">收获</span>
			</div>
			<!--开狗场-->
			<div id="openDogfloor">
				<span class="close9"></span>
				<span class="text">仓库小狗数量不够</span>
			</div>
			<!--收获-->
			<div id="harvest">
				<span class="close10"></span>
				<p class="text">恭喜收获<span class="harvestNum"></span>条小狗</p>
			</div>
			<!--一键清洗-->
			<div id="cleaning">
				<span class="close11"></span>
				<div class="queding"></div>
			</div>
			
			<!--输入喂养数-->
			<div id="feed" class="weiyang" style="display: none;">
				<span class="close12"></span>
				<span class="text">输入喂食数量</span>
				<input type="number" name="numner" class="numberFeed" />
				<div class="pan"></div>
				<div id="weiyangBtn" class="queding"></div>
			</div>
			<!--输入增养数-->
			<div id="feed" class="zengyang" style="display: none;">
				<span class="close12"></span>
				<span class="text">输入增养数量</span>
				<input type="number" name="numner" class="numberAdd" />
				<div class="pan"></div>
				<div id="addyang" class="queding"></div>
			</div>
			
		<!--好友列表-->
		<div id="friendList">
			<span class="friendListClose"></span>
			<ul>
				{{#each friend}}

				<li>
					<div class="top">
						<img src="../../res/image/index/face/1.png"/>
						<div class="vipBox">
							<span class="VipName">{{account}}</span>
							<span class="num">{{bone}}</span>
						</div>
						<a href="/user/user/visitFriend/{{id}}"><div class="btn">去拜访</div></a>
					</div>
					<div class="bottom">
						<span class="name">{{name}}</span>
						<span class="phone">{{phone}}</span>
					</div>
				</li>
				{{#end}}

			</ul>
			<div class="bottomBtn">
				<span class="onekeyclean"></span>
				<span class="yaoqing"></span>
			</div>
		</div>
		<!--我的仓库-->
		<div id="wareHouse">
			<span class="wareHouseClose"></span>
			<ul>
				<li>
					<img src="../../res/image/index/face/xiaogou.png"/>
					<div class="vipBox">
						<span class="type">小狗</span>
						<span id="xiaogoushu" class="num">{{warehouse}}</span>
					</div>
					<div id="zengyangBtn" class="btn">去增养</div>
				</li>
				<li>
					<img src="../../res/image/index/face/dogFace.png"/>
					<div class="vipBox">
						<span class="type">狗崽</span>
						<span id="bone" class="num">{{bone}}</span>
					</div>
					<div id="wYangBtn" class="btn">去喂食</div>
				</li>
				<li>
					<img id="langanlevel" src="../../res/image/index/face/langan{{@root.level}}.png"/>
					<div class="vipBox">
						<span class="type" style="margin-left: 5px;">大栅栏</span>
						<p class="text">每邀请10位好友可 获得/升级</p>
					</div>
					<div class="btn">获得</div>
				</li>
				<li>
					<img src="../../res/image/index/face/shaoba.png"/>
					<div class="vipBox">
						<span class="type" style="margin-left: 5px;">一键清洗</span>
						<p class="text">清洗所有好友小狗,期限30日</p>
					</div>
					<div id="buy" class="btn">购买</div>
				</li>
				<li>
					<img src="../../res/image/index/face/siyangyuan.png"/>
					<div class="vipBox">
						<span id="feederlevel" class="type" feederlevel = "{{feederlevel}}" style="margin-left: 5px;">饲养员Lv.<em id="dengji">{{feederlevel}}</em></span>
						<p class="text">等级越高，小狗的等级越高哦</p>
					</div>
					<div id="shengji" class="btn">升级</div>
				</li>
			</ul>
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
		<!--drag-->
        <div id="drag" class="feederLever{{feederlevel}}"></div>
		<div id="visitFriend"></div>
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
		 <!--音效-->
<!--		<audio audio-flag ="{{status}}" class="audio1" id="audio" src="/res/image/farm/gougou.mp3" preload="auto"  style="position: absolute;top: 2px;left: 2px">-->
<!--			Your browser does not support the audio element.-->
<!--		</audio>-->
		<audio audio-flag ="{{status}}" id="audio" src="/res/image/farm/gougou.mp3" preload="auto"  style="position: absolute;top: 2px;left: 2px">
			Your browser does not support the audio element.
		</audio>
			
	</div>
		
        <script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
        <script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
       <!-- <script src="/res/js/hui_drag.js" type="text/javascript" charset="utf-8"></script>-->
        <script src="/res/js/hui_landhome.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">

			// 拖拽
			var block = document.getElementById("drag");

				var oW,oH;
				// 绑定touchstart事件
				block.addEventListener("touchstart", function(e) {
					var    touches  =  e.touches[0];
					oW  =  touches.clientX - block.offsetLeft;
					oH  =  touches.clientY - block.offsetTop;
					//阻止页面的滑动默认事件
					document.addEventListener("touchmove",defaultEvent,false);
				},false)

  			    block.addEventListener("touchmove", function(e) {
					var touches =  e.touches[0];
					var oLeft   =  touches.clientX - oW;
					var oTop    =  touches.clientY - oH;

    				if(oLeft < 0) {
						oLeft = 0;
					}else if(oLeft > document.documentElement.clientWidth - block.offsetWidth) {
						oLeft = document.documentElement.clientWidth - block.offsetWidth;
					}

					if(oTop<0){
						oTop = 0
					}else if(oLeft > document.documentElement.clientHeight - block.offsetHeight){
						oLeft = document.documentElement.clientHeight - block.offsetHeight
					}

					block.style.left = oLeft + "px";
					block.style.top  = oTop  + "px";
				},false);

				block.addEventListener("touchend",function() {
					document.removeEventListener("touchmove",defaultEvent,false);
				},false);
				function defaultEvent(e) {
					e.preventDefault();
				}
			

			//狗场list
			var list = $('.dogFarm ul').find('li');
			list.each(function(i){
				var index = i
				//创建狗移动
				var i = 0;

				function move_left2right(){
					var left2right = $(list[index]).find('.left2right')
					left2right.attr('src','../../res/image/farm/00.gif')

					left2right.stop().animate({'left':'60%'},6000,function(){
						left2right.attr('src','../../res/image/farm/01.gif')
						left2right.stop().animate({'left':'15%'},6000,function(){
							move_left2right()
						})
					})

				}
				move_left2right()

				function move_right2left(){
					var right2left = $(list[index]).find('.right2left')
					right2left.attr('src','../../res/image/farm/01.gif')

					right2left.stop().animate({'left':'15%'},6000,function(){
						right2left.attr('src','../../res/image/farm/00.gif')
						right2left.stop().animate({'left':'60%'},6000,function(){
							move_right2left()
						})
					})

				}
				move_right2left()

			})
        </script>
	</body>
</html>