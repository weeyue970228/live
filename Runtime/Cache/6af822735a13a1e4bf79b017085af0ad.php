<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>兑换-我的账单-管理中心-<?php echo ($sitename); ?></title>
<link rel="stylesheet" href="__PUBLIC__/css/common.css" type="text/css" />
<script language="javascript" src="__PUBLIC__/js/CoreJS/jquery.js"></script>
<script language="javascript" src="__PUBLIC__/js/CoreJS/common.js"></script>
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
<link rel="stylesheet" href="/Public/newtpl2/css/common.css" type="text/css" />
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
			<div class="exchange">
				<div class="nums"><span>我的秀豆:</span><strong><?php echo ($userinfo['beanbalance']); ?></strong></div>
				<div class="deal-view">
					<div class="item">
						<p>想兑换的秀豆数量：</p>
						<p>
							<label><input type="radio" name="want" value="1"  onclick="javascript:exchangeAll()"/>全部</label>
							<label class="ml20"><input type="radio" name="want" value="0" /><input type="text" class="set_input" onkeyup="javascript:canget()" name="showsettlementB" id="showsettlementB"/></label>
						</p>
					</div>
					<div class="item no">
						<p>可兑换的秀币数量：</p>
						<p><input type="text" class="set_input set3" name="can" id="can" readonly="true"/></p>
						<p class="">提示：秀豆和秀币的兑换比率是 1:1</p>
						<p class="btn_change"><span class="change_data" onclick="javascript:exchange()">立即兑换</span></p>
					</div>
				</div>
			</div>
			<div class="ex_history">
				<div class="record">
					<strong>历史兑换记录</strong>
				</div>
				<div class="recod-tpl">
				                                                        
					<table class="r-table">
						<tr>
							<th class="t1">兑换时间</th>
							<th class="t2">己兑换的秀豆</th>
							<th class="t3">获得的秀币</th>
						</tr>
						<?php if(is_array($exchanges)): $k = 0; $__LIST__ = $exchanges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
								<td class="t1"><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>
								<td class="t2"><?php echo ($vo['bean']); ?></td>
								<td class="t3"><?php echo ($vo['bean']); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</table>
				</div>
			</div>
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
<script language="javascript">
function exchangeAll(){
	document.getElementById("showsettlementB").value="<?php echo ($userinfo['beanbalance']); ?>.00000".split(".")[0];
	document.getElementById("can").value="<?php echo ($userinfo['beanbalance']); ?>.00000".split(".")[0];
}
function canget(){
	document.getElementById("can").value=document.getElementById("showsettlementB").value;
	var checkObj = document.getElementsByName("want");
	for (i=0;i<checkObj.length;i++) {
       if (checkObj[i].value=="0") {
			checkObj[i].checked = true;
       }else{
       		checkObj[i].checked = false;
       }
    }
}
function exchange(){
	changelimit = document.getElementById("can").value;
	if(!(/^(\-?)(\d+)$/.test(changelimit))){
		alert("请输入数字");
		document.getElementById('can').focus();
		return;
	}					
    $.post('__URL__/doExchange/rand/'+Math.random(),{
 		changelimit:changelimit},function(data){
			if(data=="000000"){
				alert("兑换成功");
				document.location.reload();
			}else{
				alert("兑换失败");
			}
 		}
 	)
}
</script>
</body>
</html>