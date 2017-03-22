/**
 * Created by Administrator on 2017/3/6.
 */
$(document).ready(function () {
    $(".new_gc").css("height", $("#page").height() - $(".bottom").height() - $(".new_head").height() - $(".back").height()-100);
    $(".new_gcn ul li").css("height", $(".new_gcn ul li").width() * 236 / 220);

    $(".new_gcn ul li label").attr("class","zl_"+$("#zy_zl").val());
    //锁屏div
    var screen = $('#screen');

    //返回上一页
    $('.backLink').on('click',function(){
        window.location.href="/land/land/home";
        //history.back();
        //window.history.go(-1);
        //self.location=document.referrer;
    })

    //刷新
    $('.back_span2').on('click',function(){
        location.reload();
    })

    //关闭提示信息
    $('.close8').on('click',function(){
        $('#infoBox').hide();
    })

    //关闭提示信息
    $('.okInfoClose').on('click',function(){
        $('#okInfo').hide();
    })

    //关闭提示信息
    $('.okInfoClose1').on('click',function(){
        $('#okInfo1').hide();
    })

    //关闭收获
    $('.close10').on('click',function(){
        $('#harvest').hide();
        unlock(screen);
    })

    //关闭增养
    $('.close12').on('click',function(){
        $('.zengyang').hide();
        unlock(screen);
    })





    //关闭喂养
    $('.close12').on('click',function(){
        $('.weiyang').hide();
        unlock(screen);
    })

    //关闭我的伙伴
    $('.friendListClose').on('click',function(){
        $('#friendList').hide();
        unlock(screen);
    })

    bjui_config.error_report=function(userexception, src, param, msg, fnFail){
        ///fnFail();
        $('#infoBox').show();
        $('#infoBox').find('.infoText').text(msg);
    }
    
    
   

    //小狗走动
    var list = $('.new_gc ul').find('li');
    list.each(function(i){
        var index = i
        //创建狗移动
        var i = 0;
        function move_left2right(){
            var left2right = $(list[index]).find('.left2right')
            left2right.attr('src','/res/image/farm/00.gif')

            left2right.stop().animate({'left':'60%'},6000,function(){
                left2right.attr('src','/res/image/farm/01.gif')
                left2right.stop().animate({'left':'15%'},6000,function(){
                    move_left2right()
                })
            })
        }
        
        move_left2right()

        function move_right2left(){
            var right2left = $(list[index]).find('.right2left')
            right2left.attr('src','/res/image/farm/01.gif')

            right2left.stop().animate({'left':'15%'},6000,function(){
                right2left.attr('src','/res/image/farm/00.gif')
                right2left.stop().animate({'left':'60%'},6000,function(){
                    move_right2left()
                })
            })

        }
        move_right2left()

    })

    var type=1;//默认为0  1 开地  2 增氧 3  喂食  4 收获
    //阴影层函数
    function click_yy() {
        if(type==1){
            var yyc = $("#djyy").val();
        }else if(type>=2&&type<=4){
            var yyc = $("#zyyy").val();
        }
        //alert(yyc);
        if(yyc){
            var yyc_arry = yyc.split(',');
            for (i = 0; i < yyc_arry.length; i++) {
                $(".new_gcn ul li:eq(" + yyc_arry[i] + ")").append("<span class=\"click\"></span>");
            }
        }
    };
    //阴影层函数结束

    //阴影层消失
    function hide(){
        $(".new_gcn ul li span.click").remove();
    }


    //框框弹出开始
    $(document).on("touchstart", ".new_gcn ul li span.li_click", function (e) {
        e.stopPropagation();
        var index1=parseInt($(this).parent("li").index());
        $(".new_gcn ul li:eq("+index1+")").find('.paopao').remove();
        var yyc = $("#zyyy").val();
        if(yyc){
            var yyc_arry = yyc.split(',');
			var isok=0;
            for (i in yyc_arry) {
			   //alert(yyc_arry[i]+"==="+index1);
			   if(index1==yyc_arry[i]){
				   isok=1;
			   }
			}
                if (isok) {

                    BizCall(
                        "land.Land.popup",
                        {
                            "index"	:  index1
                        },
                        function(data){
                            //data['wealth']  data['history'] data['today']
                            $(".new_gcn ul li:eq("+index1+")").find('.totalNumA').text(data['wealth']);
                            $(".new_gcn ul li:eq("+index1+")").find('.todayNumA').text(data['today']);
                            $(".new_gcn ul li:eq("+index1+")").find('.oldNumA').text(data['history']);
                            // var li = $(this).parent("li").find('.dogText').parent().siblings();
                            $(".new_gcn ul li:eq("+index1+")").find('.dogText').toggle();
                            $(".new_gcn ul li:eq("+index1+")").siblings().find('.dogText').hide();
                        });
                }
        
        }

    })
    //框框弹出结束

    //泡泡点击函数
    $(document).on("click", ".new_gcn ul li span.paopao", function (e) {
        //alert("点击了泡泡层" + $(this).parent("li").index());
        e.stopPropagation();
        var yinxiao_flag =$("#audio").attr('audio-flag');
        if( yinxiao_flag == 1){
            $("#audio").get(0).play();
        }
        var index=parseInt($(this).parent("li").index());
        $(".new_gcn ul li:eq("+index+")").find('.paopao').remove();

        BizCall(
            "land.Land.hitPop",
            {
                "index"	:  index
            },
            function(data){
                //alert(data);
                //返回泡泡的值，然后修改骨头的值加
                var numA = parseFloat($('.em1').text());
                var newNumA = numA + parseFloat(data);
                $('.em1').text(newNumA.toFixed(2));
                var jiahao = $(".new_gcn ul li:eq("+index+")").find('.jiahao');
                jiahao.text(parseFloat(data).toFixed(2)).show();
                jiahao.animate({'top':6,'opacity':0},500);
            });
    })


    //泡泡层点击函数结束
    var on = 1;
    $(".bottom1 span").click(function(){
        type = $(this).attr("alt");
        //alert(type);
        if(on==1){
            click_yy();
            on=0;
        }else {
            hide();
            on = 1;
        }
    });



    //开发新狗场
    $('.mu1').on('click',function(e){
        e.stopPropagation()

        var list = $('.new_gcn ul').find('li');
        var x;
        list.each(function(i){
            var index = i



            //关闭兄弟阴影
            $(list[index]).find('.zengyangOpacity').hide();
            $(list[index]).find('.weiyangOpacity').hide();
            $(list[index]).find('.shouhuo').hide();
            x=$(list[index]).find('.kaigouChang');
            x.each(function(index2){
                var _this = $(this);
                if( _this.attr("data-flag") == "0"){

                    _this.toggle();
                    _this.off();
                    _this.on('click',function(e){
                        e.stopPropagation()
                        _this.hide();
                        _this.attr("data-flag",1);

                        BizCall(
                            "land.Land.create",
                            {
                                "index"	:  index
                            },
                            function(data){
                                imgHtml = '<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />'
                                $(list[index]).append(imgHtml);
                                function move_left2right(){
                                    var left2right = $(list[index]).find('.left2right')
                                    left2right.attr('src','../../res/image/farm/00.gif')

                                    left2right.stop().animate({'left':'60%'},6000,function(){
                                        left2right.attr('src','../../res/image/farm/01.gif')
                                        left2right.stop().animate({'left':'15%'},6000,function(){
                                            move_left2right()
                                        })
                                    })

                                }
                                move_left2right()
                                var listimg = $('.dogFarm ul li').find('.kaigouChang');
                                listimg.each(function(index){
                                    $(listimg[index]).hide();
                                })

                                var htmlDiv = '<div class="zengyangOpacity" zengyang-flag="{{dog}}"></div>';
                                $(list[index]).append(htmlDiv);

                                var htmlDiv1 = '<div class="weiyangOpacity"></div>';
                                $(list[index]).append(htmlDiv1);

                                var htmlDiv2 = '<div class="shouhuo"></div>';
                                $(list[index]).append(htmlDiv2);

                                var yyc = $("#zyyy").val();
                                if(yyc!=""){
                                    yyc=yyc+","+index;
                                }else{
                                    yyc=index;
                                }
                                $("#zyyy").val(yyc);
                            });
                    })
                }
            });
        })

        return false;

    });

    //增养
    $('.mu2').on('click',function(){

        var list = $('.new_gcn ul').find('li');
        list.each(function(i){
            var index = i
            //关闭兄弟阴影
            $(list[index]).find('.kaigouChang').hide();
            $(list[index]).find('.weiyangOpacity').hide();
            $(list[index]).find('.shouhuo').hide();

            var zengyangOpacity = $(list[index]).find('.zengyangOpacity');

            zengyangOpacity.toggle();
            zengyangOpacity.off();
            zengyangOpacity.on('click',function(e){
                e.stopPropagation()
                $('.zengyangOpacity').hide();
                $('.zengyang').show();
                lock(screen);
                BizCall(
                    "user.User.addMax",
                    {

                        "index"		: index
                    },
                    function(data){
                        $('.numberAdd').val(data);
                    });

                $('#addyang').off();
                $('#addyang').on('click',function(e){
                    e.stopPropagation()
                    var num = $('.numberAdd').val();
                    if( num == ''){
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('请输入增养数量');
                    }else{
                        var re = /^[0-9]*[1-9][0-9]*$/ ;
                        //alert(re.test(num));
                        if(!re.test(num)){
                            $('#infoBox').show();
                            $('#infoBox').find('.infoText').text('增养数量必须为大于0的正整数');
                        }else{

                            //alert(index+'==='+$('.numberAdd').val());
                            BizCall(
                                "land.Land.addOxygen",
                                {
                                    "index"	:  index,
                                    "num"		: $('.numberAdd').val()
                                },
                                function(data){
                                    var n = data
                                    var img = $(list[index]).find('img');
                                    var imglen = img.length;
                                    var addImgLen = n - imglen;
                                    var imgHtml = '';
                                    if( addImgLen == 1){
                                        if(imglen == 1){
                                            imgHtml = '<img id="moveimg" class="right2left" src="../../res/image/farm/00.gif" />'
                                        }else{
                                            imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'
                                        }

                                    }else if( addImgLen == 2 ){
                                        imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />';
                                    }else if( addImgLen == 3 ){
                                        imgHtml = '<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />'+
                                            '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />';
                                    }
                                    $(list[index]).append(imgHtml);
                                    function move_left2right(){
                                        var left2right = $(list[index]).find('.left2right')
                                        left2right.attr('src','../../res/image/farm/00.gif')

                                        left2right.stop().animate({'left':'60%'},6000,function(){
                                            left2right.attr('src','../../res/image/farm/01.gif')
                                            left2right.stop().animate({'left':'15%'},6000,function(){
                                                move_left2right()
                                            })
                                        })

                                    }
                                    move_left2right()

                                    function move_right2left(){
                                        var right2left = $(list[index]).find('.right2left')
                                        right2left.attr('src','../../res/image/farm/01.gif')

                                        right2left.stop().animate({'left':'15%'},6000,function(){
                                            right2left.attr('src','../../res/image/farm/00.gif')
                                            right2left.stop().animate({'left':'60%'},6000,function(){
                                                move_right2left()
                                            })
                                        })

                                    }
                                    move_right2left()
                                    $('.zengyang').hide();
                                    unlock(screen);
                                    $('#infoBox').find('.infoText').text('增养成功');
                                    $('#infoBox').show();
                                },
                                function(){
                                    ////
                                });

                        }
                    }

                })

            })



        })

    })


    //喂养
    $('.mu3').on('click',function(){

        var list = $('.new_gc ul').find('li');
        list.each(function(i){
            var index = i

            //关闭兄弟阴影
            $(list[index]).find('.kaigouChang').hide();
            $(list[index]).find('.zengyangOpacity').hide();
            $(list[index]).find('.shouhuo').hide();

            $(list[index]).find('.weiyangOpacity').toggle();
            $(list[index]).find('.weiyangOpacity').off();
            $(list[index]).find('.weiyangOpacity').on('click',function(e){
                e.stopPropagation()
                $('.weiyangOpacity').hide();
                //弹出喂养面板
                $('.weiyang').show();
                lock(screen);
                BizCall(
                    "user.User.feedMax",
                    {

                        "index"		: index
                    },
                    function(data){
                        $('.numberFeed').val(data);
                    });

                var weiyangBtn = $('#weiyangBtn');
                weiyangBtn.off();
                weiyangBtn.on('click',function(e){
                    e.stopPropagation()
                    var num = $('.numberFeed').val();
                    if( num == ''){
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('请输入喂食数量');
                    }else{
                        var re = /^[0-9]*[1-9][0-9]*$/ ;
                        //alert(re.test(num));
                        if(!re.test(num)){
                            $('#infoBox').show();
                            $('#infoBox').find('.infoText').text('喂食数量必须为大于0的正整数');
                        }else{
                            BizCall(
                                "land.Land.feed",
                                {
                                    "index"	:  index,
                                    "num"		: $('.numberFeed').val()
                                },
                                function(data){
                                    var n = data
                                    var img = $(list[index]).find('img');
                                    var imglen = img.length;
                                    var addImgLen = n - imglen;
                                    var imgHtml = '';
                                    if( addImgLen == 1){
                                        if(imglen == 1){
                                            imgHtml = '<img id="moveimg" class="right2left" src="../../res/image/farm/00.gif" />'
                                        }else{
                                            imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'
                                        }

                                    }else if( addImgLen == 2 ){
                                        imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />';
                                    }else if( addImgLen == 3 ){
                                        imgHtml = '<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />'+
                                            '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />';
                                    }
                                    $(list[index]).append(imgHtml);
                                    function move_left2right(){
                                        var left2right = $(list[index]).find('.left2right')
                                        left2right.attr('src','../../res/image/farm/00.gif')

                                        left2right.stop().animate({'left':'60%'},6000,function(){
                                            left2right.attr('src','../../res/image/farm/01.gif')
                                            left2right.stop().animate({'left':'15%'},6000,function(){
                                                move_left2right()
                                            })
                                        })

                                    }
                                    move_left2right()

                                    function move_right2left(){
                                        var right2left = $(list[index]).find('.right2left')
                                        right2left.attr('src','../../res/image/farm/01.gif')

                                        right2left.stop().animate({'left':'15%'},6000,function(){
                                            right2left.attr('src','../../res/image/farm/00.gif')
                                            right2left.stop().animate({'left':'60%'},6000,function(){
                                                move_right2left()
                                            })
                                        })

                                    }
                                    move_right2left()
                                    $('.weiyang').hide();
                                    unlock(screen);
                                    $('#infoBox').find('.infoText').text('喂食成功');
                                    $('#infoBox').show();
                                    var feednum = $('.numberFeed').val();
                                    var numA = $('.em1').text();
                                    var newNumA = numA - feednum;
                                    $('.em1').text(newNumA.toFixed(2));

                                    //总共加
                                    var numB = $('.em2').text();
                                    //alert(numB)
                                    //alert(feednum)
                                    var newNumB = parseFloat(numB) + parseInt(feednum);
                                    //alert(newNumB)
                                    $('.em2').text(newNumB.toFixed(2));
                                },
                                function(){
                                    ////
                                });
                        }
                    }
                })
            })
        })
    })


    //收获
    $('.mu4').on('click',function(){

        var list = $('.new_gc ul').find('li');
        list.each(function(i){
            var index = i


            //关闭兄弟阴影
            $(list[index]).find('.kaigouChang').hide();
            $(list[index]).find('.zengyangOpacity').hide();
            $(list[index]).find('.weiyangOpacity').hide();

            $(list[index]).find('.shouhuo').toggle();
            $(list[index]).find('.shouhuo').off();
            $(list[index]).find('.shouhuo').on('click',function(e){
                e.stopPropagation()
                $('.shouhuo').hide();
                //点击有阴影接口，index为索引
                bjui_config.error_report=function(userexception, src, param, msg, fnFail){
                    $('#infoBox').show();
                    $('#infoBox').find('.infoText').text(msg);
                }
                BizCall(
                    "land.Land.harvest",
                    {
                        "index"	:  index
                    },
                    function(data){
                        lock(screen);
                        //弹出面板
                        $('#harvest').show();
                        $('.harvestNum').text(data);

                        var img = $(list[index]).find('img');
                        img.remove();

                        var dogHtml = '';
                        dogHtml = '<img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />'
                        $(list[index]).append(dogHtml);
                        function move_left2right(){
                            var left2right = $(list[index]).find('.left2right')
                            left2right.attr('src','/res/image/farm/00.gif')

                            left2right.stop().animate({'left':'60%'},6000,function(){
                                left2right.attr('src','../../res/image/farm/01.gif')
                                left2right.stop().animate({'left':'15%'},6000,function(){
                                    move_left2right()
                                })
                            })

                        }
                        move_left2right();
                    },
                    function(){
                        ////
                    });
            })
        })
    })


    //去增养
    $('#zengyangBtn').on('click',function(){
        $('#wareHouse').hide();
        unlock(screen);
        var list = $('.new_gcn ul').find('li');
        list.each(function(i){
            var index = i
            //关闭兄弟阴影
            $(list[index]).find('.kaigouChang').hide();
            $(list[index]).find('.weiyangOpacity').hide();
            $(list[index]).find('.shouhuo').hide();

            var zengyangOpacity = $(list[index]).find('.zengyangOpacity');

            zengyangOpacity.toggle();
            zengyangOpacity.off();
            zengyangOpacity.on('click',function(e){
                e.stopPropagation()
                $('.zengyangOpacity').hide();
                $('.zengyang').show();
                lock(screen);
                BizCall(
                    "user.User.addMax",
                    {

                        "index"		: index
                    },
                    function(data){
                        $('.numberAdd').val(data);
                    });

                $('#addyang').off();
                $('#addyang').on('click',function(e){
                    e.stopPropagation()
                    var num = $('.numberAdd').val();
                    if( num == ''){
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('请输入增养数量');
                    }else{
                        var re = /^[0-9]*[1-9][0-9]*$/ ;
                        //alert(re.test(num));
                        if(!re.test(num)){
                            $('#infoBox').show();
                            $('#infoBox').find('.infoText').text('增养数量必须为大于0的正整数');
                        }else{

                            //alert(index+'==='+$('.numberAdd').val());
                            BizCall(
                                "land.Land.addOxygen",
                                {
                                    "index"	:  index,
                                    "num"		: $('.numberAdd').val()
                                },
                                function(data){
                                    var n = data
                                    var img = $(list[index]).find('img');
                                    var imglen = img.length;
                                    var addImgLen = n - imglen;
                                    var imgHtml = '';
                                    if( addImgLen == 1){
                                        if(imglen == 1){
                                            imgHtml = '<img id="moveimg" class="right2left" src="../../res/image/farm/00.gif" />'
                                        }else{
                                            imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'
                                        }

                                    }else if( addImgLen == 2 ){
                                        imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />';
                                    }else if( addImgLen == 3 ){
                                        imgHtml = '<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />'+
                                            '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />';
                                    }
                                    $(list[index]).append(imgHtml);
                                    function move_left2right(){
                                        var left2right = $(list[index]).find('.left2right')
                                        left2right.attr('src','../../res/image/farm/00.gif')

                                        left2right.stop().animate({'left':'60%'},6000,function(){
                                            left2right.attr('src','../../res/image/farm/01.gif')
                                            left2right.stop().animate({'left':'15%'},6000,function(){
                                                move_left2right()
                                            })
                                        })

                                    }
                                    move_left2right()

                                    function move_right2left(){
                                        var right2left = $(list[index]).find('.right2left')
                                        right2left.attr('src','../../res/image/farm/01.gif')

                                        right2left.stop().animate({'left':'15%'},6000,function(){
                                            right2left.attr('src','../../res/image/farm/00.gif')
                                            right2left.stop().animate({'left':'60%'},6000,function(){
                                                move_right2left()
                                            })
                                        })

                                    }
                                    move_right2left()
                                    $('.zengyang').hide();
                                    unlock(screen);
                                    $('#infoBox').find('.infoText').text('增养成功');
                                    $('#infoBox').show();
                                },
                                function(){
                                    ////
                                });

                        }
                    }

                })

            })



        })
    })


    //去喂养
    $('#wYangBtn').on('click',function() {
        $('#wareHouse').hide();
        unlock(screen);

        var list = $('.new_gc ul').find('li');
        list.each(function(i){
            var index = i

            //关闭兄弟阴影
            $(list[index]).find('.kaigouChang').hide();
            $(list[index]).find('.zengyangOpacity').hide();
            $(list[index]).find('.shouhuo').hide();

            $(list[index]).find('.weiyangOpacity').toggle();
            $(list[index]).find('.weiyangOpacity').off();
            $(list[index]).find('.weiyangOpacity').on('click',function(e){
                e.stopPropagation()
                $('.weiyangOpacity').hide();
                //弹出喂养面板
                $('.weiyang').show();
                lock(screen);
                BizCall(
                    "user.User.feedMax",
                    {

                        "index"		: index
                    },
                    function(data){
                        $('.numberFeed').val(data);
                    });

                var weiyangBtn = $('#weiyangBtn');
                weiyangBtn.off();
                weiyangBtn.on('click',function(e){
                    e.stopPropagation()
                    var num = $('.numberFeed').val();
                    if( num == ''){
                        $('#infoBox').show();
                        $('#infoBox').find('.infoText').text('请输入喂食数量');
                    }else{
                        var re = /^[0-9]*[1-9][0-9]*$/ ;
                        //alert(re.test(num));
                        if(!re.test(num)){
                            $('#infoBox').show();
                            $('#infoBox').find('.infoText').text('喂食数量必须为大于0的正整数');
                        }else{
                            BizCall(
                                "land.Land.feed",
                                {
                                    "index"	:  index,
                                    "num"		: $('.numberFeed').val()
                                },
                                function(data){
                                    var n = data
                                    var img = $(list[index]).find('img');
                                    var imglen = img.length;
                                    var addImgLen = n - imglen;
                                    var imgHtml = '';
                                    if( addImgLen == 1){
                                        if(imglen == 1){
                                            imgHtml = '<img id="moveimg" class="right2left" src="../../res/image/farm/00.gif" />'
                                        }else{
                                            imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'
                                        }

                                    }else if( addImgLen == 2 ){
                                        imgHtml = '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />';
                                    }else if( addImgLen == 3 ){
                                        imgHtml = '<img id="moveimg" class="left2right" src="../../res/image/farm/00.gif" />'+
                                            '<img id="moveimg" class="right2left" src="../../res/image/farm/01.gif" />'+
                                            '<img id="moveimg" class="left2right left2right1" src="../../res/image/farm/00.gif" />';
                                    }
                                    $(list[index]).append(imgHtml);
                                    function move_left2right(){
                                        var left2right = $(list[index]).find('.left2right')
                                        left2right.attr('src','../../res/image/farm/00.gif')

                                        left2right.stop().animate({'left':'60%'},6000,function(){
                                            left2right.attr('src','../../res/image/farm/01.gif')
                                            left2right.stop().animate({'left':'15%'},6000,function(){
                                                move_left2right()
                                            })
                                        })

                                    }
                                    move_left2right()

                                    function move_right2left(){
                                        var right2left = $(list[index]).find('.right2left')
                                        right2left.attr('src','../../res/image/farm/01.gif')

                                        right2left.stop().animate({'left':'15%'},6000,function(){
                                            right2left.attr('src','../../res/image/farm/00.gif')
                                            right2left.stop().animate({'left':'60%'},6000,function(){
                                                move_right2left()
                                            })
                                        })

                                    }
                                    move_right2left()
                                    $('.weiyang').hide();
                                    unlock(screen);
                                    $('#infoBox').find('.infoText').text('喂食成功');
                                    $('#infoBox').show();
                                    var feednum = $('.numberFeed').val();
                                    var numA = $('.em1').text();
                                    var newNumA = numA - feednum;
                                    $('.em1').text(newNumA.toFixed(2));

                                    //总共加
                                    var numB = $('.em2').text();
                                    //alert(numB)
                                    //alert(feednum)
                                    var newNumB = parseFloat(numB) + parseInt(feednum);
                                    //alert(newNumB)
                                    $('.em2').text(newNumB.toFixed(2));
                                },
                                function(){
                                    ////
                                });
                        }
                    }
                })
            })
        })

    })

   
    //点击显示公告
    $('#gonggao').on('click',function(){
        $('#noticeBox').show();
        lock(screen);
    })
    //显示隐藏公告内容
    $('#noticeBox ul').find('li').click(function(){
    	$(this).children('.contentBox').toggle();
    })
     
    //点击关闭公告
    $('.close13').on('click',function(){
        $('#noticeBox').hide();
        unlock(screen);
    })
    
    //我的伙伴
    $('#myFriend').on('click',function(){
        $('#friendList').show();
        lock(screen);
        //$('#friendList').hide()
    })

    //一键清洗
    $('.onekeyclean').on('click',function(){
        //alert('一键清洗');
        BizCall(
            "user.User.superClean",
            {
            },
            function(data){
                $('#infoBox').show();
                $('#infoBox').find('.infoText').text('一键清洗获得'+data+'只狗仔');
                var numA = parseFloat($('.em1').text());
                var newNumA = numA + parseFloat(data);
                $('.em1').text(newNumA.toFixed(2));
            });
    })

    //邀请好友
    //var screen1 =$('#screen1');
    $('.yaoqing').on('click',function(){
        $('#visitFriend').show();
        lock(screen);
        $('#friendList').hide();
    })

    $('#visitFriend').on('click',function(){
        $('#visitFriend').hide();
        unlock(screen)
    })

//  $('#screen').on('click',function(){
//      $('#visitFriend').hide();
//      unlock(screen)
//  })

    //关闭我的仓库
    $('.wareHouseClose').on('click',function(){
        $('#wareHouse').hide();
        unlock(screen);
    })

    //点击我的仓库
    $('.myWareHouse').on('click',function(e){
    	e.stopPropagation();
        $('#wareHouse').show();
        lock(screen);
        BizCall(
            "user.User.myWarehouse",
            {

            },
            function(data){
                $('#xiaogoushu').text(data['warehouse']);
                $('#bone').text(data['bone']);
                $('#langanlevel').attr('src','../../res/image/index/face/langan'+data['level']+'.png');
                $('#dengji').text(data['feederlevel']);
                var day = data['day'];
                var buyBtn = $('#buy');
                if( day > 0 ){
                	buyBtn.attr('disabled',"true");//禁止点击
                    buyBtn.addClass('btn1')
                    buyBtn.val('剩余'+day+'天');
                }else{
                    buyBtn.removeClass('btn1');
                    buyBtn.removeAttr("disabled");//移除禁止点击
                    buyBtn.on('click',function(e){
                        e.stopPropagation();
                        var fee=data['fee'];
                        $('#okInfo1').find('.infoxiaoxi1').text("将要花费"+fee+"小狗");
                        $('#okInfo1').show();
                        $('.okInfoB1').on('click',function(e){
                            e.stopPropagation();
                            
                            BizCall(
                                "user.User.buySuperclean",
                                {

                                },
                                function(data){
                                    buyBtn.addClass('btn1')
		                            buyBtn.val('剩余'+data+'天');
		                            $('#infoBox').show();
		                            $('#infoBox').find('.infoText').text('购买成功');
		                            buyBtn.attr('disabled',"true");//禁止点击
                                    setTimeout(function(){
                                        $('#infoBox').hide();
                                        $('#okInfo1').hide();
                                        $('#wareHouse').hide();
                                        unlock(screen);
                                    },1000);
                                    
                                });
                        });

                    })
                }
            });
    })

    //饲养员升级
    $('#shengji').on('click',function(){
        BizCall(
            "user.User.loadNextFeeder",
            {
                "level"	:  $('#dengji').text()
            },
            function(data){
                $('#okInfo').find('.infoxiaoxi').text('将消耗'+data['fee']+'只小狗,成功率'+data['showchance']+'%,继续吗?');
                $('#okInfo').show();

            });
    })

    $('.okInfoB').on('click',function(e){
        e.stopPropagation()
        //alert('发送请求');
        $('#okInfo').hide();
        BizCall(
            "user.User.upgradeFeeder",
            {
                "level"	:  $('#dengji').text()
            },
            function(data){
                if( data['flag'] == 1 ){
                    var preLevel = data['level'] - 1;
                    $('#infoBox').find('.infoText').text('升级成功');
                    $('#infoBox').show();
                    $('#drag').removeClass("feederLever"+preLevel).addClass("feederLever"+data['level']);
                    setTimeout(function(){
                        $('#infoBox').hide();
                        $('#wareHouse').hide();
                        $('#okInfo').hide();
                        unlock(screen);
                    },1000);
                }else{
                    $('#infoBox').find('.infoText').text('升级失败');
                    $('#infoBox').show();
                    setTimeout(function(){
                        $('#infoBox').hide();
                        $('#wareHouse').hide();
                        $('#okInfo').hide();
                        unlock(screen);
                    },1000);
                }
            });
    })

    // 拖拽
    var block = document.getElementById("drag");
    if(block){
        var oW,oH;
        // 绑定touchstart事件
        block.addEventListener("touchstart", function(e) {
            var    touches  =  e.touches[0];
            oW  =  touches.clientX - block.offsetLeft;
            oH  =  touches.clientY - block.offsetTop;
            //阻止页面的滑动默认事件
            document.addEventListener("touchmove",defaultEvent,false);
        },false)

        block.addEventListener("touchmove", function(e) {
            var touches =  e.touches[0];
            var oLeft   =  touches.clientX - oW;
            var oTop    =  touches.clientY - oH;

            if(oLeft < 0) {
                oLeft = 0;
            }else if(oLeft > document.documentElement.clientWidth - block.offsetWidth) {
                oLeft = document.documentElement.clientWidth - block.offsetWidth;
            }

            if(oTop<0){
                oTop = 0
            }else if(oTop > document.documentElement.clientHeight - block.offsetHeight){
                oTop = document.documentElement.clientHeight - block.offsetHeight
            }

            block.style.left = oLeft + "px";
            block.style.top  = oTop  + "px";
        },false);

        block.addEventListener("touchend",function() {
            document.removeEventListener("touchmove",defaultEvent,false);
        },false);
        function defaultEvent(e) {
            e.preventDefault();
        }
    }


})

