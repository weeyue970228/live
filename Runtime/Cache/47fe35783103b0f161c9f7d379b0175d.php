<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($familyinfo[0]['familyname']); ?>-家族首页-<?php echo ($sitename); ?></title>  
	 <meta property="qc:admins" content="2736327467566533536375" />
 
<link rel="stylesheet" href="__PUBLIC__/css/common.css" type="text/css" />

<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/home.css">


<script type="text/javascript" src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/CoreJS/wishing.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/CoreJS/common.js"></script>

<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/familyMember.css">

</head>
<body>
 <!--nav begin-->

<script>
	try { document.domain = '<?php echo ($domainroot); ?>'; }
	
	
	
	catch(e){}
</script>

<script language="javascript" type="text/javascript" src="__PUBLIC__/js/logon-2.0.js" charset="utf-8"></script>

<script>
	UAC.showUserInfo = function(data) 
	{
		if(data.isLogin == "1")
		{
			window.location.reload();
		}
	}
</script>

<div class="headjoy">

	<div class="joytip">

		<div class="vlogo">
			<a href="/" title="" target="_blank"><img src="<?php echo ($sitelogo); ?>" style="height:65px;width:auto;margin-top:0px;  margin-left: 0px;" /></a>
		</div>

		<div class="vtab">

			<a href="/" title="首页" <?php if($_GET['_URL_'][0] == ''): ?>class="on"<?php endif; ?>>首页</a>
			<a href="/index.php/xiuchang/" title="秀场" <?php if($_GET['_URL_'][0] == 'xiuchang'): ?>class="on"<?php endif; ?>>秀场</a>
			<a href="/index.php/youxi/" title="游戏" <?php if($_GET['_URL_'][0] == 'youxi'): ?>class="on"<?php endif; ?>>游戏</a>
			<a href="/index.php/order/" title="排行榜" <?php if($_GET['_URL_'][0] == 'order'): ?>class="on"<?php endif; ?>>排行榜</a>
			<a href="/index.php/User/toolItem/" title="商城" <?php if($_GET['_URL_'][1] == 'toolItem'): ?>class="on"<?php endif; ?>>商城</a>
			<a href="/index.php/Jiazu/" title="家族" <?php if($_GET['_URL_'][0] == 'Jiazu'): ?>class="on"<?php endif; ?>>家族</a>
			<a href="/index.php/emceeno/" title="靓号" <?php if($_GET['_URL_'][0] == 'emceeno'): ?>class="on"<?php else: ?>class="tabno"<?php endif; ?>">靓号</a>
			<a href="/index.php/Xiazai/" title="下载" <?php if($_GET['_URL_'][0] == 'Xiazai'): ?>class="on"<?php endif; ?>">下载</a>

		</div> 

		<div style="position:relative; right:145px; top:13px;">
			<form id="globalsearchform" method="get" action="" style="width:200px">

				<input type="text" id="roomnum" name="roomnum" placeholder="昵称/房号" value="" class="input" style="border: 1px solid #acacac;border-radius: 14px;position:absolute; right:1px; top:15px;outline:none;padding-left: 8px;height: 20px;"  onkeydown="FSubmit(event.keyCode||event.which);"/>
				<a href="###" class="png24 btn-search" id="searchBtn" style="position:absolute; right:-20px; top:15px;" onclick="searchroom();"></a>


				<div class="search-input" id="searchTips" style="position:absolute;top:0;left:0"></div>

			</form>
		</div>



		<div class="others">
		</div>

		

		

			<script language="javascript" type="text/javascript">
			

				$(function(){
		
		
		
					setTimeout(function(){ 
		
		
		
						$.get("/index.php/Show/show_headerInfo/t/"+Math.random(),function(data){ $('.others').html(data);});
		
		
		
					}, 1000);
		
		
		
					setTimeout(function(){ 
		
		
		
						$.get("/index.php/Show/show_headerInfo2/t/"+Math.random(),function(data){ $('.setting').html(data);});
		
		
		
					}, 1500);
		
		
		
				});
		
		
		
				
		
		
		
				//加载系统紧急消息公告
		
		
		
				$(document).ready(function(){	
		
		
		
					getemergencyNotice(this);
		
		
		
				});
			</script>

		

	</div>

	<script type="text/javascript">
		var globalObj = "";



		function getemergencyNotice(obj){



		globalObj = obj;



		



			$.ajax({



				type:"post",



				url:"/index.php/Show/show_getEmergencyNotice/",



				data:{},



				success:function(data){



				 var data = evalJSON(data);



				  if(data.code==0){



						dynamicNotice(obj,data.info);



				  }



				}



			});



		}



		



		// 自动滚动 



		function dynamicNotice(obj,msg) {



			if (msg == "" || msg == null) {



				//定时从服务器拿消息



				setTimeout(function() {



					getemergencyNotice(globalObj);



				}, 5*60*1000);



			} else {



				$('#moveNotices').text(msg);



				$('#noticeBody').fadeIn();



				$("#headshow").css('margin','20px auto');



			}



		}



	



		function closeNotice() {



			$('#noticeBody').fadeOut();



			$("#headshow").css('margin','0 auto');



	



			//定时从服务器拿消息



			setTimeout(function() {



				getemergencyNotice(globalObj);



			},5*60*1000);



		}
	</script>

	<style type="text/css">
		div#b,div#d,div#bb { white-space:nowrap; }
	</style>

	<div id="noticeBody" class="noticeBody">

		<div id="noticeLine" class="noticeLine">

			<div class="noticeText">

				<marquee id="moveNotices" scrollamount="3" direction="left" onmouseover="this.stop();" onmouseout="this.start();"></marquee>

			</div>

			<div class="noticeClose">

				<a id="closebutton" onclick="closeNotice();">

					<img width="12" height="12" src="__PUBLIC__/images/close.png" />

				</a>

			</div>

		</div>

	</div>

