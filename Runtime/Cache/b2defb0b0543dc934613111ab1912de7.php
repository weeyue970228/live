<?php if (!defined('THINK_PATH')) exit();?><div class="model modlist2">
	<ul class="sdes">
		<?php if(is_array($users)): $k = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; $emceelevel = getEmceelevel($vo['earnbean']); ?>
				<li>
				<a href="/<?php echo ($vo['curroomnum']); ?>" title="<?php echo ($vo['nickname']); ?>" target="_blank"><img src="<?php echo ($vo['snap']); ?>" onerror="this.src='__PUBLIC__/images/default.gif'" style="height:120px;"/></a>
					<?php echo ($vo['live_state']); ?>
					<cite><?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?>人观看</cite>
					<div class="text">
						<p><span class="star star<?php echo ($emceelevel[0]['levelid']); ?>"></span><a href="<?php echo ($vo['curroomnum']); ?>" title="<?php echo ($vo['nickname']); ?>"><?php echo ($vo['nickname']); ?></a></p>
						<p>开播时间：
							<?php echo date('H:i:s',$vo['starttime']) ?>
						</p>
					</div>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
</div>