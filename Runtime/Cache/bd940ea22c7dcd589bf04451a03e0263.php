<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>消费记录-我的账单-管理中心-<?php echo ($sitename); ?></title>

		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



<script language="javascript" src="__PUBLIC__/js/CoreJS/My97DatePicker/WdatePicker.js"></script>
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
<form name="giftFrm" id="giftFrm" method="get" action="__URL__/getConsume/">
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
		<div class="myaccount">
			



			<div class="account-l">
				<a href="__URL__/getGiftStat/"  title="礼物统计">礼物统计</a>
				<a href="__URL__/getTakedGift/"  title="我收到的礼物">我收到的礼物</a>
				<a href="__URL__/getBuyedGift/"  title="我送出的礼物">我送出的礼物</a>
				<a href="__URL__/getConsume/"  class="on" title="消费记录">消费记录</a>
                <a href="__URL__/getPresentation/"  title="获赠记录">获赠记录</a>
                
				
				
				<a href="__URL__/getShowList/"  title="直播详细">直播详细</a>
				<!--<a href="__URL__/listAward/"  title="中奖记录">中奖记录</a>//-->
			</div>
			<div class="account-r">
				<div class="recent">近期我有<strong><?php echo ($count); ?></strong>笔交易</div>
				<div class="record">
						<?php
 if($_GET['begin'] != ''){ $begin = $_GET['begin']; } else{ $begin = date('Y-m',time()).'-01'; } if($_GET['end'] != ''){ $end = $_GET['end']; } else{ $end = date('Y-m-d',time()); } ?>
						<input type="text" name="begin" id="begint"  onfocus="WdatePicker({el:'begint'});" class="input-accout" value="<?php echo ($begin); ?>"/>
						<span onclick="WdatePicker({el:'begint'});"></span>
						<p class="line">--</p>
						<input type="text" name="end" id="endt"  onfocus="WdatePicker({el:'endt'});" class="input-accout"  value="<?php echo ($end); ?>"/>
						<span onclick="WdatePicker({el:'endt'});"></span>
						<input type="submit" name="sea-record" class="sea-record" value="搜 索"/>
				</div>
				<div class="recod-tpl">
					<table class="r-table">
						<tr>
							<th class="t1">消费时间</th>
							<th class="t2">消费行为</th>
							<th class="t3">物品 </th>
							<th class="t4">数量</th>
							<th>支付秀币</th>
						</tr>
						<?php if(is_array($consumes)): $i = 0; $__LIST__ = $consumes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td class="t1"><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>
							<td class="t2"><?php echo ($vo['content']); ?></td>
							
								<td class="t3"><img src="<?php echo ($vo['objectIcon']); ?>"/></td>
													
							<td class="t4"><?php echo ($vo['giftcount']); ?></td>
							<td><?php echo ($vo['coin']); ?></td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>			
					</table>
					
						<div class="page">
							<?php echo ($page); ?>
							&nbsp;共<?php echo ($pagecount); ?>页 到第<input type="text" id="pageindex" name="p" value="1" size="2" maxlength="5"/>页 <input type="button" value="确定" onclick="btnScrollPage()"/>
	               	   </div>					
               	   					
				</div>
			</div>
		</div>
	</div>
</div>
</form>
	
	
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
<script language="javascript">
//document.getElementById("begint").value = "2012-09-01";
//document.getElementById("endt").value = "2012-09-08";
function scrollPage(step){
	document.getElementById("pageindex").value = step;
 	document.forms["giftFrm"].submit();
 }
function btnScrollPage(){
 	var curPage = document.getElementById("pageindex").value;
 	document.forms["giftFrm"].submit();
} 
</script>
</body>
</html>