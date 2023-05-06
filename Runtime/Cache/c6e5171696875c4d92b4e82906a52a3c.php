<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<title><?php echo ($userinfo['nickname']); ?>的个人直播间---<?php echo ($sitename); ?></title>

		<meta name="keywords" content="<?php echo ($metakeyword); ?>" />

		<meta name="description" content="<?php echo ($metadesp); ?>" />

		<meta http-equiv="pragma" content="no-cache" />

		<meta http-equiv="cache-control" content="no-cache" />

		<meta http-equiv="expires" content="0" />

		<link rel="stylesheet" href="__PUBLIC__/css/common.css" type="text/css" />
		<link rel="stylesheet" href="__PUBLIC__/css/CoreCSS/emceeinfo.css" type="text/css" />

		<!--xinjia-->
		<script type="text/javascript">
			//加载过滤词汇
			var shieldWordStr = '<?php echo ($shieldWord); ?>';
			var shieldWordArr = shieldWordStr.split('|');
			//加载礼物数据
			var giftShowTime = <?php echo ($giftShowTime); ?>;
			//加载道具数据
			var carList = <?php echo ($carList); ?>;
			var chatip = "<?php echo ($chatip); ?>";
			//礼物队列
			var giftQueue = new Array(); //礼物播放队列
			var giftPlayState = 0; //队列执行状态 0未为运行 1为正在执行 2为将要停止
		</script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/swfobject.js"></script>

		<!--
	作者：voidcat@163.com
	时间：2015-12-03
	描述：百度分享
