<?php

class GuardAction extends BaseAction {

	public function index() {
		
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			$this -> assign('jumpUrl', __APP__);
			$this -> error('您没有登录，请登录');

		} else {
			$this -> assign('showid', $_GET['id']);
			$this -> display();
		}
	}

	public function buTool() {
		C('HTML_CACHE_ON', false);
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {
			echo '{"msg":"请重新登录"}';
			exit ;
		}
		$userinfo = M('Member') -> find($_SESSION['uid']);
		if ($_GET['toolsubid'] == 1) {
			$needcoin = 20000;
			$duration = 3600 * 24 * 30 * 1;
			$duration1 = "1个月";
		} elseif ($_GET['toolsubid'] == 2) {
			$needcoin = 40000;
			$duration = 3600 * 24 * 30 * 3;
			$duration1 = "3个月";
		} elseif ($_GET['toolsubid'] == 3) {
			$needcoin = 70000;
			$duration = 3600 * 24 * 30 * 6;
			$duration1 = "6个月";
		} elseif ($_GET['toolsubid'] == 4) {
			$needcoin = 130000;
			$duration = 3600 * 24 * 30 * 12;
			$duration1 = "12个月";
		}

		//守护限制20人 2015-09-17 杨
		$anchorinfo = D('Member') -> where('curroomnum=' . $_GET['toolid']) -> find();
		$anchor_state = M("guard") -> where("anchorid = {$anchorinfo['id']} and userid = {$_SESSION['uid']}") -> find();
		if ($anchor_state == null || $anchor_state['maturitytime'] < time())//如果现在不是守护状态执行
		{

			$count = D('guard') -> where("anchorid = {$anchorinfo['id']}") -> count();
			//查询已守护
			if ($count >= 20) {
				echo '{"msg":"当前守护人数已满！"}';
				exit ;
			}
		}

		if ($userinfo['coinbalance'] < $needcoin) {
			echo '{"msg":"余额不足,请充值"}';
			exit ;
		} else {
			// $data['']
			D("Member") -> execute('update ss_member set spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);
			$userinfo = M('Member') -> where('id=' . $_SESSION['uid']) -> find();
			if ($userinfo['Daoju9expire'] < time()) {
				D('Member') -> execute('update ss_member set Daoju9="y",Daoju9expire=' . (time() + $duration) . ' where id=' . $_SESSION['uid']);
			} else {
				D('Member') -> execute('update ss_member set Daoju9="y",Daoju9expire=Daoju9expire+' . $duration . ' where id=' . $_SESSION['uid']);
			}
			

			$condition['anchorid'] = $anchorinfo['id'];
			$condition['userid'] = $_SESSION['uid'];
			$condition['_logic'] = "and";
			//当前时间小于守护到期时间（守护没有过期）
			if ($guardinfo = M('guard') -> where($condition) -> select()) {
				if (time() < $guardinfo[0]['maturitytime']) {
					D('guard') -> execute('update ss_guard set maturitytime=maturitytime+' . $duration . ' where anchorid=' . $anchorinfo['id']);
					//写入消费明细
					$Coindetail = D("Coindetail");
					$Coindetail -> create();
					$Coindetail -> type = 'expend';
					$Coindetail -> action = 'buy';
					$Coindetail -> uid = $_SESSION['uid'];
					$Coindetail -> giftcount = 1;
					$Coindetail -> content = '您购买了 ' . $duration1 . ' 守护';
					$Coindetail -> objectIcon = '/Public/images/shou.png';
					$Coindetail -> coin = $needcoin;
					$Coindetail -> addtime = time();
					$detailId = $Coindetail -> add();
					echo '{"msg":"购买成功"}';
				} else {
					D('guard') -> execute('update ss_guard set maturitytime=' . time() + $duration . ' where anchorid=' . $_GET['toolid']);
					//写入消费明细
					$Coindetail = D("Coindetail");
					$Coindetail -> create();
					$Coindetail -> type = 'expend';
					$Coindetail -> action = 'buy';
					$Coindetail -> uid = $_SESSION['uid'];
					$Coindetail -> giftcount = 1;
					$Coindetail -> content = '您购买了 ' . $duration1 . ' 守护';
					$Coindetail -> objectIcon = '/Public/images/shou.png';
					$Coindetail -> coin = $needcoin;
					$Coindetail -> addtime = time();
					$detailId = $Coindetail -> add();
					echo '{"msg":"购买成功"}';
				}
			} else {
				// echo '{"msg":"xx44444xxx"}';
				$data['cleartime'] = time();
				$data['maturitytime'] = time() + $duration;
				$data['anchorid'] = $anchorinfo['id'];
				$data['userid'] = $_SESSION['uid'];
				D('guard') -> add($data);
				//写入消费明细
				$Coindetail = D("Coindetail");
				$Coindetail -> create();
				$Coindetail -> type = 'expend';
				$Coindetail -> action = 'buy';
				$Coindetail -> uid = $_SESSION['uid'];
				$Coindetail -> giftcount = 1;
				$Coindetail -> content = '您购买了 ' . $duration1 . '守护';
				$Coindetail -> objectIcon = '/Public/images/shou.png';
				$Coindetail -> coin = $needcoin;
				$Coindetail -> addtime = time();
				$detailId = $Coindetail -> add();
				echo '{"msg":"购买成功"}';
			}

			//给主播已分成

			$scale = D('Member')->field("sharingratio") -> getByid($anchorinfo['id']);
			//取出改主播的信息
			if ($scale['sharingratio'] != '0') {//优先按照指定的比例算
				$beannum = ceil($needcoin * ($scale['sharingratio'] / 100));
			} else {//默认的比例
				$beannum = ceil($needcoin * ($this -> emceededuct / 100));
			}
			
			D("Member") -> execute('update ss_member set earnbean=earnbean+' . $beannum . ',beanbalance=beanbalance+' . $beannum . ' where id=' . $anchorinfo['id']);
			$Beandetail = D("Beandetail");
			$Beandetail -> create();
			$Beandetail -> type = 'income';
			$Beandetail -> action = 'getgift';
			$Beandetail -> uid = $anchorinfo['id'];
			$Beandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 购买守护 ';
			$Beandetail -> bean = $beannum;
			$Beandetail -> addtime = time();
			$detailId = $Beandetail -> add();

			if ($anchorinfo['agentuid'] != 0) {
				$beannum = ceil($needcoin * ($this -> emceeagentdeduct / 100));
				//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$emceeinfo['agentuid']);
				D("Member") -> execute('update ss_member set beanbalance2=beanbalance2+' . $beannum . ' where id=' . $anchorinfo['agentuid']);
				$Emceeagentbeandetail = D("Emceeagentbeandetail");
				$Emceeagentbeandetail -> create();
				$Emceeagentbeandetail -> type = 'income';
				$Emceeagentbeandetail -> action = 'getgift';
				$Emceeagentbeandetail -> uid = $anchorinfo['agentuid'];
				$Emceeagentbeandetail -> content = $userinfo['nickname'] . ' 向 ' . $emceeinfo['nickname'] . ' 购买守护 ';
				$Emceeagentbeandetail -> bean = $beannum;
				$Emceeagentbeandetail -> addtime = time();
				$detailId = $Emceeagentbeandetail -> add();

			}
		}
	}

}
?>