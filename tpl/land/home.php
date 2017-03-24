<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>狗崽乐园</title>
    <meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
    <meta name="browsermode" content="application">		<!-- UC应用模式 -->
    <meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
    <meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
    <meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
    <meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <link rel="stylesheet" type="text/css" href="/res/css/css.css?t=4" />
    <link rel="stylesheet" type="text/css" href="/res/css/hui_land.css" />
    <link rel="stylesheet" type="text/css" href="/res/css/hui_main.css" />
    <script src="/res/js/jquery-1.11.2.min.js" charset="utf-8"></script>
    <script src="/uilib/bjui.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/hui_tool.js" type="text/javascript" charset="utf-8"></script>
    <script src="/res/js/fastclick.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="/res/js/newland.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="/res/js/newland.min.js" type="text/javascript" charset="utf-8"></script>
    <style>
        /*------------------加号-----------------*/
        .new_gcn ul li .jiahao {
            width: 34px;
            height: 34px;
            position: absolute;
            background: url(/res/image/farm/jiahao.png) no-repeat left bottom;
            background-size: 70% 70%;
            bottom: 35px;
            left: 25px;
            z-index: 20000000;
            color: #ffffff;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #e1e732;
            display: none;
        }
        /*------------------加号-----------------*/
        /*----确定提示框------*/
        #okInfo1 {
            position: absolute;
            width: 100%;
            height: 58vh!important;
            height: 435px;
            background: url(/res/image/farm/okInfo.png) no-repeat 0 0;
            background-size: 100% 100%;
            top: 10%;
            z-index: 10000000;
            text-align: center;
            color: #815f0f;
            font-size: 18px;
            font-weight: bold;
            display: none;
        }

        #okInfo1 .okInfoClose1 {
            width: 31px;
            height: 31px;
            background: url(/res/image/close1.png) no-repeat 0 0;
            background-size: 100% 100%;
            position: absolute;
            top: 24%;
            right: 9%;
        }

        #okInfo1 .infoxiaoxi1 {
            width: 60%;
            margin: 28vh auto  !important;
            margin: 200px auto;
        }

        #okInfo1 .okInfoB1 {
            width: 62%;
            height: 6vh!important;
            height: 40px;
            background: url(/res/image/farm/queding.png) no-repeat 0 0;
            background-size: 100% 100%;
            margin: -18vh auto  !important;
            margin: 304px auto;
        }
        /*----确定提示框------*/
        

     
    </style>
