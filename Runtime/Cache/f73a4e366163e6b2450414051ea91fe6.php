<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人资料-个人中心-管理中心-<?php echo ($sitename); ?></title>


		<meta charset="UTF-8">		
		 <meta property="qc:admins" content="2736327467566533536375" />	
		<link rel="stylesheet" href="__PUBLIC__/newtpl2/css/common.css" type="text/css" />
		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.lazyload.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/jquery.SuperSlide.2.1.1.js"></script>		
		<script src="__PUBLIC__/js/CoreJS/jquery.cookie.js"></script>
		<script src="__PUBLIC__/js/CoreJS/blocksit.min.js"></script>
		<script src="__PUBLIC__/js/CoreJS/common.js"></script>
		




<script language="javascript" src="__PUBLIC__/js/CoreJS/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" src="__PUBLIC__/js/CoreJS/areas.js"></script>
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


    <div class="myaccount">
      

<div class="account-l">
<a href="__URL__/info_edit/" title="我的个人资料" class="on">我的个人资料</a>
<a href="__URL__/info_icon/" title="上传头像">上传头像</a> 
<a href="__URL__/info_changepass/" title="密码/保护">密码/保护</a> 
<a href="__URL__/info_live/" title="密码/保护">直播设置</a> 
</div>

      <div class="account-r">

	  <script type="text/javascript">
			var isEdit=false;
			var info='';
			function checkSave(){
				var y_nick='asdfsadfassfasdf';
				var nick=document.getElementById('nick');
				if(nick.value!=y_nick){
					isEdit=true;
				}
				var y_intro='xxxxxxx';
				var intro=document.getElementById('intro');
				if(intro.value!=y_intro){
					isEdit=true;
				}
				var y_realName='秦广东';
				var realName=document.getElementById('realName');
				if(realName.value!=y_realName){
					isEdit=true;
				}
				var y_date='2011年09月14日';
				var date=document.getElementById('birthday');
				if(date.value!=y_date){
					isEdit=true;
				}
				var y_aihao='Web前端开发';
				var aihao=document.getElementById('interest');
				if(aihao.value!=y_aihao){
					isEdit=true;
				}
			}
			var selectSubArea="selectSubArea";
			var selectArea="selectArea";
			var hdResidenceArea="hdResidenceArea";
			function checkall(){
			  $("#province").attr("value",$("#selectArea").find("option:selected").text());
			  $("#city").attr("value",$("#selectSubArea").find("option:selected").text());
			  var nick=document.getElementById('nick');
			  var error_nick=document.getElementById('error_nick');
			  if(nick.value==''){
				 error_nick.innerHTML='昵称不能为空';
				 nick.focus();
				 return false;
			  }
			  var exp_nick=/^[^0-9](.*)$/;
			  if(!exp_nick.test(nick.value)) {
			  	error_nick.innerHTML='昵称不能使用数字开头';
				 nick.focus();
				 return false;
			  };
			  var nickL=strbytelen(nick.value);
			  if(nickL>16){
				 error_nick.innerHTML='超出长度 最多16个英文';
				 nick.focus();
				 return false;
			  }else{
				 error_nick.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';	
			  }
			  var intro=document.getElementById('intro');
			  var error_intro=document.getElementById('error_intro');
			  var introL=strbytelen(intro.value);
			  if(intro.value!=''&&introL>100){
				 error_intro.innerHTML='超出长度 最多100个英文';
				 intro.focus();
				 return false;
			  }else{
				 error_intro.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';	
			  }
			  var realName=document.getElementById('realName');
			  var error_realName=document.getElementById('error_realName');
			  var realNL=strbytelen(realName.value);
			  if(realName.value!=''&&realNL>8){
				 error_realName.innerHTML='超出长度 最多8个英文';
				 realName.focus();
				 return false;
			  }else{error_realName.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';	}
			  var error_sex=document.getElementById('error_sex');
			  error_sex.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';
			  var birthday=document.getElementById('birthday');
			  var error_birthday=document.getElementById('error_birthday');
			  error_birthday.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';
			  var error_aihao=document.getElementById('error_aihao');
			  error_aihao.innerHTML='<img src="__PUBLIC__/space/images/xiaov.gif">';
			}	
			function strbytelen(source){    
				var endvalue=0;    
				var sourcestr=new String(source);    
				var tempstr;    
				for(var strposition=0;strposition<sourcestr.length;strposition++){  
					tempstr=sourcestr.charAt(strposition);    
					if(tempstr.charCodeAt(0)>255||tempstr.charCodeAt(0)<0){    
						endvalue=endvalue+2;    
					}else{    
						endvalue=endvalue+1;    
					}
				}    
				return(endvalue);    
			}    
		</script>
        <div class="grzl_r">
          <form name="infoForm" method="post" action="__URL__/do_info_edit/" onsubmit="return checkall();">
          <input type="hidden" name="id" value="<?php echo ($_SESSION['uid']); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%" align="right">我的昵称：</td>
                <td width="30%"><input type="text" name="nickname" value="<?php echo ($userinfo['nickname']); ?>" onchange="checkSave();" id="nick" class="set_input set3"></td>
				<td><em>不能超过8个汉字 <strong id="error_nick"></strong></em></td>

              </tr>
			  <tr>
                <td width="12%" align="right">个人签名：</td>
                <td width="30%"><textarea name="intro" id="intro"><?php echo ($userinfo['intro']); ?></textarea></td>
				<td><em>不能超过50个汉字 <strong id="error_intro"></strong></em></td>
              </tr>

			   <tr>
                <td width="12%" align="right">性别：</td>
                <td width="30%">
				   <label for="sexradio1"><input type="radio" name="sex" value="0" <?php if($userinfo['sex'] == '0'): ?>checked="checked"<?php endif; ?> id="sexradio1"> 男</label>
                   <label for="sexradio2" class="ml15"><input type="radio" name="sex" value="1" <?php if($userinfo['sex'] == '1'): ?>checked="checked"<?php endif; ?> id="sexradio2"> 女</label>
				 </td>
				 <td><em><strong id="error_sex"></strong></em></td>

              </tr>
			  <tr>
                <td width="12%" align="right">所在地：</td>
                <td width="30%">
					<select onchange="getSubArea(this);" id="selectArea" name="selectArea" class="selectStyle">
						<option value="" selected="selected">请选择...</option>
					</select>
				    <span class="mr10 ml10">省</span>

					<select onchange="copyValueToHidden(this.value);" id="selectSubArea" name="selectSubArea" class="selectStyle">
					  <option value="" selected="selected">请选择...</option>
					</select>
					<input type="hidden" name="area" value="" id="hdResidenceArea">
					<script language="javascript">
						fillArea();
						user_areas('<?php echo ($userinfo['selectArea']); ?>','<?php echo ($userinfo['selectSubArea']); ?>');
					</script>
				</td>
				<td><em> 选择所在地 <strong id="error_area"></strong></em></td>

			 </tr>
             <tr>
                <td width="12%" align="right">生日：</td>
                <td width="30%"><input type="text" name="birthday" value="<?php echo ($userinfo['birthday']); ?>" onfocus="WdatePicker({dateFmt:'yyyy年MM月dd日'})" id="birthday" class="set_input set3"></td>
				<td><em>点击选择您的生日 <strong id="error_birthday"></strong></em></td>
              </tr>
             <tr>
                <td width="12%" align="right">爱好：</td>

                <td width="30%"><input type="text" name="interest" value="<?php echo ($userinfo['interest']); ?>" id="interest" class="set_input set3"></td>
				<td><em>您的兴趣爱好 <strong id="error_aihao"></strong></em></td>
              </tr>
              
             
              
			  <tr>
                <td width="12%" align="right">&nbsp;</td>
                <td width="30%"><input type="image" name="" src="__PUBLIC__/images/tjbt.gif"><span id="edit_info"></span></td>
				<td></td>
              </tr>

            </table>
			<input type="hidden" name="province" id="province" value="<?php echo ($userinfo['province']); ?>">
			<input type="hidden" name="city" id="city" value="<?php echo ($userinfo['city']); ?>">
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>

<!--提示层-->
		<div class="poptip" id="giveBox" style="display:none;">
				<div class="pop-t"><span class="close" id="pop-close"></span><h3>提示</h3></div>	
				<div class="pop-v">
					<p class="txt" id="pop-text">修改成功!</p>
					<p class="txt"><span class="sendBtn" id="pop-btnclose">确 定</span></p>
				</div>
			</div>
		<!--提示层-->



	
	
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