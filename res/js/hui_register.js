$(document).ready(function () {
	$("#page").height($(window).height());
	$(".tjt_div").css("height", $("#page").height() - $(".bottom").height() - $(".new_content").height() - 100);


	bjui_config.error_report=function(userexception, src, param, msg, fnFail){

		$('#infoBox').show();
		$('#infoBox').find('.infoText').text(msg);
	};
	
	var screen = $('#screen');

	//关闭提示信息
	$('.close8').on('click',function(){
		$('#infoBox').hide();
	})

    //关闭确定提示框
    $('.okInfoClose3').on('click',function(){
		$('#okInfo3').hide();
		unlock(screen)
	})

	//手机格式验证
	function checkPhone(){
		var phone = $('.phone').val();
		if((/^1[34578]\d{9}$/.test(phone))){
			return true;
		}
	}
    
    
	var subBtn = $('.subBtn');
	subBtn.on('click',function(){
		var fromAccount = $('.giveuser').val();
		var account = $('.myuser').val();
		var name = $('.name').val();
		var phone = $('.phone').val();
		var sex = $('input:radio:checked').val();

		if( fromAccount == "" ){
			$('#infoBox').show();
			$('#infoBox').find('.infoText').text('请输入转赠人手机号');
		}else if( account == "" ){
			$('#infoBox').show();
			$('#infoBox').find('.infoText').text('请输入手机号码');
		}else if( name == "" ){
			$('#infoBox').show();
			$('#infoBox').find('.infoText').text('请输入姓名');
		}else if( phone == "" ){
			$('#infoBox').show();
			$('#infoBox').find('.infoText').text('请输入电话号码');
		}else{
			if( !checkPhone() ){
				$('#infoBox').show();
				$('#infoBox').find('.infoText').text('手机号格式不正确');
			}else{
				if( account != phone ){
					$('#infoBox').show();
					$('#infoBox').find('.infoText').text('手机号码不一致，请核对');
				}else{
					
//					$('#okInfo3').show();
//				    $('#okInfo3').find('.infoxiaoxi3').text("确认");

					BizCall(
						"user.User.loadRecommend",
						{
							"fromAccount" :  fromAccount
						},
						function(data){
							$('#okInfo3').show();
							$('#okInfo3').find('.infoxiaoxi3').text("确认");
					});


					
				}
			}
		}
	})
	
	$('.okInfoB3').on('click',function(){
		var fromAccount = $('.giveuser').val();
		var account = $('.myuser').val();
		var name = $('.name').val();
		var phone = $('.phone').val();
		var sex = $('input:radio:checked').val();
		
		//alert(fromAccount+'==='+account+'==='+name+'==='+sex+'==='+phone)
		BizCall(
		"user.User.register",
		{
			"fromAccount" :  fromAccount,
			"account"	:  account,
			"name"		:  name,
			"sex"      :  sex,
			"phone"    :phone,
		},
		function(data){
			$('#infoBox').show();
			$('#infoBox').find('.infoText').text("成功开发新狗场");
		});
		
	})

});