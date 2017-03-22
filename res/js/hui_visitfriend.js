$(function(){
    //锁屏 
    var screen = $('#screen')
	
	//一键清洗
	var clean_id = $('#oneKeyCleans').attr('clean_id');
	if( clean_id == 1){
		$('#oneKeyCleans').on('click',function(){
			BizCall(
			"user.User.clean",
			{
				"toAccid"	:  $('#oneKeyCleans').attr('toAccid')
			},
			function(data){
				$('#oneKeyCleans').removeClass('cleanDog1').addClass('cleanDog0')
				clean_id == 0
			    $('#screen').css({'opacity':'0.3'});
				lock(screen);
				$('#cleanGif').show();
				setTimeout(function(){
					$('#cleanGif').hide();
					unlock(screen);
					$('#infoBox').find('.infoText').text('清洗成功获取'+data+'只小狗');
					$('#infoBox').show();
				},3000);


			});

		})
	}
     	
})