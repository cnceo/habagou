/**
 * Created by Administrator on 2017/3/8.
 */
$(document).ready(function () {
    bjui_config.error_report=function(userexception, src, param, msg, fnFail){
        alert(msg);
        ///fnFail();
        //提示手机没有注册面板
        /*$('#infoBox').show();
         $('#infoBox').find('.infoText').text(msg);*/
        //lock(screen);
    }

    $(".tjt_div").css("height", $("#page").height() - $(".bottom").height() - $(".new_content").height() - 120);
    $(".new_gcn ul li").css("height", $(".new_gcn ul li").width() * 236 / 220);

    //开关按钮点击 获取 input值即可
    $("#slideThree").change(function () {
        //alert($("#slideThree").val());
        var status=$("#slideThree").val();
        if(status==1){
            status=0;
        }else if(status==0){
            status=1;
        }
        BizCall(
            "user.User.updateVideo",
            {
                "status"	:  status
            },
            function(data){

            });
        if ($("#slideThree").val() == "1") {
            $('#slideThree').removeAttr("checked");
            $("#slideThree").val(0);
        }
        else {
            $('#slideThree').attr("checked", "true");
            $("#slideThree").val(1);
        }
    });
    //开关按钮  intput 值1为开
    if ($("#slideThree").val() == "1") {
        $('#slideThree').attr("checked", "true");
    }
    else {
        $('#slideThree').removeAttr("checked");
    }

    //公告点击
    $("#gonggao div").click(function () {
        $("#gong_gao").show();
        $("#screen").show();
    });
    //公告内容展开
    $("#gong_gao dl").click(function () {
        $(this).children("dt").toggle();
    });
    
    //修改密码
    $(".new_head li.li1 span").click(function () {
        $("#edit_password").show();
        $("#screen").show();
    });

    //游戏设置
    $("#music_set").click(function () {
        $("#game_music").show();
        $("#screen").show();
    });
    //狗屋
    $("#home").click(function () {
        $("#dog_home").show();
        $("#screen").show();
    });
    //狗场邮箱
    $("#email_set").click(function () {
        $("#in_email").show();
        $("#screen").show();
    });
    //游戏规则
    $("#rule").click(function () {
        $("#game_RulePanel").show();
        $("#screen").show();
    });
    //点击关闭
    $(".close5").click(function () {
        $(this).parent().parent().hide();
        $("#screen").hide();
    });
    //头像选择
    $("#in_head li").click(function () {
        $(this).addClass("selected").siblings().removeClass("selected");
        //alert($("#in_head li.selected").attr("data_id");
        //alert($("#in_head li.selected").attr("data_id"));
        var headid=$("#in_head li.selected").attr("data_id");
        BizCall(
		    "user.User.updateHeadimg",
		    {
		        "headid"	:  headid
		    },
		    function(data){
		    	
       });
    });
    //确认头像
    $(".go_chage").click(function () {
        $(".pesron img").attr("src", $("#in_head li.selected img:eq(0)").attr("src"));
        $(".close5").trigger("click");
    });
    //更换头像
    $(".pesron").click(function () {
        $("#in_head").show();
        $("#screen").show();
    })
})
