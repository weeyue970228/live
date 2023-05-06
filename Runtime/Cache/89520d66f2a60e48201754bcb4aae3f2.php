<?php if (!defined('THINK_PATH')) exit();?>                    <ul class="room_ynew room_runwayWrap">
                    	<li class="first_runway  relative">                 		  
                    		  	<embed src="/Public/css/CoreCSS/newlaba.swf" width="84" height="46" wmode="transparent" class="laba indexlaba" >
                    		  		<em class="text_hidden" id="first_runway_box">
                    		  			<a class="a gray" href="/<?php echo ($headlines['touidinfo']['curroomnum']); ?>" target="_blank" id="first_runway_list">
                    		  				<span class="newtime"><?php echo (date('H:i',$headlines['addtime'])); ?></span>
                    		  				<span class=" cracy cra<?php echo ($headlines['uidinfo']['richlecel'][0]['levelid']); ?> star_shining"><strong class="start_img star_shining_5"></strong></span>
                    		  				<span class="text_hidden txcolor" title="<?php echo ($headlines['uidinfo']['nickname']); ?>"><?php echo ($headlines['uidinfo']['nickname']); ?></span>
                    		  				<span class="time">送给</span>
                    		  				<span class="star star<?php echo ($headlines['touidinfo']['emceelevel'][0]['levelid']); ?>"></span>
                    		  				<span class="text_hidden txcolor" title="<?php echo ($headlines['touidinfo']['nickname']); ?>"><?php echo ($headlines['touidinfo']['nickname']); ?></span>
                    		  				<span class="time giftnames text_hidden" title="<?php echo ($headlines['giftcount']); ?>个<?php echo ($headlines['giftinfo']['giftname']); ?>"><?php echo ($headlines['giftcount']); ?>个<?php echo ($headlines['giftinfo']['giftname']); ?></span>
                    		  				<img src="<?php echo ($headlines['objectIcon']); ?>">                   		  		
                    		  			</a>
                    		  		</em>
                    		  		<b class="fisrtRun_l poab"></b>
                    		  		<b class="fisrtRun_r poab"></b>
                    		  		<i class="icons runwayIcon icon_first_runway">&nbsp;</i>
                    		  		<i class="RunwayTime" time="0" maxtime="180" style="display: none;">03:00</i>
                    		  		<div class=" tipBoxRunway">
                    		  			<p class="RunwayTF">时间为本头条当前霸屏时间，此头条消费为秀币;</p>
                    		  			<p>每条头条拥有<?php echo ($headlines_time); ?>分钟的保护时间。</p>
                    		  			<p>保护期间，消费高于当前头条，即刻取替其展示；<br>保护期结束，消费满足<?php echo ($headlinesmoney); ?>w秀币即可上头条！</p>
                    		  		</div>                 		  		
                    	</li>
                    </ul>    
                    
             <script>
		  		 $(function(){
		  		 	//显示信息
		  		 	 $("i.runwayIcon").hover(function(){
		  		 	 	 $("div.tipBoxRunway").show();	  		 	 	
		  		 	 },function(){		  		 	 	
		  		 	 	$("div.tipBoxRunway").hide();
		  		 	 });
		  		 	
		  		 })
		  	</script>