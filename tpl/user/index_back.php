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
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=1"/>
		<style type="text/css">
			html {  
			  height:100%;  
			}  
			body {  
			  background:url(../../res/image/index/indexbg.png) no-repeat center center;
			  background-size: 100% 100%;  
			  /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
			} 
			#gameRulePanel ul li img{
				position: absolute;
				max-width: 28px;
                top: 42%;
                left: 2%;   
			}
		</style>
	</head>
	<body>
		<div id="page">
			<div id="gonggao">
				<span class="laba"></span>
				<div class="wanzhengText">点击查看完整公告</div>
				<div class="warp">
					{{#each notice}}
					{{#if @first}}
					<div class="movetext">{{title}}</div>
					{{#end}}
					{{#end}}
				</div>
			</div>
			<div id="zhanghu">
				<img id="faces" src="{{image}}" />
				<span class="vipUser">{{account}}</span>
				<span class="dogNum1">{{bone}}</span>
			    <span class="dogNum2">{{total}}</span>
				
				<div class="dogEmail"></div>
				<div class="shezhi"></div>
			</div>
			<div id="tuBiao">
				<div id="container" style=" min-width:400px;height:400px;margin-left:-33px;"></div>
				<a href="/land/land/home" class="intoBtn"></a>
			</div>
			<div id="footer">
				<ul>
					<li class="shopCenter"><a href="/user/user/register"></a></li>
					<li class="dogShop"></li>
					<li class="dogDaily"><a href="/log/record/growthrecord"></a></li>
					<li class="gameRule"></li>
				</ul>
			</div>
			<!--头像选择-->
			<div id="faceBox">
				<span class="close4"></span>
				<ul class="faceUl">
					{{#each images}}
					<li data_id="{{id}}">
						<img src="{{image}}" />
						<span class="duigou"></span>
					</li>
					{{#end}}
				</ul>
				<div class="ok"></div>
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
			<!--更改密码-->
			<div id="changePassBox">
				<span class="close2"></span>
				<form id="changePassForm">
					<input type="password" name="oldPassword" class="oldPassword changeInput" placeholder="旧密码" />
					<input type="password" name="oldPassword" class="newPassword changeInput" placeholder="新密码" />
					<input type="password" name="oldPassword" class="PasswordAgain changeInput" placeholder="确认密码" />
					<div class="okBtn"></div>
				</form>
			</div>

			<!--游戏设置-->
			<div id="gameSet">
				<span class="close8"></span>
				<div id="music2" class="{{status}}" style="margin-top: 40.5%;"></div>
				<a href="/user/user/loginOut"><div class="exit" style="margin: 8% auto;"></div></a>
			</div>

			<!--收件箱-->
			<div id="inBox">
				<span class="close3"></span>
				<ul>

					{{#each message}}
						<li>
							<div class="textBox">
								<span class="getDogNum">{{title}}</span>
								<span class="date">{{sendtime}}</span>
							</div>
						</li>
					{{#end}}


				</ul>
			</div>
			<!--游戏规则说明-->
			<div id="gameRulePanel">
				<span class="close5"></span>
				<ul>
					<li style="position: relative;"><em>1</em>
					    <img src="/res/image/index/icon1.png">
						<span>    关于开狗场：注册成功系统自动开普通狗场（普通狗场300），后期开金色狗场：点击开狗场按钮，点击开发的狗场地块，只要仓库小狗数量够就开狗场成功，否则失败。</span></li>
					<li style="position: relative;">
					<img src="/res/image/index/icon2.png">
					<em>2</em><span>    关于增养：增养就是把我的仓库里面的小狗增养到任意一块狗场。增养流程：点击增养按钮，出现一闪一闪的阴影狗场，点击然后弹出对话框以后，输入数量，系统默认增养该狗场最大值，点击「确定」，该狗场增养完成。</span></li>
					<li style="position: relative;">
					<img src="/res/image/index/icon3.png">
					<em>3</em><span>    关于喂食：每天登入界面收集骨头，点击喂食按钮，出现一闪一闪阴影狗场，选择任意开放的狗场，弹出对话框，输入数量，系统默认喂养最大值，点击「确定」，喂养完成。</span></li>
					<li style="position: relative;">
                     <img src="/res/image/index/icon4.png">
					<em>4</em><span>    关于收获：点击收获按钮，出现一闪一闪阴影狗场，点击任意狗场，该狗场除了最低限度外其他小狗都会收回到您的仓库，收获完成。</span></li>
					<li style="position: relative;">
					<img src="/res/image/index/icon5.png">
					<em>5</em><span>    关于转赠：转赠分为超级转赠和普通转赠，超级转赠一步到位，超级转赠按钮打开以后，小狗转赠立即到对方仓库，对方进去增养即可（适用于熟人之间转赠）普通转赠（关闭超级按钮即是普通转赠），普通转赠流程：输入转赠数量，对方电话，姓名，收到验证码以后，点击确定。普通转赠适用于陌生会员之间转赠。</span></li>
					<li style="position: relative;"><em>6</em>
					<img src="/res/image/index/icon6.png">
					<span>    关于清洗：进入我的好友，选择任意好友，点击进入，会有帮助小狗清洗的动画，如果好友喂食了，为好友清洗获得相应狗崽回到仓库，返回页面增养步骤即可。</span></li>
					<li style="position: relative;">
					<img src="/res/image/index/icon7.png">
					<em>7</em><span>    关于饲养员：饲养员设置9个等级，通过用小狗购买饲养员可以提升每日狗崽增长数量，饲养员有年轻饲养员，中年饲养员，老年饲养员，每种饲养员三个形象，衣服上对应星星，月亮，太阳三个形象对应级别。</span></li>
					<li style="position: relative;">
					<img src="/res/image/index/icon8.png">
					<em>8</em><span>    关于骨头泡泡：系统每次拆分完后，会根据每个狗场的狗崽数量产生相应骨头，当天狗场上方出现骨头泡泡。每天您只需点击泡泡让骨头进入仓库，喂食小狗。否则当天产生的骨头没有喂食的话不参与第二天的拆分，第二天骨头会被清零，所以大家一定记得喂食狗崽哦。</span></li>

				</ul>
			</div>
			<!--小狗购物-->
			<div id="smallDogShop" class="closeBox">
				<span class="close6"></span>
				<div class="smallDogBtn"></div>
			</div>
			<!--提示框-->
			<div id="infoBox">
				<span class="close8"></span>
				<div class="infoText">提示信息</div>
			</div>
			<!--遮罩-->
	        <div id="screen"></div>
		</div>
		<script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/highcharts.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_main.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_updatePass.js" type="text/javascript" charset="utf-8"></script>
		<script src="/res/js/hui_index.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function () {
		       $('#container').highcharts({
		        chart: {
		            type: 'line',
		            margin: [110, 45, 60, 95],
		            backgroundColor: 'rgba(0,0,0,0)'
		        },
		
		        title: {
		            text: null,
		            x:-100,
		            y:100
		        },
		
		        subtitle: {
		            text: null,
		            y:100
		        },
		
		        xAxis: {
		        	type: 'datetime',
		            tickInterval: 24 * 36e5, // one week
		            // lineColor : '#000', 
		            labels: {
		                format: '{value: %m-%d}',
		                align: 'right',
		                rotation: -30
		            }, 
		            
		        },
		
		        yAxis: {
		
		            title: {
		                text: null
		            }
		        },
		
		        legend: {
		        	floating:true,
		        	layout: 'vertical',
		        	x:-70,
		        	y:-270
		        },
		
		        plotOptions: {
		            line: {
		                dataLabels: {
		                    enabled: true
		                },
		                enableMouseTracking: true
		            }
		        },
		
		        credits: {
		            enabled:false
		        },
		
		        tooltip: {
		        	enabled: false
		        },
		
		        series: [{
		            name: '最新利率:',
		            data: [2.0, 2.12, 1.86, 1.9, 1.95, 2.01, 4.2],
		            pointInterval: 24 * 36e5,
		            pointStart: Date.UTC(2017, 1, 26)
		        }, {
		            name: '公共利率:',
		            data: [2.5, 2.12, 1.86, 1.8, 1.95, 2.01, 1.88],
		            pointInterval: 24 * 36e5,
		            pointStart: Date.UTC(2017, 1, 26)
		        }],
		        });
		        
		    });	
		</script>
	</body>
</html>