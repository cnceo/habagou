$(function(){
	//锁屏div
	var screen = $('#screen');
	

	//提示信息
	$('.close8').on('click',function(){
		$('#infoBox').hide();
	})
	
	//忘记密码
	$('.forget').on('click',function(){
		$('#forgetPassBox').show();
		lock(screen)
	})
	$('.close1').on('click',function(){
		$('#forgetPassBox').hide();
		unlock(screen)
	})
	
	//小狗购物
	
	$('.dogShop').on('click',function(){
		$('#smallDogShop').show();
		lock(screen)
	})
	$('.close6').on('click',function(){
		$('#smallDogShop').hide();
		unlock(screen)
	})
	$('.smallDogBtn').on('click',function(){
		$('#smallDogShop').hide();
		unlock(screen)
	})
	
	//游戏规则
	$('.gameRule').on('click',function(){
		$('#gameRulePanel').show();
		lock(screen)
	})
	$('.close5').on('click',function(){
		$('#gameRulePanel').hide();
		unlock(screen)
	})
	
	//狗场邮箱
	$('.dogEmail').on('click',function(){
		$('#inBox').show();
		lock(screen)
	})
	$('.close3').on('click',function(){
		$('#inBox').hide();
		unlock(screen)
	})
	
	//游戏设置
	$('.shezhi').on('click',function(){
		$('#gameSet').show();
		lock(screen)
	})
	$('.close8').on('click',function(){
		$('#gameSet').hide();
		unlock(screen)
	})
	

	
	//会员名称
	$('.vipUser').on('click',function(){
		$('#changePassBox').show();
		lock(screen)
	})
	$('.close2').on('click',function(){
		$('#changePassBox').hide();
		unlock(screen)
	})
	
	
	
	
	
	
	
	
	
});

