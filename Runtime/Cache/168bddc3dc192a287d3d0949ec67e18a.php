<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>道具商城-管理中心-<?php echo ($sitename); ?></title>
		
		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



		<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/shop.css" type="text/css" />
		<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/car.css" type="text/css" />

		<script type="text/javascript">
			//test
			function buy(toolid, toolsubid) {
				var info = document.getElementById('info_' + toolsubid);
				if (confirm("确认要购买此礼物吗?")) {
					jQuery.ajax({
						type: "get",
						dataType: "json",
						url: "__URL__/buyTool/toolid/" + toolid + "/toolsubid/" + toolsubid + "/v/" + Math.random(),
						beforeSend: function(XMLHttpRequest) {
							info.innerHTML = '<img src="__PUBLIC__/images/loading.gif"> ...';
						},
						success: function(data, textStatus) {
							alert(data.msg);
							info.innerHTML = "";
							if (data.msg == "您的余额不足") {
								window.location.href = '../charge/';
							}
						},
						complete: function(XMLHttpRequest, textStatus) {
							//alert('complete');											
						},
						error: function() {
							//inner.innerHTML='<font color=green>出错啦 稍后再试</font>';	
						}
					});
				}
			}
		</script>
	</head>

	<body style="background: #f1f1f1;;">
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

			<div class="shop-bd">
				<div class="shop-product">
					<div class="shop-product-hd"><em class="png24 shop-floor">1F</em>
						<h2>会员与特权</h2>

					</div>
					<div class="shop-product-bd">
						<ul class="clearfix js-bd-list">
							<li class="shop-item tq1" style="height:500px;">
								<p class="shop-sale-info"></p>
								<img class="png24 shop-img" src="/Public/carpic/v.png" width="170" height="110">
								<h3> 至尊VIP</h3>
								<p>限10富及以上等级玩家购买</p>
								<p>防止被踢</p>
								<p>防止被禁言
									<p/> 可以进入满员房间
									<p/>
									<p>1个月：20000秀币
										<button onClick="buy('1','1')">购买</button>
										<span id="info_1"></p>
              <p>12个月：120000秀币
                <button style="" onClick="buy('1','4')">购买</button>
                <span id="info_4"></p>
              <div class="shop-buy-more"> <a class="js-goBuy" href="javascript:;" data-method="give" data-itemid="63">赠送</a> </div>
            </li>
					            <li class="shop-item tq2" style="height:500px;">
					              <p class="shop-sale-info"></p>
					              <img class="png24 shop-img" src="/Public/carpic/basic-vip.png" width="170" height="110">
					              <h3>黄金VIP</h3>
					              <p>限3富及以上等级玩家购买</p>
					              <p>除房主以外，防止被踢、禁言<p/>
					              可以进入满员房间<p/>
					              房间排位直接升至10富上面
					              <p/>
					              <p>1个月：15000秀币
					                <button onClick="buy('2','5')">购买</button>
					                <span id="info_5"></p>
					              <p>12个月：100000秀币
					                <button style="" onClick="buy('2','8')">购买</button>
					                <span id="info_8"></p>
					              <p></p>
					              <div class="shop-buy-more"> <a class="js-goBuy" href="javascript:;" data-method="give" data-itemid="63">赠送</a> </div>
					            </li>
					            <li class="shop-item tq3" style="height:500px;">
					              <p class="shop-sale-info"></p>
					              <img class="png24 shop-img" src="/Public/carpic/man.png" width="170" height="110">
					              <h3>金钥匙</h3>
					              <p>不限条件</p>
					              <p>可进入满房房间<br />
					              </p>
					              <p>1个月：15000秀币
					                <button onClick="buy('3','9')">购买</button>
					                <span id="info_9"></p>
					              <p></p>
					              <div class="shop-buy-more"> <a class="js-goBuy" href="javascript:;" data-method="give" data-itemid="63">赠送</a> </div>
					            </li>
					            <li class="shop-item tq4" style="height:500px;">
              <p class="shop-sale-info"></p>
              <img class="png24 shop-img" src="/Public/carpic/jin.png" width="170" height="110">
              <h3>随意说</h3>
              <p>不限条件</p>
              <p>每日免费发布100条广播<br />
              </p>
              <p>1个月：50000秀币
                <button onClick="buy('4','10')">购买</button>
                <span id="info_10"></p>
              <p></p>
              <div class="shop-buy-more"> <a class="js-goBuy" href="javascript:;" data-method="give" data-itemid="63">赠送</a> </div>
            </li>
           						 <!--新道具Start-->
					            <?php include '/lib/action/daojuset.php'; ?>
					         </ul>
          					<div class="clear"></div>
       					 </div>
      				</div>
    			</div>
   				 <div class="shop-product">
      <div class="shop-product-hd"> <em class="png24 shop-floor">2F</em>
        <h2>秀场座驾</h2>
      
      <div class="shop-product-bd shop-prop-bd">
        <div class="car">
          <div class="car_03">
          <?php if(is_array($carList)): $i = 0; $__LIST__ = $carList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="car_07">
              <div class="car_01">
                <div class="car_02"><?php echo ($vo['name']); ?></div>
                <div class="car_04"><img src="<?php echo ($vo['image']); ?>" width="160"></div>
                <b class="shop_ico" carSwf="<?php echo ($vo['swf']); ?>"  showtime="<?php echo ($vo['showTime']); ?>">
								 预览
								</b>
              </div>
              <div class="car_05">售价：<?php echo ($vo['coin']); ?>秀币/月</div>
              <div class="car_06"><a href="#" onClick="buy('5','<?php echo ($vo['id']); ?>')">购买</a></div>
			    <div class="car_08"><span id="info_<?php echo ($vo['id']); ?>"></span></div>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>

			</div>
		</div>
		<div class="clear"></div>
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
		<script>
			var c, l;
			$(".car_01 b").click(function() {
				var e = $(this).attr("carSwf");
				var showtime = $(this).attr("showtime");
				$("#yulan").remove();
				$(this).append('<div  style="position:fixed; top:50%;left:50%; margin-top:-285px;margin-left:-450px" id="yulan"><embed src="' + e + '" wmode="transparent" width="900" height="570" class="flash" type=""></div>');
				clearTimeout(l);
				l = setTimeout(function() {
					$("#yulan").remove();
				}, showtime + 'e3');
			})
		</script>
	</body>

</html>