</head>
<body>
<div id="page">
    <div id="gonggao" style="background: transparent"><span><img src="/res/images/laba.png">点击查看完整公告</span><div>
            <marquee  scrollAmount=5>
               哈巴狗2017最新公告:{{topnotice}}
            </marquee>
        </div></div>
    <div class="new_content">
        <div class="new_head">
            <img src="{{image}}" class="pesron"/>
            <ul>
                <li class="li1"><img src="/res/images/xz.png"><span>{{account}}</span><img src="/res/images/xz.png"></li>
                <li class="li2"><span><img src="/res/images/g1.png"><em class="em1">{{bone}}</em></span><span class="span2"><img src="/res/images/g2.png"><em class="em2">{{total}}</em></span></li>
            </ul>
            <div>
                <label id="myFriend"><img src="/res/images/myFriend.png"></label>
                <label class="myWareHouse"><img src="/res/images/myWareHouse.png"></label>
            </div>
        </div>
        <div class="back"><a href="/user/user/home"><span class="back_span1"><img src="/res/images/fh.png"></span></a><span class="back_span2"><img src="/res/images/sx.png"></span></div>
        <div class="new_gc">
            <div class="new_gcn">
                <input type="hidden" id="djyy" value="{{yy}}"/>
                <input type="hidden" id="zyyy" value="{{zyyy}}"/>
                <input type="hidden" id="zy_zl" value="{{level}}"/>
                <ul>
                    <!-- <li>
                         <img src="/res/images/01.gif" style="left:10px;bottom:20px;">
                         <label></label>
                         <span class="paopao"></span>
                     </li>-->
                    {{#each landinfo}}
                    <li {{#if type== 1}}class="b2" {{#end}}>
                        <label></label>
                        <span class="li_click"></span>
                        {{#if pop == 1}}
                        <span class="paopao"></span>
                        {{#end}}
                        {{#if dog == 1}}
                        <img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />
                        {{#end}}
                        {{#if dog == 2}}
                        <img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />
                        <img id="moveimg" class="right2left" src="/res/image/farm/01.gif" />
                        {{#end}}
                        {{#if dog == 3}}
                        <img id="moveimg" class="left2right" src="/res/image/farm/00.gif" />
                        <img id="moveimg" class="right2left" src="/res/image/farm/01.gif" />
                        <img id="moveimg" class="left2right left2right1" src="/res/image/farm/00.gif" />
                        {{#end}}
                        <div class="dogText">
                            <div class="totalNum">小狗总数:<em class="totalNumA"></em></div>
                            <div class="todayNum">当天狗仔:<em class="todayNumA"></em></div>
                            <div class="oldNum">历史狗仔:<em class="oldNumA"></em></div>
                        </div>
                        {{#if dog==0}}
                        <div class="kaigouChang" data-flag="{{dog}}"></div>
                        {{#end}}
                        {{#if dog!=0}}
                        <div class="zengyangOpacity" zengyang-flag="{{dog}}"></div>
                        {{#end}}
                        {{#if dog!=0}}
                        <div class="weiyangOpacity"></div>
                        {{#end}}
                        {{#if dog!=0}}
                        <div class="shouhuo"></div>
                        {{#end}}
                        <div class="jiahao"></div>
                    </li>
                    {{#end}}

                    <!--<li class="b2"><label></label></li>-->
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom1">
        <span class="mu1" alt="1"><img src="/res/images/m1.png"><b>开狗场</b></span>
        <span class="mu2" alt="2"><img src="/res/images/m2.png"><b>增养</b></span>
        <span class="mu3" alt="3"><img src="/res/images/m3.png"><b>驯化狗崽</b></span>
        <span class="mu4" alt="4"><img src="/res/images/m4.png"><b>收获</b></span>
    </div>
    <!--饲养员-->

    <div id="drag" class="feederLever{{feederlevel}}"></div>

    <!--公告-->
    <div id="noticeBox">
        <span class="close13"></span>
        <ul>
            {{#each notice}}
            <li>
                <div class="top">
                    <span class="text">{{title}}</span>
                    <span class="time">{{sendtime}}</span>
                </div>
                <div class="contentBox">
                    <span class="num">{{id}}</span>
                    <p class="totalText">{{content}}</p>
                </div>
            </li>
            {{#end}}
        </ul>
    </div>
    <!--公告-->
    <!--好友列表-->
    <div id="friendList">
        <span class="friendListClose"></span>
        <ul>
            {{#each friend}}
            <!--<li>
                <div class="top">
                    <img src="../../res/image/index/face/1.png"/>
                    <div class="vipBox">
                        <span class="VipName">{{account}}</span>
                        <span class="num" style="font-size: 12px">{{bone}}</span>
                    </div>
                    <a href="/user/user/visitFriend/{{id}}"><div class="btn" style="width: 25%">去拜访</div></a>
                </div>
                <div class="bottom">
                    <span class="name" style="width: 48px;margin-right: 0px;margin-left: -10px;">{{name}}</span>
                    <span class="phone">{{phone}}</span>
                </div>
            </li>-->
            <!--<li style="background:#f7edbd;">
                <div style="overflow:hidden;padding:5px;">
                    <span style="display:block;float:left;margin-right:5px;"><img src="/res/image/index/face/1.png" style="width:50px;height:50px;"></span>
                    <p style="float:left;">{{account}}<span style="position:relative;border-top:solid 2px #6e5118;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 10px 0px 30px;line-height:25px;"><img src="/res/images/g2.png" style="width:30px;height:30px;position:absolute;left:-5px;top:-5px;">{{bone}}</span></p>
                    <a href="/user/user/visitFriend/{{id}}" style="display:block;float:right;background:url(http://www.habagou.com/res/image/farm/btn.png) no-repeat 0px 0px;width:50px;height:30px;line-height:30px;text-align:center;color:#fff;background-size:100% 100%">去拜访</a>
                </div>
                <div style="border-top:solid 2px #fff;overflow:hidden;padding:10px 0px;">
                    <span style="color:#fff;text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;-webkit-text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;float:left;line-height:25px;padding-left:10px;">个人信息</span>
                    <span style="position:relative;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:10px;">{{name}}</span>
                    <span style="position:relative;background:url(/res/images/tel.png) no-repeat 5px center #e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:10px;background-size:auto 100%;padding-left:30px;">{{phone}}</span>
                </div>
            </li>-->
            <li style="background:#f7edbd;border:solid 2px #6e5118;border-radius:10px 0px 10px 0px;overflow:hidden;">
                <div style="overflow:hidden;padding:5px;">
                    <span style="display:block;float:left;margin-right:5px;"><img src="{{image}}" style="width:50px;height:50px;"></span>
                    <p style="float:left;">{{account}}<span style="position:relative;border-top:solid 2px #6e5118;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 10px 0px 30px;line-height:25px;"><img src="/res/images/g2.png" style="width:30px;height:30px;position:absolute;left:-5px;top:-5px;">{{bone}}</span></p>
                    <a href="/user/user/visitFriend/{{id}}" style="display:block;float:right;background:url(/res/image/farm/btn.png) no-repeat 0px 0px;width:50px;height:30px;line-height:30px;text-align:center;color:#fff;background-size:100% 100%">去拜访</a>
                </div>
                <div style="border-top:solid 2px #fff;overflow:hidden;padding:10px 0px;">
                    <span style="color:#fff;text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;-webkit-text-shadow:#dc8a09 1px 0 0,#dc8a09 0 1px 0,#dc8a09 -1px 0 0,#dc8a09 0 -1px 0;float:left;line-height:25px;padding-left:3px;">个人信息</span>
                    <span style="position:relative;background:#e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px;line-height:25px;float:left;margin-left:5px;">{{name}}</span>
                    <span style="position:relative;background:url(/res/images/tel.png) no-repeat 5px center #e1b649;display:block;border-radius:12px;height:25px;padding:0px 5px 0px 20px;line-height:25px;float:left;margin-left:5px;background-size:auto 100%;">{{phone}}</span>
                </div>
            </li>
            {{#end}}

        </ul>
        <div class="bottomBtn">
            <span class="onekeyclean"></span>
            <span class="yaoqing"></span>
        </div>
    </div>

    <!--我的仓库-->
    <div id="wareHouse">
        <span class="wareHouseClose"></span>
        <ul>
            <li>
                <img src="../../res/image/index/face/xiaogou.png"/>
                <div class="vipBox">
                    <span class="type">小狗</span>
                    <span id="xiaogoushu" class="num">{{warehouse}}</span>
                </div>
                <div id="zengyangBtn" class="btn">去增养</div>
            </li>
            <li>
                <img src="../../res/image/index/face/dogFace.png"/>
                <div class="vipBox">
                    <span class="type">狗崽</span>
                    <span id="bone" class="num">{{bone}}</span>
                </div>
                <div id="wYangBtn" class="btn">去喂食</div>
            </li>
            <li>
                <img id="langanlevel" src="../../res/image/index/face/langan{{@root.level}}.png"/>
                <div class="vipBox">
                    <span class="type" style="margin-left: 5px;">大栅栏</span>
                    <p class="text">每邀请10位好友可 获得/升级</p>
                </div>
                <a href="/user/user/register"><div class="btn">获得</div></a>
            </li>
            <li>
                <img src="../../res/image/index/face/shaoba.png"/>
                <div class="vipBox">
                    <span class="type" style="margin-left: 5px;">一键清洗</span>
                    <p class="text">清洗所有好友小友期限30天</p>
                </div>
                <input type="button" name="buy"  id="buy" value="购买" class="btn" />
                <!--<div id="buy" class="btn">购买</div>-->
            </li>
            <li>
                <img src="../../res/image/index/face/siyangyuan.png"/>
                <div class="vipBox">
                    <span id="feederlevel" class="type" feederlevel = "{{feederlevel}}" style="margin-left: 5px;">饲养员Lv.<em id="dengji">{{feederlevel}}</em></span>
                    <p class="text">等级越高，小狗的等级越高</p>
                </div>
                <div id="shengji" class="btn">升级</div>
            </li>
        </ul>
    </div>
    <!--输入喂养-->
    <div id="feed" class="weiyang" style="display: none;">
        <span class="close12"></span>
        <span class="text">输入喂食数量</span>
        <input type="number" name="numner" class="numberFeed" />
        <div class="pan"></div>
        <div id="weiyangBtn" class="queding"></div>
    </div>
    <!--输入增养-->
    <div id="feed" class="zengyang" style="display: none;">
        <span class="close12"></span>
        <span class="text">输入增养数量</span>
        <input type="number" name="numner" class="numberAdd" />
        <div class="pan"></div>
        <div id="addyang" class="queding"></div>
    </div>
    <!--确定提示-->
    <div id="okInfo">
        <span class="okInfoClose"></span>
        <p class="infoxiaoxi"></p>
        <div class="okInfoB"></div>
    </div>

    <!--确定提示框-->
    <div id="okInfo1" >
        <span class="okInfoClose1"></span>
        <p class="infoxiaoxi1"></p>
        <div class="okInfoB1"></div>
    </div>
    <!--确定提示框-->
    <!--提示-->
    <div id="infoBox">
        <span class="close8"></span>
        <div class="infoText">提示信息</div>
    </div>
    <!--收获-->
    <div id="harvest">
        <span class="close10"></span>
        <p class="text">恭喜收获<span class="harvestNum"></span>条小狗</p>
    </div>
    <!--遮罩-->
    <div id="screen"></div>
    <div id="screen1"></div>
   
    <audio audio-flag ="{{status}}" id="audio" src="/res/image/farm/gougou.mp3" preload="auto"  style="position: absolute;top: 2px;left: 2px">
       <!-- Your browser does not support the audio element.-->
    </audio>

    <div id="visitFriend"></div>

</div>

</body>
</html>