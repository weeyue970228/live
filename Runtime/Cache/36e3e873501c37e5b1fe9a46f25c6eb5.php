<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html>

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<title>秀场<?php echo ($sitename); ?></title>

		
		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



		<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/CoreCSS/home.css" />
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
			<style>
				.anchor-list .live-tip {
					width: 50px;
				}
			</style>

			<div class="wrapper clearfix" style="margin-top:20px;">

				<div class="conL">
					<style>
						.fullSlide {
							height: 170px;
							width: 765px;
						}
						
						.fullSlide .bd li {
							height: 170px;
							width: 765px;
						}
					</style>
					<div class="fullSlide">

						<div class="bd">

							<ul>

								<?php if(is_array($rollpics)): $k = 0; $__LIST__ = $rollpics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li _src="url(<?php echo ($vo["picpath"]); ?>)" style="background:#E2025E center 0 no-repeat;">
										<a target="_blank" href="<?php echo ($vo["linkurl"]); ?>"></a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>

							</ul>

						</div>

						<div class="hd">
							<ul></ul>
						</div>

						<span class="prev"></span>

						<span class="next"></span>

					</div>

					<script type="text/javascript">
						/* 控制左右按钮显示 */
						jQuery(".fullSlide").hover(function() {
							jQuery(this).find(".prev,.next").stop(true, true).fadeTo("show", 0.5)
						}, function() {
							jQuery(this).find(".prev,.next").fadeOut()
						});
						/* 调用SuperSlide */
						jQuery(".fullSlide").slide({
							titCell: ".hd ul",
							mainCell: ".bd ul",
							effect: "fold",
							autoPlay: true,
							autoPage: true,
							trigger: "click",
							startFun: function(i) {
								var curLi = jQuery(".fullSlide .bd li").eq(i); /* 当前大图的li */
								if (!!curLi.attr("_src")) {
									curLi.css("background-image", curLi.attr("_src")).removeAttr("_src") /* 将_src地址赋予li背景，然后删除_src */
								}
							}
						});
					</script>

					<div class="hot-anchor" id="editorReco">

						<div class="hot-anchor-bd clearfix anchor-list" style="width:100%;">

							<ul style="width:100%;">

								<?php if(is_array($members)): $k = 0; $__LIST__ = $members;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>

										<a href="/<?php echo ($vo['curroomnum']); ?>" target="_blank" class="js-play" data-keyfrom="recommend2.pic">

											<img src="<?php echo ($vo['snap']); ?>" onerror="this.src='__PUBLIC__/images/default.gif'" width="180" height="120" />

											<?php if($vo["broadcasting"] == y): ?><em class="png24 live-tip">直播</em>

												<?php else: ?>

												<em class="png24 live-tip">未开</em><?php endif; ?>

											<p class="anchor-icon"></p>

											<p class="hot-anchor-hover"></p>

											<em class="anchor-play png24 icon-play"></em>

											<p class="hot-anchor-cover"></p>

											<p class="hot-anchor-fans"><em class="png24 icon-fansS"></em>
												<?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?>
											</p>

										</a>

										<p class="anchor-name"><a target="_blank" href="/<?php echo ($vo['curroomnum']); ?>" data-keyfrom="recommend2.word"><?php echo ($vo["nickname"]); ?></a><em title="" class="png24 star star<?php echo ($vo["emceelevel"]); ?>"></em></p>

									</li><?php endforeach; endif; else: echo "" ;endif; ?>

							</ul>

						</div>

					</div>

					<div class="page"><?php echo ($page); ?></div>

				</div>

				<div class="conR">

					<div class="conR-item home-zhubo" id="zhuboGold" style="">

						<div class="conR-hd">

							<h2>金牌主播</h2>

						</div>

						<div class="ladyScroll">

							<a class="prev" href="javascript:void(0)"></a>

							<div class="scrollWrap">

								<div class="dlList">

									<?php if(is_array($xinemcees)): foreach($xinemcees as $key=>$xin): ?><dl>

											<dt><a href="/<?php echo ($xin['curroomnum']); ?>"><img src="<?php echo ($xin['bigpic']); ?>"></a><span></span></dt>

											<dd><a href="/<?php echo ($xin['curroomnum']); ?>"><?php echo ($xin["nickname"]); ?></a></dd>

										</dl><?php endforeach; endif; ?>

								</div>

							</div>

							<a class="next" href="javascript:void(0)"></a>

						</div>

						<script type="text/javascript">
							jQuery(".ladyScroll").slide({
								mainCell: ".dlList",
								effect: "leftLoop",
								vis: 1,
								autoPlay: true
							});
						</script>

					</div>

					<div class="conR-item home-news home-notice">

						<div class="conR-hd">

							<h2>公告</h2>

						</div>

						<div class="home-news-bd">

							<ul>

								<?php if(is_array($announce)): $i = 0; $__LIST__ = $announce;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><em></em><a href="__APP__/Activity/huodonginfo/info/<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a>

									</li><?php endforeach; endif; else: echo "" ;endif; ?>

							</ul>

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

		</div>

	</body>

</html>