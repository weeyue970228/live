<?php if (!defined('THINK_PATH')) exit();?>

<?php
if($_SESSION['uid'] == '' || $_SESSION['uid'] == null || $_SESSION['uid'] < 0){ ?>
<ul>
	<!-- <li class="f "><a href="/index.php/login/qq" class="qq_01" onclick="toLogin()"></a></li> -->
	<li class="f" ><a class="login_dl" href="#" onclick="javascript:UAC.openUAC(0); return false;" title="登录">登录</a></li>
	<li ><a class="register_zc" href="#" onclick="javascript:UAC.openUAC(1); return false;" title="注册">注册</a></li>
</ul>
<?php
} else{ ?>
      <ul>
        <!--<li class="f"><a href="/<?php echo ($_SESSION['roomnum']); ?>" title="<?php echo ($_SESSION['nickname']); ?>" target="_blank"><?php echo ($_SESSION['nickname']); ?>(<?php echo ($_SESSION['roomnum']); ?>)</a></li>-->
        
        
        <li class="mycenter"> <a href="javascript:void(0);" class="dropdown"><img class="anchor_photo" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($userinfo['ucuid']); ?>&size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;"   style="width:37px;height:37px;border:1px solid #F44103;border-radius:50%; vertical-align: middle;margin-top:-7px;"/></a>
          <!--管理中心 begin-->
          <div class="mysetting" style="display:none;">
            <div class="setting-tip">
              <h3> <a class="agrzx" style="height: 26px; padding-left: 10px;line-height: 24px;float: left; text-decoration: none;">个人中心</a> <a class="atc" href="#" style="float:right;margin-right:10px;color:#F44103;" onclick="javascript:UAC.logout(window.location.href,2); return false;" title="退出">退出</a></h3>
              <div class="myplay">
			    <div style="width:59px;float:left;height: 59px;"> 				
				<a href="__APP__/User/info_edit/"><img class="anchor_photo" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($userinfo['ucuid']); ?>&size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;"   style="width:55px;height:55px;border:1px solid #EAEDEF;border-radius:50%;margin-top:0;margin-left: 0px;"/> </a>
			    </div>
			    <div style="width:130px;float:right;height:59px;line-height:30px;"> 	
					<p>
					  <p class="us" id="zb_info" style="font-size:12px;background: none;"><strong><a href="/index.php/User/info_edit/" title="<?php echo ($userinfo['nickname']); ?>">
					  <?php
 echo mb_substr($userinfo['nickname'],0,5,"utf-8")."..."; ?>
					  
</a></strong> <span class="<?php echo ($levelstr); ?>"></span></p>
					  <p class="us" id="zb_info" style="font-size:12px;background: none;  float: left;"> <a href="/<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>">ID：<?php echo ($userinfo['id']); ?></a></p>
					</p>
                </div>
				
				<div class="xxtiao" > 	
					<p>
					  <a class="awdzb" href="/<?php echo ($_SESSION['roomnum']); ?>" style="" title="我的直播"  target="_blank">我的直播</a>
					  <a class="aye" href="__APP__/User/userbalance/" title="余额" target="_blank" style=""><span ><?php echo ($userinfo['coinbalance']); ?></span></a>
					  <a class="acz" href="__APP__/User/charge/" title="充值" target="_blank" style="float:right;color:#fff;padding-right:8px;line-height:20px;">充值</a> 
					</p>
                </div>
				
				
              </div>
              <div class="mymenu">
                <ul>
                  <li><a href="__APP__/User/info_edit/" title="个人资料" class="t1"  target="_blank">个人资料</a></li>
                 
                  <li><a href="__APP__/User/showadmin/" title="我的房管" class="t5"  target="_blank">我的房管</a></li>
                  <li><a href="__APP__/User/myNos/" title="我的靓号" class="t6"  target="_blank">我的靓号</a></li>
                  <li><a href="__APP__/User/toolinuse/" title="我的道具" class="t7"  target="_blank">我的道具</a></li>
                  <li><a href="__APP__/User/getConsume/" title="我的账单" target="_blank" class="t8">我的账单</a></li>
                  <li><a href="__APP__/User/charge/" title="充值商城" class="t9"  target="_blank">充值商城</a></li>
                  <li><a href="__APP__/User/myfavor/" title="我的收藏" class="t10"  target="_blank">我的收藏</a></li>
                  <li><a href="__APP__/User/interestByList/" title="关注我的" class="t11"  target="_blank">关注我的</a></li>
                  <li><a href="__APP__/User/interestToList/" title="我关注的" class="t12"  target="_blank">我关注的</a></li>
                <?php
 if($userinfo['emceeagent']=="y"){ echo '<li><a href="__APP__/User/sqmyfamily/" title="我的家族" class="t13"  target="_blank">我的家族</a></li>'; } ?>
                </ul>
              </div>
            </div>
          </div>
          <!--管理中心 end-->
        </li>
		
		<!--消息 begin-->
		
        <!-- <li class="mycenter"> <a href="javascript:void(0);" class="toMeg m-1" id="msgnum">0</a>          
          <div class="mysetting" style="display:none;">
				<div class="setting-tip">
					<h3>消息</h3>
					<div class="mesage-tip">
						<ul id="message">
							
						</ul>
					</div>
				</div>
			</div>
			<script language="javascript" >
			$(document).ready(function(){	
				setTimeout("showMessages()",1000);					
			});
			</script>
        </li> -->
		
		<!--消息 end-->
		
        <!-- <li> <a href="__APP__/User/userbalance/" title="余额" target="_blank"><span class="red"><?php echo ($userinfo['coinbalance']); ?></span></a></li>     
        <li> <a href="__APP__/User/charge/" title="充值" target="_blank">充值</a> </li>
        <li><a href="#" onclick="javascript:UAC.logout(window.location.href,2); return false;" title="退出">退出</a></li> -->
      </ul>
<?php
} ?>