</div>

<script>
 

    /*function FSubmit(e)
	{
		if(e ==13|| e ==32)
		{
			searchroom();
			e.returnValue =false; 
		}
	}
*/
	function  searchroom(){
		 
	   var roomnum = $("#roomnum").val();
			/*$.ajax({
				type: "post",
				dataType: "json",
				url: "/index.php/Index/searchroom/roomnum/" + roomnum,
				success: function(data) {
					if (data=="") 
					{
						alert("您输入的房间号不存在，请重新输入")
					} 
					else 
					{
      
          var fang="http://"+window.location.host+"/" + roomnum;
          window.location.href = fang;
					}
				},
				complete: function(XMLHttpRequest, textStatus) {
					//alert('complete');											
				},
				error: function() {
					//inner.innerHTML='<font color=green>出错啦 稍后再试</font>';	
				}
			});*/
			var link = "/index.php/User/search/type/nick/keyWord/"+roomnum;
			window.location.href = link;
	
	}
</script>

<div class="team-intro-wrapper">
   <div class="team-intro-inner">
      <p class="team-intro-link">
         <em class="png24 icon-back"></em>
                  <a href="#">返回热门家族</a>
      </p>
      <div class="team-intro">
						<p class="team-intro-fans">
            <em class="png24 icon-fansB" title="人气总数">人气总数</em>
            <span><?php echo ($rqtotal); ?></span>
            <em class="png24 icon-liveWB" title="家族主播">家族主播</em>
            <span><?php echo ($total); ?></span>
            <a class="family-room-btn" href="#" target="_blank">进入家族房</a> </p>
         	<a class="fl avatar" href="#"><img src="__PUBLIC__/Familyimg/<?php echo ($familyinfo[0]['familyimg']); ?>"><em class="avatar-y"></em></a>
         	<div class="team-intro-title">
            <h2><a href="#"><?php echo ($familyinfo[0]['familyname']); ?></a></h2>
            <em class="medal-family">
            <a href="#">
            	
           
			</a>
            </em>
         </div>
         <div class="team-intro-info clearfix">
            <p class="team-intro-detail">
               <span id="familyNotice"><?php echo ($familyinfo[0]['familyinfo']); ?>&nbsp;&nbsp;<br/></span>
                           </p>
         </div>
      </div>
   </div>
