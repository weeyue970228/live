<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的粉丝-管理中心-<?php echo ($sitename); ?></title>

		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		




</head>
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
<div class="wrap">
	<div class="usercenter">
    <link rel="stylesheet" href="__PUBLIC__/css/common.css" type="text/css" />
<?php $usernav=M("member")->field("emceeagent,sign")->find( $_SESSION['uid']); ?>
<div class="utitle">
	<h2><a href="__URL__/" title="管理中心">我的管理中心</a></h2>
	<div class="utab">
		<a href="__URL__/myfavor/" title="收藏"><span>收藏</span></a>
		<a href="__URL__/interestToList/" title="偶像"><span>偶像</span></a>
		<a href="__URL__/myNos/" title="靓号"><span>靓号</span></a>
		<a href="__URL__/toolinuse/" title="道具"><span>道具</span></a>

		<a href="__URL__/showadmin/" title="房管"><span>房管</span></a>

		<a href="__URL__/interestByList/" title="粉丝"><span>粉丝</span></a>
		<a href="__URL__/info_edit/" title="个人设置"><span>个人设置</span></a>
		<a href="__URL__/getConsume/" title="账单"><span>我的账单</span></a>
		<a href="__URL__/settlement/" title="结算" style="display:none;"><span>我的结算</span></a>
		
		<?php if($usernav['emceeagent']=="y") {?>
		<a href="__URL__/sqmyfamily/" title="兑换"><span>我的家族</span></a>
			<?php } ?>
      
	</div>
	<div class="umyhelp">
		<?php if($usernav['sign']=="y") {?>
			<a href="__URL__/exchange/" title="兑换"><span>兑换</span></a>
			<?php } ?>
				
				<a href="__URL__/charge/" title="充值"><span>充值</span></a>
				<?php if($usernav['sign']=="y"){?>
					<a href="__URL__/settlement/" title="结算"><span>结算</span></a>
					<?php } ?>
	</div>
</div>

<script language="javascript">
	$(function() {
		var sm = $('.utab a,.umyhelp a').setMyTab();
	})
</script>
		<div class="attention">
			<div class="my-attention">
				<h2><strong>关注我的人</strong> (<?php echo ($count); ?>) </h2>
				<div class="att_box">
						<?php if(is_array($attentions)): $i = 0; $__LIST__ = $attentions;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl>
							<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><dt><a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>" target="_blank"><img width="60"  height="60" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle"/></a></dt>
							<dd class="tt"><a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>" target="_blank"><?php echo ($sub['nickname']); ?></a></dd>
							<dd class="bt">
									<?php
 $myattention = D("Attention")->where('uid='.$_SESSION['uid'].' and attuid='.$sub['id'])->order('id asc')->select(); if(!$myattention){ ?>
									<span class="st1" state="1" uid="<?php echo ($sub['id']); ?>">+ 关注</span>
									<?php
 } else{ ?>
									<span class="st3" state="3" uid="<?php echo ($sub['id']); ?>">互相关注|取消</span>
									<?php
 } ?>
							</dd><?php endforeach; endif; else: echo "" ;endif; ?>
						</dl><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				
				<form name="interByFrm" id="interByFrm" method="post" action="__URL__/interestByList/">

	<div class="page">
			<?php echo ($page); ?>
		
		<input type="hidden" id="p" name="p" value="1" />
	</div>					
		
		<h2>守护我的观众  </h2>
		<div class="att_box">
			
			<?php if(is_array($guard)): $i = 0; $__LIST__ = $guard;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl>
				<dt><img width="60"  height="60" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['ucuid']); ?>&size=middle"/></dt>
				<dt class="bt"><?php echo ($vo["nickname"]); ?></dt>
				<dt class="tt"><a href="/<?php echo ($vo['curroomnum']); ?>" target="_blank">直播间</a></dt>
			  </dl><?php endforeach; endif; else: echo "" ;endif; ?> 
			
		</div>
<script language="javascript">
function scrollPage(step){
	//document.getElementById("pageindex").value = step;
 	//document.forms["interByFrm"].submit();
	$('#pageindex').val(step);
	$('#interByFrm').submit();
}
</script>
                </form>
			</div>
			<div class="my-peng">
				<div class="mptitle">捧我的人<span>TOP5</span></div>
                <div class="index"><span class="pm">排名</span><span class="wp">捧我的人</span><span class="wdgxz">贡献值</span></div>
                <ul class="list">
						<?php if(is_array($mypengusers)): $k = 0; $__LIST__ = $mypengusers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i; $richlevel = getRichlevel($sub['spendcoin']); ?>
							<em><?php echo ($k); ?></em>
							<a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>" target="_blank"><img width="60" height="60" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle"/></a>
							<div class="tt">
								<span class="cracy cra<?php echo ($richlevel[0]['levelid']); ?>"></span>
								<p><a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>" target="_blank"><?php echo ($sub['nickname']); ?></a></p>
							</div>
							<span class="gxz"><?php echo ($vo['total']); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
			</div>
            <div class="clear"></div>
		</div>
	</div>
</div>
<script language="javascript">
	(function($){
	    $('.att_box .bt span').click(function(){
	    	var that=$(this);
			var intState=that.attr('state');			
			var upurl;
			//关注1，取消2
			if(intState == '1'){
				upurl = "__URL__/interest/";
			}else{
				upurl = "__URL__/cancelInterest/";
			}
			
			var intUid=$(this).attr('uid');
			
			$.ajax({
				type:"GET",
				url: upurl,
				data:{
					uid:intUid,
					t:Math.random()
				},
				success:function(response){
					//已经关注过了
					if(response != 0){
						if(intState == '1'){
							that.attr("state",2);
							that.attr("class","st2");
							that.text("- 取消关注");
						}else{
							that.attr("state",1);
							that.attr("class","st1");
							that.text("+ 关注");
						}
					}
				}
			});
		})
		
	})(jQuery);
</script>
<script language="javascript">window.onload=function(){$(".my-peng .list li").OverChange();}</script>
	
	
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