-->

		<script>
			window._bd_share_config = {
				"common": {
					"bdSnsKey": {},
					"bdText": "",
					"bdMini": "2",
					"bdMiniList": false,
					"bdPic": "",
					"bdStyle": "1",
					"bdSize": "16"
				},
				"share": {},
				slide: [{
					bdImg: 0,
					bdPos: "right",
					bdTop: 250
				}]
			};
			with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
		</script>

		
		<script src="__PUBLIC__/js/CoreJS/jquery1.42.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/js/room.js"></script>
		<script type="text/javascript" src="__PUBLIC__/js/CoreJS/json2.js"></script>
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/common.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/personal.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/interface.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/chatRoom.js"></script>
		<!--上下拖到js------------------->
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/Ku6SubField.js">
		</script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/swfobjectforegg.js"></script>

		<script type="text/jscript" language="JavaScript" src="/game/js/game.js"></script>
		<!--xinjia-->
		<!--背景图 滚动-->
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/jcarousellite.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#showbg').jCarouselLite({
					btnPrev: '#prev-01',
					btnNext: '#next-01',
					visible: 3,
				});
			});
		</script>
		<!--背景图 滚动-->
		<script>
			$(document).ready(function(e) {
				$(".m-billboard .m-tab li").hover(
					function() {
						$(this).find(".dlg").show();
						$(this).addClass("curr");
					},
					function() {
						$(this).find(".dlg").hide();
						$(this).removeClass("curr");
					}
				);
			});
		</script>
		<script>
			function GetQueryString(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
				var r = window.location.search.substr(1).match(reg); //获取url中"?"符后的字符串并正则匹配
				var context = "";
				if (r != null)
					context = r[2];
				reg = null;
				r = null;
				return context == null || context == "" || context == "undefined" ? "" : context;
			}
			var roomid = GetQueryString("roomid");
			//初始化收藏
			var uid = "<?php echo ($_SESSION['uid']); ?>";
			var touid = "<?php echo ($userinfo['id']); ?>";
			$(function() {
				$.ajax({
					type: "get",
					url: "__URL__/queryFavor/touid/" + touid + "/uid/" + uid,
					async: true,
					success: function(data) {
						data = parseInt(data);
						switch (data) {
							case 1: //收藏成功
								$("#favor").text("取消收藏");
								$("#favor").attr("state", "1");
								break;
						}
					}
				});
			})

			function shoucang() {
				if (_show.userId != _show.emceeId) {
					self.location = "/index.php/Guard/index/id/<?php echo ($userinfo['curroomnum']); ?>";
				} else {
					alert("自己不能守护自己!");
				}
			}
			//收藏操作
			function addFavor() {
				if (uid < 0) {
					UAC.openUAC(0);
				} else {
					if ($("#favor").attr("state") == "0") {
						if (_show.userId != _show.emceeId) {
							$.ajax({
								type: "get",
								url: "__URL__/addFavor/touid/" + touid + "/uid/" + uid,
								async: true,
								success: function(data) {
									data = parseInt(data);
									switch (data) {
										case 1: //收藏成功
											$("#favor").text("取消收藏");
											$("#favor").attr("state", "1");
											break;
										case 2: //收藏失败
											alert("添加收藏失败，请稍后再试~")
											break;
										case 3: //重复收藏
											alert("您已经收藏过了")
											break;
										default:
											alert("系统出错了~" + data);
											break;
									}
								}
							});
						} else {
							alert("自己不能收藏自己!")
						}
					} else {
						$.ajax({
							type: "get",
							url: "__URL__/cancelFavor/touid/" + touid + "/uid/" + uid,
							async: true,
							success: function(data) {
								data = parseInt(data);
								switch (data) {
									case 1: //收藏成功
										$("#favor").text("点我收藏哦");
										$("#favor").attr("state", "0");
										break;
									default:
										alert("系统出错了~" + data);
										break;
								}
							}
						});
					}
				}
			}
			$("document").ready(function() {
				$.ajax({
					url: "/index.php/User/queryMounts/",
					success: function(data) {
						if (data) {
							setTimeout("GiftCtrl.kaichang(" + data + ",'豪华')", 8000);
						}
					}
				});
			})
			//用于获取抽奖次数
			function get_turntable_recharge_num() {
				$.ajax({
					type: "get",
					url: "__URL__/get_turntable_recharge_num",
					async: true,
					success: function(data) {
						var num = parseInt(data);
						if (num == 0) {
							_alert2("暂时没有奖励了，快去充值吧", [function() {
									window.location.href = "/index.php/User/charge/";
									_closePop();
								},
								function() {
									_closePop();
								}
							]);
						} else {
							var newnum = parseInt($("#turntable_num").text()) + num;
							$("#ChargeClick").attr("onClick", "showTurntableCharge(" + newnum + ")");
							$("#turntable_num").text(newnum);
							_alert("成功领取了" + num + "次机会", 3);
						}
					}
				});
			}
		</script>

		<script type="text/javascript" language="javascript">
			document.domain = "<?php echo ($domainroot); ?>";
			var speaktxt = '| 输入文字不超过50个字。每次广播花费500秀币';
			var _show = {
				"isHD": 0,
				"enterChat": 0,
				"emceeId": "<?php echo $userinfo['id']; ?>",
				"fps": "<?php echo $userinfo['fps'];?>",
				"cdnl": "<?php echo $userinfo['cdnl'];?>",
				"cdn": "<?php echo $userinfo['cdn'];?>",
				"zddk": "<?php echo $userinfo['zddk'];?>",
				"pz": "<?php echo $userinfo['pz']?>",
				"zjg": "<?php echo $userinfo['zjg']?>",
				"height": "<?php echo $userinfo['height']?>",
				"width": "<?php echo $userinfo['width']?>",
				"emceeLevel": 1,
				"goodNum": "<?php echo $userinfo['curroomnum']; ?>",
				"emceeNick": "<?php echo $userinfo['nickname']; ?>",
				"oldseatnum": "0",
				"songPrice": "1500",
				"offline": 0,
				"roomId": "<?php echo $userinfo['curroomnum']; ?>",
				"titlesUrl": "",
				"titlesLength": "4",
				"bgimg": "<?php echo $userinfo['bgimg']; ?>",
				"token":'<?php echo $userinfo2['token']; ?>',
				"uname":'<?php echo $userinfo2['nickname'] ?>',
				"uroom":'<?php echo $userinfo2[curroomnum] ?>',
				"enableMounts":'<?php echo $userinfo2[enableMounts] ?>'
			
			};
			
			var _game = {
				"eggtimer": 0,
				"eggstatus": 1,
				"egginterval": 20,
				"eggstart": 10,
				"eggclosed": 1
			};

			function sumbitCloseReason() {
				var reason = $("#usuallyReason").val();
				if (reason == "otherReason") {
					reason = $("#otherReason").val();
				}
				Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"closeLive","reason":"' + reason + '"}');
				$("#closeReasonDiv").hide();
			}

			function checkReason() {
				if ($("#usuallyReason").val() == "otherReason") {
					$("#closeReasonDiv").attr("class", "c_reason1");
				} else {
					$("#closeReasonDiv").attr("class", "c_reason");
				}
			}

			function get_url() {
				var url = window.location.href;
				return url;
			}
		</script>

	</head>

	<body>

		<div class="ZBJ_leftNav">
			<div class="headjoy2">

				<div class="joytip">

					<a href="/index.php" target="_blank"><img src="./Public/images/logo2.png" height="70" width="70" /></a>

					

						<script language="javascript" type="text/javascript">
							$(function() {
								setTimeout(function() {
									$.get("/index.php/Show/show_headerInfo/t/" + Math.random(), function(data) {
										$('.others').html(data);
									});
								}, 1000);
							});
							//加载系统紧急消息公告
							$(document).ready(function() {
								getemergencyNotice(this);
								$("#redBagBox").load('__APP__/Show/show_redbaginfo/', function(responseText, textStatus, XMLHttpRequest) {
									this;
								});
								$("#hbrank").load('__APP__/Show/show_redbagrank/', function(responseText, textStatus, XMLHttpRequest) {
									this;
								});
							});
						</script>

					
					<div class="others">

					</div>
				</div>

				<script type="text/javascript">
					var globalObj = "";

					function getemergencyNotice(obj) {
						globalObj = obj;
						$.ajax({
							type: "post",
							url: "/index.php/Show/show_getEmergencyNotice/",
							data: {},
							success: function(data) {
								var data = evalJSON(data);
								if (data.code == 0) {
									dynamicNotice(obj, data.info);
								}
							}
						});
					}
					// 自动滚动 
					function dynamicNotice(obj, msg) {
						if (msg == "" || msg == null) {
							//定时从服务器拿消息
							setTimeout(function() {
								getemergencyNotice(globalObj);
							}, 5 * 60 * 1000);
						} else {
							$('#moveNotices').text(msg);
							$('#noticeBody').fadeIn();
							$("#headshow").css('margin', '20px auto');
						}
					}

					function closeNotice() {
						$('#noticeBody').fadeOut();
						$("#headshow").css('margin', '0 auto');
						//定时从服务器拿消息
						setTimeout(function() {
							getemergencyNotice(globalObj);
						}, 5 * 60 * 1000);
					}
					$(".gift_li").live("click", function() {
						$(".gift_li").removeClass("test");
						var select_li = $(this);
						select_li.addClass("test");
					})
				</script>

				<style type="text/css">
					.test {
						color: #ff8888;
						border: 1px solid #e65faa;
						height: 64px;
					}
					
					div#b,
					div#d,
					div#bb {
						white-space: nowrap;
					}


				</style>

				<div id="noticeBody" class="noticeBody">

					<div id="noticeLine" class="noticeLine">

						<div class="noticeText">

							<marquee id="moveNotices" scrollamount="3" direction="left" onmouseover="this.stop();" onmouseout="this.start();"></marquee>

						</div>

						<div class="noticeClose">

							<a id="closebutton" onClick="closeNotice();">

								<img width="12" height="12" src="__PUBLIC__/images/close.png" />

							</a>

						</div>

					</div>

				</div>

			</div>

			<ul class="leftMenu">
				<li><a href="__ROOT__/index.php" target="_blank">大厅</a></li>
				<li><a href="../index.php/User/toolItem/" target="_blank">商城</a></li>
				<li><a href="../index.php/User/charge/" target="_blank">充值</a></li>
				<li><a href="../index.php/order/" target="_blank">排行</a></li>

			</ul>

			<div class="room_sidebar">

				<ul>
					<li class="bgfilter more_aho_buts" style="display:none;">
						<a href="<?php echo U('xiuchang/index');?>" title="更多主播" class="room_side1" target="_blank"></a>
						<div class="room_pop1 rside_pop bgfilter5 poab hide" target="_blank">更多主播</div>
					</li>
					<li class="bgfilter follow_buts" style="display:none;">
						<a href="/index.php/User/interestToList/" title="我的关注" class="room_side2" target="_blank"></a>
						<div class="room_pop1 rside_pop bgfilter5 poab hide">我的关注</div>
					</li>

					<li class="bgfilter app_buts">
						<a href="javascript:void(0);" title="APP下载" class="room_side4"></a>
						<div class="ewm_pop poab bgfilter2 yj_pop hide" style="background: #fff;width: 150px;">
							<a href="<?php echo ($appurl); ?>" target="_blank" style="width: 100%;height: auto;"><img src="<?php echo ($apppic); ?>" style="width: 100%;"></a>
						</div>
					</li>
					<li class="bgfilter qq_buts" >
						<a href="javascript:void(0);" title="客服QQ" class="room_side5"></a>
						<div class="qq_pop poab bgfilter5 yj_pop hide">
							<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($qq); ?>&site=qq&menu=yes"><img border="0" src="__PUBLIC__/images/qq.png?p=2:<?php echo ($qq); ?>:53" /></a>

						</div>
			</div>
			</li>
			</ul>

		</div>

		</div>

		<script>
			try {
				document.domain = '<?php echo ($domainroot); ?>';
			} catch (e) {}
		</script>

		<script language="javascript" type="text/javascript" src="__PUBLIC__/js/logon-2.0.js" charset="utf-8"></script>

		<script>
			UAC.showUserInfo = function(data) {
				if (data.isLogin == "1") {
					window.location.reload();
				}
			}
		</script>

		<div class=my_tab>

			<span class=t-l style="display:none;"></span>

			<div class=tab-info>

				<ul>

					<li id=notice style="z-index:1000;">

						<a href=javascript:void(0); class=m-notice title=房间公告>房间公告</a>

						<div class="p-notice pop-play" id=notice-modle>

							<span class=p-close>close</span>

							<span class=p-default></span>

							<s></s>

							<div class="p-box pop_line">

								<p>
									<B>张贴房间公告</B>
								</p>

								<div class=layer-item>

									<textarea id="b2t" name="notice-v" class="p_input area-v" pro-msg="请输入文字,不超过40个...">请输入文字,不超过40个...</textarea>

								</div>

								<div class="layer-item mt10">

									<input id="b2u" type=text class="p_input area-i" pro-msg=请输入链接地址 value="请输入链接地址" />

								</div>

							</div>

							<div class="p-box mt10">

								<p>
									<B>张贴房间私聊留言(显示在私聊区)</B>
								</p>

								<div class=layer-item>

									<textarea id="b3t" name="notice-v" class="p_input area-v" pro-msg="请输入文字,不超过40个...">请输入文字,不超过40个...</textarea>

								</div>

								<div class="layer-item mt10">

									<input id="b3u" type=text class="p_input area-i" pro-msg=请输入链接地址 value="请输入链接地址" />

								</div>

								<p class="mt10"><span class=play-btn onclick=playerMenu.bulletin(3);>提交</span></p>

							</div>

						</div>

					</li>

					<li id=background>

						<a href=javascript:void(0); class=m-bgimg title=房间背景>房间背景</a>

						<div class="room-bg pop-play" id=background-modle>

							<form id=frm method=post action="" enctype=multipart/form-data>

								<span class=p-close>close</span><span class=p-default></span><s></s>

								<div class="p-box mt10">
									<p>
										<B>系统背景</B>
									</p>
									<div class="carousel">
										<a href="javascript:void(0);" class="prev" id="prev-01">&nbsp; </a>
										<div id='showbg'>
											<ul>
												<?php if(is_array($showbg)): $i = 0; $__LIST__ = $showbg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li> <img src="<?php echo ($vo["picpath"]); ?>" class="xtbg" onclick=javascript:playerMenu.setBackground('<?php echo ($vo["picpath"]); ?>');> </li><?php endforeach; endif; else: echo "" ;endif; ?>
											</ul>
										</div>
										<a href="javascript:void(0);" class="next" id="next-01">&nbsp; </a>
										<div class="clear"></div>
									</div>
									<p>
										<B>自定义背景</B>
									</p>

									<div class=" layer-item ">

										<input type=file id=bg3 name="uploadImg" />

									</div>

									<p class=mt10>
										<span class=play-btn onclick=javascript:playerMenu.setBackground(1);>提交</span>
										<span class=cancel-bg onclick=javascript:playerMenu.setBackground(0);>取消背景</span>
										<span class=cancel-bg onclick=javascript:playerMenu.setBackground(2);>恢复默认</span>

									</p>

								</div>

							</form>

						</div>

					</li>

					<li id=leave>

						<a href=javascript:void(0); class=m-video title=离线录像>离线录像</a>

						<div class="room-bg pop-play" id=leave-modle>

							<span class=p-close>close</span><span class=p-default></span><s></s>

							<div class="p-box mt10">

								<p>
									<B>当您离线时，自动播放录像</B>
								</p>

								<div class=layer-item>

									<input id=video name=lxlink-v class="p_input area-v" pro-msg="请输入离线地址..." />

								</div>

								<p class=mt10><span class=play-btn onclick=playerMenu.offVideo(1);>提交</span><span class=cancel-bg onclick=playerMenu.offVideo(0);>取消录像</span></p>

							</div>

						</div>

					</li>

					<li id="move">

						<a title="转移观众" class="m-move" href="javascript:void(0);">转移观众</a>

						<div id="move-modle" class="room-bg pop-play">

							<span class="p-close">close</span>

							<span class="p-default"></span>

							<s></s>

							<div class="p-box mt10">

								<p><b>请输入直播间地址：</b></p>

								<div class="layer-item">

									<input pro-msg="如:http://www.5show.tv/1100003122" class="p_input area-v" name="roomurl" id="roomurl" value="如:http://www.5show.tv/1100003122" />

								</div>

								<p class="mt10"><span class="play-btn" onClick="playerMenu.moveroom();">提交</span></p>

							</div>

						</div>

					</li>

					<li>

						<a href=javascript:void(0); onclick=Manage.SetchatPublic(this); state=1 class=m-chat id=chatSet></a>

					</li>

				</ul>

				<div class="c_reason" id=closeReasonDiv>

					<div class=m-songt>

						<span class=close id=s-close onClick="$('#closeReasonDiv').hide();">

		</span>

						<h4>

			关闭原因

		</h4>

					</div>

					<div class=wantgift>

						<table class=song-table id=closeReason_table2>

							<tr>

								<td colspan="2" align="center">

									<input type=button onClick="sumbitCloseReason()" value='提&nbsp;&nbsp;交'>

								</td>

							</tr>

							<tr>

								<td>

									常见原因:

								</td>

								<td align="left" width="200">

									<SELECT NAME="usuallyReason" SIZE="1" onChange="checkReason()" style="width:100px;" id="usuallyReason">

										<OPTION VALUE="裸露">

											裸露

										</option>

										<OPTION VALUE="跳舞">

											跳舞

										</option>

										<OPTION VALUE="挂录像">

											挂录像

										</option>

										<OPTION VALUE="长时间不出镜">

											长时间不出镜

										</option>

										<OPTION VALUE="黑屏">

											黑屏

										</option>

										<OPTION VALUE="双开">

											双开

										</option>

										<OPTION VALUE="代播">

											代播

										</option>

										<OPTION VALUE="播禁歌、不正当言论">

											播禁歌、不正当言论

										</option>

										<option value="otherReason">其他</option>

									</SELECT>

								</td>

							</tr>

							<tr id="otherReasonTr">

								<td>

									其他原因:

								</td>

								<td align="left" width="200">

									<textarea rows="5" cols="30" id="otherReason">

									</textarea>

								</td>

							</tr>

						</table>

					</div>

				</div>

			</div><span class=t-r style="display:none;"></span></div>

		<div id="container" class="ZBJ_Main zbj_middle ">
			<div class="wrap wrap2">
				<div class="headtitle">
					<div class="m-top-headlines" id="js-top-headlines">
						<ul class="room_ynew room_runwayWrap">
							<li class="first_runway  relative">
								<!--<img src="/Public/images/u-top.png" width="34" height="30" class="laba animated swing livelaba">-->

								<em class="text_hidden" id="first_runway_box">
                    		  			<a class="a gray" href="/<?php echo ($headlines['touidinfo']['curroomnum']); ?>" target="_blank" id="first_runway_list">
                    		  				<span class="newtime"><?php echo (date('H:i',$headlines['addtime'])); ?></span>
                    		  				<span class=" cracy cra<?php echo ($headlines['uidinfo']['richlecel'][0]['levelid']); ?> star_shining"></span>
                    		  				<span class="text_hidden txcolor" title="<?php echo ($headlines['uidinfo']['nickname']); ?>"><?php echo ($headlines['uidinfo']['nickname']); ?></span>
                    		  				<span class="time">送给</span>
                    		  				
                    		  				<span class="text_hidden txcolor" title="<?php echo ($headlines['touidinfo']['nickname']); ?>"><?php echo ($headlines['touidinfo']['nickname']); ?></span>
                    		  				<span class="time giftnames text_hidden" title="<?php echo ($headlines['giftcount']); ?>个<?php echo ($headlines['giftinfo']['giftname']); ?>"><?php echo ($headlines['giftcount']); ?>个<?php echo ($headlines['giftinfo']['giftname']); ?></span>
                    		  				<img src="<?php echo ($headlines['objectIcon']); ?>">                   		  		
                    		  			</a>
                    		  		</em>
								<b class="fisrtRun_l poab"></b>
								<b class="fisrtRun_r poab"></b>
							<!--	
								<i class="icons runwayIcon icon_first_runway">&nbsp;</i>
								<i class="RunwayTime" time="0" maxtime="180" style="display: none;">03:00</i>
								<div class=" tipBoxRunway">
									<p class="RunwayTF">时间为本头条当前霸屏时间，此头条消费为秀币;</p>
									<p>每条头条拥有<?php echo ($headlines_time); ?>分钟的保护时间。</p>
									<p>保护期间，消费高于当前头条，即刻取替其展示；
										<br>保护期结束，消费满足<?php echo ($headlinesmoney); ?>w秀币即可上头条！</p>
								</div>
								-->
							</li>
						</ul>

						<script>
							$(function() {
								//显示信息
								$("i.runwayIcon").hover(function() {
									$("div.tipBoxRunway").show();
								}, function() {
									$("div.tipBoxRunway").hide();
								});
							})
						</script>
					</div>

				</div>	
				
				<div style="position:absolute; left:100px; top:0px; z-index:999; pointer-events:none " id="test1"></div>
				<!--
	作者：voidcat@163.com
	时间：2016-01-12
	描述：游戏弹出层
