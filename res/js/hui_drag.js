    (function($) {
	        $.fn.extend({
	            AutoSize: function() {
	                var element = $(this);
	                auto();
	                function auto() {
	                    var width = $(window).width(),
	                        height = $(window).height();
	                    $("html").css("font-size", width / 15);
	                    $(element).width(width).height(height);
	                };
	                $(window).resize(auto);
	            }
	        });
	})(jQuery);
	
	
	$(function(){
	        $("body").AutoSize();
	 })
	
	$(function(){ 
	    var isdrag=false;   
	    var NowLeft,disX;
	    var NowTop,disY; 
	
	    var oDiv2 = document.getElementById("drag");
	
	    oDiv2.addEventListener('touchstart',thismousedown);  
	    oDiv2.addEventListener('touchend',thismouseup);  
	    oDiv2.addEventListener('touchmove',thismousemove);  
	
	    function thismousedown(e){   
	       isdrag = true;   
	       NowLeft = parseInt(oDiv2.style.left+0);  
	       NowTop = parseInt(oDiv2.style.top+0);   
	       disX = e.touches[0].pageX;   
	       disY = e.touches[0].pageY;
	       return false;
	    }
	
	    function thismousemove(e){   
	      if (isdrag){
	
	       oDiv2.style.left = NowLeft + e.touches[0].pageX - disX + 'px'; 
	       oDiv2.style.top = NowTop + e.touches[0].pageY - disY + 'px';
	
	       return false;   
	       }   
	    }   
	
	    function thismouseup(){  
	        isdrag = false;  
	    };
	}); 