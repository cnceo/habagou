<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>转赠记录</title>
	<meta name="screen-orientation" content="portrait">	<!-- uc强制竖屏 -->
	<meta name="browsermode" content="application">		<!-- UC应用模式 -->
	<meta name="full-screen" content="yes">				<!-- UC强制全屏 -->
	<meta name="x5-orientation" content="portrait">		<!-- QQ强制竖屏 -->
	<meta name="x5-fullscreen" content="true">			<!-- QQ强制全屏 -->
	<meta name="x5-page-mode" content="app">			<!-- QQ应用模式 -->

	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<link rel="stylesheet" type="text/css" href="/res/css/css1.css?" />
	<script src="/res/js/jquery-1.11.2.min.js" charset="utf-8"></script>
	<script src="/res/js/highcharts.js" charset="utf-8"></script>
	<script>
		$(document).ready(function () {
			$(".tjt_div").css("height", $("#page").height() - $(".bottom").height() - $(".new_content").height() - 100);
		});
	</script>
</head>
<body>
<div id="page">
	<div id="top">
		<span><a href="/user/user/home">返回</a></span>
		<label><img src="/res/images/tx.png"></label>
	</div>
	<div id="reg_div" class="box-sizing">
		<div class="reg box-sizing margin75">
			<ul class="reg_button">
				<li><a href="/user/user/register">开发新狗场</a></li>
				<li><a href="/user/user/sale">转赠小狗</a></li>
				<li class="on"><a href="/user/user/salelist">转赠记录</a></li>
			</ul>
			<div class="salelist box-sizing">
				<ul class="salelist_ul flex"><li class="box-sizing on"><a href="/user/user/buylist">购买记录</a></li><li class="box-sizing"><a href="/user/user/salelist">转赠记录</a></li></ul>
				<div class="border box-sizing">
					<dl class="flex on">
						<dt>接收人</dt><dd>数量</dd><dd>时间</dd><dt>状态</dt>
					</dl>
					{{#each datas}}
					<dl class="flex">
						<dt>{{account}}</dt>
						<dd>{{num}}</dd>

						<dd>{{launchtime}}</dd>
						<dt>
							{{#if status==1}}
							<a href="/user/user/updataStatus/{{id}}/0/4">取消交易</a>
							{{#end}}
							{{#if status==2}}
							<a href="/user/user/updataStatus/{{id}}/0/3">确认收米</a>
							{{#end}}
							{{#if status==3}}
							交易完成
							{{#end}}
							{{#if status==4}}
							交易取消
							{{#end}}
						</dt>
					</dl>
					{{#end}}
				</div>
				<div class="t_bg"><img src="/res/images/t_bg.png"></div>
			</div>
			<div class="page box flex"><a href="{{prev_page}}" class="p_left" title="上一页"></a><span>{{pageindex}}/{{totalpage}}</span><a href="{{next_page}}" class="p_right" title="下一页"></a></div>
		</div>
	</div>

</div>
</body>
</html>