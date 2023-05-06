

/*聊天*/
var Chat = {
	msgLen: 0,
	scrollChatFlag: 1,
	is_private: 0, //是否私聊
	tempMsg: "",
	gift_swf: "",
	chat_max_text_len: 200,
	fly_max_text_len: 40,
	userlengthcontrol: 10,
	toGiftInfo: "",
	arrGiftInfo: [],
	videoTimer: null,
	arrChatModel: ["gift_model", "gift-givenum", "playerBox", "playerBox1", "gift_name", "gift_num", "gift_to", "msg_to_all", "ChatFace", "showFaceInfo", "get_sofa", "user_sofa", "hoverPerson", "msgGb", "scroll_lb", "btnsubmit", "guan", "showFaceInfoGb", "ChatFaceGb"],
	clearChat: function(flag) {
		if (flag == 'pulic') {
			Chat.msgLen = 0;
			$("#chat_hall").empty();
		} else if (flag == 'private') {
			$('#chat_hall_private').find('p').remove();

		}
	},

	closeTopBar: function(modelID) {
		$('#' + modleID).hide();
	},
	richLevel: function(uid) {
		var user = $('#online_' + uid);
		var rich = user.attr('richlevel');
		if (!rich) {
			rich = 0;
		}
		return rich;
	},
	scrollChat: function() {
		if (Chat.scrollChatFlag == 1) {
			Chat.scrollChatFlag = 0;
			$("#scrollSign").attr('class', 'off');
		} else {
			Chat.scrollChatFlag = 1;
			$("#scrollSign").attr('class', 'on');
		}
	},
	turnPrivateChat: function() {
		if (Chat.is_private == 1) {
			Chat.is_private = 0;
			$("#privateSign").html("开启私聊");
			$("#privateSign").attr("class", "on");
		} else {
			Chat.is_private = 1;
			$("#privateSign").html("关闭私聊");
			$("#privateSign").attr("class", "off");
		}
	},
	setDisabled: function(n) {
		$("#btnsay").attr("disabled", "disabled");
		$("#btnsay").attr("class", "say sayoff");
		setTimeout(function() {
			$("#btnsay").attr("disabled", false);
			$("#btnsay").attr("class", "say sayon");
			$("#msg").focus();
		}, n * 80);
	},
	dosendFly: function() { //飞屏
		if (_show.userId < 0) {
			UAC.openUAC(0);
			return false;
		}
		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}

		var touid = $("#to_user_id").val(),
			toname = $('#to_nickname').val(),
			fmsg = Face.deimg($("#msg").val()),
			eid = _show.emceeId;
		if (fmsg.length > this.fly_max_text_len) {
			_alert('您的飞屏内容过长,请确保不超过40个汉字！', 3);
			$("#msg").focus();
			return false;
		}
		if (touid == "") {
			touid = 0;
		}
		if (!fmsg) {
			_alert('请输入内容！', 3);
			$("#msg").focus();
			return false;
		}
		$("#msg").focus();
		$("#msg").val('');
		if (confirm("每条飞屏将花费您1000秀币，请确认是否发送？")) {
			var url = "/index.php/Show/dosendFly/eid/" + eid + "/toid/" + touid + "/toname/" + encodeURIComponent(toname) + "/fmsg/" + encodeURIComponent(fmsg) + "/t/" + Math.random();
			$.getJSON(url, function(data) {
				
				if (data && data.code == 0) {
					var msg = "{\"msg\":[{\"_method_\":\"SendFly\",\"action\":\"23\",\"ct\":{\"token\":\"" + data.token + "\",\"uno\":\"" + _show.uroom + "\",\"uid\":\"" + _show.userId + "\",\"uname\":\"" + _show.uname + "\",\"touid\":\"" + touid + "\",\"touname\":\"" + toname + "\",\"word\":\"" + fmsg + "\",\"gift\":\"/Public/images/gift/feiping.png\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
				} else {
					_alert(data.info, 5);
				}
			});
		}
	},
	doSendMessage: function() {
		if (_show.userId < 0) {
			UAC.openUAC(0);
			return false;
		}
		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}
		var w = $("#msg");
		var wval = $("#msg").val();
		var to_user_id = $("#to_user_id").val();
		var to_nickname = $("#to_nickname").val();
		var to_goodnum = $("#to_goodnum").val();
		var whisper = $("#whisper").attr("checked") == "checked" ? 1 : -1;
		wval = wval.substr(0, this.chat_max_text_len);
		if (_show.richlevel == 0 && _show.admin != 1 && _show.emceeId != _show.userId && _show.sa != 1) {
			if (wval.length > this.userlengthcontrol) {
				_alert('富豪等级1以下用户发言不能超过10个字！快快升级吧！', 5);
				$("#msg").focus();
				$("#msg").val('');
				return false;
			}
		}
		if (to_user_id == _show.userId) {
			_alert('自己不能给自己聊！', 3);
			return false;
		}
		if (_show.is_public == "0" && whisper == -1) { //关闭私聊
			if (_show.emceeId != _show.userId && _show.admin == "0" && _show.sa == "0") { //是普通、游客类型的用户
				_alert('房间公聊已关闭！', 3);
				return false;
			}
		}
		if (!wval) {
			_alert('请输入内容！', 3);
			$("#msg").focus();
			return false;
		}
		$("#msg").focus();
		$("#msg").val('');

		//var rich=Chat.richLevel(_show.userId);//Chat.richLevel()
		//if(rich>0){Chat.setDisabled(2);}else{Chat.setDisabled(5);}
		_vc = typeof(_vc) == undefined ? "" : _vc;
		// action  0:公聊  1 悄悄  2 私聊
		if (_vc == "") {
			var rich = Chat.richLevel(_show.userId);
			if (rich > 0) {
				Chat.setDisabled(3);
			} else {
				Chat.setDisabled(5);
			}
			if (to_user_id == "" && to_nickname == "") { //公聊
				Socket.emitData('broadcast',"{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":0,\"ct\":\""+wval+"\",\"msgtype\":\"2\",\"timestamp\":\""+WlTools.FormatNowDate()+"\",\"tougood\":\"\",\"touid\":\"\",\"touname\":\"\",\"ugood\":\""+_show.roomId+"\",\"uid\":\""+_show.userId+"\",\"uname\":\""+_show.uname+"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}");
				//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"SendPubMsg","ct":"' + wval + '","checksum":""}');

			} else {
				if (whisper == 1) { // 别人看不到(私聊)+ w
					var  msg = "{\"msg\":[{\"_method_\":\"SendPrvMsg\",\"action\":\"2\",\"ct\":\"" + val + "\",\"msgtype\":\"2\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + to_goodnum + "\",\"touid\":\"" + to_user_id + "\",\"touname\":\"" + to_nickname + "\",\"ugood\":\"" + _show.uroom + "\",\"uid\":\"" + _show.userId + "\",\"uname\":\"" + _show.uname + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					//Dom.$swfId("flashCallChat")._chatToSocket(2, 2, '{"_method_":"SendPrvMsg","touid":"' + to_user_id + '","touname":"' + to_nickname + '","tougood":"' + to_goodnum + '","ct":"' + wval + '","pub":"0","checksum":""}');
				} else {
					//在公聊区域显示 大家都能看到(悄悄)
					var msg = "{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"2\",\"ct\":\"" + wval + "\",\"msgtype\":\"2\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + to_goodnum + "\",\"touid\":\"" + to_user_id + "\",\"touname\":\"" + to_nickname + "\",\"ugood\":\"" + to_goodnum + "\",\"uid\":\"" + _show.userId + "\",\"uname\":\"" + _show.uname + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					//Dom.$swfId("flashCallChat")._chatToSocket(1, 2, '{"_method_":"SendPrvMsg","touid":"' + to_user_id + '","touname":"' + to_nickname + '","tougood":"' + to_goodnum + '","ct":"' + wval + '","pub":"1","checksum":""}');
				}
			}
		} else {
			var cts = $("#ChatWrap").css({
				"top": "-114px",
				"display": "block"
			});
			if (to_user_id == "" && to_nickname == "") { //公聊
				Dom.$swfId("flashCallChat").chatVerificationCode(0, 2, '{"_method_":"SendPubMsg","ct":"' + wval + '","checksum":""}', _vc);
			} else {
				if (whisper == 1) { // 别人看不到(私聊)
					Dom.$swfId("flashCallChat").chatVerificationCode(2, 2, '{"_method_":"SendPrvMsg","touid":"' + to_user_id + '","touname":"' + to_nickname + '","ct":"' + wval + '","pub":"0","checksum":""}', _vc);
				} else {
					//在公聊区域显示 大家都能看到(悄悄)
					Dom.$swfId("flashCallChat").chatVerificationCode(1, 2, '{"_method_":"SendPrvMsg","touid":"' + to_user_id + '","touname":"' + to_nickname + '","ct":"' + wval + '","pub":"1","checksum":""}', _vc);
				}
			}
		}
	},
	//20141124
	doSendMessage2: function() {
		//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"SendPubMsg","ct":"I am alive","checksum":""}');
	},
	
	submitForm: function(evt) {
		var evt = evt ? evt : (window.event ? window.event : null);
		if (evt.keyCode == 13 || (evt.ctrlKey && evt.keyCode == 13) || (evt.altKey && evt.keyCode == 83)) {
			if ($("#btnsay").attr("disabled") != "disabled") {
				Chat.doSendMessage();
			}
		}
	},
	getUserBalance: function() { //用户秀币更新
		var url = "/index.php/Show/show_getUserBalance/t/" + Math.random();
		$.getJSON(url, function(json) {
			if (json) {
				if (json["code"] == "0") {
					$('.others .red').html(json["value"].replace(/^(\d*)\.\d+$/, "$1"));
				}
			}

		});
	},
	getRankByShow: function() { //更新本场排行榜
		var showId = _show.showId;
		if (showId == "0") {
			$('#thistop').html('<div><li class="title"><span class="t1">排名</span> <span class="t2">本场粉丝</span> <span class="t3">贡献值</span> </li></div>');
			return;
		}
		$.getJSON("/index.php/Show/show_getRankByShow/showId/" + showId + "/", {
				random: Math.random()
			},
			function(data) {
				var obj_tmp = $("<div></div>");
				obj_tmp.append('<li class="title"><span class="t1">排名</span><span class="t2">本场粉丝</span><span class="t3">贡献值</span></li>');
				if (data && data.length > 0) {
					_show.local = data[0].userid; //本场皇冠 userid
					for (i = 0; i < data.length; i++) {
						var obj_li = $("<li></li>");
						obj_li.append("<em>" + (i + 1) + "</em>");
						var obj_div_pepole = $('<div class="pepole"></div>');
						obj_div_pepole.append('<div class="img"><a href="/' + data[i].emceeno + '" target="_blank"><img src="' + data[i].icon + '" /></a></div>');
						var obj_div_txt = $('<div class="txt"></div>');
						obj_div_txt.append('<p><span class="cracy cra' + data[i].fanlevel + '"></span></p>');
						obj_div_txt.append('<p><a href="/' + data[i].emceeno + '" title="' + data[i].nickname + '" target="_blank">' + data[i].nickname + '</a></p>');
						obj_div_pepole.append(obj_div_txt);
						obj_li.append(obj_div_pepole);
						obj_li.append('<span class="nums">' + data[i].amount + '</span>');
						obj_tmp.append(obj_li);
						$('#thistop').html(obj_tmp.html());
					}
				}
			});
	},
	checkVideoLive: function() { //client 检测是否在直播
		if (_show.emceeId != _show.userId) { //不是主播
			if (_show.enterChat == 0) { //未进入聊天
				$.getJSON("/show_checkVideoLive_rid=" + _show.emceeId + Sys.ispro + ".htm?t=" + Math.random(), function(json) {
					if (json) {
						var str = "";
						if (json["data"]["showId"] > 0) { //正在直播状态

							JsInterface.beginLive(json["data"]);
						} else { //结束直播状态

							JsInterface.endLive();
						}
					}
				});
			} else {
				clearInterval(Chat.videoTimer);
			}
		}

	}
}

