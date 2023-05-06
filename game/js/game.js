function showTurntable()
{
	if(uid<0)
	{
		UAC.openUAC(0);
		return 0;
	}
	
	$("#gamePoP").html("<img onclick='closeTurntable()'  src='/Public/images/closeGame.png' style='width:60px;position:absolute;right: 0;bottom:0;z-index:9999' class='closeGame'/><IFRAME style='width:550px;height:550px;z-index:999;border: none;' src='/game/turntable/index.html'></IFRAME>");
}



function showTurntableCoin()
{
	$("#gamePoP").html("<img onclick='closeTurntable()'  src='/Public/images/closeGame.png' style='width:60px;position:absolute;right: 0;bottom:0;z-index:9999' class='closeGame'/><IFRAME style='width:550px;height:550px;z-index:999;border: none;' src='/game/turntable_coin/index.html'></IFRAME>");
}

function showTurntableCharge(num)
{
	if(num==0)
	{
		_alert("没有抽奖次数了，快去领取吧",3);
		return 0;
	}
	$("#gamePoP").html("<img onclick='closeTurntable()'  src='/Public/images/closeGame.png' style='width:60px;position:absolute;right: 0;bottom:0;z-index:9999' class='closeGame'/><IFRAME style='width:550px;height:550px;z-index:999;border: none;' src='/game/turntable_charge/index.html'></IFRAME>");
}

function closeTurntable()
{
	$("#gamePoP").html("");
}