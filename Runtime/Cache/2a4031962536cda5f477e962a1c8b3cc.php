<?php if (!defined('THINK_PATH')) exit(); $emceelevel = getEmceelevel($userinfo['earnbean']); $nextemceelevel = D("Emceelevel")->where('levelid>'.$emceelevel[0]['levelid'])->field('levelid,levelname,earnbean_low')->order('levelid asc')->select(); $richlevel = getRichlevel($userinfo['spendcoin']); $nextrichlevel = D("Richlevel")->where('levelid>'.$richlevel[0]['levelid'])->field('levelid,levelname,spendcoin_low')->order('levelid asc')->select(); ?>
<p><span>主播： </span><span class="star star<?php echo ($emceelevel[0]['levelid']); ?>"></span>距&nbsp;<span class="star star<?php echo ($nextemceelevel[0]['levelid']); ?>"></span> 还差 <?php echo ($nextemceelevel[0]['earnbean_low'] - $userinfo['earnbean']); ?>秀豆<span></span></p>
<!--
<p><span>富豪： </span><span class="cracy cra<?php echo ($richlevel[0]['levelid']); ?>"></span>距 <span class="cracy cra<?php echo ($nextrichlevel[0]['levelid']); ?>"></span> 还差<?php echo ($nextrichlevel[0]['spendcoin_low'] - $userinfo['spendcoin']); ?>秀币</p>
-->




<!--
<p>截止今日，本周排名：
		<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; $giftusers = D('Coindetail')->query('SELECT touid,sum(giftcount) as total FROM `ss_coindetail` where giftid='.$vo['id'].' and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by touid order by total desc'); if($giftusers){ $orderno = 0; $i = 1; foreach($giftusers as $val){ if($val['touid'] == $_REQUEST['emceeId']){ $orderno = $i; } $i++; } if($orderno > 0){ $smallIcon = $vo['giftIcon_25']; ?>
				<span><img src="<?php echo ($smallIcon); ?>" title="<?php echo ($vo['giftname']); ?>" alt="<?php echo ($vo['giftname']); ?>"/> 第<?php echo ($orderno); ?></span>
		<?php
 } } endforeach; endif; else: echo "" ;endif; ?>					
</p> 
-->