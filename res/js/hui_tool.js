//锁屏
function lock(obj){
	var W = $(window).width() + $(document).scrollLeft();
	var H = $(window).height() + $(document).scrollTop();
	obj.css({"width":W,"height":H});
	obj.show();
}
//解锁
function unlock(obj){
	var W = $(window).width() + $(document).scrollLeft();
	var H = $(window).height() + $(document).scrollTop();
	obj.css({"width":W,"height":H});
	obj.hide();
}


