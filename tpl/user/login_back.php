<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-touch-fullscreen" content="yes"  />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no">
		<title>登录</title>
		<link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t"/>
		<style type="text/css">
			
			html {  
			  height:100%;  
			}  
			body {  
			  background:url(../../res/image/loginbg1.png) no-repeat center center;
			  background-size: 100% 100%;  
			 /* min-heigth:min-height:100%;  height:100%; */
			 overflow: hidden;
			} 
		</style>
	</head>
	<body>
		<div id="page" style="padding-bottom:15px;">
			<img class="luogou" src="../../res/image/dropDog.png"/>
			<form id="loginForm1">
				<input type="number" name="phone" id="mobile" class="loginInput mobile" placeholder="手机" />
				<input type="password" name="pwd" id="pass" class="loginInput pass" placeholder="密码" />
				<div class="loginBtn"></div>
			</form>
			<div class="forget"></div>
			<!--忘记密码面板-->
			<div id="forgetPassBox">
				<div class="close1"></div>
				<form class="chengePassForm" style="padding-bottom:15px;">
					<input type="text" class="forgetInput" name="oldMobile" id="oldMobile" placeholder="已注册手机号" />
					<div class="checkCodeBox">
						<input type="text" name="checkCode" id="checkCode" class="forgetInput checkCode" placeholder="验证码" />
						<input type="button" id="btn" disabled-id="0" value="获取验证码" class="getCodeBtn getCodeBtn0" /> 
						<!--<div class="getCodeBtn" disabled-id="0" >获取验证码</div>-->
					</div>
					<input type="password" name="newpass" id="newpass" class="forgetInput" placeholder="新密码" />
					<input type="password" name="againPass" id="againPass" class="forgetInput" placeholder="确认密码" />
					<div class="confirmChange"></div>
				</form>
			</div>
			<!--提示框-->
			<div id="infoBox">
				<span class="close8"></span>
				<div class="infoText">提示信息</div>
			</div>
		</div>
		<!--遮罩-->
	    <div id="screen"></div>
		<script src="../../res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../res/js/hui_main.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../res/js/hui_login.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../res/js/hui_findpwd.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		     window.onload = function(){
		     	
		     	
		     	
		     	var page=document.getElementById("page")
			    var btn=document.getElementById("btn");
			    //生成骨头
			    function createSnow(l,t,s,r){
			        var  newNode=document.createElement("div");
				         newNode.style.height=s+"px";
				         newNode.style.width=s+"px";
				         newNode.style.position= "absolute"
				         newNode.style.left=l+"px";
				         newNode.style.top=t+"px";
				         newNode.style.transform="rotate("+r+"deg)";
				         newNode.style.background="url('../../res/image/gutou.png')";
				         newNode.style.backgroundSize="100% 100%";
				         newNode.style.zIndex = 3
				         page.appendChild(newNode);
				         startMove(newNode);
				    }
				    var time1=null;
				    var poY=0;
				    setInterval(function(){
			            var le=parseInt(Math.random()*375);//随机生成位置及宽高
			            var to=parseInt(Math.random()*10);
			            var size=parseInt(Math.random()*20+20);
			            var rotate = parseInt(Math.random()*360+1);
			            createSnow(le,to,size,rotate);
			        },1000);
				    //骨头下落
				    function startMove(obj){
				        var poY=obj.offsetTop;//获取对象到顶部边宽的距离
				        var time2=null;
				        function move(){
				            poY++;
				            obj.style.top=poY+"px";
				            if(poY>600){//当骨头超出容器时的处理事件
				                clearInterval(time2);//清除计时器
				                page.removeChild(obj);//删除超出容器的节点对象
				            }
				        }
				        time2=setInterval(move,30);
				    }
		     }
		</script>
	</body>
</html>