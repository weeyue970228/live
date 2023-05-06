/*客户端socket.io接收与发送*/
//连接socket服务器

var socket = new io("192.168.1.52:19967");


var users = null;
var userinfo = null;
//连接状态设置为成功
_show.enterChat = 0;
////////////////////////////////////////////////////////////////////////////////////
_userInfo = null;
var Socket = {
    nodejsInit: function () {
        this.inituser();
    },
    inituser: function () {
        /*用户init*/
        // _userBadge, _familyname, _goodnum, _h, _userlevel, _richlevel, _spendcoin, _sellm, _sortnum, _userType, _userid, _username, _vip, _root.roomId
        $.ajax({
        	type:"get",
        	url:"/index.php/Show/getUserDetailInfo",
        	async:true,
        	success:function(json){
        		var data = evalJSON(json);
        		_userInfo =  {
		            uid: _show.userId,
		            roomnum: _show.roomId,
		            nickname: _show.nickname,
		            equipment: 'pc',
		            userBadge    :'',
		            goodnum      :data.goodnum,
		            h            :data.h,
		            level        :data.level,
		            richlevel    :data.richlevel,
		            spendcoin    :data.spendcoin,
		            sellm        :data.sellm,
		            sortnum      :data.sortnum,
		            username     :data.username,
		            vip          :data.vip,
		            familyname   :'',
		            userType     :data.userType
		        };
        	}
        });

        socket.emit('conn', {uid: _show.userId,roomnum: _show.roomId,nickname: _show.nickname,equipment: 'pc',token: _show.token});
        setInterval(function () {
            if (_show.enterChat != 1) {
                $("#chat_hall").append("<font color='red'>正在连接服务器......</font><br>");
            }
        }, 2000);


    },
    //==========node改====================emitData===========================================
    emitData: function (event, msg) {
        socket.emit(event, msg);
    }
    //==========node改====================emitData===========================================
}


/*客户端广播接收broadcasting*/

socket.on('broadcastingListen', function (data) {

    JsInterface.chatFromSocket(data);
});
//==========node改====================conn===========================================
socket.on('conn', function (data) {
    _show.enterChat = 1;
    $.ajax({
        type: "get",
        url: "/index.php/Show/getRoomList?roomnum=" + _show.roomId,
        success: function (data) {
            JsInterface.chatFromSocket(data)
        }

    });
});
//==========node改====================conn===========================================
