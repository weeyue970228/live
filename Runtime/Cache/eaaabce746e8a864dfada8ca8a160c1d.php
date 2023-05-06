<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>官方靓号在线申请_靓号交易 - <?php echo ($sitename); ?></title>
<meta name="keywords" content="<?php echo ($metakeyword); ?>"/>
<meta name="description" content="<?php echo ($metadesp); ?>" />

	
		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		




  <link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/emceeno.css" type="text/css"  />

<script language="javascript" src="/Public/js/CoreJS/mymanage.js"></script>
<script> 
try { document.domain = '<?php echo ($domainroot); ?>'; }
catch(e){}
function Search(e)
{
	if(e ==13|| e ==32)
		{
			Manage.checknum()
			e.returnValue =false; 
		}
}

</script>
<script language="javascript" type="text/javascript" src="/Public/js/logon-2.0.js" charset="utf-8"></script>
</head>
<body style="background: #f1f1f1;">
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
	<div class="goodnum">
		
		<div class="gn-search">
			<div class="gns-v">
				<input type="text" id="goognums" class="gn-text" pro-msg="请输入4-8位数字..."  value="请输入4-8位数字..."  onkeydown="Search(event.keyCode||event.which);"/>
				<span class="gn_btn" onClick="Manage.checknum();"><cite>搜靓号</cite></span>
                
			</div>
		</div>
		<div class="sell_gn">
			<h2 class="rtitle">官方正在销售的靓号</h2>
			<div class="gn_item">
			    <div class="gni-t">
					<a href="javascript:Manage.changeNum(4);" class="change-gn" title="换一批">换一批</a><h3>4位靓号</h3>
				</div>
				<div class="gni-v" id="Num_area4">
					<ul>
<?php
$four_goodnums = D('Goodnum')->where('length=4 and issale="n"')->order('rand()')->limit(4)->select(); ?>
						<?php if(is_array($four_goodnums)): $k = 0; $__LIST__ = $four_goodnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<p>靓号: <strong class="f-1"><?php echo ($vo['num']); ?></strong></p>
							<p class="price">价值: <strong class="f-2"><?php echo ($vo['price']); ?></strong> 个秀币</p>
                            
                            
							<span class="gn_btn mt5" onClick="Manage.Buynum(<?php echo ($vo['num']); ?>,false)" ><cite>购买</cite></span>           
                            
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
            <div class="gn_item">
			    <div class="gni-t">
					<a href="javascript:Manage.changeNum(5);" class="change-gn" title="换一批">换一批</a><h3>5位靓号</h3>
				</div>
				<div class="gni-v" id="Num_area5">
					<ul>
<?php
$five_goodnums = D('Goodnum')->where('length=5 and issale="n"')->order('rand()')->limit(4)->select(); ?>
						<?php if(is_array($five_goodnums)): $k = 0; $__LIST__ = $five_goodnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<p>靓号: <strong class="f-1"><?php echo ($vo['num']); ?></strong></p>
							<p class="price">价值: <strong class="f-2"><?php echo ($vo['price']); ?></strong> 个秀币</p>
                            
                            
							<span class="gn_btn mt5" onClick="Manage.Buynum(<?php echo ($vo['num']); ?>,false)" ><cite>购买</cite></span>           
                            
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<div class="gn_item">
			    <div class="gni-t">
					<a href="javascript:Manage.changeNum(6);" class="change-gn" title="换一批">换一批</a><h3>6位靓号</h3>
				</div>
				<div class="gni-v" id="Num_area6">
					<ul>
<?php
$six_goodnums = D('Goodnum')->where('length=6 and issale="n"')->order('rand()')->limit(4)->select(); ?>
						<?php if(is_array($six_goodnums)): $k = 0; $__LIST__ = $six_goodnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<p>靓号: <strong class="f-1"><?php echo ($vo['num']); ?></strong></p>
							<p class="price">价值: <strong class="f-2"><?php echo ($vo['price']); ?></strong> 个秀币</p>
                            
                            
							<span class="gn_btn mt5" onClick="Manage.Buynum(<?php echo ($vo['num']); ?>,false)" ><cite>购买</cite></span>           
                            
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<div class="gn_item">
			    <div class="gni-t">
					<a href="javascript:Manage.changeNum(7);" class="change-gn" title="换一批">换一批</a><h3>7位靓号</h3>
				</div>
				<div class="gni-v" id="Num_area7">
					<ul>
<?php
$seven_goodnums = D('Goodnum')->where('length=7 and issale="n"')->order('rand()')->limit(4)->select(); ?>
						<?php if(is_array($seven_goodnums)): $k = 0; $__LIST__ = $seven_goodnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<p>靓号: <strong class="f-1"><?php echo ($vo['num']); ?></strong></p>
							<p class="price">价值: <strong class="f-2"><?php echo ($vo['price']); ?></strong> 个秀币</p>
                            
                            
							<span class="gn_btn mt5" onClick="Manage.Buynum(<?php echo ($vo['num']); ?>,false)" ><cite>购买</cite></span>           
                            
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<div class="gn_item">
			    <div class="gni-t">
					<a href="javascript:Manage.changeNum(8);" class="change-gn" title="换一批">换一批</a><h3>8位靓号</h3>
				</div>
				<div class="gni-v" id="Num_area8">
					<ul>
<?php
$eight_goodnums = D('Goodnum')->where('length=8 and issale="n"')->order('rand()')->limit(4)->select(); ?>
						<?php if(is_array($eight_goodnums)): $k = 0; $__LIST__ = $eight_goodnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
							<p>靓号: <strong class="f-1"><?php echo ($vo['num']); ?></strong></p>
							<p class="price">价值: <strong class="f-2"><?php echo ($vo['price']); ?></strong> 个秀币</p>
                            
                            
							<span class="gn_btn mt5" onClick="Manage.Buynum(<?php echo ($vo['num']); ?>,false)" ><cite>购买</cite></span>           
                            
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
    <div class="clear"></div>
</div>
<!--靓号弹层 begin-->
<div id="giveBox" class="poptip" style="display:none;">
	<div class="pop-t"><span class="close" onClick="Manage.closeBox();"></span><h3>提示</h3></div>	
	<div class="pop-v" id="goodnum-tip"></div>
</div>
<!--靓号弹层 end-->
<script language="javascript">$(function(){$(".gn-text").ClearRoom();})</script>
	
	
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