/*送礼物接口  刘俊*/

var GiftCtrl = {
	gift_to_id: '',
	gift_id: '',
	choiceGift: function(giftid, giftName) {


		$(".giftItem").removeClass("giftbg");
		$("#giftbg" + giftid).addClass("giftbg");

		GiftCtrl.gift_id = giftid;
		GiftCtrl.gift_name = giftName;
		$("#giftname").html(GiftCtrl.gift_name);
		$('#gift_model').hide();
		if ($("#giftnum").val() == "") {
			$("#giftnum").val(1);
		}
	},
	setGift: function(user_id, user_nick) {
		GiftCtrl.gift_to_id = user_id;
		$("#giftto").html(Face.de(user_nick));
		$("#playerBox").toggle();
		$("#show_gift_user_list_btn").attr("class", "btn_down");
		Gift_obj.left = $('#gift_name').offset().left;
		Gift_obj.top = $('#gift_name').offset().top;
		if ($('#giftname').html() == '') {
			$('#gift_model').css({
				"left": (Gift_obj.left - 56) + "px",
				"top": ((Gift_obj.top) - 234) + "px"
			}).show();
		}
		$("#choose_btn").attr("className", "btn_up");

	},
	setUser: function(user_id, user_nick) {
		$('#msg_to_all,#playerBox1').hide();
		$('#msg_to_one').show();
		$('#whisper').get(0).disabled = false;
		$('#msg_to_one').find('span').html(Face.de(user_nick));
		$('#to_user_id').val(user_id);
		GiftCtrl.gift_to_id = user_id;
		$('#to_nickname').val(user_nick);
	},
	closeToWho: function() {
		$("#to_user_id").val("");
		$("#to_nickname").val("");
		$('#whisper').get(0).disabled = true;
		$("#whisper").attr("checked", false);

		$("#msg_to_all").show();
		$("#msg_to_one").hide();
		$("#msg").focus();
	},
	giftNum: function(num) {
		var gnum = parseInt(num);
		$("#show_num_btn").attr("class", "btn_down");
		$("#gift-givenum").toggle();
		$("#giftnum").val(gnum);
	},
	giftNumDIY: function() {
		$("#show_num_btn").attr("class", "btn_down");
		$("#gift-givenum").toggle();
		$("#giftnum").val("");
		$("#giftnum").focus();
	},
	realizeWish: function(uid, uname) { //帮他实现愿望
		$(document).scrollTop(200);
		GiftCtrl.gift_to_id = uid;
		$("#giftto").html(uname);
		$("#choose_btn").attr("class", "btn_up");
		Gift_obj.left = $('#gift_name').offset().left;
		Gift_obj.top = $('#gift_name').offset().top;
		$('#gift_model').css({
			"left": (Gift_obj.left - 56) + "px",
			"top": ((Gift_obj.top) - 234) + "px"
		}).show();
	},
	//新增加礼物接口
	kaichang: function(lie, giftName, uid) {
		
	    var msg = "{\"msg\":[{\"_method_\":\"SystemNot\",\"action\":\"97\",\"ct\":{\"carid\":"+lie+"},\"msgtype\":\"1\",\"timestamp\":\""+WlTools.FormatNowDate()+"\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
     	//console.log(msg)
     	Socket.emitData('broadcast',msg);
		//新增礼物接口结束
	},
	sendGift: function() {
		$("#gift_model").hide();
		if (_show.userId < 0) {
			UAC.openUAC(0);
			return false;
		}
		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}
		var giftNum = $.trim($("#giftnum").val());
		var re = /^[\d]+$/;
		if (GiftCtrl.gift_id) {


			//此处判断是库存礼物还是普通礼物

			if (typeof(GiftCtrl.gift_id) == "number") {
				//普通礼物
				if (re.test(giftNum) && parseInt(giftNum) > 0) {
					if (GiftCtrl.gift_to_id) {
						var url = "/index.php/Show/show_sendGift/eid/" + _show.emceeId + "/toid/" + GiftCtrl.gift_to_id + "/count/" + giftNum + "/gid/" + GiftCtrl.gift_id + "/t/" + Math.random();
						var tmpgid = GiftCtrl.gift_id;
						//GiftCtrl.clearGiftCfg();
						$.getJSON(url, function(json) {
							
							if (json) {
								
								if (json.code == 0) {
									
									GiftCtrl.gift_to_id = _show.emceeId;
									$('#giftto').html(_show.emceeNick);
									Chat.getUserBalance(); //用户秀币更新
									
		                            var giftinfo_strjson = "{\"token\":\"" + json.token + "\"}";
								    var msg = "{\"msg\":[{\"_method_\":\"SendGift\",\"action\":\"3\",\"ct\":"+giftinfo_strjson+",\"msgtype\":\"1\",\"timestamp\":\""+WlTools.FormatNowDate()+"\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				             	    Socket.emitData('broadcast',msg);
				             	    
				             	    
								
								}else {
									_alert(json.info, 5);
									GiftCtrl.gift_to_id = _show.emceeId;
									$('#giftto').html(_show.emceeNick);
								}
								
							}
						});
					} else {
						_alert("请选择赠送人！", 3);
						return false;
					}
				} else {
					_alert("数量错误！", 3);
					$("#giftnum").focus();
					return false;
				}
			} else {	
				if (re.test(giftNum) && parseInt(giftNum) > 0) {
					if (GiftCtrl.gift_to_id) {

						var url = "/index.php/Show/get_stock_info/eid/" + _show.emceeId + "/toid/" + GiftCtrl.gift_to_id + "/count/" + giftNum + "/gid/" + GiftCtrl.gift_id + "/t/" + Math.random();
						var tmpgid = GiftCtrl.gift_id;
						//GiftCtrl.clearGiftCfg();
						$.getJSON(url, function(json) { 
							if (json) {     
								if (json.code == 0) {
									GiftCtrl.gift_to_id = _show.emceeId;
									$('#giftto').html(_show.emceeNick);
									Chat.getUserBalance(); //用户秀币更新
									var giftinfo_strjson = "{\"token\":\"" + json.token + "\"}";
								    var msg = "{\"msg\":[{\"_method_\":\"SendGift\",\"action\":\"3\",\"ct\":"+giftinfo_strjson+",\"msgtype\":\"1\",\"timestamp\":\""+WlTools.FormatNowDate()+"\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				             	   
				             	    Socket.emitData('broadcast',msg);
									
								//礼品数量减少
								var newnum = parseInt($("#"+GiftCtrl.gift_id).text()) - giftNum;
								if(newnum == 0)
								{
									$("#"+GiftCtrl.gift_id).parent().parent().remove();

								}
								else
								{
									$("#"+GiftCtrl.gift_id).text(newnum);									
								}

								
								} else {
									_alert(json.info, 5);
									GiftCtrl.gift_to_id = _show.emceeId;
									$('#giftto').html(_show.emceeNick);
								}
							}
						});
					} else {
						_alert("请选择赠送人！", 3);
						return false;
					}
				} else {
					_alert("数量错误！", 3);
					$("#giftnum").focus();
					return false;
				}
			}

		} else {
			_alert("请选择礼物！", 3);
			return false;
		}
	},
	//红包-----------------------
	sendHb: function() {
		if (_show.userId < 0) {
			UAC.openUAC(0);
			return false;
		}

		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}
		if (_show.userId == _show.emceeId) {
			_alert("自己不能给自己送红包！", 3)
			return false;
		}

		var url = "/index.php/Show/show_sendHb/eid/" + _show.emceeId + "/t/" + Math.random();
		$.getJSON(url, function(json) {
			if (json) {
				if (json.code == 0) {
					var msg = "{\"msg\":[{\"_method_\":\"SendHb\",\"action\":\"33\",\"ct\":{\"userNo\":\"" + json.userNo + "\",\"userId\":\"" + json.userId + "\",\"userName\":\"" + json.userName + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"sendHb","userNo":"' + json.userNo + '","userId":"' + json.userId + '","userName":"' + json.userName + '"}');
				} else {
					_alert(json.info, 5);
				}
			}
		});



	},

	clearGiftCfg: function() {
		GiftCtrl.gift_id = 0;
		$("#giftname").html('');
		$("#giftnum").val("");
		$("#giftto").html("");
	},
	clearSofa: function() {
		$('#getseatnum').val('');
	},
	fetch_sofa: function() {
		if (_show.userId < 0) {
			UAC.openUAC(0);
			return false;
		}
		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}
		var sofa_num = $('#getseatnum').val();
		var sof_id = parseInt($('#sofaid').val());
		var expsofa = /^([0-9])+$/;
		if (!expsofa.test(sofa_num) || sofa_num == "") {
			_alert('请输入正确的数量', 3);
			$('#getseatnum').val('');
		} else if (parseInt(sofa_num) <= _show.oldseatnum) {
			_alert('您的沙发不够,请加油!', 3);
			$('#getseatnum').val('');
		} else {
			GiftCtrl.clearSofa();
			var url = "/index.php/Show/show_takeSeat/seatid/" + sof_id + "/count/" + sofa_num + "/emceeId/" + _show.emceeId + Sys.ispro + "/t/" + Math.random();
			$.getJSON(url, function(json) {
				
				
				if (json) {
					if (json.code == 0) {
						Chat.getUserBalance(); //用户秀币更新
						_alert("抢座成功！", 3);
						var msg = "{\"msg\":[{\"_method_\":\"fetch_sofa\",\"action\":\"4\",\"ct\":{\"token\":\"" + json.token + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + _show.roomId + "\",\"touid\":\"" + _show.emceeId + "\",\"touname\":\"" + _show.emceeNick + "\",\"ugood\":\"" + _show.uroom + "\",\"uid\":\"" + _show.userId + "\",\"uname\":\"" + json.userNick + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
						Socket.emitData('broadcast',msg);
					} else {
						_alert(json.info, 3);
					}
					$("#get_sofa").hide();
				}

			});
		}
	},
	giftList: function() { //礼物列表
		var intShow = _show.showId;
		if (intShow > 0) {
			var giftList = new Array();
			$.getJSON("/index.php/Show/show_getgiftList/showID/" + intShow + "/t/" + Math.random(),
				function(json) {
					if (json) {
						$.each(json["giftList"],
							function(i, item) {
								giftList.push('<li>');
								giftList.push('<span>' + item['giftcount'] + '</span>');
								giftList.push('<img src="' + item['giftpath'] + '" width="24" height="24" title="' + item['giftname'] + '">');
								giftList.push('<em>' + item['giftname'] + '</em>')
								giftList.push('<a href="javascript:void(0);" title="' + item["username"] + '">' + (i + 1) + '. ' + item['username'] + '</a>');
								giftList.push('</li>');
							});
					}
					$("#gift_history").html(giftList.join(""));
				});
		}
	}
}

