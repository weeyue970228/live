<?php if (!defined('THINK_PATH')) exit();?><h3 class="f14"><a href="__URL__/mainFrame/" target="right">管理首页</a></h3><h3 class="f14"><span class="switchs cu on" title="展开与收缩"></span>个人信息</h3><ul><li id="_MP1" class="sub_menu"><a href="javascript:_MP(1,'/payagent/index.php/Index/edit_pwd/');" hidefocus="true" style="outline:none;">修改密码</a></li></ul><h3 class="f14"><span class="switchs cu on" title="展开与收缩"></span>充值代理</h3><ul><li id="_MP3" class="sub_menu"><a href="javascript:_MP(3,'/payagent/index.php/Index/view_beandetail/');" hidefocus="true" style="outline:none;">我的收支明细</a></li></ul><script type="text/javascript">$(".switchs").each(function(i){
	var ul = $(this).parent().next();
	$(this).click(
	function(){
		if(ul.is(':visible')){
			ul.hide();
			$(this).removeClass('on');
				}else{
			ul.show();
			$(this).addClass('on');
		}
	})
});
</script>