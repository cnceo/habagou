$(function(){
	bjui_config.error_report=function(userexception, src, param, msg, fnFail){
		$('#infoBox').show();
		$('#infoBox').find('.infoText').text(msg);
		lock(screen);

	}
	var screen = $('#screen');

	//点击显示忘记密码面板
	$('.forget1').on('click',function(){
		$('#forgetPassBox').show();
		lock(screen);
	})
	
	//获取验证码
	var disabled_id = $('.getCodeBtn').attr('disabled-id');
	if( disabled_id == 0 ){
		
		$('.getCodeBtn').on('click',function(){
	    var countdown=60;
		//验证是否输入号码和号码格式
		var phone = $('#oldMobile').val();
		if( phone == '' ){
			$('#infoBox').find('.infoText').text('请输入手机号');
	        $('#infoBox').show();
		}else{
			if( !(/^1[34578]\d{9}$/.test(phone)) ){
				$('#infoBox').show();
		        $('#infoBox').find('.infoText').text('手机号格式不正确');
			}else{
				    //alert('aaaaa');
                    $('.getCodeBtn').attr("disabled",true);
					BizCall(
					"user.User.sendForgetpwdCode",
					{
						"phone"	:  $('#oldMobile').val()
					},function(data){
						settime($('.getCodeBtn'))
						var timer = null;
						function settime(val) {
							timer = setTimeout(function() {
								settime(val)
							},1000);
							if (countdown == 0) {
								clearInterval(timer)
								val.attr("disabled",false);
								val.removeClass('getCodeBtn1').addClass('getCodeBtn0');
								val.val("获取验证码");
								countdown = 60;
							} else {
								//val.attr("disabled",true);
								val.removeClass('getCodeBtn0').addClass('getCodeBtn1');
								val.val('剩余'+countdown+'s');
								countdown--;
							}

						}
					});
			}
		}
	})
}
	
	
	
	
	
	
	
	
})
