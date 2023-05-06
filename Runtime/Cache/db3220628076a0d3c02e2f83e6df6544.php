<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>首页</title>
		
		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		


    
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/index.css" type="text/css" />
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

        <div class="scroll_top">
			<div class="fullSlide">
				
				<div class="bd">
					<ul>
						<?php if(is_array($rollpics)): $k = 0; $__LIST__ = $rollpics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li _src="url(<?php echo ($vo["picpath"]); ?>)" ><a target="_blank" href="<?php echo ($vo["linkurl"]); ?>"></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
		
				<div class="hd"><ul></ul></div>
				<span class="prev"></span>
				<span class="next"></span>
			</div>        	
        </div>	
		<div class="contenter clear">
			<div class="mian clear">
				<div class="layout-main w1280">
					<div class="bx hot-wrap nn-wrap" id="newAnchor">
						<div class="hd">
							<a href="/index.php/User/sign_view/" target="_blank" class="sign_view">我要直播</a>	
							<a href="/index.php/User/sign_view/" target="_blank" class="sign_view">我要签约</a>
							<i class="t-icon"></i>
							<h2>新人直播</h2>
							<div class="tab-tit">
								<ul>
									<li>
										<a href="javascript:void(0);" data-target-id="newAnchor" class="active">新人直播</a>
									</li>
									
									<?php
 if($_SESSION['uid'] != '' && $_SESSION['uid'] != null || $_SESSION['uid'] > 0){ ?>
									<li><span class="tab-tit-line"></span></li>	
									<li>
										<a href="javascript:void(0);" data-target-id="concern">我关注的</a>
									</li>
									<li><span class="tab-tit-line"></span></li>	
									<li>
										<a href="javascript:void(0);" data-target-id="seen">我看过的</a>
									</li>
		                            <?php }?>
									
								</ul>
							</div>					
						</div>
						<div class="bd mih175">
							<div class="tab-content tab-cur" data-content="newAnchor">
								<ul>
								<?php if(is_array($newAnchor)): $i = 0; $__LIST__ = $newAnchor;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<div class="live-panel live-panel-tag">
											<div class="host-pic">
												<img class="thumb" width="200" height="133" alt="<?php echo ($vo['nickname']); ?>" src="/Public/newtpl2/images/lazyload.png" data-original="<?php echo ($vo['snap']); ?>">
												<a class="play-mask" target="room" href="/<?php echo ($vo['curroomnum']); ?>">
												<div class="back"></div>
												<span class="play-btn">播放</span>
												</a>
											</div>
											<h4 class="name">
											<a target="room" href="/<?php echo ($vo['curroomnum']); ?>"><?php echo ($vo['nickname']); ?></a>
											<!--<span class="host-type">热舞秀 </span>-->
											</h4>
											<p class="status fr">
												<span class="" title="观众数">
												<span class="ico ico-peo"></span><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?></span>
												<!--<span class="viewer" title="粉丝数">
												<span class="ico ico-fans"></span><?php echo ($vo['fans']); ?></span>-->
											</p>
											<?php if($vo['broadcasting'] == 'y'): ?><span class="live-tip">直播中<span class="arrow"></span></span>
											<?php elseif( $vo['offlinevideo'] != null && $vo['offlinevideo'] != '' ): ?>	
											    <span class="live-tip">录像<span class="arrow"></span></span><?php endif; ?>
										</div>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>		
		
								</ul>
							</div>
						</div>
					</div>							
				</div>

			</div>
			
			<div class="main clear">
				<div class="layout-main">
					<div class="bx recommend-wrap">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>特别推荐</h2>
						</div>
						<div class="bd">
							<ul>
							   <?php if(is_array($recommend)): $i = 0; $__LIST__ = $recommend;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($i == '1' ): ?><li  class="hl" >
										<div class="live-panel">
											<div class="host-pic" title="<?php echo ($vo['nickname']); ?>">
												
												<img class="thumb" width="380" height="283" alt="<?php echo ($vo['nickname']); ?>" 
													src="/Public/newtpl2/images/lazyload.png" data-original="<?php echo ($vo['snap']); ?>">
												
												<a class="play-mask"  href="/<?php echo ($vo['curroomnum']); ?>">
													<div class="back"></div>
													<span class="play-btn">播放</span>
												</a>
											</div>
											<div class="lv">
												<i class="ico-lv zb-lv lv6"></i>
											</div>
											<h4 class="name">
												<a  href="/<?php echo ($vo['curroomnum']); ?>"><?php echo ($vo['nickname']); ?></a>
											</h4>
											<p class="status">
												<!--<span class="live-time">
												<span class="ico ico-clock"></span>
												<span class="time"> 19分钟</span>
												</span>-->
												<span class="viewer" title="观众数">
												<span class="ico ico-peo"></span>
												<span class="num"><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?></span>
												</span>
											</p>
											<?php if($vo['broadcasting'] == 'y'): ?><span class="live-tip">直播中<span class="arrow"></span></span><?php endif; ?>	
											
										</div>
									</li>		
								<?php else: ?>
									<li>
										<div class="live-panel">
											<div class="host-pic" title="<?php echo ($vo['nickname']); ?>">
												<img class="thumb" width="180" height="101" alt="<?php echo ($vo['nickname']); ?>" 
													src="/Public/newtpl2/images/lazyload.png" data-original="<?php echo ($vo['snap']); ?>">												 
												 
												<a class="play-mask"  href="/<?php echo ($vo['curroomnum']); ?>">
													<div class="back"></div>
													<span class="play-btn">播放</span>
												</a>
											</div>
											<div class="lv">
												<i class="ico-lv zb-lv lv6"></i>
											</div>
											<h4 class="name">
												<a  href="/<?php echo ($vo['curroomnum']); ?>"><?php echo ($vo['nickname']); ?></a>
												<span class="viewer" title="观众数">
												<span class="ico ico-peo"></span>
												<span class="num"><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?></span>
												</span>
											</h4>
										
											<?php if($vo['broadcasting'] == 'y'): ?><span class="live-tip">直播中<span class="arrow"></span></span><?php endif; ?>	
											
										</div>
									</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

							</ul>
						</div>
					</div>
				</div>
				<div class="layout-side">
					<div class="bx preview-wrap">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>直播预告表</h2>
						</div>
						<div class="bd">
							<div class="content prev-count-5" >
								<ul class="list-default">
									<?php if(is_array($preview)): $i = 0; $__LIST__ = $preview;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<a  class="list-default-item preview-host" href="/<?php echo ($vo['curroomnum']); ?>" >
											<img class="photo" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['ucuid']); ?>&size=middle"  width="50" height="50">
											<p class="name">
												<?php echo ($vo['nickname']); ?>
											</p>
											<p class="time">
												<span class="ico ico-nic"></span>今天 <?php echo (date("H:i",$vo['starttime'])); ?>
											</p>
										</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>

								</ul>
							</div>
						</div>
					</div>					
					
				</div>
				
			</div>
			
			<div class="main clear">
				<div class="layout-main">
					<div class="bx hot-wrap hot-host-wrap hot-live-wrap" id="liveAnchor" data-pb-block="15041004">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>正在直播</h2>
							<div class="tab-tit">
								<ul>
									<!--<li>
										<a href="javascript:void(0);" data-target-id="live" class="active" >正在直播</a>
									</li>
									<li><span class="tab-tit-line"></span></li>	
									<li>
										<a href="javascript:void(0);" data-target-id="xiuchang" >秀场直播	</a>
									</li>
									<li><span class="tab-tit-line"></span></li>	
									<li>
										<a href="javascript:void(0);" data-target-id="youxi" >游戏直播</a>
									</li>-->
								</ul>
							</div>
							<!--<a href="javascript:void(0);" class="reload"><span class="ico ico-reload"></span>换一换</a>-->
						</div>
						<div class="bd">
							<div class="tab-content tab-cur" data-content="live">
								<ul>
								<?php if(is_array($on_live)): $i = 0; $__LIST__ = $on_live;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="live-panel">
										<div class="host-pic">
											<img class="thumb" width="180" height="120" alt="<?php echo ($vo['nickname']); ?>" src="/Public/newtpl2/images/lazyload.png" data-original="<?php echo ($vo['snap']); ?>">
											<a class="play-mask" href="/<?php echo ($vo['curroomnum']); ?>">
											<div class="back"></div>
											<span class="play-btn">播放</span>
											</a>
										</div>
										<h4 class="name">
										<a href="/<?php echo ($vo['curroomnum']); ?>" ><?php echo ($vo['nickname']); ?></a>
										<!--<span class="host-type">热舞秀</span>-->
										</h4>
										<p class="status fr">
											<span class="" title="观众数">
											<span class="ico ico-peo"></span><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?>												</span>
											<!--<span class="viewer" title="粉丝数">
											<span class="ico ico-fans"></span><?php echo ($vo['fans']); ?></span>-->
										</p>
										<span class="live-tip">直播中<span class="arrow"></span></span>
									</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	

								</ul>
							</div>
						</div>
					</div>				
				
				</div>
				<div class="layout-side">
					<div class="bx ranks-wrap ml-ranks-wrap" id="_charmingRank" click-type="popularity_rank">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>魅力主播</h2>
						</div>
						<div class="bd">
							<div class="tab-tit">
								<ul >
									<li onclick="turn(1,4,1)" id="lm1_1">
										<a href="javascript:void(0);" >日榜</a>
									</li>
									<li onclick="turn(2,4,1)" id="lm1_2">
										<a href="javascript:void(0);" >周榜</a>
									</li>
									<li onclick="turn(3,4,1)" id="lm1_3">
										<a href="javascript:void(0);" >月榜</a>
									</li>
									<li class="on" onclick="turn(4,4,1)" id="lm1_4">
								 		 <a href="javascript:void(0);" >总榜</a>
									</li>
								</ul>
							</div>
							<div class="tab-content tab-cur">
								<ol class="list-default" data-type="day" id="content1_1" style="display: none;">
								<?php if(is_array($emceeRank_day1)): $i = 0; $__LIST__ = $emceeRank_day1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<a class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" href="/<?php echo ($vo['userinfo']['curroomnum']); ?>"  >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($all['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<?php if($vo['userinfo']['broadcasting'] == 'y'): ?><span class="live-tip">直播中</span><?php endif; ?>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="star star<?php echo ($vo['emceelevel'][0]['levelid']); ?>"></span></div>
									</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	

								</ol>
								<ol class="list-default" data-type="week" id="content1_2" style="display: none;">
								<?php if(is_array($emceeRank_week1)): $i = 0; $__LIST__ = $emceeRank_week1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<a class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" href="/<?php echo ($vo['userinfo']['curroomnum']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($all['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<?php if($vo['userinfo']['broadcasting'] == 'y'): ?><span class="live-tip">直播中</span><?php endif; ?>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="star star<?php echo ($vo['emceelevel'][0]['levelid']); ?>"></span></div>
									</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
								</ol>
								<ol class="list-default" data-type="month" id="content1_3" style="display: none;">
								<?php if(is_array($emceeRank_month1)): $i = 0; $__LIST__ = $emceeRank_month1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<a class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" href="/<?php echo ($vo['userinfo']['curroomnum']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($all['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<?php if($vo['userinfo']['broadcasting'] == 'y'): ?><span class="live-tip">直播中</span><?php endif; ?>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="star star<?php echo ($vo['emceelevel'][0]['levelid']); ?>"></span></div>
									</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
								</ol>
								<ol class="list-default" data-type="all" id="content1_4" >
								<?php if(is_array($emceeRank_all1)): $i = 0; $__LIST__ = $emceeRank_all1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<a class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" href="/<?php echo ($vo['userinfo']['curroomnum']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($all['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<?php if($vo['userinfo']['broadcasting'] == 'y'): ?><span class="live-tip">直播中</span><?php endif; ?>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="star star<?php echo ($vo['emceelevel'][0]['levelid']); ?>"></span></div>
									</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
	
								</ol>
								
							</div>
						</div>
					</div>					
					
				</div>
			</div>				 
			
			<div class="main clear">
				<div class="layout-main">
					<div class="bx hot-wrap hot-host-wrap" id="hotAnchor" >
						<div class="hd">
							<i class="t-icon"></i>
							<h2>热门主播</h2>
							<div class="tab-tit">
								<ul>
									<li>
										<a href="javascript:void(0);" data-target-id="hot" class="active" >热门主播</a>
									</li>
									
									<?php if(is_array($usersorts)): $i = 0; $__LIST__ = $usersorts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(is_array($vo["voo"])): $i = 0; $__LIST__ = $vo["voo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><li><span class="tab-tit-line"></span></li>	
											<li>
												<a href="javascript:void(0);" data-target-id="<?php echo ($vo2["id"]); ?>" ><?php echo ($vo2["sortname"]); ?></a>
											</li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
	
									<!--<li class="all">
									<a href="#"  class="_allAnchor">全部&gt;</a>
									</li>-->
								</ul>
							</div>
							<!--<a href="javascript:void(0);" class="reload"><span class="ico ico-reload"></span>换一换</a>-->
						</div>
						<div class="bd">
							<div class="tab-content tab-cur" data-content="hot">
								<ul>									
									<?php if(is_array($hot)): $i = 0; $__LIST__ = $hot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<div class="live-panel live-panel-tag">
											<div class="host-pic">
												<img class="thumb" width="180" height="120" alt="<?php echo ($vo['nickname']); ?>" src="/Public/newtpl2/images/lazyload.png" data-original="<?php echo ($vo['snap']); ?>">
												<a class="play-mask" target="room" href="/<?php echo ($vo['curroomnum']); ?>">
												<div class="back"></div>
												<span class="play-btn">播放</span>
												</a>
											</div>
											<h4 class="name">
											<a target="room" href="/<?php echo ($vo['curroomnum']); ?>"><?php echo ($vo['nickname']); ?></a>
											<!--<span class="host-type">热舞秀 </span>-->
											</h4>
											<p class="status fr">
												<span class="" title="观众数">
												<span class="ico ico-peo"></span><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?></span>
												<!--<span class="viewer" title="粉丝数">
												<span class="ico ico-fans"></span><?php echo ($vo['fans']); ?></span>-->
											</p>
											<?php if($vo['broadcasting'] == 'y'): ?><span class="live-tip">直播中<span class="arrow"></span></span>
											<?php elseif( $vo['offlinevideo'] != null && $vo['offlinevideo'] != '' ): ?>	
											    <span class="live-tip">录像<span class="arrow"></span></span><?php endif; ?>
										</div>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>									

								</ul>
							</div>
						</div>
					</div>				
				
				</div>
				<div class="layout-side">
					<div class="bx ranks-wrap fh-ranks-wrap" id="_charmingRank" click-type="popularity_rank">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>富豪榜</h2>
						</div>
						<div class="bd">
							<div class="tab-tit">
								<ul >
									<li onclick="turn(1,4,2)" id="lm2_1">
										<a href="javascript:void(0);" >日榜</a>
									</li>
									<li onclick="turn(2,4,2)" id="lm2_2">
										<a href="javascript:void(0);" >周榜</a>
									</li>
									<li onclick="turn(3,4,2)" id="lm2_3">
										<a href="javascript:void(0);" >月榜</a>
									</li>
									<li class="on" onclick="turn(4,4,2)" id="lm2_4">
								 		 <a href="javascript:void(0);" >总榜</a>
									</li>
								</ul>
							</div>
							<div class="tab-content tab-cur">
								<ol class="list-default" data-type="day" id="content2_1" style="display: none;">
								<?php if(is_array($richRank_day1)): $i = 0; $__LIST__ = $richRank_day1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="list-default-item rank-item <?php if($i < '3'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>	-->																	
									</p>
									<div class="trans"><span class="cracy cra<?php echo ($vo['richlevel'][0]['levelid']); ?>"></span></div>
									</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	

	
								</ol>
								<ol class="list-default" data-type="week" id="content2_2" style="display: none;">
								<?php if(is_array($richRank_week1)): $i = 0; $__LIST__ = $richRank_week1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="cracy cra<?php echo ($vo['richlevel'][0]['levelid']); ?>"></span></div>
									</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
	
								</ol>
								<ol class="list-default" data-type="month" id="content2_3" style="display: none;">
								<?php if(is_array($richRank_month1)): $i = 0; $__LIST__ = $richRank_month1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="list-default-item rank-item <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="cracy cra<?php echo ($vo['richlevel'][0]['levelid']); ?>"></span></div>
									</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
								</ol>
								<ol class="list-default" data-type="all" id="content2_4" >
								<?php if(is_array($richRank_all1)): $i = 0; $__LIST__ = $richRank_all1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="list-default-item rank-item  <?php if($i < '4'): ?>top-item<?php endif; ?> <?php if($i == '1'): ?>rank-item-active<?php endif; ?>" title="<?php echo ($vo['userinfo']['nickname']); ?>" >
									<span class="order"><?php echo ($i); ?></span>
									<img class="pic" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['userinfo']['ucuid']); ?>&size=middle" width="50" height="50">
									<em class="name"><?php echo ($vo['userinfo']['nickname']); ?></em>
									<p class="viewer" >
										<!--<span class="ico ico-fans"></span>-->
									</p>
									<div class="trans"><span class="cracy cra<?php echo ($vo['richlevel'][0]['levelid']); ?>"></span></div>
									</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>	
								</ol>
								
							</div>
						</div>
					</div>					
					
				</div>
			</div>				 
			
			<div class="main clear">
				<div class="layout-main">
					<div class="bx week-star-wrap">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>
							<span class="sr-only">明星家族</span>
							
							</h2>
						</div>
						<div class="bd">
							<ul >
							<?php if(is_array($family)): $i = 0; $__LIST__ = $family;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
								<a href="/index.php/Jiazu/family_detail/id/<?php echo ($vo['id']); ?>.html"  class="week-item">
								<div class="gift">
									<!-- ie11下元素带边框时设置圆角有锯齿 -->
									<div class="inner">
										<img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['zz']['ucuid']); ?>&size=middle" width="60" height="60">
									</div>
								</div>
								<p class="num">
									成员数<em class="em"><?php echo ($vo["total"]); ?></em>名
								</p>
								<div class="pic">
									<img src="/Public/Familyimg/<?php echo ($vo['familyimg']); ?>" width="130" height="130">
									<div class="back"></div>
									<span class="play-btn">播放</span>
								</div>
								<p class="name"><?php echo ($vo["familyname"]); ?></p>
								<!--<span class="live-tip">直播中<span class="arrow"></span></span>-->
								</a>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>		
								
							</ul>
						</div>
					</div>					
										
				</div>
				<div class="layout-side">
					
					<div class="bx notice-wrap">
						<div class="hd">
							<i class="t-icon"></i>
							<h2>公告</h2>
						</div>
						<div class="bd">
							<div class="content" >
								<ul>
									<?php if(is_array($announce)): $i = 0; $__LIST__ = $announce;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span class="ico ico-point-black"></span><a href="__APP__/Activity/huodonginfo/info/<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?> 
								</ul>
							</div>
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
	    <script src="__PUBLIC__/js/CoreJS/index.js"></script>
	    
	</body>
</html>