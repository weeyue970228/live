<?php
class ShowAction extends BaseAction {
	const SENDGIFT = 1;
	const SENDSOFA = 2;
	const SENDFLY  = 3;
	public function index() 
	{
		
		C('HTML_CACHE_ON', false);
		
		//幸运礼物提示
		$luckgift=M('luckgift')->field("is_open,giftsort")->where("id=1")->select();
		$luckgift[0]['sortname']=M('giftsort')->where("id=".$luckgift[0]['giftsort'])->getField("sortname");
		$this->assign("luckgift",$luckgift[0]);
		
		$User = D("Member");

		if ($_GET["roomnum"] == '') {
			redirect('/index.php');
			//$this->assign('jumpUrl',__APP__);
			//$this->error('缺少参数或参数不正确');
		} else {
			$userinfo = $User -> where('curroomnum=' . $_GET["roomnum"] . '') -> select();
			//获取主播等级信息
			if ($userinfo) 
			{
				if ($userinfo[0]["canlive"] != 'y') {
					$this -> error('该房间无直播权限，请联系管理员。');
					
				}

				$emceelevel = getEmceelevel($userinfo[0]['earnbean']);
				$nextemceelevel = D("Emceelevel") -> where('levelid>' . $emceelevel[0]['levelid']) -> field('levelid,levelname,earnbean_low') -> order('levelid asc') -> select();
				$richlevel = getRichlevel($userinfo[0]['spendcoin']);
				$nextrichlevel = D("Richlevel") -> where('levelid>' . $richlevel[0]['levelid']) -> field('levelid,levelname,spendcoin_low') -> order('levelid asc') -> select();
				//echo $userinfo[0]['earnbean']/$nextemceelevel[0]['earnbean_low']*100;exit;
				$this -> assign("nextlevelcoin", $nextemceelevel[0]['earnbean_low'] - $userinfo[0]['earnbean']);
				$this -> assign("nextrichlevel", $nextrichlevel);
				$this -> assign("richlevel", $richlevel);
				$this -> assign("nextemceelevel", $nextemceelevel);

				$this -> assign("emceelevel", $emceelevel);
			} 
			else 
			{
				$this -> assign('jumpUrl', __APP__);
				$this -> error('该房间不存在');
			}

			$guard_count = D('guard') -> where("anchorid = {$userinfo[0]['id']}") -> count();
			//查询已守护
			$guard_surplus = 20 - $guard_count;

			$this -> assign("guard_count", $guard_count);
			$this -> assign("guard_surplus", $guard_surplus);

			$config = M('siteconfig') -> where('id=1') -> find();
			
			$chatip=$config['chatserver'];
			$this->assign("chatip",$chatip);
			/*$userinfo[0]['zddk']   = !empty($userinfo[0]['zddk'])?$userinfo[0]['zddk']:$config['zddk'];
			$userinfo[0]['fps']    = !empty($userinfo[0]['zddk'])?$userinfo[0]['fps']:$config['fps'];
			$userinfo[0]['zjg']    = !empty($userinfo[0]['zddk'])?$userinfo[0]['zjg']:$config['zjg'];
			$userinfo[0]['pz']     = !empty($userinfo[0]['zddk'])?$userinfo[0]['pz']:$config['pz'];
			$userinfo[0]['width']  = !empty($userinfo[0]['zddk'])?$userinfo[0]['width']:$config['width'];
			$userinfo[0]['height'] = !empty($userinfo[0]['zddk'])?$userinfo[0]['height']:$config['height'];
			$userinfo[0]['cdn']    = !empty($userinfo[0]['zddk'])?$userinfo[0]['cdn']:$config['cdn'];
			$userinfo[0]['cdnl']   = !empty($userinfo[0]['zddk'])?$userinfo[0]['cdnl']:$config['cdnl'];*/
			$userinfo[0]['zddk']   = $config['zddk'];
			$userinfo[0]['fps']    = $config['fps'];
			$userinfo[0]['zjg']    = $config['zjg'];
			$userinfo[0]['pz']     = $config['pz'];
			$userinfo[0]['width']  = $config['width'];
			$userinfo[0]['height'] = $config['height'];
			$userinfo[0]['cdn']    = $config['cdn'];
			$userinfo[0]['cdnl']   = $config['cdnl'];

			if ($userinfo) {
				//if($userinfo[0]['broadcasting'] == 'y' && $_SESSION['roomnum'] == $_GET['roomnum']){
				//$this->assign('jumpUrl',__APP__);
				//$this->error('您当前正在直播，不要重复打开自己的房间');
				//}
				$this -> assign('userinfo', $userinfo[0]);

			} else {

				$numinfo = D("Roomnum") -> where('num=' . $_GET["roomnum"] . '')->field('id') -> select();
				if ($numinfo) {
					$userinfo = $User -> find($numinfo[0]['uid']);
					redirect('/' . $userinfo['curroomnum']);

					//Header("Location: '/{$userinfo['curroomnum']}'");
				} else {
					$this -> assign('jumpUrl', __APP__);
					$this -> error('该房间不存在');
				}
			}
		}

		//更新会员访问记录
		$seen=M("seen")->where("uid='$_SESSION[uid]' and touid='{$userinfo[0][id]}'")->find();
		if(!$seen){		
			M("seen")->add(array("uid"=>$_SESSION['uid'], "touid"=>$userinfo[0]['id'], "addtime"=>time() ));
		}
		//过滤词
		$shieldWord = $config['shieldWord'];
		$this -> assign("shieldWord", $shieldWord);
		//房间排行
		$paihang_cachetime=60;
		
		$rixing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid where c.touid = {$userinfo[0]['id']} and date_format(FROM_UNIXTIME(c.addtime),'%m-%d-%Y')=date_format(now(),'%m-%d-%Y') group by c.uid order by gongxian DESC limit 6";
		$rixing=set_memcache("rixing",$rixing,$paihang_cachetime);
		$this -> assign("rixing", $rixing);
		

		$zhouxing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid where c.touid = {$userinfo[0]['id']} and date_format(FROM_UNIXTIME(c.addtime),'%Y')=date_format(now(),'%Y') and date_format(FROM_UNIXTIME(c.addtime),'%u')=date_format(now(),'%u') group by c.uid  order by gongxian DESC limit 6";
		$zhouxing=set_memcache("zhouxing",$zhouxing,$paihang_cachetime);
		$this -> assign("zhouxing", $zhouxing);

		$zongxing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid   and c.touid = {$userinfo[0]['id']} group by c.uid order by gongxian DESC limit 6";
		$zongxing=set_memcache("zongxing",$zongxing,$paihang_cachetime);
		$this -> assign("zongxing", $zongxing);
		
		
		/*
		 *作者：zhl lw.zhl@qq.com 
		 *修改时间：2016-03-27 09:29:28
		 *修改内容：主播休息时推荐三个在线主播，由FLASH加载修改为div
		 */
		//获取当前主播的直播状态
		$is_live=$User->where("curroomnum=$_GET[roomnum]")->getField("broadcasting");
		 if($_SESSION['uid'] ==$userinfo[0]['id']){
        	$is_zhubo=1;
        }else{
        	$is_zhubo=0;
        }
        $this->assign("is_zhubo",$is_zhubo);
		$this->assign("is_live",$is_live);
		//获取随机三个在线主播			
		$zhuboTuijian=$User->cache(true,20,'Redis') ->where("broadcasting='y' and snap!='' and sign='y' and canlive='y'")->field("id,nickname,curroomnum,earnbean,snap,starttime,online,virtualguest")->order("rand()")->limit(3)->select();
		$this->assign("zhubotuijian",$zhuboTuijian);

		
		
		
		//礼物 列表
		$liwulist = M() -> query("select * from ss_coindetail c  join ss_member m ON m.id = c.uid where c.action = 'sendgift'  order by c.addtime DESC limit 4");

		foreach ($liwulist as $key => $value) {
			$touser = M("member") -> where("id = {$liwulist[$key]['touid']} ") -> find();
			$giftname = M("gift") -> where("id = $liwulist[$key]['giftid']") -> find();

		}

		$this -> assign("liwulist", $liwulist);
		
		//头条信息
		
		$headlines_money=$this->site['headlines_money'];
		
	    $headlines=M("coindetail")->where("coin >= '$headlines_money' and action='sendgift'")->order("addtime desc")->limit("1")->select();	
	
		if($headlines){			
			//赠送人信息			
			$uidinfo=M("member")->field("nickname,spendcoin,earnbean")->find($headlines[0]['uid']);
			$richlevel = getRichlevel($uidinfo['spendcoin']);
			$uidinfo['richlecel']=$richlevel;			
			
			//受赠人信息
			$touidinfo=M("member")->field("nickname,spendcoin,earnbean,curroomnum")->find($headlines[0]['touid']);
			$emceelevel = getEmceelevel($touidinfo['earnbean']);
			$touidinfo['emceelevel']=$emceelevel;
			//礼物信息
			
			$giftinfo=M("gift")->field("giftname")->find($headlines[0]['giftid']);
			
			
			$headlines[0]['uidinfo']=$uidinfo;
			$headlines[0]['touidinfo']=$touidinfo;
			$headlines[0]['giftinfo']=$giftinfo;
			
			cookie('showheadlines',json_encode($headlines[0]),60*60*24);	
		}

		
		$this->assign("headlines",$headlines[0]);		
		
		//头条信息
		
		//系统背景图
		$showbg=M("showbg")->field("picpath")->order("orderno asc")->select();
		$this -> assign("showbg", $showbg);
		//系统背景图
		//头条
		$headlines_time=$this->site['headlines_time'];
		$headlines_money=$this->site['headlines_money'];
		
		$headlinesmoney=floor($headlines_money/10000);
		
		$this->assign("headlines_time",$headlines_time);
		$this->assign("headlinesmoney",$headlinesmoney);
		//qq客服、app下载地址、app图标
		$appurl = M("siteconfig") -> getField("appurl");
		$this -> assign("appurl", $appurl);
		$qq = M("siteconfig") -> getField("qq");
		$this -> assign("qq", $qq);
		$apppic = M("siteconfig") -> getField("apppic");
		$this -> assign("apppic", $apppic);

		//守护

		$condition['anchorid'] = $userinfo[0]['id'];
		$condition['maturitytime'] = array('gt', time());
		$guard = D('guard') -> where($condition) -> select();

		foreach ($guard as $key => $value) {
			$WholeGuard[] = M('member') -> where('id=' . $value['userid']) -> field('bigpic,nickname,ucuid') -> find();
		}
		$this -> assign('guard', $WholeGuard);

		//用户许愿
		$userwishs = D("Wish") -> where('uid=' . $userinfo[0]['id'] . ' and date_format(FROM_UNIXTIME(wishtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y")') -> order('id asc') -> select();
		if ($userwishs) {
			$this -> assign('userwishs', $userwishs[0]);
		}

		//关注
		$uid = $_SESSION['uid'];
		$attuid = $userinfo[0]['id'];
		$data = M("attention") -> where("uid = $uid and attuid=$attuid") -> find();
		$attr_state;
		if ($data == null) {
			$ttr_state = 0;
		} else {
			$attr_state = 1;
		}
		
		$gifts_cachetime=60;
		
		//礼物
		$gifts = D('Giftsort')->cache(true,$gifts_cachetime,'Redis') -> query('select * from ss_giftsort order by orderno asc');
		foreach ($gifts as $n => $val) {
			$gifts[$n]['voo'] = D("Gift")->cache(true,$gifts_cachetime,'Redis') -> where('sid=' . $val['id']) -> order('needcoin') -> select();
		}

		$giftData = D("Gift") ->cache(true,$gifts_cachetime,'Redis')-> where() -> field('showTime,id') -> select();
		$giftShowTime = array();
		foreach ($giftData as $key => $value) {
			$giftShowTime[$value["id"]] = $value["showTime"];
		}

		$this -> assign('giftShowTime', json_encode($giftShowTime));
		$this -> assign('attr_state', $attr_state);
		$this -> assign('gifts', $gifts);
		//游戏
		$hitegg = D("Eggset")-> where('id=1')->find();
		$this -> assign('hitegg', $hitegg);
		$siteconfig= D('Siteconfig')->where("id = 1")->find();
		$this -> assign('siteconfig', $siteconfig);
		//此为给库存礼物的编号

		$StockID = D('Giftsort') -> count();
		$StockID += 1;
		$this -> assign('StockID', $StockID);

		//库存礼物

		$Stock = D('prize p') -> where('p.uid = ' . $_SESSION['uid'].' and p.number !=0 ') -> join("ss_turntable t ON t.id = p.prizeID") -> select();

		$this -> assign('Stock', $Stock);
		
		//获取用户主播剩余摇奖次数
		$turntable_recharge_number = M('member m')->where("m.id = $_SESSION[uid]")->join("ss_turntable_recharge_number t on m.id = t.uid")->getField("t.number");
		if($turntable_recharge_number == NULL) $turntable_recharge_number = 0;
		$this->assign('turntable_recharge_number',$turntable_recharge_number);
		//加载座驾
		$carList = M('car') -> select();
		$carJson;
		foreach ($carList as $key => $value) {
			$carJson[$value['id']] = $value;
		}
		$this -> assign('carList', json_encode($carJson));
		
		
		//获取观众人数
		$att_count = M("attention") -> where("attuid = {$userinfo[0][id]}") -> count();
		$this -> assign("att_count", $att_count);

       //游戏是否开启
      
	    $zadankaiqi = D("Close") -> where('id=1' )->find();
	    $liwukaiqi = D("Close") -> where('id=3' )->find();
	    $jinbikaiqi = D("Close") -> where('id=2' )->find();
	    $this -> assign('zadankaiqi', $zadankaiqi);
	    $this -> assign('liwukaiqi', $liwukaiqi);
	    $this -> assign('jinbikaiqi', $jinbikaiqi);
		//贴条
		$tietiaos = D('Tietiao') -> query('select * from ss_tietiao order by id asc');
		$this -> assign('tietiaos', $tietiaos);

		//过去30天粉丝排行
		$monthfansrank = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and action="sendgift" and touid=' . $userinfo[0]['id'] . ' and addtime>' . (time() - 2592000) . ' group by uid order by total desc LIMIT 5');
		foreach ($monthfansrank as $n => $val) {
			$monthfansrank[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$this -> assign('monthfansrank', $monthfansrank);
		
		//超级粉丝排行
		$superfansrank = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and action="sendgift" and touid=' . $userinfo[0]['id'] . ' group by uid order by total desc LIMIT 5');
		foreach ($superfansrank as $n => $val) {
			$superfansrank[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$this -> assign('superfansrank', $superfansrank);
//==========node改====================获取当前用户信息生成token===========================================
		$redis = connectionRedis();
		$userinfo2 = $User -> where('id=' . $_SESSION['uid']) -> select();
		
		if(!isset($_SESSION['uid'])){
			$_SESSION['uid'] = -(rand(1000,9999));
		}
		if(isset($_SESSION['token'])){
        	$redis -> del($_SESSION['token']);
        }
        $rand = time() . $_SESSION['uid'];
		$userinfo2[0]['token'] = md5($rand);
        $_SESSION['token'] = $userinfo2[0]['token'];
		$_SESSION['sign']  = $rand;
		//40:管理,50:主播,30:普通
		if($_SESSION['uid'] < 0){
			$user_str = $this -> formatUserInfo($userinfo);
		}else{
			$myshowadmin = D("Roomadmin")->where('uid='.$userinfo[0]['id'].' and adminuid='.$_SESSION['uid'])->order('id asc')->select();
			if($_SESSION['uid'] == $userinfo[0]['id']){
				$userType = "50";
			}else if($userinfo2[0]['showadmin'] == '1' || $myshowadmin){
				$userType = "40";
			}else{
				$userType = "30";
			}
			$user_str = $this -> formatUserInfo($userinfo,$userinfo2[0],$userType);
		}
		$redis -> set($userinfo2[0]['token'],$user_str);
		
		
		
//============================================================================================

		$this  -> assign('userinfo2', $userinfo2[0]);
		$canlive = $userinfo[0]['canlive'];
		$this -> assign("canlive", $canlive);
		$this -> assign('userinfo2', $userinfo2[0]);
		$ip = get_client_ip();
		import('ORG.Net.IpLocation');
		$ipclass = new IpLocation('UTFWry.dat');
		$area = $ipclass -> getlocation($ip);
		$address = mb_substr($area['country'], 0, 6);
		$this -> assign('address', $address);
		//fmsport
		// $siteConfig = D("siteconfig")->where("id = 1")->find();
		//
		$fmsPort = $userinfo[0]['fmsPort'];
		$this -> assign("fmsPort", $fmsPort);

		if ($userinfo[0]['size'] == 1) {

			$this -> display('gmeindex');
		} elseif ($userinfo[0]['size'] == 2) {
			$this -> display('testindex');
		} else {


			$this -> display();
		}
    
	}
//==========node改====================获取用户列表===========================================
	//获取用户列表
	public function getRoomList(){
		C('HTML_CACHE_ON',false);
		
		$redis = connectionRedis();
		
		$roominfo       = D("Member")->where('curroomnum='.$_GET["roomnum"].'')->select();
		$roomrichlevel  = getRichlevel($roominfo[0]['spendcoin']);
		$roomemceelevel = getEmceelevel($roominfo[0]['earnbean']);
		$virtualusers_str = '';
		if((int)$roominfo[0]['virtualguest'] > 0 ){
			$virtualusers = D('Member')->where('isvirtual="y"')->limit($roominfo[0]['virtualguest'])->order('rand()')->select();
			
			foreach($virtualusers as $val){
				$richlevel = getRichlevel($val['spendcoin']);
			    $emceelevel = getEmceelevel($val['earnbean']);
				
				if ((int)$val['vip'] == 1)
                {
                    $sortnum = 900000000;
                }
                else if ((int)$val['vip'] == 1)
                {
                    $sortnum = 800000000;
                }
                else if ((int)$val['vip'] > 10)
                {
                    $sortnum = 810000000;
                }
                else
                {
                    $sortnum = 300000000;
                } 
				$virtualusers_str .= "{\"actBadge\":\"\",\"familyname\":\"\",\"goodnum\":\"{$val['curroomnum']}\",\"h\":\"{$val['ucuid']}\",\"level\":\"{$emceelevel[0]['levelid']}\",\"richlevel\":\"{$richlevel[0]['levelid']}\",\"spendcoin\":\"{$val['spendcoin']}\",\"sellm\":\"{$val['sellm']}\",\"sortnum\":\"{$sortnum}\",\"ucuid\":\"{$val['ucuid']}\",\"userid\":\"{$val['id']}\",\"username\":\"{$val['nickname']}\",\"vip\":\"{$val['vip']}\"},";
			}
		}

		//虚拟观众
		if(!empty($virtualusers_str)){
			$virtualusers_str = substr($virtualusers_str, 0,strlen($virtualusers_str)-1);
		}
		
		$userArr  = $redis->hVals($_GET['roomnum'] . 'room');
		
		$userList = '['.implode(",",$userArr).']';
		$virtualusers_str = '['.$virtualusers_str.']';

		echo "{\"msg\":[{\"_method_\":\"SendMsg\",\"action\":\"0\",\"ct\":[{\"tucount\":\"2\",\"ucount\":\"".(count($userArr)+$roominfo[0]['virtualguest'])."\"},{\"ulist\":".$userList."},{\"virtualusers_str\":".$virtualusers_str."}],\"msgtype\":\"6\",\"timestamp\":\"\",\"tougood\":\"\",\"touid\":\"\",\"touname\":\"\",\"ugood\":\"\",\"uid\":\"\",\"uname\":\"\"}],\"retcode\":\"000000\",\"retmsg\":\"OK\",\"equipment\":\"pc\"}";
	    
        $redis -> close();
		
	}
    //组装用户信息json字符串
    private function formatUserInfo($roominfo,$userinfo = null,$uType = "20"){
    	if($_SESSION['uid'] < 0){
			$user_str .= "{\"sign\":\"".$_SESSION['sign']."\",\"equipment\":\"pc\",\"nowroom\":\"".$_GET['roomnum']."\",\"err\":\"no\",\"actBadge\":\"\",\"familyname\":\"\",\"goodnum\":\"{$_GET['roomnum']}\",\"h\":\"\",\"level\":\"0\",\"richlevel\":\"0\",\"spendcoin\":\"0\",\"sellm\":\"0\",\"sortnum\":\"200000000\",\"userType\":\"20\",\"ucuid\":\"\",\"userid\":\"{$_SESSION['uid']}\",\"username\":\"游客{$_SESSION['uid']}\",\"vip\":\"0\"";
			
			if($roominfo[0]['fakeuser'] == 'y'){
				$user_str .=",\"fakeroom\":\"y\"";
				$user_str .=",\"roomBadge\":\"\"";
				$user_str .=",\"roomfamilyname\":\"\"";
				$user_str .=",\"roomgoodnum\":\"{$roominfo[0]['curroomnum']}\"";
				$user_str .=",\"roomlevel\":\"{$roomemceelevel[0]['levelid']}\"";
				$user_str .=",\"roomrichlevel\":\"{$roomrichlevel[0]['levelid']}\"";
				$user_str .=",\"roomuserid\":\"{$roominfo[0]['id']}\"";
				$user_str .=",\"roomusername\":\"{$roominfo[0]['nickname']}\"";
				$user_str .=",\"roomvip\":\"1\"";
				
			}
			else{
				$user_str .=",\"fakeroom\":\"n\"}";
				
			}
			return $user_str;
			
		}else{

			
			$richlevel  = getRichlevel($userinfo['spendcoin']);
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			
			$user_str .= "{\"sign\":\"".$_SESSION['sign']."\",\"equipment\":\"pc\",\"nowroom\":\"".$_GET['roomnum']."\",\"err\":\"no\",\"actBadge\":\"\",\"familyname\":\"\",\"goodnum\":\"{$userinfo['curroomnum']}\",\"h\":\"{$userinfo['ucuid']}\",\"level\":\"{$emceelevel[0]['levelid']}\",\"richlevel\":\"{$richlevel[0]['levelid']}\",\"spendcoin\":\"{$userinfo['spendcoin']}\",\"sellm\":\"{$userinfo['sellm']}\",\"sortnum\":\"\",\"ucuid\":\"{$val['ucuid']}\",\"userid\":\"{$userinfo['id']}\",\"username\":\"{$userinfo['username']}\",\"vip\":\"0\"";
			
			if ((int)$userinfo['vip'] == 1)
                {
                    $sortnum = 900000000;
                }
                else if ((int)$userinfo['vip'] == 1)
                {
                    $sortnum = 800000000;
                }
                else if ((int)$userinfo['vip'] > 10)
                {
                    $sortnum = 810000000;
                }
                else
                {
                    $sortnum = 300000000;
                } 
			$user_str .=",\"sortnum\":\"{$sortnum}\"";	
			$user_str .=",\"userType\":\"{$uType}\"";	
			$user_str .=",\"userid\":\"{$userinfo['id']}\"";	
			$user_str .=",\"username\":\"{$userinfo['nickname']}\"";	
			
			if($userinfo['vipexpire'] > time()){
				$user_str .=",\"vip\":\"{$userinfo['vip']}\"";	
				
			}
			else{
				$user_str .=",\"vip\":\"0\"";	
			}
			if($roominfo[0]['fakeuser'] == 'y'){
				$user_str .=",\"fakeroom\":\"y\"";
				$user_str .=",\"roomBadge\":\"\"";
				$user_str .=",\"roomfamilyname\":\"\"";
				$user_str .=",\"roomgoodnum\":\"{$roominfo[0]['curroomnum']}\"";
				$user_str .=",\"roomlevel\":\"{$roomemceelevel[0]['levelid']}\"";
				$user_str .=",\"roomrichlevel\":\"{$roomrichlevel[0]['levelid']}\"";
				$user_str .=",\"roomuserid\":\"{$roominfo[0]['id']}\"";
				$user_str .=",\"roomusername\":\"{$roominfo[0]['nickname']}\"";
				$user_str .=",\"roomvip\":\"1\"";
			}
			else{
				$user_str .=",\"fakeroom\":\"n\"}";
			}
			return $user_str;
			
		}
    }
    public function getUserDetailInfo(){
    	$redis = connectionRedis();
		$userInfo = $redis -> get($_SESSION['token']);
		$redis -> close();
		echo $userInfo;
		
    }
	/*开启关闭直播状态更改*/
	public function startEndLive(){
		$action = isset($_GET['action'])?$_GET['action']:'n';
		if($action == 'y'||$action == 'n'){
			D('Member') -> where("id={$_SESSION['uid']}") -> setField("broadcasting",$action);
			echo '{"code":"0"}';
		}else{
			echo '{"code":"1"}';
		}
		
	}
//==========node改====================获取用户列表===========================================

	public function show_headerInfo() {
		C('HTML_CACHE_ON', false);

		if ($_COOKIE['autoLogin'] == '1') {
			session('uid', $_COOKIE['userid']);
			session('ucuid', $_COOKIE['ucuid']);
			session('username', $_COOKIE['username']);
			session('nickname', $_COOKIE["nickname"]);
			session('roomnum', $_COOKIE["roomnum"]);
		}

		if ($_SESSION['uid'] && $_SESSION['uid'] > 0) {
			$userinfo = D("Member") -> find($_SESSION['uid']);
			//如果不是主播显示财富等级
			if($userinfo['sign']!="y")
			{
				$emceelevel = getRichlevel($userinfo['spendcoin']);	
				
				$levelstr = 'cracy cra'.$emceelevel[0]['levelid'];			
			}
			else
			{
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				
				$levelstr = 'star star'.$emceelevel[0]['levelid'];	
			}
		
			$this->assign("levelstr",$levelstr);
			$this -> assign('userinfo', $userinfo);
		}
		$this -> display();
	}

	public function show_headerInfo2() {
		C('HTML_CACHE_ON', false);

		if ($_COOKIE['autoLogin'] == '1') {
			session('uid', $_COOKIE['userid']);
			session('ucuid', $_COOKIE['ucuid']);
			session('username', $_COOKIE['username']);
			session('nickname', $_COOKIE["nickname"]);
			session('roomnum', $_COOKIE["roomnum"]);
		}

		if ($_SESSION['uid'] && $_SESSION['uid'] > 0) {
			echo '<a href="/index.php/User/sign_view/" target="_self" title="成为签约主播" class="mplay-off" id="sign_view">成为签约主播<em></em></a>';
			echo '<a href="/' . $_SESSION['roomnum'] . '" target="_self" title="我要直播" class="mplay-2" id="startlive">我要直播</a>';
		} else {
			echo '<a href="javascript:UAC.openUAC(0)" target="_self" title="成为签约主播" class="mplay-off" id="sign_view">成为签约主播<em></em></a>';
			echo '<a href="javascript:UAC.openUAC(0)" target="_self" title="我要直播" class="mplay-2" id="startlive">我要直播</a>';
		}
	}

	public function show_getUserBalance() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"0","value":"0"}';
			exit ;
		} else {
			$userinfo = D("Member") -> find($_SESSION['uid']);
			echo '{"code":"0","value":"' . $userinfo['coinbalance'] . '"}';
			exit ;
		}
	}

	public function show_indexLogin() {
		C('HTML_CACHE_ON', false);

		if ($_COOKIE['autoLogin'] == '1') {
			session('uid', $_COOKIE['userid']);
			session('ucuid', $_COOKIE['ucuid']);
			session('username', $_COOKIE['username']);
			session('nickname', $_COOKIE["nickname"]);
			session('roomnum', $_COOKIE["roomnum"]);
		}

		$this -> display();
	}

	public function show_checkopenuac() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '0';
			exit ;
		} else {
			echo '1';
			exit ;
		}

	}

	public function show_getEmergencyNotice() {
		C('HTML_CACHE_ON', false);
		echo '{"code":1,"info":"当月累积充值达到500RMB的用户,就可以获得一次抽奖机会！大奖最高10W秀币等你来拿！"}';
		exit ;
	}

	public function show_showData() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid']) {
			$userid = rand(1000, 9999);
			$_SESSION['uid'] = -$userid;
		}

		$this -> display();
	}

	public function enterspeshow() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") -> find($_SESSION['uid']);
		//获取主播信息
		$emceeinfo = D("Member") -> find($_REQUEST['eid']);
		if ($emceeinfo) {
			if ($_REQUEST['type'] == '1') {
				if ($userinfo['coinbalance'] < $emceeinfo['needmoney']) {
					echo '{"code":"1","info":"您的余额不足"}';
					exit ;
				} else {
					D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $emceeinfo['needmoney'] . ',coinbalance=coinbalance-' . $emceeinfo['needmoney'] . ' where id=' . $_SESSION['uid']);
					//记入虚拟币交易明细
					$Coindetail = D("Coindetail");
					$Coindetail -> create();
					$Coindetail -> type = 'expend';
					$Coindetail -> action = 'enterspeshow';
					$Coindetail -> uid = $_SESSION['uid'];
					$Coindetail -> touid = $_REQUEST['eid'];

					$Coindetail -> content = $userinfo['nickname'] . ' 进入了 ' . $emceeinfo['nickname'] . ' 的收费房间';
					$Coindetail -> objectIcon = '/Public/images/fei.png';
					$Coindetail -> coin = $emceeinfo['needmoney'];
					if ($emceeinfo['broadcasting'] == 'y') {
						$Coindetail -> showId = $emceeinfo['showId'];
					}
					$Coindetail -> addtime = time();
					$detailId = $Coindetail -> add();

					//被赠送人加豆
					$scale = D('Member')->field("sharingratio") -> getByid($_REQUEST['eid']);
					//取出改主播的信息
					if ($scale['sharingratio'] != '0') {//优先按照指定的比例算
						$beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
					} else {//默认的比例
						$beannum = ceil($needcoin * ($this -> emceededuct / 100));
					}
					//$beannum = ceil($emceeinfo['needmoney'] * ($this->emceededuct / 100));
					D("Member") -> execute('update ss_member set earnbean=earnbean+' . $beannum . ',beanbalance=beanbalance+' . $beannum . ' where id=' . $_REQUEST['eid']);
					$Beandetail = D("Beandetail");
					$Beandetail -> create();
					$Beandetail -> type = 'income';
					$Beandetail -> action = 'enterspeshow';
					$Beandetail -> uid = $_REQUEST['eid'];
					$Beandetail -> content = $userinfo['nickname'] . ' 进入了 ' . $emceeinfo['nickname'] . ' 的收费房间';
					$Beandetail -> bean = $beannum;
					$Beandetail -> addtime = time();
					$detailId = $Beandetail -> add();

					if ($emceeinfo['agentuid'] != 0) {
						$beannum = ceil($emceeinfo['needmoney'] * ($this -> emceeagentdeduct / 100));
						//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$emceeinfo['agentuid']);
						D("Member") -> execute('update ss_member set beanbalance2=beanbalance2+' . $beannum . ' where id=' . $emceeinfo['agentuid']);
						$Emceeagentbeandetail = D("Emceeagentbeandetail");
						$Emceeagentbeandetail -> create();
						$Emceeagentbeandetail -> type = 'income';
						$Emceeagentbeandetail -> action = 'enterspeshow';
						$Emceeagentbeandetail -> uid = $emceeinfo['agentuid'];
						$Emceeagentbeandetail -> content = $userinfo['nickname'] . ' 进入了 ' . $emceeinfo['nickname'] . ' 的收费房间';
						$Emceeagentbeandetail -> bean = $beannum;
						$Emceeagentbeandetail -> addtime = time();
						$detailId = $Emceeagentbeandetail -> add();
					}

					session('enter_' . $emceeinfo['showId'], 'y');
					echo '{"code":"0"}';
					exit ;
				}
			}
			if ($_REQUEST['type'] == '2') {
				if ($emceeinfo['roompsw'] != $_REQUEST['password']) {
					echo '{"code":"1","info":"进入房间密码错误"}';
					exit ;
				} else {
					session('enter_' . $emceeinfo['showId'], 'y');
					echo '{"code":"0"}';
					exit ;
				}
			}
		} else {
			echo '{"code":"1","info":"主播信息有误"}';
			exit ;
		}
	}

	public function show_infoWithgwRanking() {
		C('HTML_CACHE_ON', false);
		$userinfo = D("Member") -> find($_REQUEST['emceeId']);
		if ($userinfo) {
			$this -> assign('userinfo', $userinfo);
		}
		$gifts = D('Gift') -> order('needcoin desc') -> select();
		$this -> assign('gifts', $gifts);

		$this -> display();
	}

	public function show_getgiftList() {
		C('HTML_CACHE_ON', false);
		//$curfansrank = D('Coindetail')->query('SELECT uid,touid,giftcount,sum(coin) as total FROM `ss_coindetail` where type="expend" and showId='.$_GET['showID'].' group by uid order by total desc');
		$curfansrank = D('Coindetail') -> query('SELECT uid,touid,giftid,giftcount FROM `ss_coindetail` where type="expend" and giftid>0 and giftid<9999 and showId=' . $_GET['showID'] . '');
		foreach ($curfansrank as $n => $val) {
			$curfansrank[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
			$curfansrank[$n]['voo2'] = D("Member") -> where('id=' . $val['touid']) -> select();
		}

		echo '{"code":"0","msg":"sucess","giftList":[';
		$i = 1;
		foreach ($curfansrank as $val) {
			$giftinfo = D("Gift") -> find($val['giftid']);
			//$smallIcon = str_replace('/50/','/25/',$giftinfo['giftIcon']);
			//$smallIcon = str_replace('.png','.gif',$smallIcon);
			$smallIcon = $giftinfo['giftIcon_25'];
			echo '{"giftcount":' . $val['giftcount'] . ',"username":"' . $val['voo'][0]['nickname'] . '","giftpath":"' . $smallIcon . '","userid":' . $val['uid'] . ',"touserid":' . $val['touid'] . ',"tousername":"' . $val['voo2'][0]['nickname'] . '","giftname":"' . $giftinfo['giftname'] . '"}';
			if ($i != count($curfansrank)) {echo ',';
			}
			$i++;
		}
		echo ']}';
	}

	public function show_getRankByShow() {
		C('HTML_CACHE_ON', false);
		//本场粉丝排行
		$curfansrank = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and showId=' . $_GET['showId'] . ' group by uid order by total desc LIMIT 5');
		foreach ($curfansrank as $n => $val) {
			$curfansrank[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}

		echo '[';
		$i = 1;
		foreach ($curfansrank as $val) {
			$richlevel = getRichlevel($val['voo'][0]['spendcoin']);
			echo '{"amount":' . $val['total'] . ',"icon":"' . $this -> ucurl . 'avatar.php?uid=' . $val['voo'][0]['ucuid'] . '&size=middle","emceeno":' . $val['voo'][0]['curroomnum'] . ',"fanlevel":' . $richlevel[0]['levelid'] . ',"medaltype":0,"nickname":"' . $val['voo'][0]['nickname'] . '","userid":' . $val['voo'][0]['id'] . '}';
			if ($i != count($curfansrank)) {echo ',';
			}
			$i++;
		}
		echo ']';
	}

	public function show_showSongs() {
		C('HTML_CACHE_ON', false);
		//if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){
		//exit;
		//}

		$usersong = D("Usersong");
		$count = $usersong -> where('uid=' . $_REQUEST['eid']) -> count();
		$listRows = 10;
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$usersongs = $usersong -> where('uid=' . $_REQUEST['eid']) -> limit($p -> firstRow . "," . $p -> listRows) -> order('createTime desc') -> select();
		$this -> assign('usersongs', $usersongs);
		$pagecount = ceil($count / $listRows);
		$this -> assign('pagecount', $pagecount);
		$this -> assign('count', $count);

		$this -> display();
	}

	public function show_addSongs() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			exit ;
		}

		if ($_SESSION['uid'] != $_REQUEST['eid']) {
			exit ;
		}

		for ($i = 1; $i < 6; $i++) {
			if ($_REQUEST['name_' . $i] != '' && $_REQUEST['singer_' . $i] != '') {
				$Usersong = D("Usersong");
				$Usersong -> create();
				$Usersong -> uid = $_REQUEST['eid'];
				$Usersong -> songName = $_REQUEST['name_' . $i];
				$Usersong -> singer = $_REQUEST['singer_' . $i];
				$Usersong -> createTime = time();
				$songId = $Usersong -> add();
			}
		}

		$usersong = D("Usersong");
		$count = $usersong -> where('uid=' . $_REQUEST['eid']) -> count();
		$listRows = 10;
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$usersongs = $usersong -> where('uid=' . $_REQUEST['eid']) -> limit($p -> firstRow . "," . $p -> listRows) -> order('createTime desc') -> select();
		$pagecount = ceil($count / $listRows);

		$echostr .= '{"data":{"total":';
		$echostr .= $count;
		$echostr .= ',"page":';
		$echostr .= $pagecount;
		$echostr .= ',"songs":[';
		$i = 1;
		foreach ($usersongs as $val) {
			$echostr .= '{"id":' . $val['id'] . ',"createTime":"' . date('Y/m/d', $val['createTime']) . '","singer":"' . $val['singer'] . '","songName":"' . $val['songName'] . '"}';
			if ($i != count($usersongs)) {
				$echostr .= ',';
			}
			$i++;
		}
		$echostr .= '],"cur":1';
		$echostr .= '},"code":0,"info":""}';

		echo $echostr;
		exit ;
	}

	public function show_delSong() {
		C('HTML_CACHE_ON', false);
		if ($_SESSION['uid'] == $_REQUEST['eid']) {
			D("Usersong") -> where('id=' . $_REQUEST["sid"]) -> delete();
			echo '{"code":"0"}';
			exit ;
		} else {
			echo '{"code":"1"}';
			exit ;
		}
	}

	public function pickSong() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") ->field("coinbalance") -> find($_SESSION['uid']);
		//获取主播信息
		$emceeinfo = D("Member") ->field("emceeId,earnbean,id,levelid")-> find($_REQUEST['emceeId']);
		$emceelevel = getEmceelevel($emceeinfo['earnbean']);

		if ($emceelevel[0]['levelid'] > 10) {
			$needcoin = 1500;
		} else if ($emceelevel[0]['levelid'] > 5) {
			$needcoin = 1000;
		} else {
			$needcoin = 500;
		}

		if ($userinfo['coinbalance'] < $needcoin) {
			echo '{"code":"1","info":"您的余额不足"}';
			exit ;
		}

		$Showlistsong = D("Showlistsong");
		$Showlistsong -> create();
		$Showlistsong -> uid = $_REQUEST['emceeId'];
		$Showlistsong -> pickuid = $_SESSION['uid'];
		$Showlistsong -> songName = $_REQUEST['songName'];
		$Showlistsong -> userNick = $_SESSION['nickname'];
		$Showlistsong -> addtime = time();
		$songId = $Showlistsong -> add();

		echo '{"code":"0"}';
		exit ;
	}

	public function show_listSongs() {
		C('HTML_CACHE_ON', false);
		$showlistsongs = D("Showlistsong") -> where('uid=' . $_REQUEST['eid']) -> order('addtime desc') -> select();

		$echostr .= '{"data":{';
		$echostr .= '"songs":[';
		$i = 1;
		foreach ($showlistsongs as $val) {
			$echostr .= '{"id":' . $val['id'] . ',"createTime":"' . date('H:i', $val['addtime']) . '","songName":"' . $val['songName'] . '","userNick":"' . $val['userNick'] . '","status":' . $val['status'] . ',"showStatus":"' . $val['showStatus'] . '"}';
			if ($i != count($showlistsongs)) {
				$echostr .= ',';
			}
			$i++;
		}
		$echostr .= ']';
		$echostr .= '},"code":0,"info":""}';

		echo $echostr;
	}

	public function show_agreeSong() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		if ($_SESSION['uid'] != $_REQUEST['eid']) {
			echo '{"code":"1","info":"您没有权限"}';
			exit ;
		}

		$songinfo = D("Showlistsong") -> find($_REQUEST['ssid']);
		if ($songinfo) {
			//获取点歌用户信息
			$userinfo = D("Member") -> find($songinfo['pickuid']);
			//获取主播信息
			$emceeinfo = D("Member") -> find($songinfo['uid']);
			$emceelevel = getEmceelevel($emceeinfo['earnbean']);

			if ($emceelevel[0]['levelid'] > 10) {
				$needcoin = 1500;
			} else if ($emceelevel[0]['levelid'] > 5) {
				$needcoin = 1000;
			} else {
				$needcoin = 500;
			}
			if ($userinfo['coinbalance'] < $needcoin) {
				echo '{"code":"1","info":"点歌用户余额不足"}';
				exit ;
			}

			D("Showlistsong") -> execute('update ss_showlistsong set status="1",showStatus="已同意" where id=' . $_REQUEST['ssid']);
			//扣费
			D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $songinfo['pickuid']);
			//记入虚拟币交易明细
			$Coindetail = D("Coindetail");
			$Coindetail -> create();
			$Coindetail -> type = 'expend';
			$Coindetail -> action = 'demandsongs'; //lv
			$Coindetail -> uid = $songinfo['pickuid'];
			$Coindetail -> touid = $songinfo['uid'];
			$Coindetail -> giftid = 0;
			$Coindetail -> giftcount = 1;
			$Coindetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 点了一首歌 ' . $songinfo['songName'];
			$Coindetail -> objectIcon = '/Public/images/gift/song.png';
			$Coindetail -> coin = $needcoin;
			if ($emceeinfo['broadcasting'] == 'y') {
				$Coindetail -> showId = $emceeinfo['showId'];
			}
			$Coindetail -> addtime = time();
			$detailId = $Coindetail -> add();

			//被赠送人加豆
			$scale = D('Member')->field("sharingratio") -> getByid($songinfo['uid']);
			//取出改主播的信息
			if ($scale['sharingratio'] != '0') {//优先按照指定的比例算
				$beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
			} else {//默认的比例
				$beannum = ceil($needcoin * ($this -> emceededuct / 100));
			}
			//$beannum = ceil($needcoin * ($this->emceededuct / 100));
			D("Member") -> execute('update ss_member set earnbean=earnbean+' . $beannum . ',beanbalance=beanbalance+' . $beannum . ' where id=' . $songinfo['uid']);
			$Beandetail = D("Beandetail");
			$Beandetail -> create();
			$Beandetail -> type = 'income';
			$Beandetail -> action = 'getgift';
			$Beandetail -> uid = $songinfo['uid'];
			$Beandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 点了一首歌 ' . $songinfo['songName'];
			$Beandetail -> bean = $beannum;
			$Beandetail -> addtime = time();
			$detailId = $Beandetail -> add();

			if ($emceeinfo['agentuid'] != 0) {
				$beannum = ceil($needcoin * ($this -> emceeagentdeduct / 100));
				//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$emceeinfo['agentuid']);
				D("Member") -> execute('update ss_member set beanbalance2=beanbalance2+' . $beannum . ' where id=' . $emceeinfo['agentuid']);
				$Emceeagentbeandetail = D("Emceeagentbeandetail");
				$Emceeagentbeandetail -> create();
				$Emceeagentbeandetail -> type = 'income';
				$Emceeagentbeandetail -> action = 'getgift';
				$Emceeagentbeandetail -> uid = $emceeinfo['agentuid'];
				$Emceeagentbeandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 点了一首歌 ' . $songinfo['songName'];
				$Emceeagentbeandetail -> bean = $beannum;
				$Emceeagentbeandetail -> addtime = time();
				$detailId = $Emceeagentbeandetail -> add();
			}

			echo '{"code":"0","userNo":"' . $userinfo['curroomnum'] . '","userId":"' . $userinfo['id'] . '","userName":"' . $songinfo['userNick'] . '","songName":"' . $songinfo['songName'] . '"}';
			exit ;
		} else {
			echo '{"code":"1","info":"没有该点歌记录"}';
			exit ;
		}
	}

	public function show_disAgreeSong() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		if ($_SESSION['uid'] != $_REQUEST['eid']) {
			echo '{"code":"1","info":"您没有权限"}';
			exit ;
		}
       
		$songinfo = D("Showlistsong") -> find($_REQUEST['ssid']);
		if ($songinfo) {
			D("Showlistsong") -> execute('update ss_showlistsong set status="2",showStatus="未同意" where id=' . $_REQUEST['ssid']);
             $userinfo = D("Member") -> find($songinfo['pickuid']);
				echo '{"code":"0","userNo":"' . $userinfo['curroomnum'] . '","userId":"' . $userinfo['id'] . '","userName":"' . $songinfo['userNick'] . '","songName":"' . $songinfo['songName'] . '"}';
			exit ;
		} else {
			echo '{"code":"1","info":"没有该点歌记录"}';
			exit ;
		}
	}

	public function show_setSongApply() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		D("Member") -> execute('update ss_member set SongApply="' . $_REQUEST['apply'] . '" where id=' . $_SESSION['uid']);

		echo '{"code":"0"}';
		exit ;
	}

	public function dosendFly() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		$emceeinfo = D("Member") -> find($_REQUEST['eid']);
		if ($_REQUEST['toid'] == 0) {
			$besenduinfo = $emceeinfo;
		} else {
			$besenduinfo = D("Member") -> find($_REQUEST['toid']);
		}
		if ($emceeinfo) {
			//判断虚拟币是否足够
			//获取用户信息
			$userinfo = D("Member") -> find($_SESSION['uid']);
			$needcoin = 1000;
			if ($userinfo['coinbalance'] < $needcoin) {
				echo '{"code":"1","info":"你的余额不足"}';
				exit ;
			}
			
			D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);
			//记入虚拟币交易明细
			$Coindetail = D("Coindetail");
			$Coindetail -> create();
			$Coindetail -> type = 'expend';
			$Coindetail -> action = 'sendfeiping'; //lv
			$Coindetail -> uid = $_SESSION['uid'];
			$Coindetail -> touid = $besenduinfo['id'];
			$Coindetail -> giftid = 0;
			$Coindetail -> giftcount = 1;
			$Coindetail -> content = $userinfo['nickname'] . ' 向 ' . $besenduinfo['nickname'] . ' 送了 飞屏1个';
			$Coindetail -> objectIcon = '/Public/images/gift/feiping.png';
			$Coindetail -> coin = $needcoin;
			if ($emceeinfo['broadcasting'] == 'y') {
				$Coindetail -> showId = $emceeinfo['showId'];
			}
			$Coindetail -> addtime = time();
			$detailId = $Coindetail -> add();

			//被赠送人加豆
			$scale = D('Member') ->field("sharingratio")-> getByid($besenduinfo['id']);
			//取出改主播的信息
			if ($scale['sharingratio'] != '0') {//优先按照指定的比例算
				$beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
			} else {//默认的比例
				$beannum = ceil($needcoin * ($this -> emceededuct / 100));
			}
			//$beannum = ceil($needcoin * ($this->emceededuct / 100));
			D("Member") -> execute('update ss_member set earnbean=earnbean+' . $beannum . ',beanbalance=beanbalance+' . $beannum . ' where id=' . $besenduinfo['id']);
			$Beandetail = D("Beandetail");
			$Beandetail -> create();
			$Beandetail -> type = 'income';
			$Beandetail -> action = 'getgift';
			$Beandetail -> uid = $besenduinfo['id'];
			$Beandetail -> content = $userinfo['nickname'] . ' 向 ' . $besenduinfo['nickname'] . ' 送了 飞屏1个';
			$Beandetail -> bean = $beannum;
			$Beandetail -> addtime = time();
			$detailId = $Beandetail -> add();

			if ($emceeinfo['agentuid'] != 0) {
				$beannum = ceil($needcoin * ($this -> emceeagentdeduct / 100));
				//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$emceeinfo['agentuid']);
				D("Member") -> execute('update ss_member set beanbalance2=beanbalance2+' . $beannum . ' where id=' . $emceeinfo['agentuid']);
				$Emceeagentbeandetail = D("Emceeagentbeandetail");
				$Emceeagentbeandetail -> create();
				$Emceeagentbeandetail -> type = 'income';
				$Emceeagentbeandetail -> action = 'getgift';
				$Emceeagentbeandetail -> uid = $emceeinfo['agentuid'];
				$Emceeagentbeandetail -> content = $userinfo['nickname'] . ' 向 ' . $besenduinfo['nickname'] . ' 送了 飞屏1个';
				$Emceeagentbeandetail -> bean = $beannum;
				$Emceeagentbeandetail -> addtime = time();
				$detailId = $Emceeagentbeandetail -> add();
			}

			echo '{"code":"0","token":"' . $this -> consumption(self::SENDFLY) . '"}';
			exit ;
		} else {
			echo '{"code":"1","info":"主播信息有误"}';
			exit ;
		}
	}

	public function show_bandingNote() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"2"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") -> find($_SESSION['uid']);
		//获取主播信息
		$emceeinfo = D("Member") -> find($_REQUEST['rid']);
		//获取被贴条人信息
		$betieinfo = D("Member") -> find($_REQUEST['recieverId']);
		//贴条信息
		$ttinfo = D("Tietiao") -> find($_REQUEST['noteId']);
		if ($ttinfo) 
		{
			//不能给主播贴条
			if ($_REQUEST['recieverId'] == $_REQUEST['rid']) 
			{
				echo '{"code":"2"}';
				exit ;
			}
			//判断虚拟币是否足够
			if ($userinfo['coinbalance'] < $ttinfo['needcoin']) {
				echo '{"code":"1"}';
				exit ;
			}
			//判断此人是否有被贴条，贴条是否还在身上
			$Bandingnotes = D("Bandingnote") -> where('uid=' . $_REQUEST['recieverId'] . ' and showId=' . $emceeinfo['showId'] . ' and addtime>' . (time() - 100)) -> order('addtime desc') -> select();
			if ($Bandingnotes)
			 {
				echo '{"code":"3"}';
				exit ;
			} 
			else 
			{
				//提交验证
		        PackSpendVerifi("SendFly");
				//写入贴条记录
				$Bandingnote = D("Bandingnote");
				$Bandingnote -> create();
				$Bandingnote -> uid = $_REQUEST['recieverId'];
				$Bandingnote -> showId = $emceeinfo['showId'];
				$Bandingnote -> addtime = time();
				$bandId = $Bandingnote -> add();

				//扣费
				D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $ttinfo['needcoin'] . ',coinbalance=coinbalance-' . $ttinfo['needcoin'] . ' where id=' . $_SESSION['uid']);
				//记入虚拟币交易明细
				$Coindetail = D("Coindetail");
				$Coindetail -> create();
				$Coindetail -> type = 'expend';
				$Coindetail -> action = 'jointstrip'; // lv
				$Coindetail -> uid = $_SESSION['uid'];
				$Coindetail -> touid = $_REQUEST['recieverId'];
				$Coindetail -> giftid = 0;
				$Coindetail -> giftcount = 1;
				$Coindetail -> content = $userinfo['nickname'] . ' 给 ' . $betieinfo['nickname'] . ' 贴了一个条';
				$Coindetail -> objectIcon = '/Public/images/tietiao.png';
				$Coindetail -> coin = $ttinfo['needcoin'];
				if ($emceeinfo['broadcasting'] == 'y') 
				{
					$Coindetail -> showId = $emceeinfo['showId'];
				}
				$Coindetail -> addtime = time();
				$detailId = $Coindetail -> add();

				//被赠送人加豆
				/*
				 $beannum = ceil($ttinfo['needcoin'] * 0.3);
				 D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$_REQUEST['rid']);
				 $Beandetail = D("Beandetail");
				 $Beandetail->create();
				 $Beandetail->type = 'income';
				 $Beandetail->action = 'getgift';
				 $Beandetail->uid = $_REQUEST['rid'];
				 $Beandetail->content = $userinfo['nickname'].' 给 '.$betieinfo['nickname'].' 贴了一个条';
				 $Beandetail->bean = $beannum;
				 $Beandetail->addtime = time();
				 $detailId = $Beandetail->add();
				 */

				echo '{"code":"0","money":"' . $ttinfo['needcoin'] . '"}';
				exit ;
			}
		} 
		else 
		{
			echo '{"code":"2"}';
			exit ;
		}
	}

	public function show_takeSeat() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}
		
	

		$emceeinfo = D("Member") -> find($_REQUEST['emceeId']);
		if ($emceeinfo) {

			if($_REQUEST['isactive']=='1'){
				$familyinfo=M("agentfamily")->where("id='$emceeinfo[agentuid]'")->find();
									
				$seatsql="seat='".$_REQUEST['seatid']."' and familyid='".$emceeinfo['agentuid']."' and type='0'";
			}else{
				$seatsql="seat='".$_REQUEST['seatid']."' and touid='".$_REQUEST['emceeId']."' and type='0'";					
			}
									
			$oldseat=M("member_seat")->where($seatsql)->find();

			if ($_REQUEST['count'] <= $oldseat['count']) {
				echo '{"code":"1","info":"您抢座的沙发数小于当前沙发数"}';
				exit ;
			} else {
				//判断虚拟币是否足够
				//获取用户信息
				$userinfo = D("Member") -> find($_SESSION['uid']);
				$needcoin = 100 * $_REQUEST['count'];
				if ($userinfo['coinbalance'] < $needcoin) {
					echo '{"code":"1","info":"您的余额不足"}';
					exit ;
				}
				
                D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);
				
				if($_REQUEST['isactive']=='1'){
					
					//记入虚拟币交易明细
					$Coindetail = D("coindetail_jiazu");
					$Coindetail -> create();
					$Coindetail -> type = 'expend';
					$Coindetail -> action = 'sendsofa';
					$Coindetail -> uid = $_SESSION['uid'];
					$Coindetail -> touid = $_REQUEST['emceeId'];
					$Coindetail -> giftid = 0;
					$Coindetail -> giftcount = $_REQUEST['count'];
					$Coindetail -> content = $userinfo['nickname'] . ' 向 ' . $familyinfo['familyname'] . ' 送了 沙发 ' . $_REQUEST['count'] . ' 个';
					$Coindetail -> objectIcon = '/Public/images/gift/sofa.png';
					$Coindetail -> coin = $needcoin;
					$Coindetail -> familyid = $familyinfo['id'];
					$Coindetail -> familyuid = $familyinfo['uid'];
					if ($familyinfo['showId'] != '0') {
						$Coindetail -> showId = $familyinfo['showId'];
					}
					$Coindetail -> addtime = time();
					$detailId = $Coindetail -> add();					
										
				}else{
					//记入虚拟币交易明细
					$Coindetail = D("Coindetail");
					$Coindetail -> create();
					$Coindetail -> type = 'expend';
					$Coindetail -> action = 'sendsofa';
					$Coindetail -> uid = $_SESSION['uid'];
					$Coindetail -> touid = $_REQUEST['emceeId'];
					$Coindetail -> giftid = 0;
					$Coindetail -> giftcount = $_REQUEST['count'];
					$Coindetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 送了 沙发 ' . $_REQUEST['count'] . ' 个';
					$Coindetail -> objectIcon = '/Public/images/gift/sofa.png';
					$Coindetail -> coin = $needcoin;
					if ($emceeinfo['broadcasting'] == 'y') {
						$Coindetail -> showId = $emceeinfo['showId'];
					}
					$Coindetail -> addtime = time();
					$detailId = $Coindetail -> add();	
														
					//被赠送人加豆
					$scale = D('Member')->field("sharingratio") -> getByid($_REQUEST['emceeId']);
					//取出改主播的信息
					if ($scale['sharingratio'] != '0') {//优先按照指定的比例算
						$beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
					} else {//默认的比例
						$beannum = ceil($needcoin * ($this -> emceededuct / 100));
					}
					//$beannum = ceil($needcoin * ($this->emceededuct / 100));
					D("Member") -> execute('update ss_member set earnbean=earnbean+' . $beannum . ',beanbalance=beanbalance+' . $beannum . ' where id=' . $_REQUEST['emceeId']);
					$Beandetail = D("Beandetail");
					$Beandetail -> create();
					$Beandetail -> type = 'income';
					$Beandetail -> action = 'getgift';
					$Beandetail -> uid = $_REQUEST['emceeId'];
					$Beandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 送了 沙发 ' . $_REQUEST['count'] . ' 个';
					$Beandetail -> bean = $beannum;
					$Beandetail -> addtime = time();
					$detailId = $Beandetail -> add();
	
					if ($emceeinfo['agentuid'] != 0) {
						$beannum = ceil($needcoin * ($this -> emceeagentdeduct / 100));
						//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$emceeinfo['agentuid']);
						D("Member") -> execute('update ss_member set beanbalance2=beanbalance2+' . $beannum . ' where id=' . $emceeinfo['agentuid']);
						$Emceeagentbeandetail = D("Emceeagentbeandetail");
						$Emceeagentbeandetail -> create();
						$Emceeagentbeandetail -> type = 'income';
						$Emceeagentbeandetail -> action = 'getgift';
						$Emceeagentbeandetail -> uid = $emceeinfo['agentuid'];
						$Emceeagentbeandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 送了 沙发 ' . $_REQUEST['count'] . ' 个';
						$Emceeagentbeandetail -> bean = $beannum;
						$Emceeagentbeandetail -> addtime = time();
						$detailId = $Emceeagentbeandetail -> add();
					}					
					
				}


				$data=array();
				$data['uid']=$_SESSION['uid']; 
				$data['ucuid']=$_SESSION['ucuid'];
				$data['nickname']=$_SESSION['nickname'];
				$data['count']=$_REQUEST['count'] ;
				$data['seat']=$_REQUEST['seatid'];
				$data['touid']=$_REQUEST['emceeId'];
				if($_REQUEST['isactive']=='1'){

					$data['type']=1;
					$data['showId']=0;
					$data['familyid']=$familyinfo['id'];
					$data['familyuid']=$familyinfo['uid'];
					
				}else{
					$data['type']=0;
					$data['showId']=0;
					$data['familyid']=0;
					$data['familyuid']=0;
					
				}			
				if($oldseat){
					 M("member_seat")->where("id='$oldseat[id]'")->save($data);
				}else{	
					 M("member_seat")->add($data);
				}

				//echo '{"code":"0","userNick":"' . $_SESSION['nickname'] . '","userIcon":"' . $this -> ucurl . 'avatar.php?uid=' . $_SESSION['ucuid'] . '&size=middle","seatId":"' . $_REQUEST['seatid'] . '","seatPrice":"' . $_REQUEST['count'] . '"}';
				$sofaInfoArray  = array(
                	'seatId'    => $_REQUEST['seatid'],
                	'sofa_num'  => $_REQUEST['count'] , 
                	'seatPrice' => $_REQUEST['count'] , 
                	'userIcon'  => $this -> ucurl . 'avatar.php?uid=' . $_SESSION['ucuid'] . '&size=middle', 
                	);
                echo '{"code":"0","token":"' . $this -> consumption(self::SENDSOFA,$sofaInfoArray) . '"}';
				exit ;
			}
		} else {
			echo '{"code":"1","info":"主播信息有误"}';
			exit ;
		}
	}

	public function show_sendGift() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") -> field("coinbalance,nickname") -> find($_SESSION['uid']);
		//获取被赠送人信息
		$emceeinfo = D("Member") ->field("nickname,broadcasting,showId,nickname,curroomnum") -> find($_REQUEST['toid']);
		//根据gid获取礼物信息
		$giftinfo = D("Gift") -> find($_REQUEST['gid']);
		$gidd = $_REQUEST['gid'];
		//判断虚拟币是否足够
		$needcoin = $giftinfo['needcoin'] * $_REQUEST['count'];

        $kk =  $_REQUEST['kk'];
        if(trim($kk)!='kc'){
			if($userinfo['coinbalance'] < $needcoin){
				echo '{"code":"1","info":"你的余额不足"}';
				exit;
			}
			
			D("Member")->execute('update ss_member set spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);
			$verification=$_SESSION['uid'].$_REQUEST['gid'].$_REQUEST['count'].$needcoin;
			//D("Member") ->where('id='.$_SESSION['uid'])->save(array('isdebit'=>$verification));
			D("Member")->execute("update ss_member set isdebit='{$verification}' where id={$_SESSION['uid']}");
			//记入虚拟币交易明细
			$Coindetail = D("Coindetail");
			$Coindetail->create();
			$Coindetail->type = 'expend';
			$Coindetail->action = 'sendgift';
			$Coindetail->uid = $_SESSION['uid'];
			$Coindetail->touid = $_REQUEST['toid'];
			$Coindetail->giftid = $_REQUEST['gid'];
			$Coindetail->giftcount = $_REQUEST['count'];
			$Coindetail->content = $userinfo['nickname'].' 向 '.$emceeinfo['nickname'].' 赠送礼物 '.$giftinfo['giftname'].' '.$_REQUEST['count'].' 个';
			//$smallIcon = str_replace('/50/','/25/',$giftinfo['giftIcon']);
			//$smallIcon = str_replace('.png','.gif',$smallIcon);
			$smallIcon = $giftinfo['giftIcon_25'];
			$Coindetail->objectIcon = $smallIcon;
			$Coindetail->coin = $needcoin;
			if($emceeinfo['broadcasting'] == 'y'){
				$Coindetail->showId = $emceeinfo['showId'];
			}
			$Coindetail->addtime = time();
			$detailId = $Coindetail->add();
		     // ($this->emceededuct / 100)
			//被赠送人加豆
			//($this->emceededuct / 100)
			$scale=D('Member')->field("sharingratio")->getByid($_REQUEST['toid']);//取出改主播的信息
			if($scale['sharingratio']!='0'){//优先按照指定的比例算
			     $beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
			}else{//默认的比例
		        $beannum = ceil($needcoin * ($this->emceededuct / 100));
			}
			
			//通过礼物查询分类
			$rate = M('giftsort')->where("id = $giftinfo[sid]")->getField('rate');

			//分类礼物主播加成
			$beannum = $beannum +$rate/100*$needcoin;

			//单独礼物加成
			$rate_alone=M('gift')->where("id=$_REQUEST[gid]")->getField('rate');

			if($rate_alone){
				$beannum=$beannum +$rate_alone/100*$needcoin;
			}
			
			
			
			
			
			D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$_REQUEST['toid']);
			$Beandetail = D("Beandetail");
			$Beandetail->create();
			$Beandetail->type = 'income';
			$Beandetail->action = 'getgift';
			$Beandetail->uid = $_REQUEST['toid'];
			$Beandetail->content = $userinfo['nickname'].' 向 '.$emceeinfo['nickname'].' 赠送礼物 '.$giftinfo['giftname'].' '.$_REQUEST['count'].' 个';
			$Beandetail->bean = $beannum;
			$Beandetail->addtime = time();
			$detailId = $Beandetail->add();
			
			//幸运礼物
			import("@.Action.LuckgiftAction");
			$luckGift=new LuckgiftAction();
			
			$luckGiftStatus=$luckGift->getLuckGiftStatus();
			$isLuckGift=$luckGift->isLuckGift($_REQUEST['gid']);
			if($luckGiftStatus && $isLuckGift){
				$luck_giftinfo=$userinfo['nickname'].' 向 '.$emceeinfo['nickname'].' 赠送礼物 '.$giftinfo['giftname'].' '.$_REQUEST['count'].' 个';
				
			    $luckRes=$luckGift->givePrize($_SESSION['uid'],$giftinfo['giftname'],$needcoin,$luck_giftinfo);
				
			}
			
			if($luckRes){
				$giftinfo['luckgift']=1;
				$giftinfo['prize']=$luckRes['prize'];
				$giftinfo['giveprize']=$luckRes['giveprize'];
				echo '{"code":0,"token":"'.$this -> consumption(self::SENDGIFT,$giftinfo,$emceeinfo).'"}';
			}else{
				$giftinfo['luckgift']=0;
				echo '{"code":0,"token":"'.$this -> consumption(self::SENDGIFT,$giftinfo,$emceeinfo).'"}';
			}
			
			exit ;
		} else {

			include '/lib/action/daojuset.php';
			echo '{"code":0,"token":"'.$this -> consumption(self::SENDGIFT,$giftinfo,$emceeinfo).'"}';
	
			exit ;
		}

	}

	//库存礼物信息
	function get_stock_info()
	{
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}
		//获取被赠送人信息
		$emceeinfo = D("Member") -> find($_REQUEST['toid']);
		
		//获取用户信息
		$userinfo = D("Member") -> find($_SESSION['uid']);
		//查询库存礼物
		$gid = substr($_GET['gid'], 5);
		//var_dump($_GET['gid']);
		$giftinfo = D('turntable')->find($gid);
		//礼物减少
		$surplusGift = D('prize')->where("prizeID = $gid and uid = $_SESSION[uid]")->getField('number');
		if($surplusGift>=$_REQUEST['count'])
		{
			D("prize")->where("prizeID = $gid and uid = $_SESSION[uid]")->setDec('number',$_REQUEST['count']);
		
		}
		else
		{
			echo '{"code":"1","info":"库存数量不足"}';exit;
		}

		$smallIcon = $giftinfo['giftIcon_25'];
		$icon = $info['giftIcon'];
		$swf = $info['giftSwf'];
		echo '{"code":"0","token":"' . $this -> consumption(self::SENDGIFT,$giftinfo,$emceeinfo) . '"}';
		
		exit ;
	}

	public function show_sendHb() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"1","info":"您尚未登录"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") -> find($_SESSION['uid']);
		//获取主播信息
		$emceeinfo = D("Member") -> find($_REQUEST['eid']);
		//判断红包是否足够
		if ($userinfo['fundhb'] < 1) {
			echo '{"code":"1","info":"您的红包不足"}';
			exit ;
		}

		if ($userinfo['sendhb2'] == ($this -> sendhb - 1)) {
			D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $this -> spendcoin . ',fundhb=fundhb-1,sendhb=sendhb+1,sendhb2=0 where id=' . $_SESSION['uid']);
		} else {
			D("Member") -> execute('update ss_member set fundhb=fundhb-1,sendhb=sendhb+1,sendhb2=sendhb2+1 where id=' . $_SESSION['uid']);
		}

		if ($emceeinfo['lastgethbtime'] == 0) {
			$gethb = $emceeinfo['gethb'] + 1;
			$gethb_day = $emceeinfo['gethb_day'] + 1;
			$gethb_week = $emceeinfo['gethb_week'] + 1;
			$gethb_month = $emceeinfo['gethb_month'] + 1;
		} else {
			$gethb = $emceeinfo['gethb'] + 1;
			if (date('Y-m-d', $emceeinfo['lastgethbtime']) == date('Y-m-d', time())) {
				$gethb_day = $emceeinfo['gethb_day'] + 1;
			} else {
				$gethb_day = 1;
			}
			if (date('Y', $emceeinfo['lastgethbtime']) == date('Y', time()) && date('W', $emceeinfo['lastgethbtime']) == date('W', time())) {
				$gethb_week = $emceeinfo['gethb_week'] + 1;
			} else {
				$gethb_week = 1;
			}
			if (date('Y-m', $emceeinfo['lastgethbtime']) == date('Y-m', time())) {
				$gethb_month = $emceeinfo['gethb_month'] + 1;
			} else {
				$gethb_month = 1;
			}
		}

		D("Member") -> execute("update ss_member set gethb=" . $gethb . ",gethb_day=" . $gethb_day . ",gethb_week=" . $gethb_week . ",gethb_month=" . $gethb_month . ",hbbalance=hbbalance+1,lastgethbtime=" . time() . " where id=" . $_REQUEST['eid']);

		echo '{"code":"0","userNo":"' . $_SESSION['roomnum'] . '","userId":"' . $_SESSION['uid'] . '","userName":"' . $_SESSION['nickname'] . '"}';
		exit ;
	}

	public function speaker_handler() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"code":"2"}';
			exit ;
		}

		if ($_REQUEST['msg'] == '') {
			echo '{"code":"1"}';
			exit ;
		}

		if (strlen($_REQUEST['msg']) > 100) {
			echo '{"code":"5"}';
			exit ;
		}

		//获取用户信息
		$userinfo = D("Member") -> find($_SESSION['uid']);
		if ($userinfo['atwill'] == 'y' && $userinfo['awexpire'] > time()) {
			$count = D("Coindetail") -> where('uid=' . $_SESSION['uid'] . ' and objectIcon="/Public/images/fei.png" and coin=0 and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y")') -> count();
			if ($count >= 100) {
				$isfree = "n";
			} else {
				$isfree = "y";
			}
		} else {
			$isfree = "n";
		}

		if ($isfree == 'n') {
			//判断虚拟币是否足够
			$needcoin = 500;
			if ($userinfo['coinbalance'] < $needcoin) {
				echo '{"code":"3"}';
				exit ;
			}
		} else {
			$needcoin = 0;
		}
		PackSpendVerifi("SubmitBroadcast");
		D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);
		//记入虚拟币交易明细
		$Coindetail = D("Coindetail");
		$Coindetail -> create();
		$Coindetail -> type = 'expend';
		$Coindetail -> action = 'smallhorn'; //lv
		$Coindetail -> uid = $_SESSION['uid'];
		$Coindetail -> content = $userinfo['nickname'] . ' 发送了一条小喇叭';
		$Coindetail -> objectIcon = '/Public/images/fei.png';
		$Coindetail -> coin = $needcoin;
		if ($emceeinfo['broadcasting'] == 'y') {
			$Coindetail -> showId = $emceeinfo['showId'];
		}
		$Coindetail -> addtime = time();
		$detailId = $Coindetail -> add();

		//echo '{"code":"0","msg":"<b class=\"red\">'.$userinfo['nickname'].'('.$userinfo['curroomnum'].')：</b><a href=\"/'.$_REQUEST['emceeId'].'\" target=\"_blank\">'.iconv('gbk','utf-8',$_REQUEST['msg']).'</a>"}';
		echo '{"code":"0","userName":" <span>'.date("H:i",time()).'</span>' . $userinfo['nickname'] . '","userNo":"' . $userinfo['curroomnum'] . '","emceeId":"' . $_REQUEST['emceeId'] . '","msg":"' . $_REQUEST['msg'] . '"}';
		exit ;
	}
    //wp nodeversion 恢复发言
	public function resume(){
		C('HTML_CACHE_ON', false);
		if(isset($_SESSION['uid'])){
			$res = D('Member') -> where("id={$_SESSION['uid']}") -> field("curroomnum") -> find();
			if($res['curroomnum'] == $_GET['roomnum']){
				$redis = connectionRedis();
				$redis -> del($_GET['roomnum'] . 'shutup',$_GET['uid']);
				$redis -> close();
				echo '{"code":"0"}';
				exit ;
			}else{
				echo '{"code":"1","info":"您没有权限"}';
			}
		}
	}

	public function shutup() {
		C('HTML_CACHE_ON', false);
		//获取用户信息
		$userinfo = D("Member") -> find($_REQUEST['uidlist']);
		
		if ($userinfo) {
			if ($userinfo['showadmin'] == '1') {
				echo '{"code":"1","info":"对方是系统管理员不能禁言"}';
				exit ;
			}
			if ($_REQUEST['uidlist'] == $_REQUEST['rid']) {
				echo '{"code":"1","info":"对方是主播不能禁言"}';
				exit ;
			}
			$myshowadmin = D("Roomadmin") -> where('uid=' . $_REQUEST['rid'] . ' and adminuid=' . $_REQUEST['uidlist']) -> order('id asc') -> select();
			if ($myshowadmin) {
				echo '{"code":"1","info":"对方是管理员不能禁言"}';
				exit ;
			}
			if ($userinfo['vip'] > 0 && $userinfo['vipexpire'] > time()) {
				if ($userinfo['vip'] == 1) {
					echo '{"code":"1","info":"对方是VIP不能禁言"}';
					exit ;
				}
				if ($userinfo['vip'] == 2) {
					echo '{"code":"1","info":"对方是VIP不能禁言"}';
					exit ;
				}
			} else {
				//wp nodeversion 禁言用户存储列表
				$redis = connectionRedis();
				$redis -> hSet($_GET['roomnum'] . 'shutup',$userinfo['id'],time());
				$redis -> close();
				echo '{"code":"0"}';
				exit ;
			}
		} else {
			echo '{"code":"1","info":"找不到该用户"}';
			exit ;
		}
	}

	public function kick() {
		C('HTML_CACHE_ON', false);
		//获取用户信息
		$userinfo = D("Member") -> find($_REQUEST['uidlist']);
		if ($userinfo) {
			if ($userinfo['showadmin'] == '1') {
				echo '{"code":"1","info":"对方是系统管理员不能踢出"}';
				exit ;
			}
			if ($_REQUEST['uidlist'] == $_REQUEST['rid']) {
				echo '{"code":"1","info":"对方是主播不能踢出"}';
				exit ;
			}
			$myshowadmin = D("Roomadmin") -> where('uid=' . $_REQUEST['rid'] . ' and adminuid=' . $_REQUEST['uidlist']) -> order('id asc') -> select();
			if ($myshowadmin) {
				echo '{"code":"1","info":"对方是管理员不能踢出"}';
				exit ;
			}
			if ($userinfo['vip'] > 0 && $userinfo['vipexpire'] > time()) {
				if ($userinfo['vip'] == 1) {
					echo '{"code":"1","info":"对方是VIP不能踢出"}';
					exit ;
				}
				if ($userinfo['vip'] == 2) {
					
					echo '{"code":"1","info":"对方是VIP不能踢出"}';
					exit ;
					
				}
			} else {
				//wp nodeversion 被踢用户存储列表
				$redis = connectionRedis();
				$redis -> hSet($_GET['roomnum'] . 'kick',$userinfo['id'],time());
				$redis -> close();
				echo '{"code":"0"}';
				exit ;
			}
		} else {
			echo '{"code":"1","info":"找不到该用户"}';
			exit ;
		}
	}

	public function toggleShowAdmin() {
		C('HTML_CACHE_ON', false);
		if ($_SESSION['uid'] != $_REQUEST['eid']) {
			echo '{"code":"1","info":"您没有权限"}';
			exit ;
		}

		if ($_REQUEST['state'] == 1) {
			$myshowadmin = D("Roomadmin") -> where('uid=' . $_SESSION['uid'] . ' and adminuid=' . $_REQUEST['userid']) -> order('id asc') -> select();
			if ($myshowadmin) {
				echo '{"code":"0"}';
				exit ;
			} else {
				$Roomadmin = D("Roomadmin");
				$Roomadmin -> create();
				$Roomadmin -> uid = $_SESSION['uid'];
				$Roomadmin -> adminuid = $_REQUEST['userid'];
				$Roomadmin -> add();

				echo '{"code":"0"}';
				exit ;
			}
		} else {
			D("Roomadmin") -> where('uid=' . $_SESSION['uid'] . ' and adminuid=' . $_REQUEST['userid']) -> delete();
			echo '{"code":"0"}';
			exit ;
		}
	}

	public function show_redbaginfo() {
		C('HTML_CACHE_ON', false);

		if ($_SESSION['uid'] == '' || $_SESSION['uid'] == null || $_SESSION['uid'] < 0) {
			echo '请<a href="#" onclick="javascript:UAC.openUAC(0); return false;" title="登录">登录</a>或<a href="#" onclick="javascript:UAC.openUAC(1); return false;" title="注册">注册</a>领取红包<br>&nbsp;';
		} else {
			$userinfo = D("Member") -> find($_SESSION['uid']);
			echo '</span> <div style="width:270px;height:38px;line-height:20px; margin-top:-10px;font-size:12px;"><span style="color:#cccccc;">您已累积</span><span style="font-weight:bold;color:#FF0000;" id="fundhb">' . $userinfo['fundhb'] . '</span><span style="color:#cccccc;">个红包，点击送给主播1个红包<br>已送出</span><span style="font-weight:bold;color:#FF0000;" id="sendhb">' . $userinfo['sendhb'] . '</span></span><span style="color:#cccccc;">个红包</span></div>';
			if ((int)$userinfo['vip'] > 0 && $userinfo['vipexpire'] > time()) {
				echo '<script type="text/javascript" language="javascript">var gethbinterval=setInterval(function(){$("#redBagBox").load(\'/index.php/Show/show_redbaginfo2/\',function (responseText, textStatus, XMLHttpRequest){this;});}, ' . ($this -> gethbinterval * 60 * 1000) . ');</script>';
			} else {
				echo '<script type="text/javascript" language="javascript">var gethbinterval=setInterval(function(){$("#redBagBox").load(\'/index.php/Show/show_redbaginfo2/\',function (responseText, textStatus, XMLHttpRequest){this;});}, ' . ($this -> vip_gethbinterval * 60 * 1000) . ');</script>';
			}
		}
	}

	public function show_redbaginfo2() {
		C('HTML_CACHE_ON', false);

		$userinfo = D("Member") -> find($_SESSION['uid']);
		if ($userinfo) {
			if ((int)$userinfo['vip'] > 0 && $userinfo['vipexpire'] > time()) {
				$maxdayfundhb = $this -> vip_maxdaygethb;
			} else {
				$maxdayfundhb = $this -> maxdaygethb;
			}

			if ($userinfo['lastfundtime'] == 0) {
				$userdayfund = 0;
			} else {
				if (date('Y-m-d', $userinfo['lastfundtime']) != date('Y-m-d', time())) {
					$userdayfund = 0;
					D("Member") -> execute('update ss_member set dayfund=0 where id=' . $_SESSION['uid']);
				} else {
					$userdayfund = $userinfo['dayfund'];
				}
			}

			if ($userdayfund < $maxdayfundhb) {
				echo '<div style="width:270px;height:38px;line-height:20px;margin-top:-10px;font-size:12px;"><span style="color:#cccccc;">您已累积</span><span style="font-weight:bold;color:#FF0000;" id="fundhb">' . ($userinfo['fundhb'] + 1) . '</span><span style="color:#cccccc;">个红包，点击送给主播1个红包<br>已送出</span><span style="font-weight:bold;color:#FF0000;" id="sendhb">' . $userinfo['sendhb'] . '</span><span style="color:#cccccc;">个红包</span></div>';
				D("Member") -> execute('update ss_member set fundhb=fundhb+1,lastfundtime=' . time() . ',dayfund=dayfund+1 where id=' . $_SESSION['uid']);
			} else {
				echo '<div style="width:270px;height:38px;line-height:20px;margin-top:-10px;font-size:12px;"><span style="color:#cccccc;">您已累积</span><span style="font-weight:bold;color:#FF0000;" id="fundhb">' . $userinfo['fundhb'] . '</span><span style="color:#cccccc;">个红包，点击送给主播1个红包<br>已送出</span><span style="font-weight:bold;color:#FF0000;" id="sendhb">' . $userinfo['sendhb'] . '</span><span style="color:#cccccc;">个红包</span></div>';
				echo '<script type="text/javascript" language="javascript">clearInterval(gethbinterval);</script>';
			}
		} else {
			echo '请<a href="#" onclick="javascript:UAC.openUAC(0); return false;" title="登录">登录</a>或<a href="#" onclick="javascript:UAC.openUAC(1); return false;" title="注册">注册</a>领取红包<br>&nbsp;';
		}
	}

	public function getcard() {
		$data = D("Member") -> where("id={$_SESSION['uid']}") -> find();
		$this -> ajaxReturn($data);
	}

	public function show_redbagrank() {
		C('HTML_CACHE_ON', false);

		$hbRank_day = D('Member') -> query('SELECT * FROM `ss_member` where gethb_day>0 and date_format(FROM_UNIXTIME(lastgethbtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") order by gethb_day desc LIMIT 10');
		$this -> assign('hbRank_day', $hbRank_day);
		$hbRank_week = D('Member') -> query('SELECT * FROM `ss_member` where gethb_week>0 and date_format(FROM_UNIXTIME(lastgethbtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(lastgethbtime),"%u")=date_format(now(),"%u") order by gethb_week desc LIMIT 10');
		$this -> assign('hbRank_week', $hbRank_week);
		$hbRank_month = D('Member') -> query('SELECT * FROM `ss_member` where gethb_month>0 and  date_format(FROM_UNIXTIME(lastgethbtime),"%m")=date_format(now(),"%m") order by gethb_month desc LIMIT 10');
		$this -> assign('hbRank_month', $hbRank_month);
		$hbRank_all = D('Member') -> query('SELECT * FROM `ss_member` where gethb>0 order by gethb desc LIMIT 10');
		$this -> assign('hbRank_all', $hbRank_all);

		$this -> display();
	}

	public function getucuid() {
		$ucuid = D("Member") -> field("ucuid") -> where("id={$_GET['uid']}") -> find();
		echo $ucuid['ucuid'];
	}

	public function get_gift_list() {
		//礼物 列表
		$liwulist = M() -> query("select * from ss_coindetail c  join ss_member m ON m.id = c.uid where c.action = 'sendgift'  order by c.addtime DESC limit 4");

		foreach ($liwulist as $key => $value) {
			$touser = M("member") -> where("id = {$liwulist[$key]['touid']} ") -> find();
			$giftname = M("gift") -> where("id = $liwulist[$key]['giftid']") -> find();

		}

		$str = "";
		foreach ($liwulist as $key => $value) {
			$time = date("H:i", $value['addtime']);
			$str .= "<li><b target='_blank'  style='color:#FFFFFF;'><em><span class='addtime'>{$time}</span>&nbsp;{$value['content']}</em></b></li>";
		}

		echo $str;

	}

	public function addFavor() {
		($_SESSION['uid'] <= 0 || $_SESSION == NULL) and die("非法操作");

		$uid = $_GET["uid"];
		$touid = $_GET['touid'];
		//判断是否已关注
		$res = M("favor") -> where("uid = $uid and favoruid = $touid") -> find();

		$res and die("3");
		//重复收藏

		$data["uid"] = $uid;
		$data["favoruid"] = $touid;
		$data['addtime'] = time();
		$res = M("favor") -> add($data);
		$res and die("1");
		//收藏成功
		echo "2";
		//收藏失败

	}

	public function cancelFavor() {
		($_SESSION['uid'] <= 0 || $_SESSION == NULL) and die("非法操作");

		$uid = $_GET["uid"];
		$touid = $_GET['touid'];

		$res = M("favor") -> where("uid = $uid &&  favoruid = $touid") -> delete();
		$res and die("1");
		//收藏成功
		echo "2";
		//收藏失败
	}

	public function queryFavor() {
		$uid = $_GET["uid"];
		$touid = $_GET['touid'];
		$res = M("favor") -> where("uid = $uid and favoruid = $touid") -> find();

		if ($res) {
			echo "1";
		} else {
			echo "0";
		}
	}

	/*
	 *@ 更新主播等级信息
	 *@ param eid 主播id
	 *@ return 主播进度条百分比,下个等级id,下个等级需要的秀币
	 */
	public function show_updateEmceeLevel() {
		$userinfo = null;
		if (isset($_GET['eid']) && (int)$_GET['eid'] > 0) {
			$userinfo = D('Member') -> find($_GET['eid']);
		} else {
			echo 'no';
		}
		if ($userinfo) {
			$att_count = M("attention") -> where("attuid = {$userinfo[id]}") -> count();
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$nextemceelevel = D("Emceelevel") -> where('levelid>' . $emceelevel[0]['levelid']) -> field('levelid,levelname,earnbean_low') -> order('levelid asc') -> select();
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$nextrichlevel = D("Richlevel") -> where('levelid>' . $richlevel[0]['levelid']) -> field('levelid,levelname,spendcoin_low') -> order('levelid asc') -> select();
			//进度条百分比
			$progress = $userinfo['earnbean'] / $nextemceelevel[0]['earnbean_low'] * 100;
			//下个等级
			$newLevel = $emceelevel[0]['levelid'];
			//距离下个等级还需要多少秀币
			$new_Levelcoin = $nextemceelevel[0]['earnbean_low'] - $userinfo['earnbean'];
			echo json_encode(array($progress, $newLevel, $new_Levelcoin, $emceelevel[0]['levelid'],$att_count));

		}
	}

	/*
	 * 作者：zhl
	 * 时间：2016-02-01 14:31:56
	 * 内容：定时获取守护
	 */
	
	public function updateGuard(){
		$User = D("Member");
		$userid = $User -> where('curroomnum=' . $_GET["roomid"] . '') -> getField("id");
		
		$guard_count = D('guard') -> where("anchorid = {$userid}") -> count();
        //查询已守护
        $guard_surplus = 20 - $guard_count;
		
		$condition['anchorid'] = $userid;
        $condition['maturitytime'] = array('gt', time());
        $guard = D('guard') -> where($condition) -> select();

        foreach ($guard as $key => $value) {
            $WholeGuard[] = M('member') -> where('id=' . $value['userid']) -> field('bigpic,nickname,ucuid') -> find();
		}
		$return_data=array('guard_count'=>$guard_count,'guard_surplus'=>$guard_surplus,'WholeGuard'=>$WholeGuard);
		$this->ajaxReturn($return_data);
	}

	/*
	 * 作者：zhl
	 * 时间：2016-02-01 15:03:04
	 * 内容：定时获取右上角排行
	 */
	public function updatePaihang(){
		
		$User = D("Member");
		$userid = $User -> where('curroomnum=' . $_GET["roomid"] . '') -> getField("id");
		
		$paihang_cachetime=60;
		
		$rixing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid where c.touid = {$userid} and date_format(FROM_UNIXTIME(c.addtime),'%m-%d-%Y')=date_format(now(),'%m-%d-%Y') group by c.uid order by gongxian DESC limit 6";
		$rixing=set_memcache("rixing",$rixing,$paihang_cachetime);

		
		$zhouxing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid where c.touid = {$userid} and date_format(FROM_UNIXTIME(c.addtime),'%Y')=date_format(now(),'%Y') and date_format(FROM_UNIXTIME(c.addtime),'%u')=date_format(now(),'%u') group by c.uid  order by gongxian DESC limit 6";
		$zhouxing=set_memcache("zhouxing",$zhouxing,$paihang_cachetime);


		$zongxing ="select *,sum(c.coin) gongxian from ss_coindetail c  join ss_member m ON m.id = c.uid   and c.touid = {$userid} group by c.uid order by gongxian DESC limit 6";
		$zongxing=set_memcache("zongxing",$zongxing,$paihang_cachetime);
		
		$paihang_data=array('rixing'=>$rixing,'zhouxing'=>$zhouxing,'zongxing'=>$zongxing);
		
		$this->ajaxReturn($paihang_data);
	}
		
	/*
	 * 作者：zhl
	 * 时间：2016-02-01 14:58:45
	 * 内容：定时获取库存礼物
	 */
	public function updateStock(){
		$Stock = D('prize p') -> where('p.uid = ' . $_SESSION['uid'].' and p.number !=0 ') -> join("ss_turntable t ON t.id = p.prizeID") -> select();
		
		$this->ajaxReturn($Stock);
	}
	
	public function closeRoom() {
		if (isset($_GET['eid'])) {

			$result = D('Member') -> where('id=' . $_GET['eid']) -> save(array('broadcasting' => 'n', 'canlive' => 'n'));
			if ($result) {
				echo 'success';
			}
		}
	}
	
	public function get_headlines() {
		$headlines_time=$this->site['headlines_time'];
		$headlines_money=$this->site['headlines_money'];
						
		$old=$_COOKIE['showheadlines'];

		$oldheadlines=json_decode($old,true); 		
							
		// 保护时间内  赠送超过的
		$condition2="coin > '$oldheadlines[coin]' and action='sendgift' and addtime >'$oldheadlines[addtime]'";
		$headlines2=M("coindetail")->where($condition2)->order("addtime desc")->limit("1")->select();	
		if($headlines2){
			$headlines=$headlines2;
		}else{			
			//未过保护时间			
			if(time()- $oldheadlines['addtime'] < $headlines_time*60){
				echo '';
				exit();	
			}else{
				// 保护时间过后  新头条
				$condition3="coin >= '$headlines_money' and action='sendgift' and addtime >'$oldheadlines[addtime]'";					
				$headlines=M("coindetail")->where($condition3)->order("addtime desc")->limit("1")->select();
			}	
		}
			 
		if($headlines){			
			//赠送人信息			
			$uidinfo=M("member")->field("nickname,spendcoin,earnbean")->find($headlines[0]['uid']);
			$richlevel = getRichlevel($uidinfo['spendcoin']);
			$uidinfo['richlecel']=$richlevel;			
			
			//受赠人信息
			$touidinfo=M("member")->field("nickname,spendcoin,earnbean,curroomnum")->find($headlines[0]['touid']);
			$emceelevel = getEmceelevel($touidinfo['earnbean']);
			$touidinfo['emceelevel']=$emceelevel;
			//礼物信息
			
			$giftinfo=M("gift")->field("giftname")->find($headlines[0]['giftid']);
			
			
			$headlines[0]['uidinfo']=$uidinfo;
			$headlines[0]['touidinfo']=$touidinfo;
			$headlines[0]['giftinfo']=$giftinfo;
			
			cookie('showheadlines',json_encode($headlines[0]),60*60*24);
			
		}else{
			// 没有新 满足条件的
			echo '';
			exit();
		}
        $headlinesresult=$headlines[0];
		echo '<a class="a gray" href="/'.$headlinesresult['touidinfo']['curroomnum'].'" target="_blank" id="first_runway_list">
                    		  				<span class="newtime">'.date("H:i",$headlinesresult['addtime']).'</span>
                    		  				<span class=" cracy cra'.$headlinesresult['uidinfo']['richlecel'][0]['levelid'].' star_shining"></span>
                    		  				<span class="text_hidden txcolor" title="'.$headlinesresult['uidinfo']['nickname'].'">'.$headlinesresult['uidinfo']['nickname'].'</span>
                    		  				<span class="time">送给</span>
                    		  				<span class="text_hidden txcolor" >'.$headlinesresult['touidinfo']['nickname'].'</span>
                    		  				<span class="time giftnames text_hidden" ">'.$headlinesresult['giftcount'].'个'.$headlinesresult['giftinfo']['giftname'].'</span>
                    		  				<img src="'.$headlinesresult['objectIcon'].'">                   		  		
                    		  			</a>';
	}


	//用于获取充值抽奖次数
	public function get_turntable_recharge_num()
	{
		$count = M('chargedetail')->where("uid = $_SESSION[uid] and receive != 'y' and rmb>=500")->count();
		M('chargedetail')->receive = 'y';
		M('chargedetail')->where("uid = $_SESSION[uid] and receive != 'y' and rmb>=500")->save();
		//先查询是否有过记录
		if(M('turntable_recharge_number')->where("uid = $_SESSION[uid]")->getField('id')==NULL)
		{
			M('turntable_recharge_number')->uid = $_SESSION['uid'];
			M('turntable_recharge_number')->number = 1;
			M('turntable_recharge_number')->update = time();
			M('turntable_recharge_number')->add();
		}
		else
		{
			M('turntable_recharge_number')->where("uid = $_SESSION[uid]")->setInc('number',$count);			
		}

		echo $count;
	}
	/**
     * 消费操作处理
     * @param int type消费类型 
     * @param Array detailInfo 消费详细信息
     * @return int token
	 */
	private function consumption($type,$detailInfo = "",$emceeInfo = ""){
	    $redis = connectionRedis();
		$random = md5(time().$_SESSION['uid'].$type);

		switch ($type) {
			case self::SENDGIFT:
				$msg = '{"prize":"' . $detailInfo['prize'] . '","giveprize":"' . $detailInfo['giveprize'] . '","luckgift":"' . $detailInfo['luckgift'] . '","giftPath":"' . $detailInfo['giftIcon_25'] . '","giftStyle":"' . $detailInfo['giftStyle'] . '","giftGroup":"' . $detailInfo['sid'] . '","giftType":"' . $detailInfo['giftType'] . '","toUserNo":"' . $emceeinfo['curroomnum'] . '","isGift":"0","giftLocation":"[]","giftIcon":"' . $detailInfo['giftIcon'] . '","giftSwf":"' . $detailInfo['giftSwf'] . '","toUserId":"' . $_REQUEST['toid'] . '","toUserName":"' . $emceeInfo['nickname'] . '","userNo":"' . $_SESSION['roomnum'] . '","giftCount":"' . $_REQUEST['count'] . '","userId":"' . $_SESSION['uid'] . '","giftName":"' . $detailInfo['giftName'] . '","userName":"' . $_SESSION['nickname'] . '","giftId":"' . $detailInfo['id'] . '"}'; 
				$redis -> set($random,$msg);
			
				break;
			case self::SENDSOFA:
			  
			     $msg = '{"message":" ' . $_SESSION['nickname'] . '花了' . $detailInfo['sofa_num'] . '个沙发抢座成功","seatId":"' . $detailInfo['seatId'] . '","seatPrice":"' . $detailInfo['seatPrice'] . '","userIcon":"' . $detailInfo['userIcon'] . '","userNick":"' . $_SESSION['nickname'] . '"}';
			    $redis -> set($random,$msg);
			    break;
			case self::SENDFLY:
			    $msg = time();
			    $redis -> set($random,$msg);
			    break;
			
			default:
			
				break;
		}
		$redis -> close();
		return $random;
		
	}
}
