<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>家族首页</title>

		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		



<link rel="stylesheet" type="text/css" href="/Public/css/family/family.css"/>
    <style type="text/css">
        .header .header-content .header-search .header-search-text{
            padding-right:30px;
        }
    </style>


<script>
    $(function(){
        //打开创建家族窗口
        $('.create_btn').click(function(){
            var level="<?php echo ($level); ?>";
           
            if(level >=8 ){
                $('.add_family_modal').show().css({
                    'padding-top': ($(window).height() - $('.add_family_modal .body').height())/2
                });
            }else{
                  $('.create_family_msg').show().css({
                    'padding-top': ($(window).height() - $('.create_family_msg .body').height())/2
                  });
            }
        });

        //关闭窗口
        $('.close_modal, .close_btn').click(function(){
            $($(this).attr('data-modal')).hide().find('input, textarea').val('');
        });

    })
</script>
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
	<div class="family">
    	<div class="family_banner"><img src="/Public/images/background/family_banner.jpg" /></div>
        <div class="family_c">
            <div class="family_l">
                <!-- 加入家族后显示，未加入家族隐藏 -->
                
                <div class="family_my">
                	<?php if(($userinfo['agentuid'] > 0) or ($userinfo['emceeagent'] == 'y') ): ?><h2>我的家族</h2>
                    <a href="<?php echo U('Jiazu/family_detail',array('id'=>$myfamilyinfo['familyinfo']['id']));?>">
                    <div class="family_brief">
                        <span><img src="/Public/Familyimg/<?php echo ($myfamilyinfo['familyinfo']['familyimg']); ?>" onerror="javascript:this.src='/Public/images/default.gif'"/></span>
                        <div class="family_brief_c">
                            <h3><?php echo ($myfamilyinfo['familyinfo']['familyname']); ?></h3>
                            <p>家族人数：<?php echo ($myfamilyinfo['jiazurenshu']); ?></p> 
                        </div>
                        <div>
                            <img src="/Public/images/person_label.png" />
                            <p>主播人数：<?php echo ($myfamilyinfo['emceeagent']); ?></p>
                        </div>
                    </div>
                    </a><?php endif; ?>
                </div>
               
                <div class="family_new">
                    <h2>最新家族</h2>
                    <ul>
                    <?php if(is_array($newagent)): foreach($newagent as $key=>$new): ?><li>
                        	<a href="<?php echo U('Jiazu/family_detail',array('id'=>$new['id']));?>">
                        		<img src="/Public/Familyimg/<?php echo ($new['familyimg']); ?>" onerror="javascript:this.src='/Public/images/default.gif'" />
                        		<div>
                        			<h6><?php echo ($new["familyname"]); ?></h6>
                        			<p>族长：<?php echo ($new['agentinfo']['nickname']); ?></p>
                        			<p>成员：<?php echo ($new['zbtotal']['zbtotal']); ?></p>
                        		</div>
                        	</a>
                        	<span class="icon-icon19"></span>
                        </li><?php endforeach; endif; ?>
                    </ul>
                </div>
                <div class="family_hot">
                    <h2>热门家族</h2>
                    <ul>
                    <?php if(is_array($hotfamily)): $k = 0; $__LIST__ = $hotfamily;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
                        	<a href="<?php echo U('Jiazu/family_detail',array('id'=>$vo['agentuid']));?>">
                        		<img src="/Public/Familyimg/<?php echo ($vo['familyinfo']['familyimg']); ?>" onerror="javascript:this.src='/Public/images/default.gif'" />
                        		<div>
                        			<h6><?php echo ($vo['familyinfo']["familyname"]); ?></h6>
                        			<p>族长：<?php echo ($vo['agentinfo']["nickname"]); ?></p>
                        			<p>成员：<?php echo ($vo["total"]); ?></p>
                        		</div>
                        	</a>
                        	<span class="icon-icon19"></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
            </div>
            <div class="family_r">
                <!-- 未加入家族 -->
                <?php if($sqfamily['zhuangtai'] == '未审核' ): ?><a class="kong">家族申请正在审核中...</a>
                <?php elseif(($userinfo['agentuid'] == 0 ) and ($userinfo['emceeagent'] == 'n') ): ?>
                
                <input type="button" value="创建家族" class="create_btn"/>
                <label >需要 10 等级以上才可申请</label>
                <?php elseif(($userinfo['agentuid'] > 0 ) or ($userinfo['emceeagent'] == 'y')): ?>
                    <a href="<?php echo U('Jiazu/family_detail',array('id'=>$myfamilyinfo['familyinfo']['id']));?>" class="family_detail">进入家族</a>
                <?php else: ?>
                    <a class="kong"></a><?php endif; ?>

                <div class="family_ranking">
                    <h2>家族排行</h2>
                    <ul>
                    <?php if(is_array($paihang)): $i = 0; $__LIST__ = $paihang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        	<span><?php echo ($i); ?></span>
                        	<a href="<?php echo U('Jiazu/family_detail',array('id'=>$vo['familyinfo']['id']));?>">
                        		<img <?php if($vo['familyinfo']['familyimg']): ?>src="/Public/Familyimg/<?php echo ($vo['familyinfo']['familyimg']); ?>"<?php else: ?>src="/Public/images/family/family_ranking_img.jpg"<?php endif; ?> />
                        	</a>
                        	<div>
                        		<p><?php echo ($vo['familyinfo']['familyname']); ?></p>
                        		<p>人数：<?php echo ($vo['tatol']); ?></p>
                        	</div>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="add_family_modal">
                <form action="<?php echo U('Jiazu/sqagent');?>" method="post" enctype="multipart/form-data" id="createfamily">
                    <div class="body">
                        <p class="title clearfix">
                            <a href="javascript:;" class="close_modal right" data-modal=".add_family_modal">×</a>
                        </p>
                        <h1>创建家族</h1>
                        <div class="item">
                            <span class="label inline-block">家族名称：</span>
                            <input type="text" class="family_name_text" name="familyname" />
                        </div>
                        <div class="item">
                            <span class="label inline-block">家族宣言：</span>
                            <textarea class="family_text" name="familyinfo"></textarea>
                        </div>
                        <div class="item">
                            <span class="label inline-block">家族logo：</span>
                            <input type="file" class="family_logo" name="familyimg" />
                        </div>
                        <div class="item">
                            <span class="label inline-block">徽章名称：</span>
                            <input type="text" class="badge_name" name="declaration" />
                            <span class="badge_name_msg">3个汉字内</span>
                        </div>
                        <button class="submit_btn">确认申请</button>
                    </div>
                </form>
                </div>
                <div class="create_family_msg">
                    <div class="body">
                        <p class="title">
                            <span>提示</span>
                            <a href="javascript:;" class="close_modal right" data-modal=".create_family_msg">×</a>
                        </p>
                        <p class="content">
                            <i class="inline-block"></i>
                            <span>创建家族需要10富</span>
                        </p>
                        <button class="close_btn" data-modal=".create_family_msg">确定</button>
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
    <script type="text/javascript">
        /*$(document).ready(function(){
            $(".submit_btn").on({
                click:function(){
                   $('#createfamily').submit();
                }
            });
        });*/
    </script>
</body>
</html>