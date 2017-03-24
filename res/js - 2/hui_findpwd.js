$(function(){
	bjui_config.error_report=function(userexception, src, param, msg, fnFail){
		$('#infoBox').show();
		$('#infoBox').find('.infoText').text(msg);
	}
	//phone   newpwd  confirmpwd（6为12位）
    //手机格式验证
    function checkPhone(){ 
	    var phone = $('#oldMobile').val();
	    if((/^1[34578]\d{9}$/.test(phone))){ 
	        return true; 
	    } 
	}
	
	var confirmBtn = $('.confirmChange');
	confirmBtn.off();
	confirmBtn.on('click',function(e){
		e.stopPropagation();
		var phone = $('#oldMobile').val();
		var newpwd = $('#newpass').val();
		var confirmpwd = $('#againPass').val();
		var code = $('#checkCode').val();
		//var super = $('input[type="radio"]:checked').attr('value')
	    if( phone == "" ){
	    	$('#infoBox').show();
	    	$('#infoBox').find('.infoText').text('请输入手机号')
		}else if( code == "" ){
			$('#infoBox').show();
	    	$('#infoBox').find('.infoText').text('请输入验证码')
		}else if( newpwd == "" ){
			$('#infoBox').show();
	    	$('#infoBox').find('.infoText').text('请输入新密码')
		}else if( confirmpwd == "" ){
			$('#infoBox').show();
	    	$('#infoBox').find('.infoText').text('请再次输入密码')
		}else{
			if( !checkPhone() ){
				$('#infoBox').show();
	    	    $('#infoBox').find('.infoText').text('手机号格式不正确')
			}else{
				if( newpwd != confirmpwd ){
					$('#infoBox').show();
	    	        $('#infoBox').find('.infoText').text('密码不一致')
				}else{
					BizCall(
					"user.User.updateForgetpwd",
					{
						"phone"	:  phone,
						"pwd"   :  newpwd,
						"code"  :  code
					},
					function(data){
						$('#infoBox').show();
						$('#infoBox').find('.infoText').text('重置密码成功');
						
						setTimeout(function(){
							$('#infoBox').hide();
							$('#forgetPassBox').hide();
						},1000)
						
					});
				}
			}
		}
	})
	
})
