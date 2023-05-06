(function(){
	/* 控制左右按钮显示 */
	jQuery(".fullSlide").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",0.5) },function(){ jQuery(this).find(".prev,.next").fadeOut() });

	/* 调用SuperSlide */
	jQuery(".fullSlide").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold",  autoPlay:true, autoPage:true, trigger:"click",
		startFun:function(i){
			var curLi = jQuery(".fullSlide .bd li").eq(i); /* 当前大图的li */
			if( !!curLi.attr("_src") ){
				curLi.css("background-image",curLi.attr("_src")).removeAttr("_src") /* 将_src地址赋予li背景，然后删除_src */
			}
		}
	});	
	
	//图片延迟加载
	$("img.thumb").lazyload({effect: "fadeIn"});

    //排行榜
    $(".rank-item").mouseover(function(){
    	 $(this).parents(".list-default").find(".rank-item-active").removeClass("rank-item-active");
    	 $(this).addClass("rank-item-active");   	
    });
    
    
    
    //热门主播 分类获取

    $(".tab-tit a").bind("click",function(){
    	 var datatarget=$(this).attr("data-target-id");
         var that=$(this);
    	 $(this).parent().siblings().find("a").removeClass("active");
    	 $(this).addClass("active");
    	 
    	 $(this).parents("div.hd").siblings(".bd").find("div.tab-content").hide();
    	 
    	 //判断数据是否存在
    	 if($("[data-content="+datatarget+"]").length>0){
    	 	$("[data-content="+datatarget+"]").show();   
    	 }else{
    	 	 if(datatarget=='live'){ //正在直播
 
    	 	}else if(datatarget=='xiuchang'){ //秀场直播
    	 		$.get("/index.php/Index/xiuchang",{sid:datatarget},function(data,t){					  
						that.parents("div.hd").siblings(".bd").append(data);
						$("img.thumb").lazyload({effect: "fadeIn"});
					})  
    	 	}else if(datatarget=='youxi'){ //游戏直播
    	 		$.get("/index.php/Index/youxi",{sid:datatarget},function(data,t){					  
						that.parents("div.hd").siblings(".bd").append(data);
						$("img.thumb").lazyload({effect: "fadeIn"});
					})  
    	 	}else if(datatarget=='concern'){ //我关注的
    	 		$.get("/index.php/Index/concern",{sid:datatarget},function(data,t){					  
						that.parents("div.hd").siblings(".bd").append(data);
						$("img.thumb").lazyload({effect: "fadeIn"});
					})  
    	 		
    	 	}else if(datatarget=='seen'){ //我看过的
    	 		$.get("/index.php/Index/seen",{sid:datatarget},function(data,t){					  
						that.parents("div.hd").siblings(".bd").append(data);
						$("img.thumb").lazyload({effect: "fadeIn"});
					}) 
    	 	}else { // 主播分类   	 		
				$.get("/index.php/Index/listEmceeByGrade2",{sid:datatarget},function(data,t){					  
						that.parents("div.hd").siblings(".bd").append(data);
						$("img.thumb").lazyload({effect: "fadeIn"});
					})   	 		  	 		
    	 	}

    	 }

    	
    });
	
})()