</div>
<div class="container clearfix">
 <div class="conL">
                  
        		<div class="team-admin clearfix">
			<div class="team-leader">
				<h3>家族族长</h3>
				<a class="fl avatar js-box" href="/<?php echo ($agentinfo[0]['curroomnum']); ?>" title="" userid="2329077333287591594">
					<img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($agentinfo[0]['ucuid']); ?>&size=middle">
				</a>
				<p>
					<em class="png24 medal-wealth25">
					</em>
				</p>
			</div>
            <div class="douban" style="margin:0 auto">
               <div class="hd">           
               	<h2>最新加入</h2>
               	<a class="next"></a>
               	<a class="prev prevStop"></a>
               	<ul>
               		<li class="on"></li>
               		<li></li>
               		<li></li>
               	</ul>
			
		</div>
		<div class="bd">         <div class="tempWrap" style="overflow:hidden; position:relative; width:605px">		<ul style="width: 1815px; left: 0px; position: relative; overflow: hidden; padding: 0px; margin: 0px;">        <?php if(is_array($newjoin)): foreach($newjoin as $key=>$new): ?><li style="float: left; width: 106px;">					<a class="fl avatar js-box" href="/<?php echo ($new["curroomnum"]); ?>"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($new['ucuid']); ?>&size=middle"></a>					<em class=""></em>				</li><?php endforeach; endif; ?>              			</ul></div>
			

		</div>

	</div>
    <script type="text/javascript">jQuery(".douban").slide({ mainCell:".bd ul", effect:"left", delayTime:800,vis:5,scroll:5,pnLoop:false,trigger:"click",easing:"easeOutCubic" });</script>
  		</div>
		<div class="team-member team-family-room">
			<h3>在线人气主播</h3>
			<div class="anchor-list clearfix">
				<ul>
				<?php if(is_array($olrqzb)): foreach($olrqzb as $key=>$ol): ?><li>
						<a class="js-play" href="/<?php echo ($ol['curroomnum']); ?>" target="_blank">
							<img src="<?php echo ($ol["bigpic"]); ?>" width="160" height="120">
							<em class="live-tip">直播</em>
							<span class="hot-anchor-hover" title="进入直播间"></span>
							<em class="anchor-play png24 icon-play"></em>
							<span class="hot-anchor-cover"></span>
							<span class="hot-anchor-fans"><em class="png24 icon-fansS"></em><?php if($ol['virtualguest'] > 0){echo ($ol['online'] + $ol['virtualguest'] + $virtualcount);}else{echo $ol['online'];} ?></span>
						</a>
						<p class="anchor-name"><a href="/<?php echo ($ol['curroomnum']); ?>" target="_blank"><?php echo ($ol["nickname"]); ?></a></p>
					</li><?php endforeach; endif; ?>	  
				</ul>
			</div>
		</div>
				<div class="team-member">
			<h3>全部主播 (<?php echo ($total); ?>)</h3>
			<div class="team-member-list anchor-list clearfix">
				<ul>
					
			<?php if(is_array($emceeinfo)): foreach($emceeinfo as $key=>$emcee): ?><li>
						<a class="js-play" href="/<?php echo ($emcee['curroomnum']); ?>" target="_blank">
							<img src="<?php echo ($emcee["bigpic"]); ?>" width="160" height="120">
					<span class="hot-anchor-hover" title="进入直播间"></span>
							<em class="anchor-play png24 icon-play"></em>
							<span class="hot-anchor-cover"></span>
							<span class="hot-anchor-fans"><em class="png24 icon-fansS"></em>1190</span>
						</a>
						<p class="anchor-name"><a class="js-box" href="javascript:;"> <?php echo ($emcee["nickname"]); ?></a><em title="
" class="star star<?php echo ($emcee['emceelevel'][0]['levelid']); ?>"></em></p>
								</li><?php endforeach; endif; ?>	
				</ul>
							</div>

<p class="page">
     <?php echo ($page); ?>