/* 宠物 */
var Pet = {
	skill: function(fn) {
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能对自己操作哦！", 3);
			return false;
		} else {
			$.getJSON("showPet.do?m=skill", {
				func: fn,
				zid: _show.emceeId,
				toid: UserListCtrl.user_id,
				timeout: 5,
				t: Math.random()
			}, function(json) {
				if (json) {
					if (json["code"] != 0) {
						_alert(json["info"], 3);
						return false;
					} else {
						_alert("操作成功！", 3);
					}
				}
			});
		}
	}
}

/*点歌接口 刘俊*/
var Song = {
	intMiddle: '',
	userSureVodSongid: '',
	initVodSong: function() {
		var strSong = "";
		var url = "/index.php/Show/show_listSongs/eid/" + _show.emceeId + "/t/" + Math.random();
		if (_show.emceeId == _show.userId) {
			$.getJSON(url, function(json) {
				Song.displayShowSong(json, 1);
			});
		} else {
			$.getJSON(url, function(json) {
				Song.displayShowSong(json, 2);
			});
		}
	},
	userVodSong: function(page) {
		$('.p-Song').hide();
		page = page || 1;
		$.getJSON("/index.php/Show/show_showSongs/eid/" + _show.emceeId + "/p/" + page + Sys.ispro + "/t/" + Math.random(), function(json) {
			Song.displaySongs(json);
		});
	},
	userAddSong: function() {
		var songName = $.trim($("#songName").val());
		var songSinger = $("#songSinger").val();
		if (songName == '' || songName == '歌曲名(必选)') {
			_alert("请填写歌曲名称！", 10);
			return false;
		}
	},
	batchAddSong: function() {
		$('.p-Song').hide();
		this.intMiddle = getMiddlePos('addSong');
		$('#addSong').css({
			"left": (this.intMiddle.pl) + "px",
			"top": (this.intMiddle.pt) + "px"
		}).show();
	},
	saveBatchSong: function() {
		var url = "/index.php/Show/show_addSongs/eid/" + _show.emceeId;
		var song1 = $("#name_1").val().trim();
		var song2 = $("#name_2").val().trim();
		var song3 = $("#name_3").val().trim();
		var song4 = $("#name_4").val().trim();
		var song5 = $("#name_5").val().trim();
		var song = (song1 == "" ? "" : ("/name_1/" + encodeURIComponent(song1) + "/singer_1/" + encodeURIComponent($("#singer_1").val().trim() == '' ? '未填写' : $("#name_1").val().trim()))) +
			(song2 == "" ? "" : ("/name_2/" + encodeURIComponent(song2) + "/singer_2/" + encodeURIComponent($("#singer_2").val().trim() == '' ? '未填写' : $("#name_1").val().trim()))) +
			(song3 == "" ? "" : ("/name_3/" + encodeURIComponent(song3) + "/singer_3/" + encodeURIComponent($("#singer_3").val().trim() == '' ? '未填写' : $("#name_1").val().trim()))) +
			(song4 == "" ? "" : ("/name_4/" + encodeURIComponent(song4) + "/singer_4/" + encodeURIComponent($("#singer_4").val().trim() == '' ? '未填写' : $("#name_1").val().trim()))) +
			(song5 == "" ? "" : ("/name_5/" + encodeURIComponent(song5) + "/singer_5/" + encodeURIComponent($("#singer_5").val().trim() == '' ? '未填写' : $("#name_1").val().trim())));
		if (song != "") {

			url += song + "/t/" + Math.random();

			$.getJSON(url, function(data) {
				$("#name_1,#name_2,#name_3,#name_4,#name_5,#singer_1,#singer_2,#singer_3,#singer_4,#singer_5").val("");
				$('#addSong').hide();
				Song.displaySongs(data);
			});
		}
	},
	DelSong: function(id) {
		if (!id) {
			_alert("歌曲出错，请刷新再试！", 3);
			return false;
		}
		if (confirm("确定要删除该歌曲！") == false) {
			return false;
		}
		$.getJSON("/index.php/Show/show_delSong/eid/" + _show.emceeId + "/sid/" + id + Sys.ispro + "/t/" + Math.random(), function(json) {
			if (json && json["code"] == 0) {
				$("#songbook_" + id).remove();
				_alert("操作成功!", 3);
			} else {
				_alert("操作失败，请重试！", 5);
				return false;
			}
		});
	},
	wangSong: function(page) {
		if (_show.enterChat == 0) { //没有进入chat
			_alert('连接异常请等待！', 3);
			return false;
		}
		page = page || 1;
		$('.p-Song').hide();
		var songArray = new Array();
		$.getJSON("/index.php/Show/show_showSongs/eid/" + _show.emceeId + "/p/" + page + "/t/" + Math.random(),
			function(json) {
				songArray.push('<tr>');
				songArray.push('<th>日期</th>');
				songArray.push('<th>歌名</th>');
				songArray.push('<th>原唱</th>');
				songArray.push('<th>操作</th>');
				songArray.push('</tr>');
				if (json && json["code"] == 0) {
					if (json["data"]) {
						$.each(json["data"]["songs"],
							function(i, item) {
								songArray.push('<tr id="songbook_' + item['id'] + '">');
								songArray.push('<td class="mt1">' + item['createTime'] + '</td>');
								songArray.push('<td class="mt1"><div class="song_name">' + item['songName'] + '</div></td>');
								songArray.push('<td class="mt1"><div class="song_singer">' + item['singer'] + '</div></td>');
								songArray.push('<td class="mt1"><a href="javascript:void(0);" onclick="Song.vodSongPre(\'' + item.songName + '\',\'' + item.singer + '\',' + item.id + ')">点歌</a></td>');

								songArray.push('</tr>');
							});
					}

					var pages = json.data.page;
					var cur = json.data.cur;
					var cols = 5;
					var str = "";
					if (cur > 1)
						str += "<a href=\"javascript:Song.wangSong(" + (cur - 1) + ");\">上一页</a>";
					else
						str += "<span>上一页</span>";

					var start = cur > 2 ? cur - 2 : 1;
					if (pages - start <= cols && start >= cols) {
						start = pages - (cols - 1);
					}
					if (start > 1)
						str += "<span onclick='javascript:Song.wangSong(1);'>1</span>";
					if (start > 2)
						str += "<em>...</em>";
					var end = pages;
					for (i = start; i < start + cols && i <= pages; i++) {
						end = i;
						if (i == cur)
							str += "<span class=\"cur\">" + i + "</span>";
						else
							str += "<a href=\"javascript:Song.wangSong(" + i + ");\">" + i + "</a>";
					}
					if (pages - 1 > end)
						str += "<em>...</em>";
					if (cur < pages)
						str += "<a href=\"javascript:Song.wangSong(" + (cur + 1) + ");\">下一页</a>";
					else
						str += "<span>下一页</span>";

					$("#page2").html(str);
				}
				$("#song_table2").html(songArray.join(""));
			});
		this.intMiddle = getMiddlePos('song_dialog2');
		$('#song_dialog2').css({
			"left": (this.intMiddle.pl) + "px",
			"top": (this.intMiddle.pt) + "px"
		}).show();
	},
	vodSongPre: function(songName, singer, id) {
		if (!songName) {
			_alert("歌曲出错，请刷新再试！", 3);
			return false;
		}
		$("#songName").val(songName);
		$("#songSinger").val(singer);
		$("#songId").val(id);

		var txt = "点歌需" + _show.songPrice + "个秀币,主播确认后才收取！";

		if (confirm(txt))
			Song.agreeDemand();
		else
			Song.disagreeDemand();
	},
	agreeDemand: function() {
		_closePop();
		Song.vodSong();
	},
	vodSong: function() {
		var songName = $("#songName").val();
		var singer = $("#songSinger").val();
		var songId = $("#songId").val();
		if (songName == "") {
			_alert("请先选择或输入您想要点播的歌曲名！", 3);
			return false;
		}
		songName = encodeURIComponent(songName);
		singer = encodeURIComponent(singer);

		$.getJSON("/index.php/Show/pickSong/songName/" + songName + "/singer/" + singer + "/songId/" + songId + "/emceeId/" + _show.emceeId + "/t/" + Math.random(),
			function(json) {
				if (json && json.code == 0) {
					Song.initVodSong();
					_alert("点歌成功，等待主播同意！", 3);
					var msg = "{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"9\",\"ct\":{\"userNo\":\"" + _show.uroom + "\",\"userId\":\"" + _show.userId + "\",\"userName\":\"" + _show.uname + "\",\"songName\":\"" + songName + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					//Dom.$swfId("flashCallChat")._chatToSocket(2, 2, '{"_method_":"vodSong","songName":"' + songName + '"}');
				} else
					_alert(json.info, 3);
			});
	},
	agreeSong: function(songId) {
		if (!songId) {
			_alert("请先选择歌曲！", 3);
			return;
		}
		$("#song_" + songId).html("同意");
		$.getJSON("/index.php/Show/show_agreeSong/eid/" + _show.emceeId + "/ssid/" + songId + "/t/" + Math.random(), function(json) {
			if (json && json.code == 0) {
				$("#song_" + songId).html("已同意");
				_alert("操作成功！", 3);
				var msg = "{\"msg\":[{\"_method_\":\"AgreeSong\",\"action\":\"10\",\"ct\":{\"userNo\":\"" + json.userNo + "\",\"userId\":\"" + json.userId + "\",\"userName\":\"" + json.userName + "\",\"songName\":\"" + json.songName + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				Socket.emitData('broadcast',msg);
				//Dom.$swfId("flashCallChat")._chatToSocket(2, 2, '{"_method_":"agreeSong","userNo":"' + json.userNo + '","userId":"' + json.userId + '","userName":"' + json.userName + '","songName":"' + json.songName + '"}');
			} else {
				$("#song_" + songId).html("<a onclick=\"Song.agreeSong(" + songId + ")\" href=\"javascript:void(0);\">等待同意</a>");
				_alert(json.info, 3);
			}
		});
	},
	disAgreeSong: function(songId) {
		if (!songId) {
			_alert("请先选择歌曲！", 3);
			return;
		}
		$.getJSON("/index.php/Show/show_disAgreeSong/eid/" + _show.emceeId + "/ssid/" + songId + "/t/" + Math.random(), function(json) {
			if (json && json.code == 0) {
				$("#song_" + songId).html("未同意");
				_alert("操作成功！", 3);
				var msg = "{\"msg\":[{\"_method_\":\"disAgreeSong\",\"action\":\"101\",\"ct\":{\"userNo\":\"" + json.userNo + "\",\"userId\":\"" + json.userId + "\",\"userName\":\"" + json.userName + "\",\"songName\":\"" + json.songName + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				Socket.emitData('broadcast',msg);
			}
		});
	},
	setSongApply: function(a) {
		a = a || 1;
		$.getJSON("/index.php/Show/show_setSongApply/eid/" + _show.emceeId + "/apply/" + a + "/t/" + Math.random(), function(json) {
			if (json.code > 0)
				_alert("操作失败，请稍后重试!", 3);
			var msg = "{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"17\",\"ct\":{\"apply\":\"" + a + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
		    Socket.emitData('broadcast',msg);
			//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"setSongApply","apply":"' + a + '"}');
		});
	},
	disagreeDemand: function() {
		_closePop();
		return false;
	},
	displaySongs: function(json) {
		var songArray = new Array();
		songArray.push('<tr>');
		songArray.push('<th>日期</th>');
		songArray.push('<th>歌名</th>');
		songArray.push('<th>原唱</th>');
		songArray.push('<th>操作</th>');
		songArray.push('</tr>');
		if (json && json["code"] == 0) {
			if (json["data"]) {
				$.each(json["data"]["songs"],
					function(i, item) {
						songArray.push('<tr id="songbook_' + item['id'] + '">');
						songArray.push('<td class="mt1">' + item['createTime'] + '</td>');
						songArray.push('<td class="mt1"><div class="song_name">' + item['songName'] + '</div></td>');
						songArray.push('<td class="mt1"><div class="song_singer">' + item['singer'] + '</div></td>');
						songArray.push('<td class="mt1"><a href="javascript:void(0);" onclick="Song.DelSong(' + item['id'] + ')" style="color:#07834A;">删除</a></td>');
						songArray.push('</li>');
					});
			}
			var pages = json.data.page;
			var cur = json.data.cur;
			var cols = 5;
			var str = "";
			if (cur > 1)
				str += "<a href=\"javascript:Song.userVodSong(" + (cur - 1) + ");\">上一页</a>";
			else
				str += "<span>上一页</span>";

			var start = cur > 2 ? cur - 2 : 1;
			if (pages - start <= cols && start >= cols) {
				start = pages - (cols - 1);
			}
			if (start > 1)
				str += "<span onclick='javascript:Song.userVodSong(1);'>1</span>";
			if (start > 2)
				str += "<em>...</em>";
			var end = pages;
			for (i = start; i < start + cols && i <= pages; i++) {
				end = i;
				if (i == cur)
					str += "<span class=\"cur\">" + i + "</span>";
				else
					str += "<a href=\"javascript:Song.userVodSong(" + i + ");\">" + i + "</a>";
			}
			if (pages - 1 > end)
				str += "<em>...</em>";
			if (cur < pages)
				str += "<a href=\"javascript:Song.userVodSong(" + (cur + 1) + ");\">下一页</a>";
			else
				str += "<span>下一页</span>";

			$("#page").html(str);

		}
		$("#song_table").html(songArray.join(""));
		this.intMiddle = getMiddlePos('song_dialog');
		$('#song_dialog').css({
			"left": (this.intMiddle.pl) + "px",
			"top": (this.intMiddle.pt) + "px"
		}).show();
	},
	displayShowSong: function(json, type) {
		var json = json;
		var userSongArray = new Array();
		if (json && json.code == 0) {
			$.each(json.data.songs, function(i, item) {
				strSong = "";
				if (item['status'] == 0) {
					if (type == 1) {
						strSong = '<cite id="song_' + item['id'] + '"><a href="javascript:Song.agreeSong(' + item['id'] + ');"><img src="/Public/images/right_icon.gif"/></a>  <a href="javascript:Song.disAgreeSong(' + item['id'] + ');"><img src="/Public/images/wrong_icon.gif"/></a></cite>';
					} else {
						strSong = '<cite id="song_' + item['id'] + '">' + item['showStatus'] + '</a></cite>';
					}
				} else if (item['status'] == 1) {
					strSong = '<cite id="song_' + item['id'] + '" style="color: green;">' + item['showStatus'] + '</cite>';
				} else if (item['status'] == 2) {
					strSong = '<cite id="song_' + item['id'] + '" style="color: red;">' + item['showStatus'] + '</cite>';
				}
				userSongArray.push('<li id="everysong_' + item.id + '">');
				userSongArray.push('<span class="t1">' + item.createTime + '</span>');
				userSongArray.push('<span class="t2">' + item.songName + '</span>');
				userSongArray.push('<span class="t3">' + item.userNick + '</span>');
				userSongArray.push('<span class="t4">' + strSong + '</span>');
				userSongArray.push('</li>');
			});

			$("#usersonglist").html(userSongArray.join(""));
		}
	}
}


