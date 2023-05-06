<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<title><?php echo ($sitename); ?></title>
		<meta http-equiv="Pragma" contect="no-cache" />
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/joylogin.css" type="text/css" media="screen" />
		<script language="javascript" src="__PUBLIC__/js/CoreJS/jquery.js"></script>
		<script language="javascript" src="__PUBLIC__/js/usercenter.js?32162655"></script>
		<script type="text/javascript">
			document.domain = '<?php echo ($domainroot); ?>';
			document.onkeydown = function() {
				if (window.event && window.event.keyCode == 13) {
					var type = $("#switch_type").val();
					if (type == "0") {
						login();
					} else {
						reg();
					}
				}
			}
			var cl = false;

			function code_login() {
				if (cl != true) {
					document.getElementById('img_authCode1').src = '__APP__/Base/verify/v/' + Math.random();
					cl = true;
				}
			}
			var cr = false;

			function code_reg() {
				if (cr != true) {
					document.getElementById('img_authCode').src = '__APP__/Base/verify/v/' + Math.random();
					cr = true;
				}
			}
			var childWindow;

			function toLogin() {
				childWindow = window.open("../../../Connect2.0/example/oauth/index.php", "TencentLogin", "width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
			}

			function closeChildWindow() {
				childWindow.close();
			}
			var timestamp = (new Date()).valueOf();
			/*$(function(){

				$("#reg_email").val(timestamp+"@c.com");
			})*/
			//imageCode_getImgage.htm
		</script>

	</head>

	<body onload="init();">
		<!-- 登录层 -->

		<div class="joymodlogin">
			<span title="关闭" class="close"><a href="javascript:parent.UAC.closeUAC();"></a></span>

			<div class="jmltitle">
				<span id="btn_login" class="cur" onclick="switchDiv(0);">登录</span>
				<span id="btn_reg" onclick="switchDiv(1);">注册</span>

			</div>
			<!--	 登录 注册切换按钮-->

			<div class="login_bodya">
				<div class="denglu_01">
					<input type="hidden" id="switch_type" value="0" />
					<p id="id_googtip" class="goodtip" style="display:none;">
						<!--<?php echo ($sitename); ?>注册用户请直接登录 -->
					</p>
					<div class="jmllogin1" id="div_login" >
						<ul>
							<li class="spe1"><span id="login_info"></span></li>
							<li><input class="user" placeholder="用户名"   value="" type="text" id="userName" /></li>
							<li><input class="password" placeholder="密码"  value="" type="password" id="password" /></li>
						</ul>
						<div class="jmlbtn1">
							<div style="float:left">
								<input type="checkbox" id="autoLogin" checked />
								<span>下次自动登录</span>
							</div>
							<div style="float:right">

								<a href="__URL__/findBackPwdPage/" target="_self">忘记密码？</a>

							</div>
						</div>
						<div class="jmlbtn2"><input type="button" id="btn_login" value="登&nbsp;&nbsp;&nbsp;&nbsp;录" onclick="login();" /></div>

					</div>

					<div class="jmllogin3" id="div_reg" style="display:none;">
						<ul>
							<li id="li_userName"><span><input class="user_02" value="" type="text" id="reg_userName" onfocus="showRegInfo('name');" onblur="validateRegInfo('name');"  placeholder="用户名" /></span>
								<input type="hidden" id="id_isExist" value="" />
								<div class="jmlfalsebox">
									<samp id="name_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="name_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="name_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="name_false" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">6~18个字符，包括数字、字母、下划线</div>
									</div>
									<div id="name_false2" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">用户名已存在</div>
									</div>
								</div>
							</li>
							<li id="li_pwd"><span><input class="password_02" value="" type="password" id="reg_password" onfocus="showRegInfo('pwd');" onblur="validateRegInfo('pwd');"  placeholder="密码" /></span>
								<div class="jmlfalsebox">
									<samp id="pwd_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="pwd_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="pwd_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="pwd_false" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">6-16个字符(字母、数字)区分大小写</div>
									</div>
								</div>
							</li>
							<li id="li_pwd2" style="display:none"><span><input class="password_02" type="password" id="reg_password2" onfocus="showRegInfo('pwd2');" onblur="validateRegInfo('pwd2');" /></span>
								<div class="jmlfalsebox">
									<samp id="pwd2_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="pwd2_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="pwd2_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="pwd2_false" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">请再输入一遍密码</div>
									</div>
								</div>
							</li>
							<li id="li_email">
								<span>
			
			<input class="email_02" type="text" id="reg_email" onfocus="showRegInfo('email');" onblur="validateRegInfo('email');"  placeholder="邮箱" /></span>
								<input type="hidden" id="id_isExist2" value="" />
								<div class="jmlfalsebox">
									<samp id="email_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="email_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="email_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="email_false" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">请填写邮箱</div>
									</div>
									<div id="email_false2" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">邮箱已存在</div>
									</div>
								</div>
							</li>

							<li id="li_mobile"><span><input class="phone_02" type="text" id="reg_mobile" onfocus="showRegInfo('mobile');" onblur="validateRegInfo('mobile');"  placeholder="手机号" /></span>
								<input type="hidden" id="id_isExist2" value="" />
								<div class="jmlfalsebox">
									<samp id="mobile_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="mobile_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="mobile_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="mobile_false" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">请填写手机号</div>
									</div>
									<div id="mobile_false2" class="jmlfalse" style="display:none">
										<div class="jmlfalsecon">手机号已存在</div>
									</div>
								</div>
							</li>

							<li id="li_code" class="nli_code" style="padding-left:5px;margin-top: -12px;">
								<div class="yzm_01">验证码&nbsp;&nbsp;</div>
								<div class="yzmhyh">
									<span class="jmlma1"><input style="width:50px;border-radius: 0;" type="text" id="reg_validateCode" maxlength="4" onclick="javascript:code_reg()" onmouseover="javascript:code_reg()" onfocus="showRegInfo('code');javascript:code_reg()" onblur="validateRegInfo('code');" /></span>

								</div>
								<img id="img_authCode" class="jmlma2 njmlma2" src="/index.php/Base/verify/" />
								<em><a onclick="switchAuthCode();return false;" style="cursor:pointer;">换一换？</a></em>
								<div class="jmlfalsebox jmlkuan3">
									<samp id="code_con01" class="jmlicon01" style="display:none"></samp>
									<samp id="code_con02" class="jmlicon02" style="display:none"></samp>
									<samp id="code_empty" class="jmlicon_empty" style="display:none"></samp>
									<div id="code_false" class="jmlfalse" style="display:none">

									</div>
								</div>
							</li>

						</ul>
						<div class="jmlbtn1" style="padding-left:6px; padding-top:0px; display:none; "><input type="checkbox" id="id_protocol" checked/><span><a href="#" target="_blank">同意《<?php echo ($sitename); ?>免责条款》</a></span>
							<div id="div_protocol" class="jmlfalsebox jmlkuan" style="display:none">
								<samp class="jmlicon01"></samp>
								<div class="jmlfalse">
									<div class="jmlfalsecon">请接受免费条款</div>
								</div>
							</div>
						</div>
						<div class="jmlbtn2"><input type="button" id="btn_reg" value="完成注册" onclick="reg();" /></div>

					</div>
				</div>

				<dl class="M-hz-list">
					<dt>快速登录</dt>
					<dd class="qq-item">
						<a class="qq" title="QQ账号登录" href="/index.php/login/qq" target="_blank">QQ账号登录</a>
					</dd>
					<!--<dd class="weixin-item">
						<a class="weixin" title="微信账号登录" href="javascript:void(0)">微信账号登录</a>
					</dd>
					<dd class="wb-item">
						<a class="wb" title="新浪微博账号登录" href="javascript:void(0)">新浪微博账号登录</a>
					</dd>-->
				</dl>

			</div>
			<div style="clear:both;"></div>
		</div>

	</body>

</html>