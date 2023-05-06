<?php
class GameAction extends BaseAction {

	public function index() {

	}
	
	
	//礼物转盘游戏
	public function get_turntable() {
		if($_SESSION['uid']==NULL || $_SESSION['uid']<=0)
		{
			exit;
		}
		$award = M('turntable')->field('id,name,probability,giftIcon_25,price')->select();
		//var_dump($award);
		/*$award = array(
		// 奖品ID => array('奖品名称',概率)
		1 => array('5+观赛券', 0.05), 2 => array('黑色手机', 0.2), 3 => array('谢谢您', 0.1), 4 => array('白色手机', 0.1), 5 => array('谢谢您', 0.1), 6 => array('鼠标 U盘', 0.05), 7 => array('谢谢您', 0.05), 8 => array('5+观赛券', 0.15), 9 => array('鼠标 U盘', 0.15), 10 => array('谢谢您', 0.05), );*/

		$r = rand(1, 100);
		$num = 0;
		$award_id = 0;
		foreach ($award as $k => $v) {
			$tmp = $num;
			$num += $v['probability'] * 100;
			if ($r > $tmp && $r <= $num) {
				$award_id = $k;
				break;
			}
		}
		
		//扣费
		$userinfo = M('member')->find($_SESSION['uid']);
		
		$turntable_expense = M('siteconfig')->where("id = 1")->getField("turntable_expense");
		$needcoin = $turntable_expense;
		
		
		if($_SESSION['uid']==NULL || $_SESSION['uid']<=0)
		{
			echo '{error:"未登录"}';
			exit;
		}
		
		if($userinfo['coinbalance']<$needcoin)
		{
			echo '{error:"余额不足"}';
			exit;
		}
		
		D("Member")->execute('update ss_member set spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);
				//记入虚拟币交易明细
		$Coindetail = D("Coindetail");
		$Coindetail->create();
		$Coindetail->type = 'expend';
		$Coindetail->action = 'turntable_gift';
		$Coindetail->uid = $_SESSION['uid'];
		$Coindetail->touid = 0;
		$Coindetail->giftid = 5656;
		$Coindetail->giftcount = 1;
		$Coindetail->content = $userinfo['nickname'].'通过转盘得到'.$award[$award_id]['name'];
		$Coindetail->objectIcon = '/Public/images/zhuanpan_icon.jpg';
		$Coindetail->coin = $needcoin;

		$Coindetail->addtime = time();
		$detailId = $Coindetail->add();
		
		$prize = M('prize');
		$res = $prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->select();
		if($res==NULL)	//添加
		{	
			$prize -> uid = $_SESSION['uid'];
			$prize -> prizeID = $award[$award_id]['id'];
			$prize -> updatetime = time();
			$prize -> number = 1;
			$prize -> add();
			//echo M()->getLastSql();
		}
		else			//更新
		{
			$prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->setInc('number',1);
		}
		
		$this->jsonBack(array('award_id' => $award[$award_id]['id'], 'award_name' => $award[$award_id]['name'],"giftIcon_25" => $award[$award_id]['giftIcon_25']));


	}



	//金币转盘游戏
	public function get_turntable_coin() {
		if($_SESSION['uid']==NULL || $_SESSION['uid']<=0)
		{
			exit;
		}
		$award = M('turntable_coin')->field('id,name,probability')->select();
	
	//var_dump($award);

		$r = rand(1, 100);
		$num = 0;
		$award_id = 0;
		foreach ($award as $k => $v) {
			$tmp = $num;
			$num += $v['probability'] * 100;
			if ($r > $tmp && $r <= $num) {
				$award_id = $k;
				
				//var_dump($v);
				//var_dump($award_id);
				break;
			}
		}
		
		//扣费
		$userinfo = M('member')->find($_SESSION['uid']);
		
		$turntable_expense = M('siteconfig')->where("id = 1")->getField("turntableCoin_expense");
		$turntable_expense = $turntable_expense-$award[$award_id]['name'];
		$needcoin = $turntable_expense;
		
		if($_SESSION['uid']==NULL || $_SESSION['uid']<=0)
		{
			echo '{error:"未登录"}';
			exit;
		}
		
		if($userinfo['coinbalance']<$needcoin)
		{
			echo '{error:"余额不足"}';
			exit;
		}
		D("Member")->execute('update ss_member set spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);
				//记入虚拟币交易明细
		$Coindetail = D("Coindetail");
		$Coindetail->create();
		$Coindetail->type = 'expend';
		$Coindetail->action = 'turntable_coin';
		$Coindetail->uid = $_SESSION['uid'];
		$Coindetail->touid = 0;
		$Coindetail->giftid = 7878;
		//赢了多少
		$Coindetail->giftcount = $award[$award_id]['name'];
		$Coindetail->content = $userinfo['nickname'].'通过幸运币转盘获得'.$award[$award_id]['name'];
		$Coindetail->objectIcon = '/Public/images/zhuanpan_icon.jpg';
		//花了多少
		$Coindetail->coin = $needcoin;

		$Coindetail->addtime = time();
		$detailId = $Coindetail->add();
		
		$prize = M('prize');
		$res = $prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->select();
		if($res==NULL)	//添加
		{	
			$prize -> uid = $_SESSION['uid'];
			$prize -> prizeID = $award[$award_id]['id'];
			$prize -> updatetime = time();
			$prize -> number = 1;
			$prize -> add();
			//echo M()->getLastSql();
		}
		else			//更新
		{
			$prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->setInc('number',1);
		}
		

		
		$this->jsonBack(array('award_id' => $award[$award_id]['id'], 'award_name' => $award[$award_id]['name']));


	}


	//充值转盘游戏
	function get_turntable_charge()
	{
		if($_SESSION['uid']==NULL || $_SESSION['uid']<=0) exit;
			

		//判断抽奖次数
		$turntable_recharge_number = M('member m')->where("m.id = $_SESSION[uid]")->join("ss_turntable_recharge_number t on m.id = t.uid")->getField("t.number");
		if($turntable_recharge_number==NULL || $turntable_recharge_number==0)
		{
			echo '{"code":999}';
			exit();
		}

		//扣费
		$userinfo = M('member')->find($_SESSION['uid']);
		$award = M('turntable')->field('id,name,probability,giftIcon_25,price')->select();
		//var_dump($award);
		/*$award = array(
		// 奖品ID => array('奖品名称',概率)
		1 => array('5+观赛券', 0.05), 2 => array('黑色手机', 0.2), 3 => array('谢谢您', 0.1), 4 => array('白色手机', 0.1), 5 => array('谢谢您', 0.1), 6 => array('鼠标 U盘', 0.05), 7 => array('谢谢您', 0.05), 8 => array('5+观赛券', 0.15), 9 => array('鼠标 U盘', 0.15), 10 => array('谢谢您', 0.05), );*/

		$r = rand(1, 100);
		$num = 0;
		$award_id = 0;
		foreach ($award as $k => $v) {
			$tmp = $num;
			$num += $v['probability'] * 100;
			if ($r > $tmp && $r <= $num) {
				$award_id = $k;
				break;
			}
		}
		
		
		
		
		//更新更新库存
		
		$prize = M('prize');
		$res = $prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->select();
		if($res==NULL)	//添加
		{	

			$prize -> uid = $_SESSION['uid'];
			$prize -> prizeID = $award[$award_id]['id'];
			$prize -> updatetime = time();
			$prize -> number = 1;
			$prize -> add();
			//echo M()->getLastSql();
		}
		else			//更新
		{
			$prize->where("uid = $userinfo[id] and prizeID = ".$award[$award_id]['id'])->setInc('number',1);
		}
		
		//更新用户抽奖次数
		M('turntable_recharge_number')->where("uid = $_SESSION[uid]")->setDec('number',1);
		$this->jsonBack(array('award_id' => $award[$award_id]['id'], 'award_name' => $award[$award_id]['name'],"giftIcon_25" => $award[$award_id]['giftIcon_25']));
		
	}

	public function get_turntable_count()
	{
		$count = M('turntable')->count();
		echo $count;
	}
	
/*=======================赌注游戏开始=============================*/
	public function betting()
	{
		$config = M('siteconfig')->where("id = 1")->field('shieldWord')->find();
		//过滤词
		$shieldWord = $config['shieldWord'];
		//投注选择
		$beetingList = M('bettingset')->select();

		$userinfo = M('member')->where("id = $_SESSION[uid]")->find();

		if($userinfo['canlive'] == "y")
		{
			$this->error("主播禁止入内┑(￣Д ￣)┍");
		}
		
		//创建token------------------------------------
		$redis = connectionRedis();
		
		//创建token
		if($_SESSION['uid']==NULL || $_SESSION['uid'] <=0){
			$_SESSION['uid'] = -(rand(1000,9999));
			$userinfo['nickname'] = '游客-'.$_SESSION['uid'];
			$userinfo['id'] = $_SESSION['uid'];
		}
		
		if(isset($_SESSION['token'])){
        	$redis -> del($_SESSION['token']);
        }
		
		$rand = time() . $_SESSION['uid'];
		$userinfo['token'] = md5($rand);
		//40:管理,50:主播,30:普通
		if($_SESSION['uid'] < 0){
			$user_str = $this -> formatUserInfo($userinfo);
		}else{
				$userType = "30";
		}
		$roominfo = array(
			'roomnum' => "36"
		);
		$user_str = $this -> formatUserInfo($roominfo,$userinfo,$userType);
		
		$redis -> set($userinfo['token'],$user_str);
		
		//创建token结束------------------------------------
		
		//本期中奖名单

		$gid = M('bettingwinning')->order('id desc')->getField('id');
		$winningID = M('bettingwinning')->order('id desc')->getField('winningID');
		$beetingUser = M('bettingnote b')->where("b.GameNo=$gid and b.beetId= $winningID")->field('uid,coin,ucuid,nickname')->join("ss_member m on b.uid = m.id")->select();
		
		//免费票
		//$Freecount = D('bettingfreenum')->where("uid = $_SESSION[uid]")->getField('count');
		
		//$Freecount == null && $Freecount = 0;
		
		$beeCoin = M('beeconfig')->where('id = 1')->getField("coin");
		
		$this -> assign('beeCoin',$beeCoin);
		$this -> assign('Freecount',$Freecount);
		$this -> assign('gid',$gid);
		$this -> assign('beetingUser',$beetingUser);
		$this -> assign('shieldWord',$shieldWord);
		$this -> assign('beetingList',$beetingList);
		$this -> assign('userinfo',$userinfo);
		$this -> display();
	}
	
	//押注
	public function PostBetting()
	{
		
		$beetId = (int)$_GET['beetId'];
		$gid = M('bettingwinning')->order('id desc')->getField('id');
		$uid = $_SESSION['uid'];
		if($uid==NULL || $uid <=0)
		{
			$info['code'] = "0";
			$info['info'] = "登录后再操作";
			echo json_encode($info);
			exit;
		}
		$redis = connectionRedis();
		$info['code'] = "1";
		
		/*if($redis->get('bettingState')!="0")
		{
			$info['code'] = "0";
			$info['info'] = "客官，未到投注时间~";
			echo json_encode($info);
			exit;
		}*/
		$bettingnote = M('bettingnote');
		$bettingRes = $bettingnote->where("uid = $_SESSION[uid] and GameNo = $gid and beetId = $beetId")->field('id')->find();
		
		if($bettingRes)
		{
			$info['code'] = "0";
			$info['info'] = "已经投过注了";
		}
		else
		{
			
			$beeconfig = M('beeconfig')->where('id = 1')->find();
			$coinbalance = M('member')->where("id = $_SESSION[uid]")->getField('coinbalance');
			if($coinbalance<$beeconfig['coin'])
			{
				$info['code'] = "0";
				$info['info'] = "余额不足，需要".$beeconfig['coin']."币";
				echo json_encode($info);
				exit;
			}
			$bettingnote->uid = $_SESSION['uid'];
			$bettingnote->coin = D('bettingset')->where("id = $beetId")->getField('coin');
			$bettingnote->GameNo = $gid;
			$bettingnote->addtime = time();
			$bettingnote->beetId = $beetId;
			
			
			
			//记录押注信息
			$bettingName = M('bettingset')->where("id = $beetId")->getField('name');
			$beetingCoin = M('beeconfig')->where('id = 1')->getField('coin');
			$bettingnote->bettingName = $bettingName;//押注名称
			$bettingnote->beetingCoin = $beetingCoin;//押注金额
			
			
			$res = $bettingnote->add();
			if($res)
			{
				$info['code'] = "1";
				$info['info'] = "投注成功";
				
				
				$redis = connectionRedis();
				//判断是否有免费
		
				//$freeNum = D('bettingfreenum')->where("uid = $_SESSION[uid]")->getField('count');
				
				if(FALSE/*$freeNum && $freeNum>0*/)
				{//扣除免费票
					M('bettingfreenum')->where("uid = $_SESSION[uid]")->setDec('count',1);
				}
				else
				{
				
					//扣钱
					
					
					
					
					
					$beeconfig = D('beeconfig')->where('id = 1')->find();
					$coinbalance = M('member')->where("id = $_SESSION[uid]")->getField('coinbalance');
					if($coinbalance<$beeconfig['coin'])
					{
						$info['code'] = "0";
						$info['info'] = "余额不足，需要".$beeconfig['coin']."币";
					}

					$Coindetail = D("Coindetail");
					$Coindetail->type = 'expend';
					$Coindetail->action = 'Betting';
					$Coindetail->uid = $_SESSION['uid'];
					$Coindetail->touid = 0;
					$Coindetail->giftid = 99996;
					$Coindetail->giftcount = 1;
					$Coindetail->content = "投注花费".$beeconfig['coin']."秀豆";
					$Coindetail->objectIcon = '/Public/images/zhuanpan_icon.jpg';
					$Coindetail->coin = $beeconfig['coin'];
					
					$Coindetail->addtime = time();
					$detailId = $Coindetail->add();
					
					M("Member")->execute('update ss_member set spendcoin=spendcoin+'.$beeconfig['coin'].',coinbalance=coinbalance-'.$beeconfig['coin'].' where id='.$_SESSION['uid']);
				}
			}
			else
			{
				$info['code'] = "0";
				$info['info'] = "出错啦~稍后再试~";
			}

		}
		
		echo json_encode($info);
		
	}

	//新建投注
	public function AddNewBetting()
	{
		$redis = connectionRedis();
		if($redis->get('bettingState')=="0")
		{
			M('bettingwinning')->winningID = 0;
			M('bettingwinning')->addtime = time();
			M('bettingwinning')->add();

			echo "ok";
		}
			
	}
	
	//返回初始化信息
	
	public function initBetting()
	{
		$gid = M('bettingwinning')->order('id desc')->getField('id');
		
		$uid = $_SESSION['uid'];
		$listState = D('bettingnote')->where("GameNo = $gid and uid = $_SESSION[uid]")->field('id,beetid')->select();
		echo json_encode($listState);
	}
	
	//给用户返回信息	
	public function get_BettingUser()
	{
		$winningID = M('bettingwinning')->order('id desc')->getField('winningID');
		$res = M('bettingset')->where("id = $winningID")->find();
		$this->jsonBack(array('award_id' => $res['id'], 'award_name' => $res['name'],"giftIcon_25" => $res['giftIcon_25']));
	}


	public function get_BeetingServer()
	{
		$gid = M('bettingwinning')->order('id desc')->getField('id');
		$winningID = M('bettingwinning')->order('id desc')->getField('winningID');
		//取出中奖用户
		$beetingUser = M('bettingnote b')->where("b.GameNo=$gid and b.beetId= $winningID")->field('uid,coin,ucuid,nickname')->join("ss_member m on b.uid = m.id")->select();
		
		$winningID = M('bettingwinning')->order('id desc')->getField('winningID');
		$res = M('bettingset')->where("id = $winningID")->find();
		
		//$FreebeetingUser =  D('bettingnote b')->where("b.GameNo=$gid and b.beetId= $winningID")->field('uid,coin,ucuid,nickname')->join("ss_member m on b.uid = m.id")->select();
		
		$arrBeet[0] = $beetingUser;
		$arrBeet[1] = $gid;
		$arrBeet[2] = $res['name'];
		$arrBeet[3] = $FreebeetingUser;
		
		echo json_encode($arrBeet);
	}
	//返回中奖结果
	public function get_BettingInfo() {
		$redis = connectionRedis();
		
		$gid = M('bettingwinning')->order('id desc')->getField('id');
		$beetingInfo['code'] = '1';
		if($redis->get('bettingState')!="2")
		{
			$beetingInfo['code'] = "1";
			$beetingInfo['info'] ="非法操作";
		}
		$award = M('bettingset')->field('id,name,probability,giftIcon_25')->select();


		$r = rand(1, 100);
		$num = 0;
		$award_id = 0;
		foreach ($award as $k => $v) {
			$tmp = $num;
			$num += $v['probability'] * 100;
			if ($r > $tmp && $r <= $num) {
				$award_id = $k;
				break;
			}
		}

		//写入记录
		M('bettingwinning')->winningID = $award[$award_id]['id'];

		M('bettingwinning')->where("id = $gid")->save();
		$winningID = $award[$award_id]['id'];
		//取出中奖用户
		$beetingUser = M('bettingnote b')->where("b.GameNo=$gid and b.beetId= $winningID")->field('uid,coin,ucuid,nickname')->join("ss_member m on b.uid = m.id")->select();
		
		//免费再来一次
		//$freeNum = D('beeconfig')->where("id = 1")->getField('freeID');
		$FreebeetingUser = NULL;
		//$FreebeetingUser =  D('bettingnote b')->where("b.GameNo=$gid and b.beetId= $freeNum")->field('uid,coin,ucuid,nickname')->join("ss_member m on b.uid = m.id")->select();

		//加免费次数
		/*for($i = 0;$i<count($FreebeetingUser);$i++)
		{
			$uid =  $FreebeetingUser[$i]['uid'];
			$res = D('bettingfreenum')->where('uid = '.$uid)->find();

			if(!$res)
			{
				D('bettingfreenum')->uid = $uid;
				D('bettingfreenum')->updatetime = time();
				D('bettingfreenum')->count = 1;
				D('bettingfreenum')->add();
			}
			else
			{
				$res = D('bettingfreenum')->where('uid ='.$uid)->setInc('count',1);
			}
		}*/
		
		//给用户加钱
		for($i = 0;$i<count($beetingUser);$i++)
		{
			$uid =  $beetingUser[$i]['uid'];
			M('member')->where('id = '.$uid)->setInc("coinbalance",$beetingUser[$i]['coin']);
			
			//消费记录
			$Coindetail = D("Coindetail");
			$Coindetail->type = 'expend';
			$Coindetail->action = 'Betting';
			$Coindetail->uid = $_SESSION['uid'];
			$Coindetail->touid = 0;
			$Coindetail->giftid = 99996;
			$Coindetail->giftcount = 1;
			$Coindetail->content = "投注赚的".$beetingUser[$i]['coin']."秀豆";
			$Coindetail->objectIcon = '/Public/images/zhuanpan_icon.jpg';
			$Coindetail->coin = $beetingUser[$i]['coin'];
			
			$Coindetail->addtime = time();
			$detailId = $Coindetail->add();
			
			//更改中奖状态
			$bettingnote = D('bettingnote');
			
			$bettingnote->state = 1;
			$bettingnote->where("beetId = $winningID and GameNO = $gid")->save();
			$bettingnote->state = 2;
			$bettingnote->where("beetId != $winningID and GameNO = $gid")->save();
		}
		if($_GET['action']=="user")
		{	$arrBeet[0] = $beetingUser;
			$arrBeet[1] = $gid;
			$arrBeet[2] = $award[$award_id]['name'];
			$arrBeet[3] = $FreebeetingUser;
			echo json_encode($arrBeet);			
		}
		else
		{
			$this->jsonBack(array('award_id' => $award[$award_id]['id'], 'award_name' => $award[$award_id]['name'],"giftIcon_25" => $award[$award_id]['giftIcon_25']));
		}

	}


/*=======================赌注游戏结束=============================*/
	private function jsonBack($data) {
			header("Content-type: application/json");
			if (isset($_GET['callback'])) {
				echo $_GET['callback'] . "(" . json_encode($data) . ")";
			} else {
				echo json_encode($data);
			}
			exit();
	}

  //组装用户信息json字符串
    private function formatUserInfo($roominfo,$userinfo = null,$uType = "20"){
    	if($_SESSION['uid'] < 0){
			$user_str .= "{\"sign\":\"".$_SESSION['sign']."\",\"equipment\":\"pc\",\"nowroom\":\"".$roominfo['roomnum']."\",\"err\":\"no\",\"actBadge\":\"\",\"familyname\":\"\",\"goodnum\":\"{$_GET['roomnum']}\",\"h\":\"\",\"level\":\"0\",\"richlevel\":\"0\",\"spendcoin\":\"0\",\"sellm\":\"0\",\"sortnum\":\"200000000\",\"userType\":\"20\",\"userid\":\"{$_SESSION['uid']}\",\"username\":\"游客{$_SESSION['uid']}\",\"vip\":\"0\"";
			

			$user_str .=",\"fakeroom\":\"n\"}";
				
			return $user_str;
			
		}else{

			
			$richlevel  = getRichlevel($userinfo['spendcoin']);
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			
			$user_str .= "{\"sign\":\"".$_SESSION['sign']."\",\"equipment\":\"pc\",\"nowroom\":\"".$roominfo['roomnum']."\",\"err\":\"no\",\"actBadge\":\"\",\"familyname\":\"\",\"goodnum\":\"{$_GET['roomnum']}\",\"h\":\"{$userinfo['ucuid']}\",\"level\":\"{$emceelevel[0]['levelid']}\",\"richlevel\":\"{$richlevel[0]['levelid']}\",\"spendcoin\":\"{$userinfo['spendcoin']}\",\"sellm\":\"{$userinfo['sellm']}\",\"sortnum\":\"\",\"userid\":\"{$userinfo['id']}\",\"username\":\"{$userinfo['username']}\",\"vip\":\"0\"";
			
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

			$user_str .=",\"fakeroom\":\"n\"}";
			
			return $user_str;
			
		}
    }
	

}
