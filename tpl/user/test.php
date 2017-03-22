<html>
<head></head>
<script>
    window.onload=function(){
        alert(111);
        bjui_config.error_report=function(userexception, src, param, msg, fnFail){
            //alert(msg);
            ///fnFail();
            //提示手机没有注册面板
            $('#infoBox').show();
            $('#infoBox').find('.infoText').text(msg);
        }
        BizCall(
            "user.User.login",
            {
                "phone"	:  '15270765826',
                "pwd"	: '123456'
            },
            function(data){
                //alert('登录成功');
                window.location.href="/user/user/home";
            });
    }
</script>
<body></body>
</html>