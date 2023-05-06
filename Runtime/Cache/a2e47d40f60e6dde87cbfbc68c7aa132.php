<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>APP下载-<?php echo ($sitename); ?></title> 
  
		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		




<link href="__PUBLIC__/css/CoreCSS/download.css" type="text/css" rel="stylesheet">
		<script type="text/javascript">
	        function browserRedirect() {
	            var sUserAgent = navigator.userAgent.toLowerCase();
	            var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
	            var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
	            var bIsMidp = sUserAgent.match(/midp/i) == "midp";
	            var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
	            var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
	            var bIsAndroid = sUserAgent.match(/android/i) == "android";
	            var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	            var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	            //document.writeln("您的浏览设备为：");

	            //安卓
	            if(bIsAndroid){
	                //document.writeln("安卓");
	                window.location.href="<?php echo ($app_android); ?>";
	            }else if(bIsIpad || bIsIphoneOs) {
	               // document.writeln("肾");
	                window.location.href="<?php echo ($app_ios); ?>";
	                
	            }else if(bIsWM){
	               // document.writeln("wp");
	            }else{
	               // document.writeln("pc");
	            }
	        }

	        browserRedirect();
	    </script>

 </head>
<body class="home-l-layout">

<div class="home-layout js-homeLayout" style="width: 100%;">
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

	<div id="bd">
		<div class="content">
			<div class="user-act" >
				<a href="<?php echo ($app_android); ?>" target="_blank"  class="btn-download"><span class="icon-android"></span><span class="text">Android<br>立即下载</span><span class="icon-download"></span></a>
				<a href="<?php echo ($app_ios); ?>" target="_blank"  class="btn-download"><span class="icon-iphone"></span><span class="text">App Store<br>立即下载</span><span class="icon-download"></span></a>
			</div>
			<div class="QR-codes">
				  <img src='<?php echo ($apppic); ?>'>
			</div>

		</div>
	</div>
	

    	
	
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