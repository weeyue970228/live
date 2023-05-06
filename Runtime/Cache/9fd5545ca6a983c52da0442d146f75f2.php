<?php if (!defined('THINK_PATH')) exit();?>﻿
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo ($userinfo['nickname']); ?>的个人直播间 - 美女主播视频聊天室_视频K歌 - <?php echo ($sitename); ?></title>

<meta name="keywords" content="<?php echo ($metakeyword); ?>"/>

<meta name="description" content="<?php echo ($metadesp); ?>" />

<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />

<meta http-equiv="pragma" content="no-cache"/>

<meta http-equiv="cache-control" content="no-cache"/>

<meta http-equiv="expires" content="0"/> 

<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/common_test.css" type="text/css" />

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/jquery.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/common.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/personal.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/interfacetest.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/chatRoom.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/swfobject.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/swfobjectforegg.js"></script>

<?php if($userinfo2['Daoju8']=='y'&&$userinfo2['Daoju8expire']>time()){?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(8,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju7']=='y'&&$userinfo2['Daoju7expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(7,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju6']=='y'&&$userinfo2['Daoju6expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(6,'豪华')",8000);});   

</script>

<?php }elseif($userinfo2['Daoju5']=='y'&&$userinfo2['Daoju5expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(5,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju4']=='y'&&$userinfo2['Daoju4expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(4,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju3']=='y'&&$userinfo2['Daoju3expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(3,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju2']=='y'&&$userinfo2['Daoju2expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(2,'豪华')",8000);});

</script>

<?php }elseif($userinfo2['Daoju1']=='y'&&$userinfo2['Daoju1expire']>time()){ ?>

<script>

$(document).ready(function(){setTimeout("GiftCtrl.kaichang(1,'豪华')",8000);});

</script>

<?php }?>

<script type="text/javascript" language="javascript">

document.domain="<?php echo ($domainroot); ?>";

var speaktxt='| 输入文字不超过50个字。每次广播花费500秀币';

var _show={"isHD":0,"enterChat":0,"emceeId":"<?php echo $userinfo['id']; ?>","fps":"<?php echo $userinfo['fps'];?>","cdnl":"<?php echo $userinfo['cdnl'];?>","cdn":"<?php echo $userinfo['cdn'];?>","zddk":"<?php echo $userinfo['zddk'];?>","pz":"<?php echo $userinfo['pz']?>","zjg":"<?php echo $userinfo['zjg']?>","emceeLevel":1,"goodNum":<?php echo $userinfo['curroomnum']; ?>,"emceeNick":"<?php echo $userinfo['nickname']; ?>","oldseatnum":"0","songPrice":"1500","offline":0,"roomId":"<?php echo $userinfo['curroomnum']; ?>","titlesUrl":"","titlesLength":"4","bgimg":"<?php echo $userinfo['bgimg']; ?>","width":"<?php echo $userinfo['width']?>","height":"<?php echo $userinfo['height']?>"};



var _game={"eggtimer":0,"eggstatus":1,"egginterval":20,"eggstart":10,"eggclosed":1};



function sumbitCloseReason(){

	var reason = $("#usuallyReason").val();

	if(reason == "otherReason"){

		reason = $("#otherReason").val();

	}

	

	//$.ajax({

	    //type: "POST",

	    //url: "__APP__/User/exitroom/",

	    //data: "uid="+_show.emceeId+"&roomid="+_show.goodNum,

	    //success: function(id){

		      ////$("#closeReasonDiv").hide();

		      ////JsInterface.endLiveShow(1);

		    //}

	//});

	

	Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"closeLive","reason":"' + reason + '"}');

	$("#closeReasonDiv").hide();

}



function checkReason(){

	 if($("#usuallyReason").val() == "otherReason"){

		    //$("#otherReason").attr("disabled",false);

		    //$("#usuallyReason").attr("disabled",true);

		    $("#closeReasonDiv").attr("class","c_reason1");

	 }else{

		    //$("#otherReason").attr("disabled",true);

		    //$("#usuallyReason").attr("disabled",false);

		    $("#closeReasonDiv").attr("class","c_reason");

	 }

}

function get_url(){

	var url=window.location.href; 

	return url;

}



</script>

<style type="text/css">

<!--

body {



 background-color:#f2f2f2;

}

-->



.headjoy { width: 100%;

  margin: 0 auto;

  height: 66px;

  background: #fff;

  border-top: 4px solid #2ac481;

  box-shadow: 0 2px 2px #ddd;

  border-bottom: 0px solid #F44103;

}



.joytip {

  width: 1130px;

  margin: 0 auto;

  height: 55px;

  padding-top: 0px;

}



.vlogo {

  float: left;

  margin-top: -4px;

  width: 280px;

  height: 65px;

  zoom: 1;

  border-top: 4px solid #fe32b8;

}



a {

  color: #333;

  text-decoration: none;

  outline: none;

}



.vtab {

  float: left;

  padding-left: 0px;

  margin-right: 20px;

  border-top: 4px solid #ae21ff;

  margin-top: -4px;

}



.vtab a {

  display: inline-block;

  width: 80px;

  height: 50px;

  text-align: center;

  line-height: 50px;

  float: left;

  margin-left: -1px;

  font-size: 16px;

  color: #666;

  font-family: 微软雅黑;

  border-top: 12px solid #ffffff;

}



.vtab a.on {

  color: #ae21ff;

  text-decoration: none;

  display: block;

  width: 70px;

  height: 50px;

  background: #fff;

  border-top: 12px solid #ae21ff;

}



.vtab a:hover{  

  color: #ae21ff;

  text-decoration: none;

  border-top: 12px solid #ae21ff;}



.others {

  float: right;

  padding-top: 25px;

  padding-right: 20px;

}



.others li {

  border-radius: 3px;

  font-size: 12px;

  line-height: 30px;

  padding: 0 6px;

}

  

.others li a{

  color: #666;

  font-size: 14px;

  font-family: "微软雅黑";

  }



#globalsearchform input{

  font-family: Arial,sans-serif,\5b8b\4f53;

  font-size: 12px;

}



#globalsearchform a{

  position: absolute;

  right: -20px;

  top: 15px;

  background-position: 0 -2198px;

  height: 20px;

  width: 20px;

  background-image: url('http://img1.cache.netease.com/bobo/image/btn-sed072d3b32.png');

  background-repeat: no-repeat;

}













</style>

</head>

<body id="ex1">



<script> 

try { document.domain = '<?php echo ($domainroot); ?>'; }

catch(e){}

</script>

<script language="javascript" type="text/javascript" src="__PUBLIC__/js/logon-2.0.js" charset="utf-8"></script>

<script>

UAC.showUserInfo = function(data) {if(data.isLogin == "1"){window.location.reload();}}

</script>





<body>

		<script>
	try { 
		document.domain = '<?php echo ($domainroot); ?>';
	}catch(e){}
</script>
<script src="__PUBLIC__/js/logon-2.0.js"></script>
<script>
	UAC.showUserInfo = function(data) 
	{
		if(data.isLogin == "1")
		{
			window.location.reload();
		}
	}
</script>
<div class="header clear">
	<div class="contenter navbar">
		<div class=" logo fn_left">
			<a href="<?php echo ($siteurl); ?>" target="_blank"><img src="<?php echo ($sitelogo); ?>"></a>
		</div>
		<div class="nav_list fn_left">
			<ul>
				<li><a href="/index.php" target="_self" <?php if($_GET['_URL_'][0] == ''): ?>class="on"<?php endif; ?>>首页</a></li>
				<li><a href="/index.php/xiuchang/" target="_self" <?php if($_GET['_URL_'][0] == 'xiuchang'): ?>class="on"<?php endif; ?> >秀场</a></li>
				<li style="display:none;"><a href="/index.php/youxi/" target="_self" <?php if($_GET['_URL_'][0] == 'youxi'): ?>class="on"<?php endif; ?> >游戏</a></li>
				<li><a href="/index.php/order/" target="_self" <?php if($_GET['_URL_'][0] == 'order'): ?>class="on"<?php endif; ?> >排行榜</a></li>
				<li><a href="/index.php/User/toolItem/" target="_self" <?php if($_GET['_URL_'][1] == 'toolItem'): ?>class="on"<?php endif; ?> >商城</a></li>
				<li><a href="/index.php/Jiazu/" target="_self" <?php if($_GET['_URL_'][0] == 'Jiazu'): ?>class="on"<?php endif; ?> >家族</a></li>
				<li><a href="/index.php/emceeno/" target="_self" <?php if($_GET['_URL_'][0] == 'emceeno'): ?>class="on"<?php endif; ?>">靓号</a></li>
				<li><a href="/index.php/Xiazai/" target="_self" <?php if($_GET['_URL_'][0] == 'Xiazai'): ?>class="on"<?php endif; ?> >APP</a></li>
			</ul>

		</div>

		<div class="header_login fn_right others">

			
				<script language="javascript" type="text/javascript">
					$(function(){	
						setTimeout(function(){ 
							$.get("/index.php/Show/show_headerInfo/t/"+Math.random(),function(data){ $('.header_login').html(data);});
						}, 1000);
						setTimeout(function(){ 
							$.get("/index.php/Show/show_headerInfo2/t/"+Math.random(),function(data){ $('.setting').html(data);});
						}, 1500);
					});
				</script>

			

		</div>
		<div class="search-form fn_right">
			<form id="globalsearchform" method="get" action="" style="width:140px">
				<input type="text" id="roomnum" name="roomnum" placeholder="昵称/房号" value="" class="input" style="" onkeydown="FSubmit(event.keyCode||event.which);">
				<div href="" class="btn-search fn_right" id="searchBtn" onclick="searchroom();"></div>
				<div class="search-input" id="searchTips" style="position:absolute;top:0;left:0"></div>

			</form>
		</div>

	</div>

</div>











</div>

<div id="container" style="margin-bottom:30px;">

<div class="show_01" id="show_01"><a href="#" onClick="show_01();"></a></div>





<div class="show_right" id="show_right">

  	

     

    <div class="main_chat">

    <div class="chat-tip" id="xu_01">

      <div class="chatroom_area" id="chatroom_area">

	      <div class="chat_area">

	          <h2 class="play-t"><a class="link_right" href="/" target="_blank">返回大厅</a>

			<span id="lm4_1" class="on" onClick="turn(1,4,4);">公聊</span>

			<span id="lm4_2" onClick="turn(2,4,4);">礼物</span> 

            <span id="lm4_3" onClick="turn(3,4,4);">观众(<cite>0</cite>)</span> 

            <span id="lm4_4" onClick="turn(4,4,4);">排行榜</span> 

		</h2>

            

            

	        <div class="chat" id="content4_1">

	          <div class="room_notice" id="room_notice"><span id="room_public_notice"><a href="<?php if($userinfo['annlink'] == ''): ?>javascript://<?php else: echo ($userinfo['annlink']); endif; ?>" target="_blank">

			  <font color="#424542"><?php echo ($userinfo['announce']); ?> </font></a></span></div>

			  <div class="pubChatSet">富豪等级1以下的用户发言请不要超过10个字哦！</div>

	          <div id="upChat" class="chat_room chat_public">

	            <div class="chat_btn" style="display:none;"> 

					<span class="btn_clearMsg" onClick="Chat.clearChat('pulic');">清屏</span>

					<a class="screen_btn" href="javascript:void(0);" onClick="Chat.scrollChat();">

					   <cite id="scrollSign" class="on">滚屏</cite>

					</a>

				</div>

	            <div id="chat_hall" class="chat_hall"></div>

	          </div>

	          

	          

	          <p  id="chat_close" class="chat_close">房间公聊关闭</p>

	        </div>

		

			<div class="chat" id="content4_2" style="display:none;"><ul id="gift_history"></ul></div>

            <ul id="content4_3"  class="chat"><li id="loading_online"><img src="__PUBLIC__/images/loading.gif" /></li></ul>

            <div class="chat" id="content4_4" style="display:none;"><div class="my_top">







      <h2 class="play-t"> <span class="on" id="lm3_1" onClick="turn(1,3,3);">30天粉丝排行榜</span> <span id="lm3_2" onClick="turn(2,3,3);">超粉榜</span> <span id="lm3_3" onClick="turn(3,3,3);">本场榜</span> </h2>

      <ol class="top_board" id="content3_1">

        <li class="title"> <span class="t1">排名</span> <span class="t2">过去30天粉丝</span> <span class="t3">贡献值</span> </li>

        <?php if(is_array($monthfansrank)): $k = 0; $__LIST__ = $monthfansrank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i; $richlevel = getRichlevel($sub['spendcoin']); ?>

        <li> <em><?php echo ($k); ?></em>

          <div class="pepole">

            <div class="img"> <a href="/<?php echo ($sub['curroomnum']); ?>" target="_blank"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle" /></a> </div>

            <div class="txt">

              <p><span class="cracy cra<?php echo ($richlevel[0]['levelid']); ?>"></span></p>

              <p><a href="/<?php echo ($sub['curroomnum']); ?>" target="_blank" title="<?php echo ($sub['nickname']); ?>"><?php echo ($sub['nickname']); ?></a></p>

            </div>

          </div>

          <span class="nums"><?php echo ($vo['total']); ?></span>

        </li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

      </ol>

      <ol class="top_board" id="content3_2" style="display:none;">

        <li class="title"> <span class="t1">排名</span> <span class="t2">超级粉丝</span> <span class="t3">贡献值</span> </li>

        <?php  if($superfansrank){ ?>

        		<script>_show.supper=<?php echo ($superfansrank[0]['uid']); ?>;</script>

		<?php
 } ?>







        <?php if(is_array($superfansrank)): $k = 0; $__LIST__ = $superfansrank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i; $richlevel = getRichlevel($sub['spendcoin']); ?>







        <li> <em><?php echo ($k); ?></em>







          <div class="pepole">







            <div class="img"> <a href="/<?php echo ($sub['curroomnum']); ?>" target="_blank"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle" /></a> </div>







            <div class="txt">







              <p><span class="cracy cra<?php echo ($richlevel[0]['levelid']); ?>"></span></p>







              <p><a href="/<?php echo ($sub['curroomnum']); ?>" target="_blank" title="<?php echo ($sub['nickname']); ?>"><?php echo ($sub['nickname']); ?></a></p>







            </div>







          </div>







          <span class="nums"><?php echo ($vo['total']); ?></span>







        </li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>







        	







        







      </ol>

<ol class="top_board" id="content3_3" style="display:none;">

       

<div id="thistop"><li class="title"><span class="t1">排名</span><span class="t2">本场超级粉丝</span><span class="t3">本场贡献值</span> </li></div>



      </ol>







    </div></div>

		

	      </div>

	      <!-- 在线观众 <div class="chat_online">

	        <h2 class="play-t"> <span id="lm2_1" onClick="turn(1,2,2);">管理员(<cite>0</cite>)</span> <span class="no_bg on" id="lm2_2" onClick="turn(2,2,2);">观众(<cite>0</cite>)</span> </h2>

	        <div class="viewer_wrap">

	          <ul id="content2_1" class="viewer_list" style="display:none;"><li id="loading_manage"><img src="__PUBLIC__/images/loading.gif" /></li></ul>

	          <ul id="content2_2" class="viewer_list"><li id="loading_online"><img src="__PUBLIC__/images/loading.gif" /></li></ul>

	        </div>

	      </div> -->

	  </div>

	  <div class="chatroom_limit" id="chatroom_limit"><img src="__PUBLIC__/images/cover_screen.jpg" /></div>

    </div>

    <div class="chat_msg">

      <div class="clear"></div>

      

      

      <textarea class="in_tx" id="msg" onKeyDown="Chat.submitForm(event);" placeholder="这里输入聊天内容" name="msg"></textarea>

      <div class="fayan">

      <div onClick="javascript:Face.showFace();" id="showFaceInfo" class="msg_face"></div>

      <button id="btnsay" class="say sayon" onClick="Chat.doSendMessage();" type="button">发言</button>

      <button class="say fly" onClick="Chat.dosendFly();" type="button">飞屏</button>

      </div>

		<div id="ChatWrap" style="position:absolute; left:184px; top:-100000px; z-index:99; width:290px; height:150px;">

			<div id="flashCallChat"></div>

            

            

			<script type="text/javascript">

			swfobject.embedSWF("/Public/swf/5ShowChat.swf?roomId=<?php echo ($userinfo['curroomnum']); ?>&rtmpHost=<?php echo ($userinfo['host']); ?>&rtmpPort=1935&appName=5show", "flashCallChat", 290, 150, "10.0", "", {},{wmode:"transparent", allowscriptaccess:"always"});

			</script>

			<script type="text/javascript">

			if ((navigator.userAgent.indexOf("Maxthon") != -1 && navigator.userAgent.indexOf("WebKit") == -1) || (navigator.userAgent.indexOf("theworld") != -1 && navigator.userAgent.indexOf("WebKit") == -1) || (navigator.userAgent.indexOf("MSIE 9.0") != -1 && navigator.userAgent.indexOf("WebKit") == -1)) { 

				document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="290" height="150" id="flashCallChat2">');

				document.write('<param name="movie" value="/Public/swf/5ShowChat.swf" />');

				document.write('<param name="quality" value="high" />');

				document.write('<embed src="/Public/swf/5ShowChat.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="290" height="150"></embed>');

				document.write('<param name="flashVars" value="roomId=<?php echo ($userinfo['curroomnum']); ?>&rtmpHost=<?php echo ($userinfo['host']); ?>&rtmpPort=1935&appName=5show"/>');

				document.write('</object>');

			}

			</script>

		</div>

    </div>

    

	

  </div>

  </div>













<div class="wrap" style="width:830px">

	<div class="ad">  

     <div class="game_pic"><a href="<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($userinfo['ucuid']); ?>&size=middle" width="40" /></a></div>

     <div class="game_04">

     <div class="game_02"><?php echo ($userinfo['nickname']); ?></div>

     <div class="game_01">

     <p id="wishImitation">

					<?php
 if($userwishs){ ?>

					<?php echo ($userwishs['wish']); ?>

					<?php
 } else{ ?>

					<!--<strong class="p1">您今天还未许愿,快去许愿吧！</strong>-->

					<?php
 } ?>

                </p>

                </div>

     

     <div class="game_03" >

<?php
 $emceelevel = getEmceelevel($userinfo['earnbean']); $nextemceelevel = D("Emceelevel")->where('levelid>'.$emceelevel[0]['levelid'])->field('levelid,levelname,earnbean_low')->order('levelid asc')->select(); $richlevel = getRichlevel($userinfo['spendcoin']); $nextrichlevel = D("Richlevel")->where('levelid>'.$richlevel[0]['levelid'])->field('levelid,levelname,spendcoin_low')->order('levelid asc')->select(); ?>

<p><span class="star star<?php echo ($emceelevel[0]['levelid']); ?>"></span>距&nbsp;<span class="star star<?php echo ($nextemceelevel[0]['levelid']); ?>"></span> 还差 <?php echo ($nextemceelevel[0]['earnbean_low'] - $userinfo['earnbean']); ?>秀豆<span></span></p>

      </div>

     

     

     </div>

     <div class="fans">

    <span style="float:left;" onClick="Manage.AttentionTo(this);" state="1" class="attentions">+ 关注</span><span style="float:right; display:none;"><?php echo ($count); ?></span>

    

    </div>

     </div>

  <div class="main-flash" id="main-flash" style="overflow:hidden;">

    <div class=flashbox style="width:100%;"><div class=video-u style="width:100%;height:475px">

    <!--礼物之星 begin-->

      	

    <div class="preTopGift" style="display:none;">

		<img src="/Public/images/hb.gif">&nbsp;<span style="color:#FF0000;" id="gethb"><?php echo ($userinfo['gethb']); ?></span>

	</div>

    <!--礼物之星 end-->

    <div id=livebox style="width:100%;"></div>

    <div class=room_limit id=mask1><div class=full_room></div><p class=full>购买<a href=__APP__/User/toolItem/ title=VIP>VIP</a>或者<a href=__APP__/User/toolItem/ title=金钥匙>金钥匙</a>可以进入</p></div>

    <div class=room_limit id=mask2><div class=money_room></div><p class=pays><span class=give>需支付秀币：</span><span class=money id=money></span></p><p class=pay_btn><a href=javascript:playerMenu.enter(); title=支付>支付</a></p></div>

    <div class="room_limit" id="mask4"><p class="stop">您已经被禁止进入本房间！</p></div>

    <div class=room_limit id=mask3><div class=pwd_room></div><div class=pwdroom><span><input type=password name=room_pwd id=room_pwd /> </span><a href=javascript:playerMenu.enter(); title=确定>确定</a></div></div></div></div>

    

    

    

    

    

    <div class="liwu"><div class="sgift-box">

      

       <div class="gift-tip giftmodel"  style=" display:block; ">

<div class="gift-v"  >

	<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><ul id="content8_<?php echo ($k); ?>" <?php if($k != 6): ?>style='display:none;'<?php endif; ?>>

		<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li onClick="GiftCtrl.choiceGift(<?php echo ($sub['id']); ?>,'<?php echo ($sub['giftname']); ?>');">

		<img src="<?php echo ($sub['giftIcon']); ?>" width="42" height="42"/>

		<span><?php echo ($sub['giftname']); ?></span>

		<div class="h_dou"><?php echo ($sub['needcoin']); ?>个秀币</div></li><?php endforeach; endif; else: echo "" ;endif; ?>

	</ul><?php endforeach; endif; else: echo "" ;endif; ?>

	

</div></div>

    </div>

    

    <div class="send_gift">

	  	

        <span>数量</span>

        <div id="gift_num" class="gift_num">

          <input type="text" class="glo_gift t2" maxlength="5" name="gift_num"  id="giftnum"/>

          <div id="show_num_btn" class="btn_down">↓</div>

        </div>

        <span>給</span>

        <div id="gift_to" class="gift_to">

          <div class="glo_gift t3" id="giftto"></div>

          <div id="show_gift_user_list_btn" class="btn_down">↓</div>

        </div>

        

        

        <a class="btn_send" id="btn_send_gift" onClick="GiftCtrl.sendGift();" href="javascript:void(0);"> 发送</a> 

		<a class="paytojoy" target="_blank" href="__APP__/User/charge/">充值</a>

		<a href="javascript://" onClick="GiftCtrl.sendHb();"><img src="/Public/images/hb.gif" align="absmiddle" style="padding-left:8px;" id="hb_btn"></a>







	  </div>

    

    

    

    </div>

    

    

    

    

    

    

    <div id="user_sofa" class="giftbox" style="display:none;"></div>

    

    

 

  </div>

  

  

  <div class="clear"></div>

</div>

</div>

<!--<div id="flashCallGift"></div>-->

<div id="flashFlyWord"></div>

<!--<div id="egg" style="position:absolute;width:460;height:368;left:490px;top:-10000px; z-index:1000000"><div id="flashContent" style="text-align:left;"></div></div> -->

<script>

swfobject_h.embedSWF("/Public/swf/Gifts.swf", "flashCallGift",1,1, "10.0", "", {},{wmode:"transparent",allowscriptaccess:"always"});

swfobject_h.embedSWF("/Public/swf/FlyWord.swf", "flashFlyWord",1,1, "10.0", "", {},{wmode:"transparent",allowscriptaccess:"always"});

</script>









<script type="text/javascript" language="javascript">

	var hbrankinterval=setInterval(function(){$("#hbrank").load('/index.php/Show/show_redbagrank/',function (responseText, textStatus, XMLHttpRequest){this;});}, 60000);

</script>





<div id="videobox">

	<div class="jtitle"><span class="jlogo"></span><span class="jfriend"></span><span id="zoom" class="on"></span></div>

	<a href="#" id="jumplikn" target="_blank"><div class="jdetail"><p>欢迎进入<?php echo ($sitename); ?>互动直播平台！</p><p><strong>如还想接着观看之前的视频专辑</strong></p><span>请点击这里返回</span></div></a>

</div>

<div class="p-Song" id="chatOff">

  <div class="m-songt"><h4>提示</h4></div>

  <div class="m-songv">

    <div class="promt-msg">

      <p class="msg-text">您与聊天服务断开连接！</p>

      <div class="msg-btn"><span class="play-btn" id="chat_button">确定</span></div>

    </div>

  </div>

</div>

<div class="pop_hinfo" id="hoverPerson">

	<div class="hover_des">

		<h4 id="person_title"></h4>

		<ul id="ctrllist">

			<li onClick="ChatApp.ShutUp();" class="tdeal">禁言5分钟</li> 

			<li onClick="ChatApp.Resume();" class="tdeal">恢复发言</li>

			<li onClick="ChatApp.Kick();" class="tdeal">踢出一小时</li>

			<span class="menuline"></span>

			<li id="tietiao" style="color: rgb(102, 51, 153);"><b>给TA贴条</b></li>

			<li onClick="UserListCtrl.sendGift();">发送礼物</li> 

			<li onClick="UserListCtrl.chatPublic();">公开地说</li> 

			<li onClick="UserListCtrl.chatPrivate();">悄悄的说</li>

			<li><a href="javascript:void(0);" title="" class="enterroom" target="_blank">进入房间</a></li> 

			<li class="dblack" onClick="ChatApp.setBlack();">加入黑名单</li>

			<li class="dmanage" onClick="ChatApp.setManager();">设为管理</li>

			<li class="dmanage" onClick="ChatApp.delManager();">删除管理</li>

		</ul>

	</div>

</div>

<div class="g_and_b" id="tietiaob">

<div class="girl_tittle"><ul><li><a onMouseOver="show('g')"><img src="__PUBLIC__/images/note/girl_tittle1.png" /></a></li>

    <li><a onMouseOver="show('b')"><img src="__PUBLIC__/images/note/boy_tittle1.png" /></a></li>

</ul></div>

<div class="girl_top"><a href="#"><img src="__PUBLIC__/images/note/girl_tittle.png" /></a></div>





<div class="girl_center">

<ul class="girl_content">

<?php if(is_array($tietiaos)): $k = 0; $__LIST__ = $tietiaos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($vo['sex'] == '1'): ?><li><a onClick="lj.sendTietiao(<?php echo ($vo['id']); ?>);"><img src="<?php echo ($vo['ttIcon']); ?>" /></a>

  <p><?php echo ($vo['needcoin']); ?>秀币</p></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

</ul>



<ul class="boy_content">

<?php if(is_array($tietiaos)): $k = 0; $__LIST__ = $tietiaos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($vo['sex'] == '0'): ?><li><a onClick="lj.sendTietiao(<?php echo ($vo['id']); ?>);"><img src="<?php echo ($vo['ttIcon']); ?>" /></a>

  <p><?php echo ($vo['needcoin']); ?>秀币</p></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

</ul>



</div>

<div class="girl_foot"><img src="__PUBLIC__/images/note/bodybj_foot.png" /></div>

</div>

<script type="text/javascript">

  function show(i){

    if (i=="g") {

      $(".girl_content").show();

      $(".girl_tittle").find("li").eq(0).find("img").attr("src","__PUBLIC__/images/note/girl_tittle1.png");

      $(".girl_tittle").find("li").eq(1).find("img").attr("src","__PUBLIC__/images/note/boy_tittle1.png");

      $(".boy_content").hide();

    }

    else if(i=="b"){

      $(".boy_content").show();

      $(".girl_tittle").find("li").eq(0).find("img").attr("src","__PUBLIC__/images/note/girl_tittle11.png");

      $(".girl_tittle").find("li").eq(1).find("img").attr("src","__PUBLIC__/images/note/boy_tittle11.png");

      $(".girl_content").hide();

    }



  }



</script>

<div class="lpet">

	<div id="JoyPet_left"></div>

</div>

<div class="rpet">

	<div id="JoyPet_right"></div>

</div>

<div id=giveBox class="poptip wishpop"><div class=pop-t><span class=close></span><ul><li class=on id=lm5_1 onclick=turn(1,3,5);>收到礼物</li> <li id=lm5_2 onclick=turn(2,3,5);>疯狂热捧</li></ul></div><div class="pop-v mywishv" id=content5_1><div class=wantgift><span>我想要</span> <input type=text id=wishGiftNum name=gift-num class=giftnum /> <span>个</span>

<div class=gift-title onClick="$('#wish-gift-tip').fadeIn();">

<div id=wishGiftName class=gift-name>礼物</div><div class=choose></div></div><span class="sendBtn fl" onClick="WishGiftCtrl.save($('#wishGiftNum').val(),$('#wishGiftName').html(),1)">确定</span></div><div id=wishGiftId style=display:none;></div>

<div class="gift-tip giftmodel" id=wish-gift-tip style=display:none;><div class=gift-tab>

<ul>

	<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li <?php if($k == 1): ?>class=on<?php endif; ?> id=lm6_<?php echo ($k); ?> onclick="turn(<?php echo ($k); ?>,<?php echo (count($gifts)); ?>,6);" style=""><?php echo ($vo['sortname']); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>

</ul></div>

<div class=gift-v>

	<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><ul id="content6_<?php echo ($k); ?>" <?php if($k != 6): ?>style='display:none;'<?php endif; ?>>

		<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li onClick="WishGiftCtrl.choiceGift(<?php echo ($sub['id']); ?>,'<?php echo ($sub['giftname']); ?>');">

		<img src="<?php echo ($sub['giftIcon']); ?>" width="42" height="42"/>

		<span><?php echo ($sub['giftname']); ?></span>

		<div class="h_dou"><?php echo ($sub['needcoin']); ?>个秀币</div></li><?php endforeach; endif; else: echo "" ;endif; ?>

	</ul><?php endforeach; endif; else: echo "" ;endif; ?>

	

</div></div></div><div class="pop-v mywishv" style=display:none; id=content5_2><div class=wantgift><span>我希望今天收到</span> <input type=text id=warm name=gift-num class=giftnum /> 

<span>人热捧</span><span class="sendBtn fl" onClick="WishGiftCtrl.save($('#warm').val(),null,2);">确定</span></div></div>

<div class="pop-v mywishv" style=display:none; id=content5_3><div class=wantgift><textarea id="customWish" class="custom-txt" onChange="if(value.length>100) value=value.substr(0,100);" ></textarea> <span class=sendBtn onclick=WishGiftCtrl.save(null,null,3)>确定</span></div></div></div>



<div class="giftBox" id="gift-givenum"><div class="gift-numul"><ul><li><a onClick="GiftCtrl.giftNum(11)" href="javascript:void(0);">11个</a></li><li><a onClick="GiftCtrl.giftNum(66)" href="javascript:void(0);">66个</a></li><li><a onClick="GiftCtrl.giftNum(99)" href="javascript:void(0);">99个</a></li><li><a onClick="GiftCtrl.giftNum(300)" href="javascript:void(0);">300个</a></li><li><a onClick="GiftCtrl.giftNum(520)" href="javascript:void(0);">520个</a></li><li><a onClick="GiftCtrl.giftNum(1314)" href="javascript:void(0);">1314个</a></li>

</ul></div></div>



<div class="giftBox" id="redBagBox" style="width:250px;color:#333333;">

loading...

</div>



<div class=playerBox id=playerBox><div class=playerlist><ul id=gift_userlist></ul></div></div>

<div class=playerBox id=playerBox1><div class=playerlist><ul id=chat_userlist></ul></div></div><div class=p-Song id=alertBox><div class=m-songt><span class=s-close id=close_msg></span><h4>提示</h4></div><div class=m-songv><div class=promt-msg><p class=msg-text id=msg_text></p><div class=msg-btn id=poptype1><span class=play-btn id=data-confirm>确定</span></div><div class=msg-btn id=poptype2><span id=btnAgree class=play-btn>同意</span> <span id=btnDisgree class=play-btn>取消</span></div><div class=msg-btn id=poptype3><span class=play-btn id=btnConfirm>确定</span></div></div></div></div><div class="p-Song d-song" id=song_dialog><div class=m-songt><span class=close onClick="$('#song_dialog').hide();"></span><h4>主播的点歌本</h4></div><div class=m-songv><table class=song-table id=song_table><tr><th>歌名</th><th>歌名</th><th>原唱</th><th>操作</th></tr></table><div class=page id=page></div><div class=song-txt><strong>点歌说明：</strong><p>1、皇冠主播1500,钻石主播1000,其他主播500秀币 <br /> 2、当主播接受点歌后正式收取点歌费用</p></div></div></div><div class=p-Song id=addSong><div class=m-songt><span class=close onClick="$('#addSong').hide();"></span><h4>添加歌曲</h4></div><div class=m-songv><table class=song-table><tr><th>歌名(必填)</th><th>原唱(选填)</th></tr><tr><td><strong>1</strong> <input type=text id=name_1 class="song_in sinput1" /></td><td><input type=text id=singer_1 class="song_in sinput2" /></td></tr><tr><td><strong>2</strong> <input type=text id=name_2 class="song_in sinput1" /></td><td><input type=text id=singer_2 class="song_in sinput2" /></td></tr><tr><td><strong>3</strong> <input type=text id=name_3 class="song_in sinput1" /></td><td><input type=text id=singer_3 class="song_in sinput2" /></td></tr><tr><td><strong>4</strong> <input type=text id=name_4 class="song_in sinput1" /></td><td><input type=text id=singer_4 class="song_in sinput2" /></td></tr><tr><td><strong>5</strong> <input type=text id=name_5 class="song_in sinput1" /></td><td><input type=text id=singer_5 class="song_in sinput2" /></td></tr></table><div class=song_btn><span class=play-btn onclick=Song.saveBatchSong();>提交</span></div></div></div><iframe id=frmFile name=frmFile frameborder=0 scrolling=no height=0 width=0></iframe><div class="p-Song d-song" id=song_dialog2><div class=m-songt><span class=close onClick="$('#song_dialog2').hide();"></span><h4>主播的点歌本</h4></div><div class=m-songv><table class=song-table id=song_table2><tr><th>歌名</th><th>歌名</th><th>原唱</th><th>状态</th></tr></table>

<div class="page" id="page2"></div><div class=wantgift><span>自选歌曲</span> <input type=text name=songName id=songName class=songNotebook pro-msg=歌曲名(必选) value="歌曲名(必选)"/> <input type=text name=songSinger id=songSinger class=ysong value=原唱 pro-msg="原唱"/> <input type=hidden name=songId id=songId value="0"/> <span class=play-btn onclick=Song.vodSong();>点歌</span></div><div class=song-txt><strong>点歌说明：</strong><p>1、皇冠主播1500,钻石主播1000,其他主播500秀币 <br /> 2、当主播接受点歌后正式收取点歌费用</p></div></div></div>



<!--广播显示框-->



<script>

	var labasb = new myscrollbar("scrollblock","scrollbar","theContent","theText","leftbtn","rightbtn")

		labasb.Initialize();

		labasb.execute()

</script>

<div class="UbbFace" id="ChatFace">

	<div class="faceinfo">

		<em onClick="javascript:Face.addEmot('[`OK`]');" class=face1 title=OK></em>

		<em onClick="javascript:Face.addEmot('[`baiyan`]');" class=face2 title=白眼></em>

		<em onClick="javascript:Face.addEmot('[`bao`]');" class=face3 title=抱抱></em>

		<em onClick="javascript:Face.addEmot('[`caidao`]');" class=face4 title=菜刀></em>

		<em onClick="javascript:Face.addEmot('[`chengrang`]');" class=face5 title=承让></em>

		<em onClick="javascript:Face.addEmot('[`chouren`]');" class=face6 title=愁人></em>

		<em onClick="javascript:Face.addEmot('[`daku`]');" class=face7 title=大哭></em>

		<em onClick="javascript:Face.addEmot('[`daxiao`]');" class=face8 title=大笑></em>

		<em onClick="javascript:Face.addEmot('[`danding`]');" class=face9 title=淡定></em>

		<em onClick="javascript:Face.addEmot('[`ding`]');" class=face10 title=顶></em>

		<em onClick="javascript:Face.addEmot('[`feiwen`]');" class=face11 title=飞吻></em>

		<em onClick="javascript:Face.addEmot('[`ganka`]');" class=face12 title=尴尬></em>

		<em onClick="javascript:Face.addEmot('[`geili`]');" class=face13 title=给力></em>

		<em onClick="javascript:Face.addEmot('[`gouyin`]');" class=face14 title=勾引></em>

		<em onClick="javascript:Face.addEmot('[`guzhang`]');" class=face15 title=鼓掌></em>

		<em onClick="javascript:Face.addEmot('[`guiba`]');" class=face16 title=跪拜></em>

		<em onClick="javascript:Face.addEmot('[`hainiu`]');" class=face17 title=害羞></em>

		<em onClick="javascript:Face.addEmot('[`houhou`]');" class=face18 title=吼吼></em>

		<em onClick="javascript:Face.addEmot('[`huaixiao`]');" class=face19 title=坏笑></em>

		<em onClick="javascript:Face.addEmot('[`huoda`]');" class=face20 title=火大></em>

		<em onClick="javascript:Face.addEmot('[`jianxiao`]');" class=face21 title=奸笑></em>

		<em onClick="javascript:Face.addEmot('[`jingya`]');" class=face22 title=惊讶></em>

		<em onClick="javascript:Face.addEmot('[`kaixin`]');" class=face23 title=开心></em>

		<em onClick="javascript:Face.addEmot('[`kelian`]');" class=face24 title=可怜></em>

		<em onClick="javascript:Face.addEmot('[`kuangxiao`]');" class=face25 title=狂笑></em>

		<em onClick="javascript:Face.addEmot('[`liangzai`]');" class=face26 title=靓仔></em>

		<em onClick="javascript:Face.addEmot('[`meinv`]');" class=face27 title=美女></em>

		<em onClick="javascript:Face.addEmot('[`meiyan`]');" class=face28 title=媚眼></em>

		<em onClick="javascript:Face.addEmot('[`outu`]');" class=face29 title=呕吐></em>

		<em onClick="javascript:Face.addEmot('[`piaoguo`]');" class=face30 title=飘过></em>

		<em onClick="javascript:Face.addEmot('[`qinqin`]');" class=face31 title=亲亲></em>

		<em onClick="javascript:Face.addEmot('[`sese`]');" class=face32 title=色色></em>

		<em onClick="javascript:Face.addEmot('[`shangbuqi`]');" class=face33 title=伤不起></em>

		<em onClick="javascript:Face.addEmot('[`byebye`]');" class=face34 title=拜拜></em>

		<em onClick="javascript:Face.addEmot('[`tiaoxi`]');" class=face35 title=调戏></em>

		<em onClick="javascript:Face.addEmot('[`touxiao`]');" class=face36 title=偷笑></em>

		<em onClick="javascript:Face.addEmot('[`tuxue`]');" class=face37 title=吐血></em>

		<em onClick="javascript:Face.addEmot('[`wabi`]');" class=face38 title=挖鼻></em>

		<em onClick="javascript:Face.addEmot('[`weiguan`]');" class=face39 title=围观></em>

		<em onClick="javascript:Face.addEmot('[`weiqu`]');" class=face40 title=委屈></em>

		<em onClick="javascript:Face.addEmot('[`wuyu`]');" class=face41 title=无语></em>

		<em onClick="javascript:Face.addEmot('[`yali`]');" class=face42 title=鸭梨></em>

		<em onClick="javascript:Face.addEmot('[`ye`]');" class=face43 title=耶></em>

		<em onClick="javascript:Face.addEmot('[`zeml`]');" class=face44 title=怎么了></em>

		<em onClick="javascript:Face.addEmot('[`zhuaguang`]');" class=face45 title=抓狂></em>

	</div>

</div>

<!-- 广播表情 -->

<div class="UbbFace" id="ChatFaceGb">

	<div class="faceinfo">

		<em onClick="javascript:FaceGb.addEmot('[`OK`]');" class=face1 title=OK></em>

		<em onClick="javascript:FaceGb.addEmot('[`baiyan`]');" class=face2 title=白眼></em>

		<em onClick="javascript:FaceGb.addEmot('[`bao`]');" class=face3 title=抱抱></em>

		<em onClick="javascript:FaceGb.addEmot('[`caidao`]');" class=face4 title=菜刀></em>

		<em onClick="javascript:FaceGb.addEmot('[`chengrang`]');" class=face5 title=承让></em>

		<em onClick="javascript:FaceGb.addEmot('[`chouren`]');" class=face6 title=愁人></em>

		<em onClick="javascript:FaceGb.addEmot('[`daku`]');" class=face7 title=大哭></em>

		<em onClick="javascript:FaceGb.addEmot('[`daxiao`]');" class=face8 title=大笑></em>

		<em onClick="javascript:FaceGb.addEmot('[`danding`]');" class=face9 title=淡定></em>

		<em onClick="javascript:FaceGb.addEmot('[`ding`]');" class=face10 title=顶></em>

		<em onClick="javascript:FaceGb.addEmot('[`feiwen`]');" class=face11 title=飞吻></em>

		<em onClick="javascript:FaceGb.addEmot('[`ganka`]');" class=face12 title=尴尬></em>

		<em onClick="javascript:FaceGb.addEmot('[`geili`]');" class=face13 title=给力></em>

		<em onClick="javascript:FaceGb.addEmot('[`gouyin`]');" class=face14 title=勾引></em>

		<em onClick="javascript:FaceGb.addEmot('[`guzhang`]');" class=face15 title=鼓掌></em>

		<em onClick="javascript:FaceGb.addEmot('[`guiba`]');" class=face16 title=跪拜></em>

		<em onClick="javascript:FaceGb.addEmot('[`hainiu`]');" class=face17 title=害羞></em>

		<em onClick="javascript:FaceGb.addEmot('[`houhou`]');" class=face18 title=吼吼></em>

		<em onClick="javascript:FaceGb.addEmot('[`huaixiao`]');" class=face19 title=坏笑></em>

		<em onClick="javascript:FaceGb.addEmot('[`huoda`]');" class=face20 title=火大></em>

		<em onClick="javascript:FaceGb.addEmot('[`jianxiao`]');" class=face21 title=奸笑></em>

		<em onClick="javascript:FaceGb.addEmot('[`jingya`]');" class=face22 title=惊讶></em>

		<em onClick="javascript:FaceGb.addEmot('[`kaixin`]');" class=face23 title=开心></em>

		<em onClick="javascript:FaceGb.addEmot('[`kelian`]');" class=face24 title=可怜></em>

		<em onClick="javascript:FaceGb.addEmot('[`kuangxiao`]');" class=face25 title=狂笑></em>

		<em onClick="javascript:FaceGb.addEmot('[`liangzai`]');" class=face26 title=靓仔></em>

		<em onClick="javascript:FaceGb.addEmot('[`meinv`]');" class=face27 title=美女></em>

		<em onClick="javascript:FaceGb.addEmot('[`meiyan`]');" class=face28 title=媚眼></em>

		<em onClick="javascript:FaceGb.addEmot('[`outu`]');" class=face29 title=呕吐></em>

		<em onClick="javascript:FaceGb.addEmot('[`piaoguo`]');" class=face30 title=飘过></em>

		<em onClick="javascript:FaceGb.addEmot('[`qinqin`]');" class=face31 title=亲亲></em>

		<em onClick="javascript:FaceGb.addEmot('[`sese`]');" class=face32 title=色色></em>

		<em onClick="javascript:FaceGb.addEmot('[`shangbuqi`]');" class=face33 title=伤不起></em>

		<em onClick="javascript:FaceGb.addEmot('[`byebye`]');" class=face34 title=拜拜></em>

		<em onClick="javascript:FaceGb.addEmot('[`tiaoxi`]');" class=face35 title=调戏></em>

		<em onClick="javascript:FaceGb.addEmot('[`touxiao`]');" class=face36 title=偷笑></em>

		<em onClick="javascript:FaceGb.addEmot('[`tuxue`]');" class=face37 title=吐血></em>

		<em onClick="javascript:FaceGb.addEmot('[`wabi`]');" class=face38 title=挖鼻></em>

		<em onClick="javascript:FaceGb.addEmot('[`weiguan`]');" class=face39 title=围观></em>

		<em onClick="javascript:FaceGb.addEmot('[`weiqu`]');" class=face40 title=委屈></em>

		<em onClick="javascript:FaceGb.addEmot('[`wuyu`]');" class=face41 title=无语></em>

		<em onClick="javascript:FaceGb.addEmot('[`yali`]');" class=face42 title=鸭梨></em>

		<em onClick="javascript:FaceGb.addEmot('[`ye`]');" class=face43 title=耶></em>

		<em onClick="javascript:FaceGb.addEmot('[`zeml`]');" class=face44 title=怎么了></em>

		<em onClick="javascript:FaceGb.addEmot('[`zhuaguang`]');" class=face45 title=抓狂></em>

	</div>

</div>

<div id=current_sofa class=current_sofa><div class=sofa-tip>抢座，当前：<span></span>个沙发</div></div><div id=get_sofa class=get_sofa><div class=get_sofa_tip>沙发数量：<input type=text class=getseatnum id=getseatnum> <input type=hidden id=sofaid value=0> <button onclick=GiftCtrl.fetch_sofa();>抢座</button> <em>(100秀币/沙发)</em></div>

</div>





<script type="text/javascript" language="javascript">

$(function(){

	InitCache();

	//tmpstart

	//$("#tietiaob").hide();

	//tmpend

	setTimeout(function(){ 

		$("#aboutp").load('__URL__/show_infoWithgwRanking/emceeId/'+_show.emceeId+'/rand/'+Math.random()+'/',function(responseText,textStatus,XMLHttpRequest){this;});//本周排行

	}, 2800);

	$(".area-v,.area-i,.giftnum,.ysong,.songNotebook").ClearRoom();//初始化清空

	$(document.body).click(function(e){var f=e.target;while(f.tagName.toLowerCase()!="body"){if(in_array(f.id,Chat.arrChatModel)){return}f=f.parentNode;}$('#gift_model,#playerBox,#playerBox1,#ChatFace,#get_sofa,#gift-givenum,#hoverPerson,#tishikuang,#ChatFaceGb').hide();});//layer Dom处理

	<?php
 if($userwishs){ ?>

	WishGiftCtrl.Countdown();

	<?php
 } ?>

	initBack();

});

	

</script>

<script language="javascript">new Drager('dragLine','upChat','downChat','v',150,290);</script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/joy_tip.js"></script>





<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/stringprototype.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/date.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/qpxl.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/wishing.js"></script>

<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/mymanage.js"></script>

<script type="text/javascript">

if(Sys.ie6){

	$(".lpet,.rpet").css({"position":"absolute", "top":(window.screen.height-window.screenTop+document.documentElement.scrollTop-446)+"px"});

	window.onscroll=function(){

		$(".lpet,.rpet").css({"top":(window.screen.height-window.screenTop+document.documentElement.scrollTop-446)+"px"});

	}

}

//20141124

setInterval("Chat.doSendMessage2()", 10000);

//20141124

</script>

<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>



<NOLAYER>

	<IFRAME name="voteframe" id="voteframe" marginWidth=0 marginHeight=0 src="" frameBorder=0 width="0" scrolling=0 height="0">

</IFRAME>

</NOLAYER>

 <a class="a_bigImg" href="<?=$bqsr[titleurl]?>" target="_blank" title="<?=$bqr[title]?>"><img alt="<?=$bqr[title]?>" src="<?=$bqr[banner]?>" style="display:none" ></a>
<!-- <style type="text/css">

hot-anchor {
    position: relative;
    width: 800px;
}
ol, ul {
    list-style: none;
}
.hot-anchor .anchor-list li {
    width: 180px;
    margin-right: 15px;
    margin-bottom: 10px;
}
.anchor-list li {
    float: left;
    position: relative;
    width: 160px;
    height: 170px;
}
li {
    list-style-type: none;
}

a {
    color: #333;
    text-decoration: none;
    outline: none;
}
a {
    color: #fff;
    text-decoration: none;
}
user agent stylesheeta:-webkit-any-link {
    color: -webkit-link;
    text-decoration: underline;
    cursor: auto;
}
.home-l-layout .conL {
    float: left;
}



</style>
 -->
	
	
		<div class="footer clear">
			<div class="contenter">
				<div class="footmenu">
				 	 <a href="/index.php/Company/about">关于我们</a><span>|</span>
				 	 <a href="#">主播招聘</a><span>|</span>
				 	 <a href="#">联系我们</a><span>|</span>
				 	 <a href="#">帮助中心</a>
				 	
				 </div>
				 <div class="copyright">
				 	  <?php echo ($footinfo); ?>		
				 </div>
						
			</div>
		</div>	

</body>

</html>