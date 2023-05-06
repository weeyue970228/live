<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的靓号-管理中心-<?php echo ($sitename); ?></title>

		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



<script language="javascript" src="__PUBLIC__/js/CoreJS/mymanage.js"></script>
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
                <h4>我的靓号 <cite>(<?php echo (count($mynos)); ?>)</cite></h4>
                <span><a href="__APP__/emceeno/">购买其他靓号?</a></span>
            </div>
            <div class="djsc_list">
                <table border="0" class="txt_table">
                  <tr>
                    <th scope="col">靓号</th>
                    <th scope="col">获取时间</th>
                    <th scope="col">到期时间</th>
                    <th scope="col">状态</th>
                    <th scope="col">操作项</th>
                  </tr>
                  <?php if(is_array($mynos)): $i = 0; $__LIST__ = $mynos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><strong>
						<?php
 if($vo['num'] == $userinfo['curroomnum']){ echo $vo['num']; } else{ echo '<a href="#" class="c1" style="color:red;">'.$vo['num'].'</a>'; } ?>
				 </strong></td>
                    <td>  	<?php
 if($vo['num'] == $userinfo['curroomnum']){ echo date('Y-m-d H:i:s',$vo['addtime']); }else{ echo '<span style="color:red;">'.date('Y-m-d H:i:s',$vo['addtime']).'</span>'; }?></td>
                    <td>
                    	<?php
 if($vo['num'] == $userinfo['curroomnum']){ if($vo['expiretime'] == 0){ echo '永久'; } else{ echo date('Y-m-d H:i:s',$vo['expiretime']); } }else{ if($vo['expiretime'] == 0){ echo '<span style="color:red;">永久</a>'; } else{ echo '<span style="color:red;">'.date('Y-m-d H:i:s',$vo['expiretime']).'</span>'; } } ?>
                    </td>
                    <td>
						<?php
 if($vo['num'] == $userinfo['curroomnum']){ echo '<a href="#" class="c1">正在使用</a>'; } else{ echo '<a href="#" class="c1" style="color:red;">停用</a>'; } ?>
                    </td>
                    <td class="numdeal">
                    	<div class="mydeal">
							<?php
 if($vo['original'] == 'y'){ echo '<span>(原始号终身享有)</span>'; if(count($mynos) > 1){ if($vo['num'] != $userinfo['curroomnum']){ echo '<a href="__URL__/setcurroomnum/roomnum/'.$vo['num'].'/" class="c1">启用</a>'; } } } else{ if($vo['num'] != $userinfo['curroomnum']){ echo '<a href="__URL__/setcurroomnum/roomnum/'.$vo['num'].'/" class="c1">启用</a>'; echo '<a href="javascript:showgivebox('.$vo['num'].');" class="c1">赠送他人</a>'; } } ?>
					  </div>
                    </td>
                  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
		</div>
    </div>   
</div>
<script language="javascript">
function showgivebox(num){
	$('#no').html(num);
	$.JShowTip({centerTip:$('#giveBox')});
}
</script>
<!--赠送他人 begin-->
<div id="giveBox" class="poptip" style="display:none;">
	<div class="pop-t">
		<span class="close"></span>
		<h3>赠送他人</h3>
	</div>	
	<div class="pop-v">
       <div id="msg-datas">
            <p><span>要赠出的号码：</span><strong id="no"></strong></p>
            <p><span>选择赠送对象：</span>
                <select id="grantId" name="grantId" class="selectStyle sw135">
                <?php if(is_array($attentions)): $i = 0; $__LIST__ = $attentions;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['attuid']); ?>"><?php echo ($sub['nickname']); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </p>
            <p class="btn">
				<span class="sendBtn" onclick="Manage.GiveGoodNum();">提 交</span>
	 		</p>
       </div>
       <div id="msg-return" style="display:none;">
       	   	<p class="txt" id="return-text"></p>
            <p class="txt">
				<span class="sendBtn" id="return-button">确 定</span>
	 		 </p>
       </div> 
	</div>
	<div class="pop-msg" id="pop-msgs">
		<p>温馨提示：要赠送号码给对方，您需先关注对方。</p>
	</div>
</div>
<!--赠送他人 end-->
	
	
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