//
var jumpAnchor = function() {
	var _time = 1000;
	if (arguments.length == 2) _time = arguments[1];
	if ($("." + arguments[0]).length > 0)
		$("html,body").animate({
			scrollTop: $("." + arguments[0]).offset().top
		}, {
			duration: _time,
			queue: false
		});
}

/*特权命令操作*/
var UserListCtrl = {
	user_id: '',
	nickname: '',
	Tid: '',
	level: '', //等级
	goodnum: '',
	sendGift: function() {
		try {
			if (UserListCtrl.user_id) {
				if (!in_array(UserListCtrl.user_id, Chat.arrGiftInfo) && _show.emceeId != UserListCtrl.user_id) { //防止重复 且 不是主播
					Chat.toGiftInfo = "<li><a href=\"javascript:void(0);\" onclick=\"GiftCtrl.setGift('" + UserListCtrl.user_id + "','" + UserListCtrl.nickname + "')\"><span class=\"cracy cra" + UserListCtrl.level + "\"></span>" + UserListCtrl.nickname + "</a></li>";
					Chat.arrGiftInfo.push(UserListCtrl.user_id);
					$('#gift_userlist').append(Chat.toGiftInfo);
					$('#chat_userlist').append(Chat.toGiftInfo.replace('setGift', 'setUser'));
				}
				GiftCtrl.gift_to_id = UserListCtrl.user_id;
				$("#giftto").html(UserListCtrl.nickname);
				$("#choose_btn").attr("class", "btn_up");
				Gift_obj.left = $('#gift_name').offset().left;
				Gift_obj.top = $('#gift_name').offset().top;
				$('#gift_model').css({
					"left": (Gift_obj.left - 56) + "px",
					"top": ((Gift_obj.top) - 234) + "px"
				}).show();
				$("#giftnum").focus();

			} else {
				return false;
			}
		} catch (e) {}
	},
	chatPublic: function() {
		try {
			if (UserListCtrl.user_id) {
				$("#to_user_id").val(UserListCtrl.user_id);
				$("#to_nickname").val(UserListCtrl.nickname);
				$("#to_goodnum").val(UserListCtrl.goodnum);
				$("#msg_to_one").html('<span>' + UserListCtrl.nickname + '</span>');
				$(".msg_to_all").hide();
				$("#msg_to_one").show();
				$("#whisper").attr("disabled", false);
				$("#whisper").attr("checked", false);
				$("#msg").focus();
			} else {
				return false;
			}
		} catch (e) {}
	},
	chatPrivate: function() {
		try {
			if (UserListCtrl.user_id) {
				$("#to_user_id").val(UserListCtrl.user_id);
				$("#to_nickname").val(UserListCtrl.nickname);
				$("#to_goodnum").val(UserListCtrl.goodnum);
				$("#msg_to_one").html('<span>' + UserListCtrl.nickname + '</span>');
				$("#whisper").attr("checked", true);
				$(".msg_to_all").hide();
				$("#msg_to_one").show();
				$("#msg").focus();
			} else {
				return false;
			}
		} catch (e) {}
	}
}

