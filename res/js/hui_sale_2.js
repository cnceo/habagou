
$(document).ready(function () {

	$(".tjt_div").css("height", $("#page").height() - $(".bottom").height() - $(".new_content").height() - 100);
	var obj = {};
	obj.supers = $("#slideThree").val();
	var zz_type=$("#slideThree").val();
	//开关按钮点击 获取 input值即可
	$("#slideThree").change(function () {
		if ($("#slideThree").val() == "1") {
			$('#slideThree').removeAttr("checked");
			$("#slideThree").val(0);
			zz_type=0;
		}
		else {
			$('#slideThree').attr("checked", "true");
			$("#slideThree").val(1);
			zz_type=1;
		}
		obj.supers = $("#slideThree").val();
	});
	//开关按钮  intput 值1为开
	if ($("#slideThree").val() == "1") {
		$('#slideThree').attr("checked", "true");
	}
	else {
		$('#slideThree').removeAttr("checked");
	}



	//关闭提示信息
	$('.close8').on('click',function(){
		$('#infoBox').hide();
	})


	//关闭获取验证码面板
	$(".checkCodeBoxclose").on('click',function(){
		$('#checkCodeBox').hide();
	})




	//验证提交数据
	var subBtn = $('.subBtn');
	subBtn.on('click', function () {
	    var num = $('.giveuser').val();
	    var toAccount = $('.myuser').val();
	    var toName = $('.name').val();
	    obj.supers = $("#slideThree").val();
	    //alert(obj.supers);
	    if (num == "") {
	        $('#infoBox').show();
	        $('#infoBox').find('.infoText').text('请输入转赠数量');

	    } else if (!/^[+-]?(\d){1,}0$/.test(num)) {

	        $('#infoBox').show();
	        $('#infoBox').find('.infoText').text('转赠数量必须为十的倍数');

	    } else if (toAccount == "") {
	        $('#infoBox').show();
	        $('#infoBox').find('.infoText').text('请输入目标手机号');
	    } else if (toName == "") {
	        $('#infoBox').show();
	        $('#infoBox').find('.infoText').text('请输入目标姓名');
	    } else {

	        bjui_config.error_report = function (userexception, src, param, msg, fnFail) {

	            //提示手机没有注册面板
	            $('#infoBox').show();
	            $('#infoBox').find('.infoText').text(msg);
	        };

	        $('#checkCodeBox').show();

	    }

	});

	$('#btn').on('click', function () {

	    var num = $('.giveuser').val();
	    var toAccount = $('.myuser').val();
	    var toName = $('.name').val();

	    var countdown = 60;
	    bjui_config.error_report = function (userexception, src, param, msg, fnFail) {
	        $('#infoBox').show();
	        $('#infoBox').find('.infoText').text(msg);
	    }
		var num = $('.giveuser').val();
		var toAccount = $('.myuser').val();
	    BizCall(
            "user.User.sendSaleCode",
            {
                "account": toAccount,
                "num": num
            },
            function (data) {
                settime($('.getCodeBtn'));
                var timer = null;
                function settime(val) {
                    timer = setTimeout(function () {
                        settime(val)
                    }, 1000)
                    if (countdown == 0) {
                        clearInterval(timer)
                        val.attr("disabled", false);
                        val.removeClass('getCodeBtn1').addClass('getCodeBtn0');
                        val.val("获取验证码");
                        countdown = 60;
                    } else {
                        val.attr("disabled", true);
                        val.removeClass('getCodeBtn0').addClass('getCodeBtn1');
                        val.val('剩余' + countdown + 's');
                        countdown--;
                    }

                }
            });
	});

	$('.checkBtn').on('click', function () {
		//alert(zz_type+"--sdf");
		if(zz_type == 0 )
		{
			puzz();
		}
		else
		{
			cjzz();
		}
	})

});




function cjzz() {
    var code = $('#checkcode').val();

    if (code == '') {
        $('#infoBox').show();
        $('#infoBox').find('.infoText').text('请输入验证码');
    } else {

        if (code.length != 6) {
            $('#infoBox').show();
            $('#infoBox').find('.infoText').text('验证码必须为6位');
        } else {
            var num = $('.giveuser').val();
            var toAccount = $('.myuser').val();
            var toName = $('.name').val();
            BizCall(
                    "user.User.superSale",
                    {
                        "toAccount": toAccount,
                        "toName": toName,
                        "num": num,
                        "code": code
                    },
                    function (data) {
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('超级转账成功');
                    });
        }
    }
}

function puzz() {
    var code = $('#checkcode').val();
    //alert(code)
    if (code == '') {
        $('#infoBox').show();
        $('#infoBox').find('.infoText').text('请输入验证码');
    } else {
        if (code.length != 6) {
            $('#infoBox').show();
            $('#infoBox').find('.infoText').text('验证码必须为6位');
        } else {
            var num = $('.giveuser').val();
            var toAccount = $('.myuser').val();
            var toName = $('.name').val();
            //alert('验证码为'+code);
            BizCall(
                    "user.User.sale",
                    {
                        "toAccount": toAccount,
                        "toName": toName,
                        "num": num,
                        "code": code
                    },
                    function (data) {
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('普通转赠成功，请等待好友' + toName + '打米');
                    });
        }
    }
}