</p>

		</div>
 </div>
 <div class="conR">
    <div class="apply-join">
   		<a href="__URL__/sqjoinfamily/familyid/<?php echo ($agentid); ?>" class="orange-btn js-need-login">申请加入该家族</a>
   		<p>每人暂时只能加入一个家族</p>
   	</div>
 	<div class="team-rank">
   		<h2>家族排行榜</h2>
        
        
        
        
        <div class="conR-item home-rank slideTxtBox">
    <div class="team-rank-hd hd">
        <h3>明星榜</h3>
        <ul>
        	<li class="on"><a class="js-tab" href="javascript:;" data-period="3">月</a><i class="home-italicS"></i></li>
            <li><a class="js-tab" href="javascript:;" data-period="0">总</a></li>
        </ul>
        
    </div>
    
    
    <div class="team-star-rank bd">
        	
		<ul class="js-content">
        	<li class="home-rank clearfix">
        		<em class="png24 num-home1"></em>
        		<a class="fl avatar-m js-box" href="javascript:;">
        			<img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($emceeRank_month[0]['userinfo']['ucuid']); ?>&size=middle"><em></em>
        		</a>
            <a class="fl js-box" href="$emceeRank_month[0]['userinfo']['curroomnum']"><?php echo ($emceeRank_month[0]['userinfo']['nickname']); ?></a>
            <em class="star star<?php echo ($emceeRank_month[0]['emceelevel'][0]['levelid']); ?>"></em>
        	</li>
            <?php if(is_array($emceeRank_month4)): foreach($emceeRank_month4 as $key=>$month): ?><li><em class="png24 num-home<?php echo ($month["xuhao"]); ?>"></em>
            	<a class="fl js-box" href="/<?php echo ($month['userinfo']['curroomnum']); ?>"><?php echo ($month['userinfo']['nickname']); ?></a>
            	<em class="star star<?php echo ($month[0]['emceelevel'][0]['levelid']); ?>"></em>
            </li><?php endforeach; endif; ?>
        </ul>
	
		
		
		
		
		
        <ul class="js-content">
        	<li class="home-rank clearfix"><em class="png24 num-home1"></em><a class="fl avatar-m js-box" href="javascript:;"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($emceeRank_all[0]['userinfo']['ucuid']); ?>&size=middle"><em></em></a>
            <a class="fl js-box" href="javascript:;"><?php echo ($emceeRank_all[0]['userinfo']['nickname']); ?></a><em class="star star<?php echo ($emceeRank_all[0]['emceelevel'][0]['levelid']); ?>"></em></li>
            <?php if(is_array($emceeRank_all4)): foreach($emceeRank_all4 as $key=>$all): ?><li><em class="png24 num-home<?php echo ($all["xuhao"]); ?>"></em><a class="fl js-box" href="/<?php echo ($all['userinfo']['curroomnum']); ?>"><?php echo ($all['userinfo']['nickname']); ?></a><em class="star star<?php echo ($all[0]['emceelevel'][0]['levelid']); ?>"></em></li><?php endforeach; endif; ?>
        </ul>
    </div>
