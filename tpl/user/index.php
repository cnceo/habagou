<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>哈巴狗</title>
	<meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
	<meta name="browsermode" content="application">		<!-- UC应用模式 -->
	<meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
	<meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
	<meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
	<meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->

	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<link rel="stylesheet" type="text/css" href="/res/css/css1.css?" />
	<style>
		/*-----提示面板-------*/
		@media (min-width:600px) {
			#infoBox{width:600px;margin:0px auto;width:calc(100vh*9/16)!important;height:calc(100vh*375/736);}
		}
		#infoBox {
			position: absolute;
			width: 100%;
			height: 375px;
			background: url(/res/image/infoBg.png) no-repeat center center;
			background-size: 100% 100%;
			position: absolute;
			z-index: 100000001;
			top: 12%;
			left: 0;
			text-align: center;
			display: none;
		}

		#infoBox .close8 {
			width: 31px;
			height: 31px;
			background: url(/res/image/close1.png) no-repeat center center;
			background-size: 100% 100%;
			position: absolute;
			top: 23%;
			right: 9%;
		}

		#infoBox .infoText {
			width: 100%;
			color: #815f0f;
			font-size: 18px;
			font-weight: bold;
			margin-top: 190px;
		}
		/*-----提示面板-------*/
	</style>
	<script src="/res/js/jquery-1.11.2.min.js" charset="utf-8"></script>
	<script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
	<script src="/res/js/highcharts.js" charset="utf-8"></script>
	<script src="/res/js/index.js" charset="utf-8"></script>
    <script type="text/javascript">
    	//$(".tjt_div").css("height", $("#page").height() - $(".bottom").height() - $(".new_content").height() - 120);
    </script>
