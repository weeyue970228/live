<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的房管-管理中心-<?php echo ($sitename); ?></title>

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
        <div class="usercenter_con">
            <div class="djsc_top">
                <h4>我的房管 <cite>(<?php echo (count($roomadmins)); ?>)</cite></h4>
            </div>
            <div class="house-v">
<?php
$monthloginArr = array(); $monthnotloginArr = array(); $i = 0; foreach($roomadmins as $k){ if(date('Y-m',$k['voo'][0]['lastlogtime']) == date('Y-m',time())){ array_push($monthloginArr,$roomadmins[$i]); } else{ array_push($monthnotloginArr,$roomadmins[$i]); } $i++; } ?>
				<div class="house-box">
					<h3>一个月内登录过的 <span>(<?php echo (count($monthloginArr)); ?>)</span></h3>
					<div class="house-tip">
						<?php if(is_array($monthloginArr)): $k = 0; $__LIST__ = $monthloginArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><dl>
							<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><dt><a href="/<?php echo ($sub['curroomnum']); ?>"><img width="110px" height="110px" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle" /></a></dt>
							<dd class="info">
								<p><strong><a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>"><?php echo ($sub['nickname']); ?></a></strong></p>
								<p>性别: <?php if($sub['sex'] == '0'): ?>男<?php endif; if($sub['sex'] == '1'): ?>女<?php endif; ?></p>
								<p>地区: <?php echo ($sub['province']); ?> <?php echo ($sub['city']); ?></p>
								<p>上次直播时间: <?php if($sub['showId'] != 0): echo date('Y-m-d H:i:s',$sub['showId']); else: ?>无<?php endif; ?></p>
								<?php
 $emceelevel = getEmceelevel($sub['earnbean']); ?>
								<p>主播等级:<span class="star star<?php echo ($emceelevel[0]['levelid']); ?> ml5"></span></p>
							</dd>
							<dd class="deals">
								<span state="0" uid="<?php echo ($sub['id']); ?>">解除管理权限</span>
							</dd><?php endforeach; endif; else: echo "" ;endif; ?>
						</dl><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					
				</div>
				<div class="house-box">
					<h3>一个月内未登录过的 <span>(<?php echo (count($monthnotloginArr)); ?>)</span></h3>
					<div class="house-tip">
						<?php if(is_array($monthnotloginArr)): $k = 0; $__LIST__ = $monthnotloginArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><dl>
							<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><dt><a href="/<?php echo ($sub['curroomnum']); ?>"><img width="110px" height="110px" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($sub['ucuid']); ?>&size=middle" /></a></dt>
							<dd class="info">
								<p><strong><a href="/<?php echo ($sub['curroomnum']); ?>" title="<?php echo ($sub['nickname']); ?>"><?php echo ($sub['nickname']); ?></a></strong></p>
								<p>性别: <?php if($sub['sex'] == '0'): ?>男<?php endif; if($sub['sex'] == '1'): ?>女<?php endif; ?></p>
								<p>地区: <?php echo ($sub['province']); ?> <?php echo ($sub['city']); ?></p>
								<p>上次直播时间: <?php if($sub['showId'] != 0): echo date('Y-m-d H:i:s',$sub['showId']); else: ?>无<?php endif; ?></p>
								<?php
 $emceelevel = getEmceelevel($sub['earnbean']); ?>
								<p>主播等级:<span class="star star<?php echo ($emceelevel[0]['levelid']); ?> ml5"></span></p>
							</dd>
							<dd class="deals">
								<span state="0" uid="<?php echo ($sub['id']); ?>">解除管理权限</span>
							</dd><?php endforeach; endif; else: echo "" ;endif; ?>
						</dl><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					
				</div>
			</div>
		</div>
    </div>
    
</div>
<div style="margin-top:-100px">
	
	
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
</div>
<script language="javascript">
	(function($){
	    $('.house-tip .deals span').click(function(){
			var intState=$(this).attr('state');
			var intUid=$(this).attr('uid');
			var posturl='__URL__/toggleEmceeShowAdmin/';
			var that=$(this);
			$.ajax({
				type:"post",
				url:posturl,
				data:{
					m:'toggleEmceeShowAdmin',
					userid:intUid,
					state:intState
				},
				success:function(data){
					if(data=='0'){
						that.attr('state',0);
						that.html("解除管理权限");
					}else if(data=='1'){
						that.attr('state',1);
						that.html("恢复管理权限");
					}
				}
			});
		})
		
	})(jQuery);
</script>
</body>
</html>