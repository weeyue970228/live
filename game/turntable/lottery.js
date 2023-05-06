
function getSwf(movieName){
    if(window.document[movieName]){
        return window.document[movieName];   
    }else if(navigator.appName.indexOf("Microsoft") == -1){
        if(document.embeds && document.embeds[movieName])   
            return document.embeds[movieName];
    }else{
        return document.getElementById(movieName);
    }   
}

award = '';//中奖名称
award_id = 0;//中奖id
award_img = "";//中奖图片

function start_lottery(){
    var res = getSwf('lottery').start_lottery();
    if(res != 1) return;//-1为加载资源失败；0为已在抽奖中
    $.ajax({
        url: '/index.php/Game/get_turntable',
        type: "post",
        data:null,
        dataType: "json",
        timeout: 20000,
        cache: false,
        beforeSend: function(){// 提交之前
        },
        error: function(){//出错
            getSwf('lottery').reset_lottery();//取消“正中抽奖中”标志，则可重新抽奖
            alert('服务端出错！');
        },
        success: function(res){//成功
        	if(typeof(res.error)!='undefined'){
        		window.parent._alert(res.error,3);
        		getSwf('lottery').reset_lottery();
        		return 0;
        	}
            if(typeof(res.award_id)!='undefined'){
                award = res.award_name;//得到奖品名称
                award_id = res.award_id;
                award_img = res.giftIcon_25;
                getSwf('lottery').show_lottery();//展现转动效果
                setTimeout(function(){//得到抽奖结果，等5秒钟转动效果才显示结果
                    getSwf('lottery').stop_lottery(res.award_id);
                },5000);
            }else{
                getSwf('lottery').reset_lottery();//取消“正中抽奖中”标志，则可重新抽奖
                alert('抽奖出错！');
            }
        }
    });
}
//结束后调用的函数
function lottery_result(){
	window.parent._alert('恭喜您获得：' + award,5);
	var oldNum = $("#Stock"+award_id,parent.document).text();
	
	if(oldNum!=undefined && oldNum != null && oldNum!= "" )
	{
		var newNum = parseInt(oldNum)+1;
		$("#Stock"+award_id,parent.document).text(newNum);		
	}
	else
	{
		//alert("追加一个"+award);
		var str = '<li onclick="GiftCtrl.choiceGift(\'Stock'+award_id+'\',\''+award+'\');" class="gift_li"><img src="'+award_img+'" width="42" height="42"><span>'+award+'</span><div class="h_dou" style="display: none; left: 275px; top: 520px;"> 剩余<i id="Stock'+award_id+'">1</i>个</div></li>'
		
		$("#stock_data",parent.document).append(str);
		
		//parent.location.reload();
	}

//    alert('恭喜您获得：' + award);
    //
}