</head>
<body>
<div id="page">
	<div id="gonggao"><span><img src="/res/images/laba.png">点击查看完整公告</span><div>
	<marquee scrollAmount=5>
	{{#each notice}}
		{{#if @first}}
			{{title}}
		{{#end}}
	{{#end}}
	</marquee></div></div>
	<div class="new_content">
		<div class="new_head">
			<span class="pesron"><img src="{{image}}"></span>
			<ul>
				<li class="li1"><img src="/res/images/xz.png"><span>{{account}}</span><img src="/res/images/xz.png"></li>
				<li class="li2"><span><img src="/res/images/g1.png">{{bone}}</span><span class="span2"><img src="/res/images/g2.png">{{total}}</span></li>
			</ul>
			<div>
				<label id="music_set"><img src="/res/images/shezhi.png"></label>
				<label id="email_set"><img src="/res/images/dogEmail.png"></label>
			</div>
		</div>
	</div>
	<div class="tjt_div">
		<img src="/res/images/topTitle.png" class="img1">
		<img src="/res/images/ding1.png" class="img2">
		<img src="/res/images/ding2.png" class="img3">
		<div class="tjt">
			<div id="tjt">
				<div id="tjt_pic"></div>
			</div>
			<a href="/land/land/home"><span class="go_div">进入狗崽乐园</span></a>
		</div>
	</div>
	<div class="bottom">
		<a href="/user/user/register"><span alt="1"><img src="/res/images/shopCenter.png"></span></a>
		<span id="home"><img src="/res/images/dogShop.png"></span>
		<a href="/log/record/growthrecord"><span alt="3"><img src="/res/images/dogDaily.png"></span></a>
		<span id="rule"><img src="/res/images/gameRule.png"></span>
	</div>

	<div id="screen"></div>

	<div id="game_RulePanel" class="rule_div">
		<div style="position:relative;">
			<span class="close5"></span>
			<ul>
				<li style="position: relative;">
					<em>1</em>
					<img src="/res/images/icon1.png">
					<span>    关于开狗场：注册成功系统自动开普通狗场（普通狗场300），后期开金色狗场：点击开狗场按钮，点击开发的狗场地块，只要仓库小狗数量够就开狗场成功，否则失败。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/icon2.png">
					<em>2</em><span>    关于增养：增养就是把我的仓库里面的小狗增养到任意一块狗场。增养流程：点击增养按钮，出现一闪一闪的阴影狗场，点击然后弹出对话框以后，输入数量，系统默认增养该狗场最大值，点击「确定」，该狗场增养完成。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/m3.png">
					<em>3</em><span>    关于喂食：每天登入界面收集骨头，点击喂食按钮，出现一闪一闪阴影狗场，选择任意开放的狗场，弹出对话框，输入数量，系统默认喂养最大值，点击「确定」，喂养完成。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/icon4.png">
					<em>4</em><span>    关于收获：点击收获按钮，出现一闪一闪阴影狗场，点击任意狗场，该狗场除了最低限度外其他小狗都会收回到您的仓库，收获完成。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/icon5.png">
					<em>5</em><span>    关于转赠：转赠分为超级转赠和普通转赠，超级转赠一步到位，超级转赠按钮打开以后，小狗转赠立即到对方仓库，对方进去增养即可（适用于熟人之间转赠）普通转赠（关闭超级按钮即是普通转赠），普通转赠流程：输入转赠数量，对方电话，姓名，收到验证码以后，点击确定。普通转赠适用于陌生会员之间转赠。</span>
				</li>
				<li style="position: relative;">
					<em>6</em>
					<img src="/res/images/icon6.png">
					<span>    关于清洗：进入我的好友，选择任意好友，点击进入，会有帮助小狗清洗的动画，如果好友喂食了，为好友清洗获得相应狗崽回到仓库，返回页面增养步骤即可。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/icon7.png">
					<em>7</em><span>    关于饲养员：饲养员设置9个等级，通过用小狗购买饲养员可以提升每日狗崽增长数量，饲养员有年轻饲养员，中年饲养员，老年饲养员，每种饲养员三个形象，衣服上对应星星，月亮，太阳三个形象对应级别。</span>
				</li>
				<li style="position: relative;">
					<img src="/res/images/icon8.png">
					<em>8</em><span>    关于骨头泡泡：系统每次拆分完后，会根据每个狗场的狗崽数量产生相应骨头，当天狗场上方出现骨头泡泡。每天您只需点击泡泡让骨头进入仓库，喂食小狗。否则当天产生的骨头没有喂食的话不参与第二天的拆分，第二天骨头会被清零，所以大家一定记得喂食狗崽哦。</span>
				</li>
			</ul>
		</div>
	</div>

	<div id="gong_gao" class="rule_div">
		<div style="position:relative;">
			<span class="close5"></span>
			<div class="gonggao_div">
				{{#each notice}}
				<dl>
					<dd><span><img src="/res/images/textIcon.png">{{title}}</span><label>{{sendtime}}</label></dd>
					<dt>{{content}}</dt>
				</dl>
				{{#end}}

			</div>
		</div>
	</div>

	<div id="in_email" class="rule_div">
		<div style="position:relative;">
			<span class="close5"></span>
			<ul>
				{{#each message}}
				<li>
					<img src="/res/images/icon4.png">
					<span class="getDogNum">{{title}}</span>
					<span class="date">{{sendtime}}</span>
				</li>
				{{#end}}
			</ul>
		</div>
	</div>

	<div id="in_head">
		<div style="position:relative;">
			<span class="close5"></span>
			<ul class="faceUl">
				{{#each images}}
				<li data_id="{{id}}">
					<img src="{{image}}">
					<span class="duigou"></span>
				</li>
				{{#end}}
			</ul>
			<span class="go_chage">确认选择</span>
		</div>
	</div>

	<div id="game_music">
		<div style="position:relative;">
			<span class="close5"></span>
			<div class="m_div1">
				<span>音效</span>
				<div class="slideThree">
					<input type="checkbox" value="{{status}}" id="slideThree"  name="check"
						   checked="checked"/>
					<label for="slideThree"></label>
				</div>
			</div>
			<a href="/user/user/loginout"><span class="loginout">注销登录</span></a>
		</div>
	</div>

	<div id="dog_home">
		<div style="position:relative;">
			<span class="close5"></span>
			<div style="overflow:hidden;">
				<div class="home_intro box">
					<img src="/res/images/laba.png">敬请期待
				</div>
			</div>
		</div>
	</div>

	<div id="edit_password">
		<div style="position:relative;">
			<span class="close5"></span>
			<div style="overflow:hidden;">
				<div class="box">
					<form>
						<input type="password" class="oldPassword"  placeholder="旧密码">
						<input type="password" class="newPassword" placeholder="新密码">
						<input type="password" class="PasswordAgain" placeholder="确认密码">
						<span class="botton okBtn">确认修改</span>
					</form>
				</div>

			</div>
		</div>
	</div>
	<div id="infoBox">
		<span class="close8"></span>
		<div class="infoText">提示信息</div>
	</div>
</div>



<script type="text/javascript">
    
	$(function () {

		//关闭提示信息
		$('.close8').on('click',function(){
			$('#infoBox').hide();
		})
		//最新利率
		var arr1 = [1.0, 2.12, 1.86, 1.9, 1.95, 2.01, 5.2];
		//公共利率
		var arr2 = [2.5, 2.12, 1.86, 1.8, 1.95, 2.01, 1.88];

		$('#tjt_pic').highcharts({
			chart: {
				type: 'spline',
				margin: [0, 0,170, 0],
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
				y:-300
			},

			plotOptions: {
				spline: {
					dataLabels: {
						enabled: true //开启数据标签
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
				data: arr1,
				pointInterval: 24 * 36e5,
				pointStart: Date.UTC(2017,1, 30)
			}, {
				name: '公共利率:',
				data: arr2,
				pointInterval: 24 * 36e5,
				pointStart: Date.UTC(2017, 1, 30)
			}],
		});




		//密码验证
		function checkpass(){
			if( $('.newPassword').val().length > 5 && $('.newPassword').val().length < 13 ){
				return true
			}
		}

		var okBtn = $('.okBtn');
		okBtn.on('click',function(){
			var oldpwd = $('.oldPassword').val();
			var newpwd = $('.newPassword').val();
			var confirmpwd = $('.PasswordAgain').val();
			//var super = $('input[type="radio"]:checked').attr('value')

			if( oldpwd == "" ){
				$('#infoBox').show();
				$('#infoBox').find('.infoText').text('请输入旧密码');
			}else if( newpwd == "" ){
				$('#infoBox').show();
				$('#infoBox').find('.infoText').text('请输入新密码');
			}else if( confirmpwd == "" ){
				$('#infoBox').show();
				$('#infoBox').find('.infoText').text('请再次输入密码');
			}else{
				if( !checkpass() ){
					$('#infoBox').show();
					$('#infoBox').find('.infoText').text('密码必须是5到12位');
				}else{
					if( newpwd != confirmpwd ){
						$('#infoBox').show();
						$('#infoBox').find('.infoText').text('确认密码不一致');
					}else{
						bjui_config.error_report=function(userexception, src, param, msg, fnFail){
							$('#infoBox').show();
							$('#infoBox').find('.infoText').text(msg);
						}
						//alert(oldpwd+'==='+newpwd);
						BizCall(
								"user.User.updateLoginpwd",
								{
									"oldpwd"	:  oldpwd,
									"pwd"   :  newpwd
								},
								function(data){
									$('#infoBox').show();
									$('#infoBox').find('.infoText').text('修改密码成功');
									
//									setTimeout(function(){
//										$('#infoBox').hide();
//										$("#edit_password").hide();
//                                      $("#screen").hide();
//									},1000)
									
								});
					}
				}

			}
		})

	});
</script>
</body>
</html>