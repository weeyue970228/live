<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的道具-管理中心-<?php echo ($sitename); ?></title>

		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



<style>
	.enableMounts
	{
	    width: 86px;
	    height: 26px;
	    line-height: 23px;
	    background: url(/Public/images/daojubtn.png) no-repeat;
	    display: block;

	    text-align: center;
	}
</style>
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
<div class="headhead"></div>


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
    
<script language="javascript">
$("document").ready(function(){
	
	//道具初始化
	
	$.ajax({
		url:"__URL__/queryMounts",
		success:function(data)
		{
			$(".enableMounts").each(function()
			{
				var id = parseInt($(this).attr("enableMountsID")) ;

				if(data == id)
				{
					$(this).text("卸载");
					//$(this).attr("disabled","disabled");
				}
			});
		}
	});
	//装备
	$(".enableMounts").click(function(){
			var mountsID = $(this).attr("enableMountsID");
			var text = $(this).text();
			if(text=="卸载") mountsID = 0;
			var $this = $(this);
			$.ajax(
				{
					url:"__URL__/enableMounts/mountsID/"+mountsID,
					success:function(data)
					{
						if(data==1)
						{
							$(".enableMounts").text("启用");
							$(".enableMounts").removeAttr("disabled");
							if(text!="卸载") $this.text("卸载");
							//$this.attr("disabled","disabled");
							alert("操作成功");
							
						}
						else if(data==-1)
						{
							//道具未购买 正常操作不会出现该问题
						}
						else if(data==2)
						{
							alert("出错了~请刷新重试");
						}
					}
				}
			);
		
	});
		
})



</script>
        <div class="usercenter_con">
            <div class="djsc_top">
                <h4>我的道具</h4>
                <span><a href="__URL__/toolItem/" class="djbuy">购买道具</a></span>
            </div>
            <div class="djsc_list">
                <table border="0">
                  <tr>
                    <th scope="col">道具</th>
                    <th scope="col">威力</th>
                    <th scope="col">操作</th>
                    <th scope="col">有效期</th>
                  </tr>
                  <?php
 if($userinfo['vip'] == '1' and $userinfo['vipexpire'] > time()){ ?>
					 <tr>
		                    <td>至尊VIP<span class='props vip1'></span></td>
		                    <td>
		                    	<ul>
									<li>名称前"<span class="props vip1"></span>"紫色标识</li><li>防止被踢</li><li>防止被禁言</li><li>可以进入满员房间</li>
		                        </ul>
		                    </td>
		                    <td>--</td>
							<td><?php echo date('Y-m-d H:i:s',$userinfo['vipexpire']); ?></td>
					 </tr>
				  <?php
 } ?>
				  <?php
 if($userinfo['vip'] == '2' and $userinfo['vipexpire'] > time()){ ?>
					 <tr>
		                    <td>VIP<span class='props vip2'></span></td>
		                    <td>
		                    	<ul>
									<li>名称前"<span class="props vip2"></span>"标识</li><li>除房主以外，防止被踢</li><li>除房主以外，防止被禁言</li><li>可以进入满员房间</li><li>房间排位直接升至10富上面</li>
		                        </ul>
		                    </td>
		                    <td>--</td>
							<td><?php echo date('Y-m-d H:i:s',$userinfo['vipexpire']); ?></td>
					 </tr>
				  <?php
 } ?>
				  <?php
 if($userinfo['goldkey'] == 'y' and $userinfo['gkexpire'] > time()){ ?>
					 <tr>
		                    <td>金钥匙<span class='props vip4'></span></td>
		                    <td>
		                    	<ul>
									可以进入满员房间
		                        </ul>
		                    </td>
		                    <td>--</td>
							<td><span><?php echo date('Y-m-d H:i:s',$userinfo['gkexpire']); ?></span></td>
					 </tr>
				  <?php
 } ?>
				  <?php
 if($userinfo['atwill'] == 'y' and $userinfo['awexpire'] > time()){ ?>
					 <tr>
		                    <td>随意说<span class='props vip900'></span></td>
		                    <td>
		                    	<ul>
									每日免费发布100条广播
		                        </ul>
		                    </td>
		                    <td>--</td>
							<td><span><?php echo date('Y-m-d H:i:s',$userinfo['awexpire']); ?></span></td>
					 </tr>
				  <?php
 } ?>
				  <?php if(is_array($carList)): $i = 0; $__LIST__ = $carList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                <td><?php echo ($vo['name']); ?><span class='props vip900' style="background:url('<?php echo ($vo["icon"]); ?>');background-position:center center;background-size:100% 100%;"></span></td>
		                    <td>
		                    	<ul>
									<?php echo ($vo['explain']); ?>
		                        </ul>
		                    </td>
		                    <td>
		                    	<button class="enableMounts" enableMountsID="<?php echo ($vo['carID']); ?>">启用</button>
		                    </td>
							<td><span><?php echo date('Y-m-d H:i:s',$vo['endtime']); ?></span></td>
					 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
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
 
</body>
</html>