/*命令接口 白少鹏*/
var ChatApp = {
	serverID: "",
	/**
	 * 根据rid uid 取出 管理员列表
	 * @param rid 房间ID,uid 用户ID
	 * @return json
	 */
	GetManagerList: function() {},
	/**
	 * 根据uidlist 踢出指定的多个用户
	 * @param rid 房间ID,uid 用户ID/uidlist 被踢的用户列表 
	 */
	Kick: function() {
		
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能踢自己啊！", 3);
			return false;
		} else {
			$.getJSON("/index.php/Show/kick/", {
				rid: _show.emceeId,
				uidlist: UserListCtrl.user_id,
				roomnum: _show.roomId,
				t: Math.random()
			}, function(json) {
				if (json) {
					if (json["code"] != 0) {
						_alert(json["info"], 3);
						return false;
					} else
					var msg = "{\"msg\":[{\"_method_\":\"KickUser\",\"action\":\"0\",\"ct\":\"你被踢出了本房间\",\"msgtype\":\"4\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + _show.uroom + "\",\"touid\":\"" + UserListCtrl.user_id + "\",\"touname\":\"" + UserListCtrl.nickname + "\",\"ugood\":\"" + _show.roomId + "\",\"uid\":\"" + _show.emceeId + "\",\"uname\":\"" + _show.nickname + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					_alert("操作成功！", 3);
				}
			});
		}
	},
	/**
	 * 根据uidlist 将指定的多个用户禁言
	 * @param rid 房间ID,uid 用户ID/uidlist 被禁言的用户列表  timeout(禁言时间) 
	 */
	ShutUp: function() {
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能给自己禁言！", 3);
			return false;
		} else {
			$.getJSON("/index.php/Show/shutup/", {
				rid: _show.emceeId,
				uidlist: UserListCtrl.user_id,
				timeout: 5,
				roomnum:_show.roomId,
				t: Math.random()
			}, function(json) {
				
				if (json) {
					if (json["code"] != 0) {
						_alert(json["info"], 3);
						return false;
					} else
					
					var msg = "{\"msg\":[{\"_method_\":\"ShutUpUser\",\"action\":\"1\",\"ct\":\"\",\"msgtype\":\"4\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + _show.uroom + "\",\"touid\":\"" + UserListCtrl.user_id + "\",\"touname\":\"" + UserListCtrl.nickname + "\",\"ugood\":\"" + _show.roomId + "\",\"uid\":\"" + _show.userId + "\",\"uname\":\"" + _show.nickname + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					_alert("操作成功！", 3);
				}
			});
		}
	},
	/**
	 * 根据uidlist 将指定的多个用户恢复发言
	 * @param rid 房间ID,uid 用户ID/uidlist 被恢复发言的用户列表
	 */
	Resume: function() {
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能恢复自己的发言！", 3);
			return false;
		} else {
			
			$.getJSON("/index.php/Show/resume/",{
					roomnum:_show.roomId,
					uid:UserListCtrl.user_id,
					t:Math.random()
				},function(json){
					
					if(json){
						if(json["code"]!=0){
							_alert(json["info"],3);
							return false;
						}else{
							var msg = "{\"msg\":[{\"_method_\":\"ResumeUser\",\"action\":\"2\",\"ct\":\"\",\"msgtype\":\"4\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"" + _show.uroom + "\",\"touid\":\"" + UserListCtrl.user_id + "\",\"touname\":\"" + UserListCtrl.nickname + "\",\"ugood\":\"" + _show.roomId + "\",\"uid\":\"" + _show.nickname + "\",\"uname\":\"" + _show.emceeNick + "\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
							Socket.emitData('broadcast',msg);
							_alert("操作成功！", 3);
						}
					}
				}
			);

			
		}
	},
	setManager: function() { //设为管理员
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能对自己操作！", 3);
			return false;
		} else {
			$.getJSON("/index.php/Show/toggleShowAdmin/", {
				eid: _show.emceeId,
				state: 1,
				userid: UserListCtrl.user_id,
				t: Math.random()
			}, function(json) {
				if (json) {
					if (json["code"] == 0) {

						Socket.emitData("broadcast","{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"13\",\"ct\":\"%7b%22message%22%3a%22%3ca href%3d%5c%22javascript%3avoid%280%29%3b%5c%22 id%3d%5c%22" + _show.userId + "%5c%22 gn%3d%5c%22" + _show.goodNum + "%5c%22 class%3d%5c%22chatuser%5c%22%3e" + _show.nickname + "%3c%5c%2fa%3e %e6%8a%8a %3ca href%3d%5c%22javascript%3avoid%280%29%3b%5c%22 id%3d%5c%22" + UserListCtrl.user_id + "%5c%22 gn%3d%5c%22" + UserListCtrl.user_id + "%5c%22 class%3d%5c%22chatuser%5c%22%3e" + UserListCtrl.nickname + " %3c%5c%2fa%3e%e8%ae%be%e4%b8%ba%e6%88%bf%e9%97%b4%e7%ae%a1%e7%90%86%e5%91%98%22%2c%22userNick%22%3a%22" + UserListCtrl.nickname + "%22%2c%22userId%22%3a" + UserListCtrl.user_id + "%7d\",\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}");
						//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"setManager","tougood":"' + UserListCtrl.goodnum + '","touid":"' + UserListCtrl.user_id + '","touname":"' + UserListCtrl.nickname + '"}');
						_alert('操作成功！', 3);
					} else {
						_alert(json["info"], 3);
					}
				}
			});
		}
	},
	setBlack: function() { //黑名单操作
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能对自己操作！", 3);
			return false;
		} else {
			$.getJSON("bl.do", {
				eid: _show.emceeId,
				m: "setBlack",
				userid: UserListCtrl.user_id,
				t: Math.random()
			}, function(json) {
				if (json) {
					if (json.code == 0) {
						_alert(json.info, 3);
					} else {
						_alert(json.info, 3);
					}
				}
			});
		}
	},
	delManager: function() { //删除管理员
		if (UserListCtrl.user_id == _show.userId) {
			_alert("不能对自己操作！", 3);
			return false;
		} else {
			$.getJSON("/index.php/Show/toggleShowAdmin/", {
					eid: _show.emceeId,
					state: 0,
					userid: UserListCtrl.user_id,
					t: Math.random()
				},
				function(json) {
					
					if (json) {
						if (json["code"] == 0) {
							Socket.emitData("broadcast", "{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"14\",\"ct\":\"%7B%22message%22%3A%22%3Ca href%3D%5C%22javascript%3Avoid%280%29%3B%5C%22 id%3D%5C%22" + _show.userId + "%5C%22 gn%3D%5C%22" + _show.goodNum + "%5C%22 class%3D%5C%22chatuser%5C%22%3E" + _show.nickname + "%3C%5C%2Fa%3E %E5%8F%96%E6%B6%88%E4%BA%86 %3Ca href%3d%5c%22javascript%3avoid%280%29%3b%5c%22 id%3d%5c%22" + UserListCtrl.user_id + "%5c%22 gn%3d%5c%22" + UserListCtrl.user_id+ "%5c%22 class%3D%5C%22chatuser%5C%22%3E" + UserListCtrl.nickname + " %3C%5C%2Fa%3E%E7%9A%84%E7%AE%A1%E7%90%86%E5%91%98%E8%B5%84%E6%A0%BC%22%2C%22userNick%22%3A%22" + UserListCtrl.nickname + "%22%2C%22userId%22%3A" + UserListCtrl.user_id + "%7D\",\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}");
							//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"delManager","tougood":"' + UserListCtrl.goodnum + '","touid":"' + UserListCtrl.user_id + '","touname":"' + UserListCtrl.nickname + '"}');
							_alert('操作成功！', 3);
						} else {
							_alert(json["info"], 3);
						}
					}
				});
		}
	}
}