-->
				<div style="position:absolute;left:100px; top:0px; z-index:999;width: 550px; " id="gamePoP"></div>

				<div class="zuoce m_YZ">

					<div class="zbj_zbinfo clear">
						<div class="top" style="width: 100%;height: 90px;">
							<p class="zbhead fl">
								<a href="<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>"><img class="anchor_photo" src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($userinfo['ucuid']); ?>&size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 70px;;height:70px;" /><s></s></a>
							</p>
							<div class="R_b fl">
								<p class="us" id="zb_info"> <span style="display: none;" title='距离下级还剩<?php echo ($surplus_earbean); ?>' class="star star<?php echo ($emceelevel[0]['levelid']); ?>"></span><strong><a class="niacheng" href="<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>"><?php echo ($userinfo['nickname']); ?></a></strong>
									<span class="time" id="showTime" style="display: none;"></span></p>

								<div class="gzcz">

									<?php if($attr_state == 1): ?><button id="gzbtn" class="Focus gzbtn " onClick="Manage.AttentionTo(this);" state="0" style="font-size:12px">+取消关注</button>
										<?php else: ?>

										<button id="gzbtn" class="Focus gzbtn " onClick="Manage.AttentionTo(this);" state="1" style="font-size:12px">+关注</button><?php endif; ?>

									<a class="topUpBtn" target="_blank" style="font-size:12px"><span id='att_count'><?php echo ($att_count); ?></span>人</a>
								</div>

							</div>
						</div>
						<div class="bottom" style="width:100%;height: 50px;">

							<div class="numxb" id="new_Levelcoin">还差<?php echo ($nextlevelcoin); ?>秀豆</div>
							<div class="jdt" style="width: 100%;  height: 30px;">
								<div class="djjbt" style="width: 100%;  height: 30px;">

									<div id="nowlevel" class="star star<?php echo ($emceelevel[0]['levelid']); ?> llevel_1"></div>
									<div class="level-1_1">
										<div class="levelnp">
											<div class="leveln n2" id="progress" style="width:<?php echo ($userinfo['earnbean'] / $nextemceelevel[0]['earnbean_low']) * 100; ?>%; border-radius: 4px;"></div>
										</div>

									</div>

									<div id="next_level" class="star star<?php echo ($nextemceelevel[0]['levelid']); ?> llevel_2"></div>
								</div>

							</div>

						</div>

					</div>

					<div class="gift">
						<div class="hat" id="lb_hat">
							<div class="times fl">守护团</div>
							<div class="text fl">

								<a onclick="shoucang()" class="openDefend" target="_bank">开通 <span id='guard_count'><?php echo ($guard_count); ?></span>/20</a></div>

							<div class="textsc"><a href="javascript:;" class="" state="0" id="favor" target="" onclick="addFavor()">点我收藏哦</a></div>
						</div>
						<div class="ZBJ_jscrollUpAndBottom clear" style="height:90px; overflow-y:auto; overflow-x:hidden; position: relative;">
							<div class="jscroll-c" style="top: 0px; z-index: 99; zoom: 1; position: relative; padding: 0px;">
								<div style="height:0px;overflow:hidden">
								</div>
								<ul class="giftCon c_scroll_con" id="lb_list">
									<span id="old_guard">
			        <?php if(is_array($guard)): $i = 0; $__LIST__ = $guard;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="guardians" title="<?php echo ($vo[nickname]); ?>"> 
	                	<a href="#"  >
	                		<img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($vo['ucuid']); ?>&size=middle"  /></a>
	                <div  class="shouhu_06" ><?php echo ($vo[nickname]); ?></div>
					</li><?php endforeach; endif; else: echo "" ;endif; ?> 
				</span>
									<span id="guard_surplus">
					
					<?php $__FOR_START_8421__=0;$__FOR_END_8421__=$guard_surplus;for($i=$__FOR_START_8421__;$i < $__FOR_END_8421__;$i+=1){ ?><li class="guard_no">
						<a onclick="shoucang()"></a>
					</li><?php } ?>
				</span>
								</ul>
								<style>
									.zbj_middle .m_YZ .gift .giftCon li.guard_no a {
										background: url(..//Public/images/a.png) no-repeat;
									}
									
									.zbj_middle .m_YZ .gift .giftCon li.guard_no a:hover {
										background: url(..//Public/images/a_hover.png) no-repeat;
									}
								</style>

							</div>

						</div>

					</div>
					<div class="chat_online">

						<h2 class="play-t"> 
	                  <span class="no_bg " id="lm2_1" onClick="turn(1,3,2);">管理员(<cite>0</cite>)</span>
                      <span class="no_bg on" id="lm2_2" onClick="turn(2,3,2);">观众(<cite>0</cite>)</span>
			          <span class="no_bg" id="lm2_3" onClick="turn(3,3,2);" style="display:none;">麦序(<cite>0</cite>)</span> </h2>

						<div class="viewer_wrap">

							<ul id="content2_1" class="viewer_list" style="display:none;">
								<li id="loading_manage">
									<img src="__PUBLIC__/images/loading.gif" />
								</li>
							</ul>
							<input type="hidden" value="<?php echo ($address); ?>" id="address">
							<ul id="content2_2" class="viewer_list">
								<li id="loading_online">
									<img src="__PUBLIC__/images/loading.gif" />
								</li>
							</ul>

						</div>

					</div>
				</div>

				<div class="main-flash">
					<div class="my_top" style="display:none;">

						</span>

						</h2>

						<div class="aboutp" id="aboutp">

						</div>

					</div>
					<div class="zbj_windows_body">
						<div class="zbj_windows">

							<div class=video-u>

								<!--礼物之星 begin-->

								<div class="preTopGift" style="display:block;">

									<img src="/Public/images/hb.gif">&nbsp;<span style="color:#FF0000;" id="gethb"><?php echo ($userinfo['gethb']); ?></span>

								</div>

								<!--礼物之星 end-->

								<div class="room_limit" id="mask1">
									<div class="full_room"></div>
									<p class="full">购买<a href="__APP__/User/toolItem/" title="VIP">VIP</a>或者<a href=__APP__/User/toolItem/ title=金钥匙>金钥匙</a>可以进入</p>
								</div>

								<div class="room_limit" id="mask2">
									<div class="money_room"></div>
									<p class="pays"><span class="give">需支付秀币：</span><span class="money" id="money"></span></p>
									<p class="pay_btn"><a href="javascript:playerMenu.enter();" title=支付>支付</a></p>
								</div>

								<div class="room_limit" id="mask4">
									<p class="stop">您已经被禁止进入本房间！</p>
								</div>

								<div class="room_limit" id="mask3">
									<div class="pwd_room"></div>
									<div class="pwdroom" <span>
										<input type="password" name="room_pwd" id="room_pwd" /> </span><a href=javascript:playerMenu.enter(); title=确定>确定</a></div>
								</div>
							</div>
                          
							<div class="windows_flash">
								<div style="position: absolute; z-index: 99; left: 0px; <?php if($is_live == 'n' and $is_zhubo == '0'): ?>display:none;<?php endif; ?>  ">
                                        <div id=livebox>

                                        </div>
                                </div>
                                <?php if($is_live == 'n' and $is_zhubo == '0'): ?><div style="position: absolute;height:360px;width:480px;z-index:99;">
                                                     <p style="color:#fff;text-align: center;font-size: 20px;margin: 40px;">主播正在休息中</p>
                                                <ul style="padding-top:40px;" class="anchor-list">
                                                    <?php if(is_array($zhubotuijian)): $i = 0; $__LIST__ = $zhubotuijian;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>                
                                                        <a href="/<?php echo ($vo['curroomnum']); ?>"  class="js-play" >                
                                                            <img src="<?php echo ($vo['snap']); ?>" onerror="this.src='__PUBLIC__/images/default.gif'" width="150" height="100" />                            
                                                            <em class="png24 live-tip">直播</em>                                                                                
                                                            <p class="anchor-icon"></p>                        
                                                            <p class="hot-anchor-cover"></p>                
                                                            <p class="hot-anchor-fans"><em class="png24 icon-fansS"></em>
                                                                <?php if($vo['virtualguest'] > 0){echo ($vo['online'] + $vo['virtualguest'] + $virtualcount);}else{echo $vo['online'];} ?>
                                                            </p>                
                                                        </a>                
                                                        <p class="anchor-name"><a target="_blank" href="/<?php echo ($vo['curroomnum']); ?>" data-keyfrom="recommend2.word"><?php echo ($vo["nickname"]); ?></a></p>                
                                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                                </ul>
                                                </div><?php endif; ?>
							</div>

						</div>

						<!--主播操作菜单-->
						<div id="room_zhu" class="roomzhubo clearfix"></div>
						<div class="clear"></div>
					</div>

					<div class=flashbox style="display:none;"></div>
					<div class="my_basic" style="display:none;">

						<dl>

							<dt><a href="<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>"><img src="<?php echo ($ucurl); ?>avatar.php?uid=<?php echo ($userinfo['ucuid']); ?>&size=middle" /></a></dt>

							<dd>

								<p>

									<?php  $emceelevel = getEmceelevel($userinfo['earnbean']); ?>
										<!--主播名称-->
										<span class="star star<?php echo ($emceelevel[0]['levelid']); ?>"></span><strong><a href="<?php echo ($userinfo['curroomnum']); ?>" title="<?php echo ($userinfo['nickname']); ?>"><?php echo ($userinfo['nickname']); ?></a></strong>
										<span class="time" id="showTime"></span>

								</p>

								<p id="wishImitation">

									<?php
 if($userwishs){ ?>

										<?php echo ($userwishs['wish']); ?>

										<?php
 } else{ ?>

											<strong class="p1">您今天还未许愿,快去许愿吧！</strong>

											<?php
 } ?>

								</p>

								<span onClick="Manage.AttentionTo(this);" state="1" class="attentions">+ 关注</span>

							</dd>

						</dl>

					</div>

					<div id="user_sofa" class="giftbox" style="display:block;"></div>

					<div id=giveBox class="poptip wishpop">
						<div class=pop-t><span class=close></span>
							<ul>
								<li class=on id=lm5_1 onclick=turn(1,3,5);>收到礼物</li>
								<li id=lm5_2 onclick=turn(2,3,5);>疯狂热捧</li>
							</ul>
						</div>
						<div class="pop-v mywishv" id=content5_1>
							<div class=wantgift><span>我想要</span>
								<input type=text id=wishGiftNum name=gift-num class=giftnum /> <span>个</span>
								<div class=gift-title onClick="$('#wish-gift-tip').fadeIn();">
									<div id=wishGiftName class=gift-name>礼物</div>
									<div class=choose></div>
								</div><span class="sendBtn fl" onClick="WishGiftCtrl.save($('#wishGiftNum').val(),$('#wishGiftName').html(),1)">确定</span></div>
							<div id=wishGiftId style=display:none;></div>
							<div class="gift-tip giftmodel" id=wish-gift-tip style=display:none;>
								<div class=gift-tab>
								</div>
								<div class=gift-v>

								</div>
							</div>
						</div>
						<div class="pop-v mywishv" style=display:none; id=content5_2>
							<div class=wantgift><span>我希望今天收到</span>
								<input type=text id=warm name=gift-num class=giftnum /> <span>人热捧</span><span class="sendBtn fl" onClick="WishGiftCtrl.save($('#warm').val(),null,2);">确定</span></div>
						</div>
						<div class="pop-v mywishv" style=display:none; id=content5_3>
							<div class=wantgift>
								<textarea id="customWish" class="custom-txt" onChange="if(value.length>100) value=value.substr(0,100);"></textarea> <span class=sendBtn onclick=WishGiftCtrl.save(null,null,3)>确定</span></div>
						</div>
					</div>
					<?php if(($luckgift[is_open]) == "1"): ?><!--<div style="color:#fff;text-align: center;position: absolute;margin-top: -25px;width: 480px;">当前已开启幸运礼物奖励，赠送【<?php echo ($luckgift["sortname"]); ?>】中的礼物可获得虚拟币奖励。</div>--><?php endif; ?>
					<div class="gift-tip giftmodel">
						<div class=gift-tab>

							<ul>

								<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li <?php if($k == 1): ?>class=on<?php endif; ?> id=lm6_<?php echo ($k); ?> onclick="turn(<?php echo ($k); ?>,<?php echo ($StockID); ?>,6);" style=""><?php echo ($vo['sortname']); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
								<li id="lm6_<?php echo ($StockID); ?>" onclick="turn(<?php echo ($StockID); ?>,<?php echo ($StockID); ?>,6)">库存</li>
								<li onclick="window.location.href='/index.php/User/charge/'" class="charge">前往充值</li>
							</ul>
						</div>
 
						<div class="gift-v">

							<?php if(is_array($gifts)): $k = 0; $__LIST__ = $gifts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><ul id="content6_<?php echo ($k); ?>" <?php if($k != 1): ?>style='display:none;'<?php endif; ?>>

									<?php if(is_array($vo['voo'])): $i = 0; $__LIST__ = $vo['voo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li onClick="GiftCtrl.choiceGift(<?php echo ($sub['id']); ?>,'<?php echo ($sub['giftname']); ?>');" class="gift_li">

											<img src="<?php echo ($sub['giftIcon']); ?>" width="42" height="42" />

											<span><?php echo ($sub['giftname']); ?></span>

											<div class="h_dou"> <?php echo ($sub['needcoin']); ?> 个秀币</div>

										</li><?php endforeach; endif; else: echo "" ;endif; ?>

								</ul><?php endforeach; endif; else: echo "" ;endif; ?>
							<!--
	作者：voidcat@163.com
	时间：2016-01-13
	描述：库存礼物
-->

							<ul id="content6_<?php echo ($StockID); ?>">
								<span id='stock_data'>
			<?php if(is_array($Stock)): $i = 0; $__LIST__ = $Stock;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$StockVO): $mod = ($i % 2 );++$i;?><li onClick="GiftCtrl.choiceGift('Stock<?php echo ($StockVO['prizeID']); ?>','<?php echo ($StockVO['name']); ?>');" class="gift_li" >
	
			<img src="<?php echo ($StockVO['giftIcon_25']); ?>" width="42" height="42"/ >
	
			<span><?php echo ($StockVO['name']); ?></span>

								<div class="h_dou"> 剩余<i id="Stock<?php echo ($StockVO['prizeID']); ?>"><?php echo ($StockVO['number']); ?></i>个</div>

								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</span>
							</ul>
						</div>
					</div>

					<div class="giving">
						<div class="send_gift">

							<span>数量</span>

							<div id="gift_num" class="gift_num">

								<input type="text" class="glo_gift t2" maxlength="5" name="gift_num" id="giftnum" />

								<div id="show_num_btn" class="btn_down">↓</div>

							</div>
							<span>给</span>

							<div id="gift_to" class="gift_to">

								<div class="glo_gift t3" id="giftto"></div>

								<div id="show_gift_user_list_btn" class="btn_down">↓</div>

							</div>

						</div>
						<button id="btn_send_gift" class="givingBtn" onClick="GiftCtrl.sendGift();"  type="button" value="赠送">赠送</button>

						<!--暂时隐藏 当前免费花数量-->
						<div class="givingflowers hvr-float-shadow">
							<a href="javascript:void(0);" id="hb_btn" onClick="GiftCtrl.sendHb();" class="flowersBtn fl"></a>

						</div>

						<button id="giftEffects" class="givingBtn" onClick="Manage.giftEffects();"  type="button" value="关闭礼物特效">关闭礼物特效</button>

					</div>

				</div>

				<div class="main_chat">
					<div class="m-billboard" avalonctrl="roomRank">
						<div class="m-tab f-cb">
							<ul>
								<li class="">
									<div class="options">
										<span class="t-name xiusta" stadata="{en:'rank_day_btn_room',xst:'c',et:'mo',tm:'more',v:1}">本日之星</span>
										<img width="50" height="50" alt="" src="
                  <?php
 if($rixing==null) { echo '/passport/avatar.php?uid=10000&amp;size=middle'; } else { echo '/passport/avatar.php?uid='.$rixing[0]['ucuid'].'&amp;size=middle'; } ?>

                  " />
										<i class="icon u-opt"></i>
										<i class="icon u-crown"></i>
									</div>

									<div class="dlg dlg-star-ba f-dn">
										<div class="til f-cb">
											<span class="til-rank">排名</span><span class="til-star">本日之星</span><span class="til-count">贡献值</span>
										</div>
										<!--ms-if-->
										<!-- 无排行时显示 -->
										<div class="jscroll-c">

											<ul class="u-list">
												<span id='rixing_data'>
							<?php if(is_array($rixing)): $k = 0; $__LIST__ = $rixing;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li> 
									<?php if($k < 4): ?><em class="rank-<?php echo ($k); ?>"><?php echo ($k); ?></em><?php else: ?>
									<em class="rank-o"><?php echo ($k); ?></em><?php endif; ?>
									<span class="count" style="line-height:37px;"><?php echo ($vo['gongxian']); ?></span>
												<img class="anchor_photo" src="/passport/avatar.php?uid=<?php echo ($vo['ucuid']); ?>&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;">
												<span class="user-name f-toe" style="line-height:37px;"><?php echo ($vo['nickname']); ?></span>
												<p class="mis">
													<em class="ul ul08" align="absmiddle"></em>
													<!--ms-if-->
												</p>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</span>
								</ul>
								</div>
								</div>
								</li>
								<li class="">
									<div class="options">
										<span class="t-name xiusta" stadata="{en:'rank_week_btn_room',xst:'c',et:'mo',tm:'more',v:1}">本周之星</span>
										<img width="50" height="50" alt="" src="
                  <?php
 if($zhouxing==null) { echo '/passport/avatar.php?uid=10000&amp;size=middle'; } else { echo '/passport/avatar.php?uid='.$zhouxing[0]['ucuid'].'&amp;size=middle'; } ?>

                  " />
										<i class="icon u-opt"></i>
										<i class="icon u-crown"></i>
									</div>

									<div class="dlg dlg-star-ba f-dn">
										<div class="til f-cb">
											<span class="til-rank">排名</span><span class="til-star">本周之星</span><span class="til-count">贡献值</span>
										</div>
										<div class="jscroll-c">

											<!--ms-repeat-->

											<ul class="u-list">
												<span id='zhouxing_data'>
							<?php if(is_array($zhouxing)): $k = 0; $__LIST__ = $zhouxing;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li> 
									<?php if($k < 4): ?><em class="rank-<?php echo ($k); ?>"><?php echo ($k); ?></em><?php else: ?>
									<em class="rank-o"><?php echo ($k); ?></em><?php endif; ?>
									<span class="count" style="line-height:37px;"><?php echo ($vo['gongxian']); ?></span>
												<img class="anchor_photo" src="/passport/avatar.php?uid=<?php echo ($vo['ucuid']); ?>&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;">
												<span class="user-name f-toe" style="line-height:37px;"><?php echo ($vo['nickname']); ?></span>
												<p class="mis">
													<em class="ul ul08" align="absmiddle"></em>
													<!--ms-if-->
												</p>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</span>
								</ul>
								<!--ms-repeat-end-->
								</div>
								<!-- 无排行时显示 -->
								<!--ms-if-->
								</div>
								</li>
								<li class="last">
									<div class="options">
										<span class="t-name xiusta" stadata="{en:'rank_all_btn_room',xst:'c',et:'mo',tm:'more',v:1}">总排行榜</span>
										<img width="50" height="50" alt="" src="
                  <?php
 if($zongxing==null) { echo '/passport/avatar.php?uid=10000&amp;size=middle'; } else { echo '/passport/avatar.php?uid='.$zongxing[0]['ucuid'].'&amp;size=middle'; } ?>

                  " />
										<i class="icon u-opt"></i>
										<i class="icon u-crown"></i>
									</div>

									<div class="dlg dlg-star-ba f-dn">
										<div class="til f-cb">
											<span class="til-rank">排名</span><span class="til-star">总排行榜</span><span class="til-count">贡献值</span>
										</div>
										<div class="jscroll-c">
											<ul class="u-list">
												<span id='zongxing_data'>
							<?php if(is_array($zongxing)): $k = 0; $__LIST__ = $zongxing;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li> 
									<?php if($k < 4): ?><em class="rank-<?php echo ($k); ?>"><?php echo ($k); ?></em><?php else: ?>
									<em class="rank-o"><?php echo ($k); ?></em><?php endif; ?>
									<span class="count" style="line-height:37px;"><?php echo ($vo['gongxian']); ?></span>
												<img class="anchor_photo" src="/passport/avatar.php?uid=<?php echo ($vo['ucuid']); ?>&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;">
												<span class="user-name f-toe" style="line-height:37px;"><?php echo ($vo['nickname']); ?></span>
												<p class="mis">
													<em class="ul ul08" align="absmiddle"></em>
													<!--ms-if-->
												</p>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</span>
								</ul>
								</div>
								<!-- 无排行时显示 -->
								<!--ms-if-->
								</div>
								</li>
							</ul>
						</div>

					</div>



					<div class="m-top-line" id="js-top-line">
						<ul class="js-hl-list" style="height: auto">

							<?php if(is_array($liwulist)): $i = 0; $__LIST__ = $liwulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>

									<b target="_blank" style="color:#FFFFFF;"><em><span class="addtime"><?php echo (date("H:i",$vo['addtime'])); ?></span>&nbsp;<?php echo ($vo['content']); ?></em></b></li><?php endforeach; endif; else: echo "" ;endif; ?>

						</ul>
					</div>
					<div class="chat-tip">

						<div class="chatroom_area" id="chatroom_area">

							<div class="chat_area">

								<h2 class="play-t"><span id="lm4_1" class="on" onClick="turn(1,4,4);">公聊</span><span id="lm4_2" onClick="turn(2,4,4);">礼物</span> <span id="lm4_3" onClick="turn(3,4,4);" style="font: bold;color: #fff;size: 12px">点歌</span> <span id="lm4_4" onClick="turn(4,4,4);">游戏</span> </h2>

								<div class="chat" id="content4_1">

									<div class="room_notice"><strong>【房间公告】</strong>:<span id="room_public_notice"><a href="<?php if($userinfo['annlink'] == ''): ?>javascript://<?php else: echo ($userinfo['annlink']); endif; ?>" target="_blank"><font><?php echo nl2br($userinfo['announce'])?></a></span></div>

									<div class="pubChatSet">富豪等级1以下的用户发言请不要超过10个字哦！</div>

									<div id="upChat" class="chat_room chat_public">

										<div class="chat_btn" style="display:none;">

											<span class="btn_clearMsg" onClick="Chat.clearChat('pulic');">清屏</span>

											<a class="screen_btn" href="javascript:void(0);" onClick="Chat.scrollChat();">

												<cite id="scrollSign" class="on">滚屏</cite>

											</a>

										</div>

										<div id="chat_hall" class="chat_hall"></div>

									</div>

									<div id="dragLine" class="rolling"></div>

									<div id="downChat" class="chat_room chat_private">

										<div class="chat_btn" style="display:none;">

											<span class="btn_clearMsg" onClick="Chat.clearChat('private');">清屏</span>

											<a onClick="Chat.turnPrivateChat();" href="javascript:void(0);" class="screen_btn">

												<cite class="on" id="privateSign">开启私聊</cite>

											</a>

										</div>

										<div id="chat_hall_private" class="chat_hall">

											<div class="pri_txt"><span id="room_private_notice"><a href="<?php if($userinfo['ann2link'] == ''): ?>javascript://<?php else: echo ($userinfo['ann2link']); endif; ?>" target="_blank"><?php if($userinfo['announce2'] == ''): ?>欢迎进入<?php echo ($userinfo['nickname']); ?>直播间<?php else: echo nl2br($userinfo['announce2']); endif; ?></a></span></div>

											<div class="pri_login"></div>

										</div>

									</div>

									<p id="chat_close" class="chat_close">房间公聊关闭</p>

								</div>

								<div class="gift_get" id="content4_2" style="display:none;">
									<ul id="gift_history"></ul>
								</div>

								<div class="song_list" id="content4_3" style="display:none;">

									<div class="song_deal">
										<p></p><span class="sdeal" style="display:none"><em id="songApplyShow"></em>点歌<cite id="songApplyIcon" class="on"></cite></span>

									</div>

									<div class="song_tle">

										<span class="t1">时间</span>

										<span class="line">|</span>

										<span class="t2">歌名</span>

										<span class="line">|</span>

										<span class="t3">点歌粉丝</span>

										<span class="line">|</span>

										<span class="t4">状态</span>

									</div>

									<ul id="usersonglist"></ul>

								</div>

								<div class="song_list" id="content4_4" style="display:none;">
									<div style="width:90%;margin:0 auto">
										<div class="room_notice">
											<?php if($zadankaiqi['enabled'] == 1): ?><a style="text-decoration: none;float:left;" href="javascript:showEgg();" onClick="showEgg();"> <img src="__PUBLIC__/images/xg.png" width="100px" alt="砸金蛋游戏">
													<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;砸金蛋游戏</a>
												<div class="notediv">
													游戏说明：
													<br/> 1.每次花费<?php echo ($hitegg['onceneedcoin']); ?>秀币，最高可获得奖励：<?php echo ($hitegg['wincoin']); ?>秀币。
													<br/> 2.一等奖：<?php echo ($hitegg['wincoin']); ?>秀币；二等奖：<?php echo ($hitegg['wincoin2']); ?>秀币；三等奖：<?php echo ($hitegg['wincoin3']); ?>秀币；四等奖：<?php echo ($hitegg['wincoin4']); ?>秀币。
												</div>
												<div class="linediv"> </div><?php endif; ?>
										</div>
										<div class="room_notice">

											<?php if($liwukaiqi['enabled'] == 1): ?><a style="text-decoration: none;float:left;" href="javascript:showTurntable();" onClick="showTurntable();"> <img src="__PUBLIC__/images/zhuanpan33.gif" width="100px" alt="礼品">
													<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;礼品转盘</a>
												<div class="notediv">
													游戏说明：
													<br/> 每次转盘需要花费<?php echo ($siteconfig["turntable_expense"]); ?>秀币,转中的礼品可用于送给主播。
												</div>
												<div class="linediv"> </div><?php endif; ?>
										</div>
										<div class="room_notice">
											<?php if($jinbikaiqi['enabled'] == 1): ?><a style="text-decoration: none;float:left;" href="javascript:showTurntableCoin();" onClick="showTurntableCoin()"> <img src="__PUBLIC__/images/truntable_icon.gif" width="100px" alt="乐豆转盘">
													<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;秀币转盘</a>
												<div class="notediv">
													游戏说明：
													<br/> 每次转盘需要花费<?php echo ($siteconfig['turntableCoin_expense']); ?>秀币,转中后获取转盘上的秀币奖励。
												</div>
												<div class="linediv"> </div><?php endif; ?>

										</div>

										<div class="room_notice"><a href="###" onClick="showTurntableCharge(<?php echo ($turntable_recharge_number); ?>)" id="ChargeClick">充值奖励抽奖</a> 还剩<i id="turntable_num"><?php echo ($turntable_recharge_number); ?></i>次
											<button class="" onclick="get_turntable_recharge_num()">领取奖券</button>
										</div>
									</div>

								</div>

							</div>

						</div>

						<div class="chatroom_limit" id="chatroom_limit"><img src="__PUBLIC__/images/cover_screen.jpg" /></div>

					</div>

					<div class="chat_msg">
						<div class="xianze">
							<strong>对</strong>

							<p><span id="msg_to_all" class="msg_to_all">所有人</span><span id="msg_to_one" style="display:none;" onClick="GiftCtrl.closeToWho();" class="msg_to_one"><span>MYGO无奈</span></span>

								<input type="hidden" value="" id="to_user_id" name="to_user_id" />
								<input type="hidden" value="" id="to_nickname" name="to_nickname" />
								<input type="hidden" value="" id="to_goodnum" name="to_goodnum" />
							</p>

							<p>
								<input type="checkbox" class="in_check" id="whisper" name="whisper" disabled="disabled" />
								<label for="whisper">悄悄</label>
							</p>

							<div onClick="javascript:Face.showFace();" id="showFaceInfo" class="msg_face"></div>
						</div>
						<p>
							<input type="text" class="in_tx" id="msg" onKeyDown="Chat.submitForm(event);" name="msg" />
						</p>

						<button id="btnsay" class="say sayon" onClick="Chat.doSendMessage();" type="button" value="发言">发言</button>

						<button class="say fly" onClick="Chat.dosendFly();" type="button" value="飞屏">飞屏</button>

					</div>

				</div>

				<div class="clear"></div>

			</div>

		</div>
		<style>
			#flashCallGiftswf {
			  width: 1px;
			  height: 1px;
			  position: absolute;
			  left: 48.2%;
			  top: 234px;
			  z-index: 10000;
			  margin-left: -470px;
			  pointer-events: none;
			}
			#flashCallGiftone {
			  width: 1px;
			  height: 1px;
			  position: absolute;
			  left: 48.2%;
			  top: 234px;
			  z-index: 10000;
			  margin-left: -470px;
			  pointer-events: none;
			}						
		</style>
		<div id="flashCallGiftswf"></div>		
		<div id="flashCallGiftone"></div>
		<div id="flashCallGift"></div>
		<div id="flashFlyWord"></div>

		<div id="egg" style="position:absolute;width:460;height:368;left:490px;top:-10000px; z-index:1000000">
			<div id="flashContent" style="text-align:left;"></div>
		</div>

		<script>
			swfobject_h.embedSWF("/Public/swf/Gifts.swf", "flashCallGift", 1, 1, "10.0", "", {}, {
				wmode: "transparent",
				allowscriptaccess: "always"
			});
			swfobject_h.embedSWF("/Public/swf/Gifts.swf", "flashCallGiftswf", 1, 1, "10.0", "", {}, {
				wmode: "transparent",
				allowscriptaccess: "always"
			});
			swfobject_h.embedSWF("/Public/swf/Gifts.swf", "flashCallGiftone", 1, 1, "10.0", "", {}, {
				wmode: "transparent",
				allowscriptaccess: "always"
			});
			swfobject_h.embedSWF("/Public/swf/FlyWord.swf", "flashFlyWord", 1, 1, "10.0", "", {}, {
				wmode: "transparent",
				allowscriptaccess: "always"
			});
		</script>

		<div class="collectpage" id="collectpage">

			<a href="javascript:favorite.close('collectpage');" class="close" title="关闭">关闭</a>

			<p><a href="javascript:favorite.add();" class="faviate" title="收藏本页">收藏本页</a></p>

		</div>

		

			<script type="text/javascript" language="javascript">
				var hbrankinterval = setInterval(function() {
					$("#hbrank").load('/index.php/Show/show_redbagrank/', function(responseText, textStatus, XMLHttpRequest) {
						this;
					});
				}, 60000);
			</script>

		

		<div id="videobox">

			<div class="jtitle"><span class="jlogo"></span><span class="jfriend"></span><span id="zoom" class="on"></span></div>

			<a href="#" id="jumplikn" target="_blank">
				<div class="jdetail">
					<p>欢迎进入<?php echo ($sitename); ?>互动直播平台！</p>
					<p><strong>如还想接着观看之前的视频专辑</strong></p><span>请点击这里返回</span></div>
			</a>

		</div>

		<div class="p-Song" id="chatOff">

			<div class="m-songt">
				<h4>提示</h4></div>

			<div class="m-songv">

				<div class="promt-msg">

					<p class="msg-text">您与聊天服务断开连接！</p>

					<div class="msg-btn"><span class="play-btn" id="chat_button">确定</span></div>

				</div>

			</div>

		</div>

		<div class="pop_hinfo" id="hoverPerson">

			<div class="hover_des">

				<h4 id="person_title"></h4>
				<ul style="display:none;">

					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico1.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="zslw">赠送礼物</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico2.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="fsxx">发送消息</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico3.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="addfriend">申请加为好友</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico4.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="jy">禁言</a></div>
					</li>
					<li class="line">
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico5.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="hfjy">恢复发言</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico6.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="swgly">升级管理</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico7.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="jwpthy">降为普通会员</a></div>
					</li>
					<li>
						<div class="NavIco fl"> <img src="__PUBLIC__/images/zbj_user_ico8.png"> </div>
						<div class="NavCon fl"><a href="javascript:void(0);" id="tcfj">踢出房间</a></div>
					</li>
				</ul>
				<ul id="ctrllist">

					<li onClick="ChatApp.ShutUp();" class="tdeal"><img src="__PUBLIC__/images/zbj_user_ico4.png">禁言5分钟</li>

					<li onClick="ChatApp.Resume();" class="tdeal"><img src="__PUBLIC__/images/zbj_user_ico5.png">恢复发言</li>

					<li onClick="ChatApp.Kick();" class="tdeal"><img src="__PUBLIC__/images/zbj_user_ico8.png">踢出一小时</li>

					<span class="menuline"></span>

					<!--<li id="tietiao" style="color: rgb(102, 51, 153);"><img src="__PUBLIC__/images/zbj_user_ico7.png"><b>给TA贴条</b></li>-->

					<li onClick="UserListCtrl.sendGift();"><img src="__PUBLIC__/images/zbj_user_ico1.png">发送礼物</li>

					<li onClick="UserListCtrl.chatPublic();"><img src="__PUBLIC__/images/zbj_user_ico6.png">公开地说</li>

					<li onClick="UserListCtrl.chatPrivate();"><img src="__PUBLIC__/images/zbj_user_ico6.png">悄悄的说</li>

					<li>
						<a href="javascript:void(0);" title="" class="enterroom" target="_blank"><img src="__PUBLIC__/images/zbj_user_ico6.png">进入房间</a>
					</li>

					<li class="dblack" onClick="ChatApp.setBlack();"><img src="__PUBLIC__/images/zbj_user_ico6.png">加入黑名单</li>

					<li class="dmanage" onClick="ChatApp.setManager();"><img src="__PUBLIC__/images/zbj_user_ico6.png">设为管理</li>

					<li class="dmanage" onClick="ChatApp.delManager();"><img src="__PUBLIC__/images/zbj_user_ico6.png">删除管理</li>

				</ul>

			</div>

		</div>

		<div class="g_and_b" id="tietiaob">

			<div class="girl_tittle">
				<ul>
					<li>
						<a onMouseOver="show('g')"><img src="__PUBLIC__/images/note/girl_tittle1.png" /></a>
					</li>

					<li>
						<a onMouseOver="show('b')"><img src="__PUBLIC__/images/note/boy_tittle1.png" /></a>
					</li>

				</ul>
			</div>

			<div class="girl_top">
				<a href="#"><img src="__PUBLIC__/images/note/girl_tittle.png" /></a>
			</div>

			<div class="girl_center">

				<ul class="girl_content">

					<?php if(is_array($tietiaos)): $k = 0; $__LIST__ = $tietiaos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($vo['sex'] == '1'): ?><li>
								<a onClick="lj.sendTietiao(<?php echo ($vo['id']); ?>);"><img src="<?php echo ($vo['ttIcon']); ?>" /></a>

								<p><?php echo ($vo['needcoin']); ?>秀币</p>
							</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

				</ul>

				<ul class="boy_content">

					<?php if(is_array($tietiaos)): $k = 0; $__LIST__ = $tietiaos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($vo['sex'] == '0'): ?><li>
								<a onClick="lj.sendTietiao(<?php echo ($vo['id']); ?>);"><img src="<?php echo ($vo['ttIcon']); ?>" /></a>

								<p><?php echo ($vo['needcoin']); ?>秀币</p>
							</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

				</ul>

			</div>

			<div class="girl_foot"><img src="__PUBLIC__/images/note/bodybj_foot.png" /></div>

		</div>

		<script type="text/javascript">
			function show(i) {
				if (i == "g") {
					$(".girl_content").show();
					$(".girl_tittle").find("li").eq(0).find("img").attr("src", "__PUBLIC__/images/note/girl_tittle1.png");
					$(".girl_tittle").find("li").eq(1).find("img").attr("src", "__PUBLIC__/images/note/boy_tittle1.png");
					$(".boy_content").hide();
				} else if (i == "b") {
					$(".boy_content").show();
					$(".girl_tittle").find("li").eq(0).find("img").attr("src", "__PUBLIC__/images/note/girl_tittle11.png");
					$(".girl_tittle").find("li").eq(1).find("img").attr("src", "__PUBLIC__/images/note/boy_tittle11.png");
					$(".girl_content").hide();
				}
			}
		</script>

		<div class="lpet">

			<div id="JoyPet_left"></div>

		</div>

		<div class="rpet">

			<div id="JoyPet_right"></div>

		</div>

		</volist>

		</div>
		</div>
		</div>
		<div class="pop-v mywishv" style=display:none; id=content5_2>
			<div class=wantgift><span>我希望今天收到</span>
				<input type=text id=warm name=gift-num class=giftnum /> <span>人热捧</span><span class="sendBtn fl" onClick="WishGiftCtrl.save($('#warm').val(),null,2);">确定</span></div>
		</div>
		<div class="pop-v mywishv" style=display:none; id=content5_3>
			<div class=wantgift>
				<textarea id="customWish" class="custom-txt" onChange="if(value.length>100) value=value.substr(0,100);"></textarea> <span class=sendBtn onclick=WishGiftCtrl.save(null,null,3)>确定</span></div>
		</div>
		</div>

		<div class="giftBox1" id="gift-givenum">
			<div class="gift-numul">
				<ul>
					<li><a onClick="GiftCtrl.giftNum(11)" href="javascript:void(0);">11个</a></li>
					<li><a onClick="GiftCtrl.giftNum(66)" href="javascript:void(0);">66个</a></li>
					<li><a onClick="GiftCtrl.giftNum(99)" href="javascript:void(0);">99个</a></li>
					<li><a onClick="GiftCtrl.giftNum(300)" href="javascript:void(0);">300个</a></li>
					<li><a onClick="GiftCtrl.giftNum(520)" href="javascript:void(0);">520个</a></li>
					<li><a onClick="GiftCtrl.giftNum(1314)" href="javascript:void(0);">1314个</a></li>

				</ul>
			</div>
		</div>

		<div class="giftBox1" id="redBagBox" style="width:250px;color:#333333;">

			loading...

		</div>

		<div class=playerBox id=playerBox>
			<div class=playerlist>
				<ul id=gift_userlist></ul>
			</div>
		</div>

		<div class=playerBox id=playerBox1>
			<div class=playerlist>
				<ul id=chat_userlist></ul>
			</div>
		</div>
		<div class=p-Song id=alertBox>
			<div class=m-songt><span class=s-close id=close_msg></span>
				<h4>提示</h4></div>
			<div class=m-songv>
				<div class=promt-msg>
					<p class=msg-text id=msg_text></p>
					<div class=msg-btn id=poptype1><span class=play-btn id=data-confirm>确定</span></div>
					<div class=msg-btn id=poptype2><span id=btnAgree class=play-btn>同意</span> <span id=btnDisgree class=play-btn>取消</span></div>
					<div class=msg-btn id=poptype3><span class=play-btn id=btnConfirm>确定</span></div>
				</div>
			</div>
		</div>
		<div class="p-Song d-song" id=song_dialog>
			<div class=m-songt><span class=close onClick="$('#song_dialog').hide();"></span>
				<h4>主播的点歌本</h4></div>
			<div class=m-songv>
				<table class=song-table id=song_table>
					<tr>
						<th>歌名</th>
						<th>歌名</th>
						<th>原唱</th>
						<th>操作</th>
					</tr>
				</table>
				<div class=page id=page></div>
				<div class=song-txt><strong>点歌说明：</strong>
					<p>1、皇冠主播1500,钻石主播1000,其他主播500秀币
						<br /> 2、当主播接受点歌后正式收取点歌费用</p>
				</div>
			</div>
		</div>
		<div class=p-Song id=addSong>
			<div class=m-songt><span class=close onClick="$('#addSong').hide();"></span>
				<h4>添加歌曲</h4></div>
			<div class=m-songv>
				<table class=song-table>
					<tr>
						<th>歌名(必填)</th>
						<th>原唱(选填)</th>
					</tr>
					<tr>
						<td><strong>1</strong>
							<input type=text id=name_1 class="song_in sinput1" />
						</td>
						<td>
							<input type=text id=singer_1 class="song_in sinput2" />
						</td>
					</tr>
					<tr>
						<td><strong>2</strong>
							<input type=text id=name_2 class="song_in sinput1" />
						</td>
						<td>
							<input type=text id=singer_2 class="song_in sinput2" />
						</td>
					</tr>
					<tr>
						<td><strong>3</strong>
							<input type=text id=name_3 class="song_in sinput1" />
						</td>
						<td>
							<input type=text id=singer_3 class="song_in sinput2" />
						</td>
					</tr>
					<tr>
						<td><strong>4</strong>
							<input type=text id=name_4 class="song_in sinput1" />
						</td>
						<td>
							<input type=text id=singer_4 class="song_in sinput2" />
						</td>
					</tr>
					<tr>
						<td><strong>5</strong>
							<input type=text id=name_5 class="song_in sinput1" />
						</td>
						<td>
							<input type=text id=singer_5 class="song_in sinput2" />
						</td>
					</tr>
				</table>
				<div class=song_btn><span class=play-btn onclick=Song.saveBatchSong();>提交</span></div>
			</div>
		</div>
		<iframe id=frmFile name=frmFile frameborder=0 scrolling=no height=0 width=0></iframe>
		<div class="p-Song d-song" id=song_dialog2>
			<div class=m-songt><span class=close onClick="$('#song_dialog2').hide();"></span>
				<h4>主播的点歌本</h4></div>
			<div class=m-songv>
				<table class=song-table id=song_table2>
					<tr>
						<th>歌名</th>
						<th>歌名</th>
						<th>原唱</th>
						<th>状态</th>
					</tr>
				</table>

				<div class="page" id="page2"></div>
				<div class=wantgift><span>自选歌曲</span>
					<input type=text name=songName id=songName class=songNotebook pro-msg=歌曲名(必选) value="歌曲名(必选)" />
					<input type=text name=songSinger id=songSinger class=ysong value=原唱 pro-msg="原唱" />
					<input type=hidden name=songId id=songId value="0" /> <span class=play-btn onclick=Song.vodSong();>点歌</span></div>
				<div class=song-txt><strong>点歌说明：</strong>
					<p>1、皇冠主播1500,钻石主播1000,其他主播500秀币
						<br /> 2、当主播接受点歌后正式收取点歌费用</p>
				</div>
			</div>
		</div>

		<!--广播显示框-->

		<div class="g">

			<div class="scroll">

				<div class="scroll_ll"></div>

				<div class="scroll_cc">

					<div class="scroll_lt">

						<div class="scroll_text" id="theContent">

							<div id="theText" class="theText">
								<ul>
								</ul>
							</div>

						</div>

						<div class="gundongtiao">

							<div class="j_l" id="leftbtn"></div>

							<div class="j_c" id="scrollbar"> <span class="s_tiao" id="scrollblock"></span></div>

							<div class="j_r" id="rightbtn"></div>

						</div>

					</div>

					<div class="scroll_lb" id="scroll_lb">发布广播</div>

					<!--发布广播begin-->

					<div class="tishikuang" id="tishikuang" style="display:none;">

						<div class="mingcheng">输入文本 <span class="guan" id="guan"></span>

							<div class="clear"></div>

						</div>

						<form action="" id="broadcastFrom" name="broadcastFrom" method="get">

							<textarea name="" id="msgGb" cols="" rows="" class="textarea">| 输入文字不超过50个字。每次广播花费500秀币</textarea>

							<div class="msg_face" id="showFaceInfoGb" onClick="FaceGb.showFace()"></div>

							<div class="que" id="btnsubmit">确定</span>
							</div>

						</form>

						<script type="text/javascript">
							// 发布广播控制
							broadcast.initialize();
						</script>

						<!--发布广播end-->

					</div>

				</div>

				<div class="scroll_rr"></div>

				<div class="clear"></div>

			</div>

		</div>

		<script>
			var labasb = new myscrollbar("scrollblock", "scrollbar", "theContent", "theText", "leftbtn", "rightbtn")
			labasb.Initialize();
			labasb.execute()
		</script>

		<div class="UbbFace" id="ChatFace">

			<div class="faceinfo">

				<em onClick="javascript:Face.addEmot('[`1f61a`]');" class=face1 title=OK></em>

				<em onClick="javascript:Face.addEmot('[`1f61c`]');" class=face2 title=白眼></em>

				<em onClick="javascript:Face.addEmot('[`1f63b`]');" class=face3 title=抱抱></em>

				<em onClick="javascript:Face.addEmot('[`1f63f`]');" class=face4 title=菜刀></em>

				<em onClick="javascript:Face.addEmot('[`1f600`]');" class=face5 title=承让></em>

				<em onClick="javascript:Face.addEmot('[`1f601`]');" class=face6 title=愁人></em>

				<em onClick="javascript:Face.addEmot('[`1f602`]');" class=face7 title=大哭></em>

				<em onClick="javascript:Face.addEmot('[`1f603`]');" class=face8 title=大笑></em>

				<em onClick="javascript:Face.addEmot('[`1f604`]');" class=face9 title=淡定></em>

				<em onClick="javascript:Face.addEmot('[`1f605`]');" class=face10 title=顶></em>

				<em onClick="javascript:Face.addEmot('[`1f606`]');" class=face11 title=飞吻></em>

				<em onClick="javascript:Face.addEmot('[`1f607`]');" class=face12 title=尴尬></em>

				<em onClick="javascript:Face.addEmot('[`1f608`]');" class=face13 title=给力></em>

				<em onClick="javascript:Face.addEmot('[`1f609`]');" class=face14 title=勾引></em>

				<em onClick="javascript:Face.addEmot('[`1f610`]');" class=face15 title=鼓掌></em>

				<em onClick="javascript:Face.addEmot('[`1f611`]');" class=face16 title=跪拜></em>

				<em onClick="javascript:Face.addEmot('[`1f612`]');" class=face17 title=害羞></em>

				<em onClick="javascript:Face.addEmot('[`1f613`]');" class=face18 title=吼吼></em>

				<em onClick="javascript:Face.addEmot('[`1f614`]');" class=face19 title=坏笑></em>

				<em onClick="javascript:Face.addEmot('[`1f615`]');" class=face20 title=火大></em>

				<em onClick="javascript:Face.addEmot('[`1f616`]');" class=face21 title=奸笑></em>

				<em onClick="javascript:Face.addEmot('[`1f617`]');" class=face22 title=惊讶></em>

				<em onClick="javascript:Face.addEmot('[`1f618`]');" class=face23 title=开心></em>

				<em onClick="javascript:Face.addEmot('[`1f619`]');" class=face24 title=可怜></em>

				<em onClick="javascript:Face.addEmot('[`1f620`]');" class=face25 title=狂笑></em>

				<em onClick="javascript:Face.addEmot('[`1f621`]');" class=face26 title=靓仔></em>

				<em onClick="javascript:Face.addEmot('[`1f622`]');" class=face27 title=美女></em>

				<em onClick="javascript:Face.addEmot('[`1f623`]');" class=face28 title=媚眼></em>

				<em onClick="javascript:Face.addEmot('[`1f624`]');" class=face29 title=呕吐></em>

				<em onClick="javascript:Face.addEmot('[`1f625`]');" class=face30 title=飘过></em>

				<em onClick="javascript:Face.addEmot('[`1f626`]');" class=face31 title=亲亲></em>

				<em onClick="javascript:Face.addEmot('[`1f627`]');" class=face32 title=色色></em>

				<em onClick="javascript:Face.addEmot('[`1f628`]');" class=face33 title=伤不起></em>

				<em onClick="javascript:Face.addEmot('[`1f629`]');" class=face34 title=拜拜></em>

				<em onClick="javascript:Face.addEmot('[`1f630`]');" class=face35 title=调戏></em>

				<em onClick="javascript:Face.addEmot('[`1f631`]');" class=face36 title=偷笑></em>

				<em onClick="javascript:Face.addEmot('[`1f632`]');" class=face37 title=吐血></em>

				<em onClick="javascript:Face.addEmot('[`1f633`]');" class=face38 title=挖鼻></em>

				<em onClick="javascript:Face.addEmot('[`1f634`]');" class=face39 title=围观></em>

				<em onClick="javascript:Face.addEmot('[`1f635`]');" class=face40 title=委屈></em>

				<em onClick="javascript:Face.addEmot('[`1f636`]');" class=face41 title=无语></em>

				<em onClick="javascript:Face.addEmot('[`1f637`]');" class=face42 title=鸭梨></em>

				<em onClick="javascript:Face.addEmot('[`1f638`]');" class=face43 title=耶></em>

				<em onClick="javascript:Face.addEmot('[`1f639`]');" class=face44 title=怎么了></em>

				<em onClick="javascript:Face.addEmot('[`1f640`]');" class=face45 title=抓狂></em>

			</div>

		</div>

		<!-- 广播表情 -->

		<div class="UbbFace" id="ChatFaceGb">

			<div class="faceinfo">

				<em onClick="javascript:FaceGb.addEmot('[`1f61a`]');" class=face1 title=OK></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f61c`]');" class=face2 title=白眼></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f63b`]');" class=face3 title=抱抱></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f63f`]');" class=face4 title=菜刀></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f600`]');" class=face5 title=承让></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f601`]');" class=face6 title=愁人></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f602`]');" class=face7 title=大哭></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f603`]');" class=face8 title=大笑></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f604`]');" class=face9 title=淡定></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f605`]');" class=face10 title=顶></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f606`]');" class=face11 title=飞吻></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f607`]');" class=face12 title=尴尬></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f608`]');" class=face13 title=给力></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f609`]');" class=face14 title=勾引></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f610`]');" class=face15 title=鼓掌></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f611`]');" class=face16 title=跪拜></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f612`]');" class=face17 title=害羞></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f613`]');" class=face18 title=吼吼></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f614`]');" class=face19 title=坏笑></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f615`]');" class=face20 title=火大></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f616`]');" class=face21 title=奸笑></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f617`]');" class=face22 title=惊讶></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f618`]');" class=face23 title=开心></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f619`]');" class=face24 title=可怜></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f620`]');" class=face25 title=狂笑></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f621`]');" class=face26 title=靓仔></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f622`]');" class=face27 title=美女></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f623`]');" class=face28 title=媚眼></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f624`]');" class=face29 title=呕吐></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f625`]');" class=face30 title=飘过></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f626`]');" class=face31 title=亲亲></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f627`]');" class=face32 title=色色></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f628`]');" class=face33 title=伤不起></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f629`]');" class=face34 title=拜拜></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f630`]');" class=face35 title=调戏></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f631`]');" class=face36 title=偷笑></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f632`]');" class=face37 title=吐血></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f633`]');" class=face38 title=挖鼻></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f634`]');" class=face39 title=围观></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f635`]');" class=face40 title=委屈></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f636`]');" class=face41 title=无语></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f637`]');" class=face42 title=鸭梨></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f638`]');" class=face43 title=耶></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f639`]');" class=face44 title=怎么了></em>

				<em onClick="javascript:FaceGb.addEmot('[`1f640`]');" class=face45 title=抓狂></em>

			</div>

		</div>

		<div id=current_sofa class=current_sofa>
			<div class=sofa-tip>当前抢座，最少需要抢<span></span>个沙发</div>
		</div>
		<div id=get_sofa class=get_sofa>
			<div class=get_sofa_tip>沙发数量：
				<input type=text class=getseatnum id=getseatnum>
				<input type=hidden id=sofaid value=0>
				<button onclick=GiftCtrl.fetch_sofa();>抢座</button> <em>(100秀币/沙发)</em></div>

		</div>

		<script type="text/javascript" language="javascript">
			$(function() {
				InitCache();
				setTimeout(function() {
					$("#aboutp").load('__URL__/show_infoWithgwRanking/emceeId/' + _show.emceeId + '/rand/' + Math.random() + '/', function(responseText, textStatus, XMLHttpRequest) {
						this;
					}); //本周排行
				}, 2800);
				$(".area-v,.area-i,.giftnum,.ysong,.songNotebook").ClearRoom(); //初始化清空
				$(document.body).click(function(e) {
					var f = e.target;
					while (f.tagName.toLowerCase() != "body") {
						if (in_array(f.id, Chat.arrChatModel)) {
							return
						}
						f = f.parentNode;
					}
					$('#gift_model,#playerBox,#playerBox1,#ChatFace,#get_sofa,#gift-givenum,#hoverPerson,#tishikuang,#ChatFaceGb').hide();
				}); //layer Dom处理
				<?php
 if($userwishs){ ?>
				WishGiftCtrl.Countdown();
				<?php
 } ?>
				initBack();
			});
		</script>

		<script>
			jQuery(document).ready(function() {
				$('#dragLine').KSubfield({
					_axes: 'y', //y = pageY, y=page=Y
					_axesElement: '#upChat,#downChat' //上下 DIV元素
				});
			});
		</script>
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/joy_tip.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/stringprototype.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/date.js"></script>

		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/wishing.js"></script>
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/mymanage.js"></script>
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/socket.io.js"></script>
		<script type="text/javascript" language="javascript" src="__PUBLIC__/js/CoreJS/eventListen.js"></script>

		<script type="text/javascript">
			if (Sys.ie6) {
				$(".lpet,.rpet").css({
					"position": "absolute",
					"top": (window.screen.height - window.screenTop + document.documentElement.scrollTop - 446) + "px"
				});
				window.onscroll = function() {
					$(".lpet,.rpet").css({
						"top": (window.screen.height - window.screenTop + document.documentElement.scrollTop - 446) + "px"
					});
				}
			}
			//20141124
			setInterval("Chat.doSendMessage2()", 10000);
			//20141124
		</script>

		<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
		<script>
			$(document).ready(function(e) {
				$(".gamebuts").click(function() {
					$(this).find(".room_pop1").show();
				});
				$(".room_pop1").mouseleave(
					function() {
						$(this).hide();
					}
				);
				$(".room_sidebar ul li").hover(
					function() {
						$(this).find("div").show();
						$(this).addClass("mm");
					},
					function() {
						$(this).find("div").hide();
						$(this).removeClass("mm");
					}
				);
				$('#mike_btn').click(function() {
					Chat.doUpMike();
				});
			});
			/*
			 * 作者：zhl
			 * 时间：2016-02-01 14:22:01
			 * 内容：定时获取直播页的信息（主播信息，守护，库存礼物，右上角排行）
			 */
			//定时更新主播信息
			function updateEmceeLevel() {
				$.ajax({
					type: "get",
					url: "/index.php/Show/show_updateEmceeLevel/eid/" + _show.emceeId,
					async: true,
					success: function(data) {
						var levelInfo = evalJSON(data);
						$('#progress').css("width", levelInfo[0] + "%");
						if (levelInfo[3]) {
							var nextlevel = parseInt(levelInfo[3]) + parseInt(1);
						} else {
							var nextlevel = levelInfo[1];
						}
						$('#next_level').addClass("star star" + nextlevel + " llevel_2");
						$('#new_Levelcoin').html("还差" + levelInfo[2] + "秀豆");
						$('#nowlevel').addClass("star star" + levelInfo[3] + " llevel_1");
						$('#att_count').text(levelInfo[4]);
					}
				});
			}
			//定时更新守护
			function updateGuard() {
				$.ajax({
					type: "get",
					url: "/index.php/Show/updateGuard/roomid/" + _show.goodNum,
					async: true,
					success: function(data) {
						//当前守护数
						$("#guard_count").text(data.guard_count);
						//剩余守护空位
						var m = '';
						var li_data = '<li class="guard_no"><a onclick="shoucang()"></a></li>';
						for (var i = 0; i < data.guard_surplus; i++) {
							m += li_data;
						}
						$("#guard_surplus").html();
						$("#guard_surplus").html(m);
						//当前守护
						var mm = '';
						if (data.WholeGuard) {
							for (var ii = 0; ii < data.WholeGuard.length; ii++) {
								mm += '<li class="guardians" title="' + data.WholeGuard[ii]["nickname"] + '"> <a href="#"  ><img src="<?php echo ($ucurl); ?>avatar.php?uid=' + data.WholeGuard[ii]["ucuid"] + '&size=middle"  /></a><div  class="shouhu_06" >' + data.WholeGuard[ii]["nickname"] + '</div></li>';
							}
							$("#old_guard").html();
							$("#old_guard").html(mm);
						}
					}
				});
			}
			//定时更新右上角排行
			function updatePaihang() {
				$.ajax({
					type: "get",
					url: "/index.php/Show/updatePaihang/roomid/" + _show.goodNum,
					async: true,
					success: function(data) {
						//日排行
						var today = '';
						for (var i = 0; i < data.rixing.length; i++) {
							var i2 = parseInt(i) + parseInt(1);
							if (i < 4) {
								today += '<li><em class="rank-' + i2 + '">' + i2 + '</em><span class="count" style="line-height:37px;">' + data.rixing[i]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.rixing[i]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.rixing[i]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							} else {
								today += '<li><em class="rank-o">' + i2 + '</em><span class="count" style="line-height:37px;">' + data.rixing[i]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.rixing[i]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.rixing[i]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							}
						}
						$('#rixing_data').html('');
						$('#rixing_data').html(today);
						//周排行
						var week = '';
						for (var ii = 0; ii < data.zhouxing.length; ii++) {
							var ii2 = parseInt(ii) + parseInt(1);
							if (i < 4) {
								week += '<li><em class="rank-' + ii2 + '">' + ii2 + '</em><span class="count" style="line-height:37px;">' + data.zhouxing[ii]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.zhouxing[ii]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.zhouxing[ii]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							} else {
								week += '<li><em class="rank-o">' + ii2 + '</em><span class="count" style="line-height:37px;">' + data.zhouxing[ii]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.zhouxing[ii]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.zhouxing[ii]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							}
						}
						$('#zhouxing_data').html('');
						$('#zhouxing_data').html(week);
						//总排行
						var zong = '';
						for (var iii = 0; iii < data.zongxing.length; iii++) {
							var iii2 = parseInt(iii) + parseInt(1);
							if (i < 4) {
								zong += '<li><em class="rank-' + iii2 + '">' + iii2 + '</em><span class="count" style="line-height:37px;">' + data.zongxing[iii]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.zongxing[iii]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.zongxing[iii]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							} else {
								zong += '<li><em class="rank-o">' + iii2 + '</em><span class="count" style="line-height:37px;">' + data.zongxing[iii]["gongxian"] + '</span><img class="anchor_photo" src="/passport/avatar.php?uid=' + data.zongxing[iii]["ucuid"] + '&amp;size=middle" onerror="this.src=&quot;http://sr.9513.com/live/images/nophoto.gif&quot;" style="width: 37px;height:37px;float:left;margin-right:5px;"><span class="user-name f-toe" style="line-height:37px;">' + data.zongxing[iii]["nickname"] + '</span><p class="mis"><em class="ul ul08" align="absmiddle"></em><!--ms-if--></p></li>';
							}
						}
						$('#zongxing_data').html('');
						$('#zongxing_data').html(zong);
					},
				});
			}
			setInterval(function() {
				updateEmceeLevel();
				//updateGuard();
				//updatePaihang();
			}, 10000);
			//定时更新库存礼物(暂时未用)
			function updateStock() {
				$.ajax({
					type: "get",
					url: "/index.php/Show/updateStock/",
					async: true,
					success: function(data) {
						var m = '';
						for (var i = 0; i < data.length; i++) {
							var prizeID = data[i]['prizeID'];
							var name = data[i]['name'];
							var giftIcon25 = data[i]['giftIcon_25'];
							var Number = data[i]['number'];
							m += '<li onClick="GiftCtrl.choiceGift("Stock' + prizeID + '","' + name + '");" class="gift_li" ><img src="' + giftIcon25 + '" width="42" height="42"/ ><span>' + name + '</span><div class="h_dou" style="display: none; left: 275px; top: 520px;"> 剩余<i id="' + prizeID + '">' + Number + '</i>个</div></li>';
						}
						$("#stock_data").html(m);
					},
				});
			}
			//礼物特效初始化
			if ($cookie.getCookie("giftEffects") != "NO") {
				$("#giftEffects").text("关闭礼物特效");
			} else {
				$("#giftEffects").text("开启礼物特效");
			}
		</script>

		<div style="height:80px;"></div>

	</body>

</html>