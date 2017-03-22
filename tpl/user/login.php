<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>哈巴狗</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <link rel="stylesheet" type="text/css" href="/res/css/css.css?t=2" />
    <link rel="stylesheet" type="text/css" href="/res/css/hui_main.css?t=1"/>
    <link rel="stylesheet" type="text/css" href="../../res/css/yanzhengma.css"/>
    <script src="/res/js/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
    <script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/hui_main.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/hui_login.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/hui_findpwd.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/js.KinerCode.js" type="text/javascript" charset="utf-8"></script>
    
</head>
<body>
    <div id="login">
        <div class="logo"></div>
        <div class="login_form">
            <form>
                <input type="text" id="mobile" placeholder="手机">
                <input type="password" id="pass" placeholder="密码">
                <input type="button" class="loginbtn" value="登录">
            </form>

        </div>
        <div class="forget1"><span>忘记密码</span></div>
        <!--提示框-->
        <div id="infoBox">
            <span class="close8"></span>
            <div class="infoText">提示信息</div>
        </div>
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
        
        <fieldset id="yanzhengBox">
        	<div class="close1"></div>
		    <!--<form class="inputBox">
		        <input id="inputCode" type="text" class="inputs"/>
		        <span id="code" class="mycode"></span>
		        <input type="button" value="提交" id="submit" class="inputs"/>
		    </form>-->
		</fieldset>
        
        <!--遮罩-->
        <div id="screen"></div>
        <div id="screen3"></div>
    </div>


    <script type="text/javascript">
        window.onload = function(){
        	var screen = $('#screen');
        	//全局报错
        	bjui_config.error_report=function(userexception, src, param, msg, fnFail){
        		$('.inputBox').remove();
        		$('#screen3').fadeOut();
				$('#infoBox').show();
				$('#infoBox').find('.infoText').text(msg);
			}
			

        	
        	//骨头下落动画
//          var page=document.getElementById("login")
//          //生成骨头
//          function createSnow(l,t,s,r){
//              var  newNode=document.createElement("div");
//              newNode.style.height=s+"px";
//              newNode.style.width=s+"px";
//              newNode.style.position= "absolute"
//              newNode.style.left=l+"px";
//              newNode.style.top=t+"px";
//              newNode.style.transform="rotate("+r+"deg)";
//              newNode.style.background="url('../../res/image/gutou.png')";
//              newNode.style.backgroundSize="100% 100%";
//              newNode.style.zIndex = 3
//              page.appendChild(newNode);
//              startMove(newNode);
//          }
//          var time1=null;
//          var poY=0;
//          setInterval(function(){
//              var le=parseInt(Math.random()*375);//随机生成位置及宽高
//              var to=parseInt(Math.random()*10);
//              var size=parseInt(Math.random()*20+20);
//              var rotate = parseInt(Math.random()*360+1);
//              createSnow(le,to,size,rotate);
//          },2000);
//          //骨头下落
//          function startMove(obj){
//              var poY=obj.offsetTop;//获取对象到顶部边宽的距离
//              var time2=null;
//              function move(){
//                  poY++;
//                  obj.style.top=poY+"px";
//                  if(poY>600){//当骨头超出容器时的处理事件
//                      clearInterval(time2);//清除计时器
//                      page.removeChild(obj);//删除超出容器的节点对象
//                  }
//              }
//              time2=setInterval(move,30);
//          }
            
            
            //关闭点击验证码
            $('.close1').on('click',function(){
            	$('#yanzhengBox').hide();
            	$('.inputBox').remove();
            	$('#screen3').hide();
            })
            
            //随机验证码
            function yanzhengma(){
            	
	            var inp = document.getElementById('inputCode');
			    var code = document.getElementById('code');
			    var submit = document.getElementById('submit');
			    var inp2 = document.getElementById('inputCode2');
			    var code2 = document.getElementById('code2');
			
			
			    var c = ["+", "-", "*", "/"];
			    var arr = [];
			
			    for (var i = 0; i < 1000; i++) {
			
			        var num = parseInt(Math.random() * 100 + 1);
			        var num2 = parseInt(Math.random() * 100 + 1);
			        var num3 = parseInt(Math.random() * 4);
			
			        if (c[num3] === '/') {
			            var x = num % num2;
			            if (x != 0) {
			                num -= x;
			
			                if(num==0){
			                    var temp = num;
			                    num2 = num;
			                    num = temp;
			                }
			
			            }
			        }
			
			        if(num==0&&num==0){
			            continue;
			        }
			
			        var str = num + c[num3] + num2;
			
			        arr.push(str);
			
			    }
			
			
			    //======================插件引用主体
			    var c = new KinerCode({
			        len: 4,//需要产生的验证码长度
			        //chars: ["1+2","3+15","6*8","8/4","22-15"],//问题模式:指定产生验证码的词典，若不给或数组长度为0则试用默认字典
			        chars: [
			            1, 2, 3, 4, 5, 6, 7, 8, 9, 0,
			            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
			        ],//经典模式:指定产生验证码的词典，若不给或数组长度为0则试用默认字典
			        question: false,//若给定词典为算数题，则此项必须选择true,程序将自动计算出结果进行校验【若选择此项，则可不配置len属性】,若选择经典模式，必须选择false
			        copy: false,//是否允许复制产生的验证码
			        bgColor: "",//背景颜色[与背景图任选其一设置]
			        bgImg: "/res/image/kong.png",//若选择背景图片，则背景颜色失效
			        randomBg: false,//若选true则采用随机背景颜色，此时设置的bgImg和bgColor将失效
			        inputArea: inp,//输入验证码的input对象绑定【 HTMLInputElement 】
			        codeArea: code,//验证码放置的区域【HTMLDivElement 】
			        click2refresh: true,//是否点击验证码刷新验证码
			        false2refresh: true,//在填错验证码后是否刷新验证码
			        validateObj: submit,//触发验证的对象，若不指定则默认为已绑定的输入框inputArea
			        validateEven: "click",//触发验证的方法名，如click，blur等
			        validateFn: function (result, code) {//验证回调函数
			            if (result) {
			            	$('#infoBox').find('.infoText').text('验证成功');
			                $('#infoBox').show();
			                $('#yanzhengBox').hide();
          					BizCall(
									"user.User.login",
									{
										"phone"	:  $('#mobile').val(),
										"pwd"	: $('#pass').val()
									},
									function(data){
			                          $('#infoBox').find('.infoText').text('登录成功');
			                          $('#infoBox').show();
			                          $('.inputBox')[0].reset()
									  window.location.href="/user/user/home";
								});
			            } else {
			                if (this.opt.question) {
			                	$('#infoBox').find('.infoText').text('验证码输入错误');
			                    $('#infoBox').show();
			                    $('.inputBox')[0].reset()
			                    //alert('验证失败:' + code.answer);
			                } else {
			                	$('#infoBox').find('.infoText').text('验证码输入错误');
			                    $('#infoBox').show();
			                    $('.inputBox')[0].reset()
			                    //alert('验证失败:' + code.strCode);
			                }
			            }
			        }
			    });
            }
            
            //电话验证
            function checkPhone(){ 
			    var phone = $('#mobile').val();
			    if((/^1[34578]\d{9}$/.test(phone))){ 
			        return true; 
			    } 
			}
		    
		    //密码验证
		    function checkpass(){
		    	if( $('#pass').val().length > 5 ){
					return true		
			    }
		    }
		    
			$(".loginbtn").click(function(e){
				e.stopPropagation();
				var phone = $('#mobile').val();
				var pwd   = $('#pass').val();
				
				if( phone == "" ){
					$('#infoBox').show();
		    	    $('#infoBox').find('.infoText').text('请输入手机号');
				}else if( pwd == "" ){
					$('#infoBox').show();
		    	    $('#infoBox').find('.infoText').text('请输入密码');
				}else{
					if( !checkPhone() && !checkpass() ){
						$('#infoBox').show();
		    	        $('#infoBox').find('.infoText').text('手机号格式不正确||密码至少6位');
					}else{
						if( !checkPhone() ){
							$('#infoBox').show();
		    	            $('#infoBox').find('.infoText').text('手机号格式不正确');
						}else if( !checkpass() ){
							$('#infoBox').show();
		    	            $('#infoBox').find('.infoText').text('密码至少6位');
						}else{
		                   
		                    var htmlForm = '<form class="inputBox">'+
										        '<input id="inputCode" type="text" class="inputs"/>'+
										        '<span id="code" class="mycode"></span>'+
										        '<input type="button" value="提交" id="submit" class="inputs"/>'+
										    '</form>'
			                $('#yanzhengBox').append(htmlForm);
			                $('#yanzhengBox').show();	
			                $('#screen3').show();
		                    yanzhengma()
						}
					}
				}
		    })
            
            
            
            
            
        }
    </script>


</body>
</html>