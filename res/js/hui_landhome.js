
$(function(){
	bjui_config.error_report=function(userexception, src, param, msg, fnFail){
		///fnFail();
		$('#infoBox').show();
		$('#infoBox').find('.infoText').text(msg);
	}
	//锁屏div
	var screen = $('#screen');
	
	//返回上一页
	$('.back').on('touchend',function(){
		window.location.href="/user/user/home"; 
	})
	
	
	//刷新
	$('.refresh').on('touchend',function(){
		location.reload();
	})
	
	//获得跳转
	$('#huode').on('click',function(){
		window.location.href="/user/user/register"; 
	})
	
	//邀请好友
	$('.yaoqing').on('click',function(){
		$('#visitFriend').show();
		lock(screen);
		$('#friendList').hide()
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

					var numA = $('.numA').text();
					var newNumA = parseFloat(numA) + parseFloat(data);
					$('.numA').text(newNumA.toFixed(2));
				});
	})

	$('#visitFriend').on('click',function(){
		$('#visitFriend').hide();
		unlock(screen)
	})

	$('#screen').on('click',function(){
		$('#visitFriend').hide();
		unlock(screen)
	})
	//点击显示公告
	$('#gonggao').on('touchend',function(){
		$('#noticeBox').show();
		
		//公告列表
		var gonggaolist = $('#noticeBox ul').find('li');
		gonggaolist.each(function(i){
			var index = i;
			var top = $(gonggaolist[index]).find('.top');
			top.on('click',function(){
				$(gonggaolist[index]).find('.contentBox').toggle();
			})
		})
		
		lock(screen);
	})
	//点击关闭公告
	$('.close13').on('click',function(){
	    $('#noticeBox').hide();
	    unlock(screen);
	})
	
	//关闭提示信息
	$('.close8').on('click',function(){
		$('#infoBox').hide();
	})
	
	//关闭提示信息
	$('.okInfoClose').on('click',function(){
		$('#okInfo').hide();
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
    
    //弹出好友列表
	$('.myFrieng').on('click',function(){
		$('#friendList').show();
		lock(screen);
	})
	//关闭增养
	$('.friendListClose').on('click',function(){
		$('#friendList').hide();
		unlock(screen);
	})
	

	//关闭我的仓库
	$('.wareHouseClose').on('click',function(){
		$('#wareHouse').hide();
		unlock(screen);
	})
	
	//弹出我的仓库
	$('.myWareHouse').on('click',function(){
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
					buyBtn.addClass('btn1')
					buyBtn.text('剩余'+day+'天');
				}else{

					buyBtn.removeClass('btn1')
					buyBtn.on('click',function(){
						BizCall(
						"user.User.buySuperclean",
						{
							
						},
						function(data){
							buyBtn.addClass('btn1')
					        buyBtn.text('剩余'+data+'天');
					        $('#infoBox').show();
							$('#infoBox').find('.infoText').text('购买成功');
							day = 0;
						});
					})
				}
		});
	})


	//开发新狗场
	$('.openFarm').on('click',function(e){
		e.stopPropagation()
		
		var list = $('.dogFarm ul').find('li');
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
						//点击有阴影接口，index为索引
//						var newarray = arrDog[index];
//						alert(newarray)




						//alert(index);
                        //createDog(40,40,35,img1);
                        
                       
                        
						BizCall(
							"land.Land.create",
							{
								"index"	:  index
							},
							function(data){
								///rediect('home.html');
								var html = '<img class="img img1" src="../../res/image/farm/dog/0.png" />'
	                            $(list[index]).append(html);
								var img = $(list[index]).find('.img');
								move1(img);
								var listimg = $('.dogFarm ul li').find('.kaigouChang');
								listimg.each(function(index){
									$(listimg[index]).hide();
								})
						});
					})
				}
			});
		})
		
		return false;
		
	})
	
	//增养
	$('.add').on('click',function(){
		
		var list = $('.dogFarm ul').find('li');
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
												imgHtml = '<img id="moveimg" class="left2right1" src="../../res/image/farm/00.gif" />'
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
	$('.feed').on('click',function(){
		
		var list = $('.dogFarm ul').find('li');
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
												imgHtml = '<img id="moveimg" class="left2right1" src="../../res/image/farm/00.gif" />'
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
										var numA = $('.numA').text();
										var newNumA = numA - feednum;
										$('.numA').text(newNumA.toFixed(2));

										//总共加
										var numB = $('.numB').text();
										//alert(numB)
										//alert(feednum)
										var newNumB = parseFloat(numB) + parseInt(feednum);
										//alert(newNumB)
										$('.numB').text(newNumB.toFixed(2));
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
	$('.harvest').on('click',function(){
		
		var list = $('.dogFarm ul').find('li');
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
						dogHtml = '<img id="moveimg" class="right2left" src="../../res/image/farm/00.gif" />'
						$(list[index]).append(dogHtml);
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
					},
					function(){
									 ////
					});
			})
		})
	})

	//狗场list
	var list = $('.dogFarm ul').find('li');
	list.each(function(i){
	var index = i
	//显示隐藏文本
	var flag = $(this).attr('dog-flag');
	var zengyang_flag = $(list[index]).find('.zengyangOpacity').attr('zengyang-flag');
	if( flag != 0  ){
		$(this).on('click',function(e){
			e.stopPropagation();
			BizCall(
			"land.Land.popup",
			{
				"index"	:  index
			},
			function(data){
				$(list[index]).find('.totalNumA').text(data['wealth']);
				$(list[index]).find('.todayNumA').text(data['history']);
				$(list[index]).find('.oldNumA').text(data['today']);
				$(list[index]).find('.dogText').toggle();
				var li = $(list[index]).find('.dogText').parent().siblings()
				li.each(function(i){
					var index2 = i;
					$(li[index2]).find('.dogText').hide();
				})

			});
		})
	}

		
	});


	//狗场list
	var list = $('.dogFarm ul').find('li');
	list.each(function(i){
		var index = i

		//隐藏泡泡
		var yinxiao_flag =$(list[index]).find("#audio").attr('audio-flag');
		$(this).find('.paopaoDog').on('click',function(e){
			e.stopPropagation();
			yinxiao_flag=1;
			if( yinxiao_flag == 1){
				$("#audio").get(0).play();

			}

			$(list[index]).find('.paopaoDog').animate({'top':6,'opacity':0},500);
			//泡泡索引
			//alert(index);
			BizCall(
					"land.Land.hitPop",
					{
						"index"	:  index
					},
					function(data){
						//alert(data)
						//返回泡泡的值，然后修改骨头的值加
						var NumSpan = $('.numA');
						var numA = NumSpan.text();
						var addNum = parseFloat(numA) + parseFloat(data);
						var num = addNum.toFixed(2)
						NumSpan.text(num);
						var jiahao = $(list[index]).find('.jiahao');
						jiahao.text(parseFloat(data).toFixed(2)).show();
						jiahao.animate({'top':6,'opacity':0},500);
					},
					function(){

					});
		});

	});
	
	//去增养
	$('#zengyangBtn').on('click',function(){
		$('#wareHouse').hide();
		unlock(screen);
		
		var list = $('.dogFarm ul').find('li');
		list.each(function(i){
			var index = i

			//关闭兄弟阴影
			$(list[index]).find('.kaigouChang').hide();
			$(list[index]).find('.weiyangOpacity').hide();
			$(list[index]).find('.shouhuo').hide();
			
			$(list[index]).find('.zengyangOpacity').toggle();
			$(list[index]).find('.zengyangOpacity').off()
			$(list[index]).find('.zengyangOpacity').on('click',function(e){
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
				$('#addyang').on('click',function(){

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
												imgHtml = '<img id="moveimg" class="left2right1" src="../../res/image/farm/00.gif" />'
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
	$('#wYangBtn').on('click',function(){
		$('#wareHouse').hide();
		unlock(screen);
		
		var list = $('.dogFarm ul').find('li');
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
				weiyangBtn.on('click',function(){
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
												imgHtml = '<img id="moveimg" class="left2right1" src="../../res/image/farm/00.gif" />'
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
										var numA = $('.numA').text();
										var newNumA = parseFloat(numA) - parseInt(feednum);;
										$('.numA').text(newNumA.toFixed(2));

										//总共加
										var numB = $('.numB').text();
										//alert(numB)
										//alert(feednum)
										var newNumB = parseFloat(numB) + parseInt(feednum);
										//alert(newNumB)
										$('.numB').text(newNumB.toFixed(2));
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
})