/**
 * 主播Menu SetTing
 */
var playerMenu = {
	bulletin: function(t) {

		var ot = "#b" + t + "t";
		var ou = "#b" + t + "u";
		var text = $("#b" + t + "t").val();
		var link = $("#b" + t + "u").val();

		if (text.length > 40 || text.trim() == "" || text.trim() == "请输入文字,不超过40个...") {
			_alert("请输入文字,不超过40个...", 5);
			return;
		}
		if (link == "请输入链接地址")
			link = "";

		$.post("/index.php/User/setBulletin/", {

			m: "setBulletin",
			eid: _show.emceeId,
			bt: t,
			t: text,
			u: link,
			r: Math.random()
		}, function(data) {
			if (data.code == 0) {
				$(ot).val("");
				$(ou).val("");
				_alert("操作成功！", 3);
				$("#notice-modle").hide();
				var msg = "{\"msg\":[{\"_method_\":\"SetBulletin\",\"action\":\"6\",\"ct\":{\"text\":\"" + text + "\",\"link\":\"" + link + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				Socket.emitData('broadcast',msg);
				//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"setBulletin","bt":"' + t + '","t":"' +  text.replace("\n","<br>") + '","u":"' + link + '"}');
			} else
				_alert(data.info, 5);
		}, "json");

		playerMenu.bulletio(2);
	},
	bulletio: function(t) {
		var ot = "#b" + t + "t";
		var ou = "#b" + t + "u";
		var text = $("#b" + t + "t").val();
		var link = $("#b" + t + "u").val();

		if (text.length > 40 || text.trim() == "" || text.trim() == "请输入文字,不超过40个...") {
			_alert("请输入文字,不超过40个...", 5);
			return;
		}
		if (link == "请输入链接地址")
			link = "";

		$.post("/index.php/User/setBulletin/", {

			m: "setBulletin",
			eid: _show.emceeId,
			bt: t,
			t: text,
			u: link,
			r: Math.random()
		}, function(data) {
			if (data.code == 0) {
				$(ot).val("");
				$(ou).val("");
				_alert("操作成功！", 3);
				$("#notice-modle").hide();
				var msg = "{\"msg\":[{\"_method_\":\"SetBulletin\",\"action\":\"5\",\"ct\":{\"text\":\"" + text + "\",\"link\":\"" + link + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
				Socket.emitData('broadcast',msg);
				//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"setBulletin","bt":"' + t + '","t":"' +  text.replace("\n","<br>") + '","u":"' + link + '"}');
			} else
				_alert(data.info, 5);
		}, "json");

	},



	offVideo: function(s) {
		if (s == 1) {
			var addr = $("#video").val().trim();
			if (addr == "" || addr == "请输入离线地址...") {
				_alert("请输入离线地址...", 5);
				return;
			}
			var url = "/index.php/User/setOfflineVideo/?&url=" + encodeURIComponent(addr) + "&eid=" + _show.emceeId + "&t=" + Math.random();
		} else {
			var url = "/index.php/User/cancelOfflineVideo/eid/" + _show.emceeId + "/t/" + Math.random();
		}
		$.getJSON(url, function(data) {
			if (data && data.code == 0) {
				$("#video").val("");
				$('.pop-play').hide();
				_alert("操作成功！", 5);
			} else
				_alert(data.info, 5);
		});
	},
	setBackground: function(t) {
		if (t == 1) {
			var file = $("#bg3").val().toLowerCase();
			if (file != "") {
				if (file.indexOf(".jpg") == -1) {
					_alert("图片须为jpg格式文件！", 5);
					return;
				}
			} else {
				_alert("请选择背景图片！", 5);
				return;
			}
			var f = Dom.$getid("frm");
			f.action = "/index.php/User/setBackground/eid/" + _show.emceeId;
			f.target = "frmFile";
			f.submit();
		}
		if (t == 0) {
			var url = "/index.php/User/cancelBackground/eid/" + _show.emceeId + "/t/" + Math.random();
			$.getJSON(url, function(data) {
				if (data && data.code == 0) {
					$("body").removeAttr("style");
					var file = $("#bg3");
					file.after(file.clone().val(""));
					file.remove();
					_alert("操作成功！", 3);
					var msg = "{\"msg\":[{\"_method_\":\"cancelBackground\",\"action\":\"8\",\"ct\":\"\",\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
					Socket.emitData('broadcast',msg);
					//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"cancelBackground"}');
				} else
					_alert("操作失败，请重试！", 5);
			});
		}
		if (t == 2) {
			document.body.style.backgroundImage = "url('../Public/images/showbackground.jpg')";
		}
		if (t.indexOf('Public/')>=0) {
	
			var url = "/index.php/User/changeBackground/?eid=" + _show.emceeId + "&bgimg="+t+"&t=" + Math.random();
			$.getJSON(url, function(data) {
				if (data && data.code == 0) {

					_alert("操作成功！", 3);
					
					document.body.style.backgroundImage = "url('"+t+"')";
				} else
					_alert("操作失败，请重试！", 5);
			});			
			
		}

	},
	setBackground2: function(bg) {
		var msg = "{\"msg\":[{\"_method_\":\"SetBackground\",\"action\":\"7\",\"ct\":{\"image\":\"" + bg + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
		Socket.emitData('broadcast',msg);
		//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"setBackground","bg":"' + bg + '"}');
	},
	enter: function() {
		var url = "/index.php/Show/enterspeshow/eid/" + _show.emceeId + "/type/" + _show.deny;
		if (_show.deny == 2)
			url += "/password/" + $("#room_pwd").val();
		url += "/t/" + Math.random();
		$.getJSON(url, function(json) {
			if (json) {
				if (json.code == 0) {
					window.location.reload();
				} else {
					_alert(json.info, 5);
				}
			}
		});
	},
	sel: function(i) {
		$("#bg1").removeClass();
		$("#bg2").removeClass();
		$("#bg" + i).addClass("on");
		var file = $("#bg3");
		file.after(file.clone().val(""));
		file.remove();
		$("#bgh").val(i);
	},
	moveroom: function() {
		var moveurl = $('#roomurl').val();
		var rexp = /^http:\/\/www.dome.fmscms.com\/[0-9]{1,12}$/;
		var rexp1 = /^http:\/\/www.dome.fmscms.com\/f\/[0-9]{1,12}$/;
		var rexp2 = /^http:\/\/dome.fmscms.com\/[0-9]{1,12}$/;
		var msg = "{\"msg\":[{\"_method_\":\"MoveRoom\",\"action\":\"19\",\"ct\":{\"url\":\"" + moveurl + "\"},\"msgtype\":\"1\",\"timestamp\":\"" + WlTools.FormatNowDate() + "\",\"tougood\":\"\",\"touid\":\"0\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"0\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\"}";
		Socket.emitData('broadcast',msg);
		//Dom.$swfId("flashCallChat")._chatToSocket(0, 2, '{"_method_":"moveroom","url":"' + moveurl + '"}');
		/*if(moveurl!="" && (rexp.test(moveurl) || rexp1.test(moveurl) || rexp2.test(moveurl))){
			
			var urlhttp="show.do?m=shiftRoom&rid="+_show.emceeId+"&url="+encodeURIComponent(moveurl)+"&t="+Math.random();
			$.getJSON(urlhttp,function(json){
				if(json){
					if(json.code!=0){
						_alert(json.info,"5");
				 	}
				 	else{
				 		_alert("操作成功！","5");
				 	}	
				}   
			});
			
			
		}else{
			_alert("请输入正确的房间地址！",5);
		}*/
	}


}