</div>
 <div class="conR-item home-rank slideTxtBox">
    <div class="team-rank-hd hd">
        <h3>富豪榜</h3>
        <ul>
        	<li class="on"><a class="js-tab" href="javascript:;" data-period="3">月</a><i class="home-italicS"></i></li>
            <li><a class="js-tab" href="javascript:;" data-period="0">总</a></li>
        </ul>
        
    </div>
    
    
    <div class="team-star-rank bd">
        <ul class="js-content">
        	<li class="home-rank clearfix"><em class="png24 num-home1"></em><a class="fl avatar-m js-box" href="javascript:;"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($richRank_month[0]['userinfo']['ucuid']); ?>&size=middle"><em></em></a>
            <a class="fl js-box" href="<?php echo ($richRank_month[0]['userinfo']['curroomnum']); ?>"><?php echo ($richRank_month[0]['userinfo']['nickname']); ?></a><em class="cracy cra<?php echo ($richRank_month[0]['richlevel'][0]['levelid']); ?>"></em></li>
            <?php if(is_array($richRank_month4)): foreach($richRank_month4 as $key=>$month): ?><li><em class="png24 num-home<?php echo ($month["xuhao"]); ?>"></em><a class="fl js-box" href="<?php echo ($richRank_month[0]['userinfo']['curroomnum']); ?>"><?php echo ($month['userinfo']['nickname']); ?></a><em class="cracy cra<?php echo ($month[0]['richlevel'][0]['levelid']); ?>"></em></li><?php endforeach; endif; ?>
        </ul>
        <ul class="js-content">
        	      	<li class="home-rank clearfix"><em class="png24 num-home1"></em><a class="fl avatar-m js-box" href="javascript:;"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($richRank_month[0]['userinfo']['ucuid']); ?>&size=middle"><em></em></a>
            <a class="fl js-box" href="<?php echo ($richRank_all[0]['userinfo']['curroomnum']); ?>"><?php echo ($richRank_all[0]['userinfo']['nickname']); ?></a><em class="cracy cra<?php echo ($richRank_all[0]['richlevel'][0]['levelid']); ?>"></em></li>
            <?php if(is_array($richRank_all4)): foreach($richRank_all4 as $key=>$all): ?><li><em class="png24 num-home<?php echo ($all["xuhao"]); ?>"></em><a class="fl js-box" href="<?php echo ($richRank_all[0]['userinfo']['curroomnum']); ?>"><?php echo ($all['userinfo']['nickname']); ?></a><em class="cracy cra<?php echo ($all[0]['richlevel'][0]['levelid']); ?>"></em></li><?php endforeach; endif; ?>
  
        </ul>
    </div>
</div>
<script type="text/javascript">jQuery(".slideTxtBox").slide();</script>
        
        
        
        
      	
<div class="super-member">
	
</div>
    </div>
</div>
</div>
 <!--footer begin-->

<!--
	作者：voidcat@163.com
	时间：2015-12-07
	描述：用于撑起footer
-->

<div class="footer">

	<div class="footertip">

	<p><?php echo ($footinfo); ?></p>

	</div>

</div>



<script type="text/javascript">



	//加载注册完毕后的邮箱验证浮层

	var showEmailLayer = "null";

	if(showEmailLayer!=null && parseInt(showEmailLayer)==1){

		window.setTimeout(function(){

			firstApplyByEmail._alertLayer1();				   

		},3000);

	}

	

</script>



<div id="regSucMsg" class="joymodlogin">

	<h3><em id=div_title>用户登录</em><span title="关闭"><a id="close_msg1">关闭</a></span></h3>

	<div class="login_body1">

		<ul>

			<li style=" font-size:16px; margin-left:1px;">您已成功获得<span style="color:#fd4817;">10秀币</span>！</li>

			<li style=" font-size:16px; margin-left:1px; font-weight:bold;">完成认证可再获得<span style="color:#fd4817;">100秀币</span>！</li>

		</ul>

		<p class="login_icon1" id="data-confirm1">立即完成认证</p>

	</div>

</div>

	

<div id="alertBox1" class="joymodlogin">

	<h3><em id=div_title>用户登录</em><span title="关闭"><A id="close_msg2">关闭</a></span></h3>

	<div class="login_body2">

		<ul>

			<li>

				<label>请输入邮箱地址：</label><input type="text" class="login1" id="emailAddress">

			</li>

			<li style=" font-size:12px; color:#fc4817; margin-left:110px; margin-top:10px;">请填写真实有效的邮箱地址</li>

		</ul>

		<p class="login_icon2" id="data-confirm2">立即完成认证</p>

	</div>

</div>

<!--footer end-->

<script>
$(document).ready(function(e) {
    $(".js-area,.js-areaSelect").hover(
		function(){
			$(".js-areaSelect").show();
			},
		function(){
			$(".js-areaSelect").hide();
			}
	);
	$(".js-level,.js-levelSelect").hover(
		function(){
			$(".js-levelSelect").show();
			},
		function(){
			$(".js-levelSelect").hide();
			}
	);
	$(".js-play").hover(
		function(){
			$(this).addClass("hover");
			},
		function(){
			$(this).removeClass("hover");
			}
	);
	
});
</script>

</body></html>