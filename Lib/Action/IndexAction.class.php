<?php

class IndexAction extends BaseAction {
   private $cache_time = 60;
	//搜索房间
	public function searchroom() {
		$roomnum = $_GET['roomnum'];
		$res = M("member") -> where("curroomnum=" . $roomnum) -> select();

		if ($res == null) {
			echo json_encode("0");
			exit ;
		} else {
			echo json_encode("1");
			exit ;
		}
	}

	public function randenterroom() {
		$users = D('Member') -> where('') -> order('rand()') -> limit(1) -> select();
		if ($users) {
			header("Content-type: text/html; charset=utf-8");
			echo "<script>location.href='/" . $users[0]['curroomnum'] . "';</script>";
			exit ;
		} else {
			header("Content-type: text/html; charset=utf-8");
			echo "<script>alert('暂无房间');self.close();</script>";
			exit ;
		}
	}

	//首页
	public function index() {

		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：明星日排行
		 -->*/

		$emceeRank_day1 = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by uid order by total desc LIMIT 0,10';
		$emceeRank_day1 = set_memcache('emceeRank_day1', $emceeRank_day1, $this->cache_time);
		$a = 0;
		foreach ($emceeRank_day1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,broadcasting,earnbean") -> find($vo['uid']);
			$emceeRank_day1[$a]['userinfo'] = $userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_day1[$a]['emceelevel'] = $emceelevel;
			$a++;
		}
		$this -> assign("emceeRank_day1", $emceeRank_day1);

		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：明星周排行
		 -->*/
		$emceeRank_week1 = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by uid order by total desc LIMIT 0,10';
		$emceeRank_week1 = set_memcache('emceeRank_week1', $emceeRank_week1, $this->cache_time);
		foreach ($emceeRank_week1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,broadcasting,earnbean") -> find($vo['uid']);
			$emceeRank_week1[$k]['userinfo'] = $userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_week1[$k]['emceelevel'] = $emceelevel;

		}
		$this -> assign("emceeRank_week1", $emceeRank_week1);

		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：明星月排行
		 -->*/

		$emceeRank_month1 = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 0,10';
		$emceeRank_month1 = set_memcache('emceeRank_month1', $emceeRank_month1, $this->cache_time);

		foreach ($emceeRank_month1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,broadcasting,earnbean") -> find($vo['uid']);
			$emceeRank_month1[$k]['userinfo'] = $userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_month1[$k]['emceelevel'] = $emceelevel;

		}
		$this -> assign("emceeRank_month1", $emceeRank_month1);

		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：明星总排行
		 -->*/
		$emceeRank_all1 = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" group by uid order by total desc LIMIT 0,10';
		$emceeRank_all1 = set_memcache('emceeRank_all1', $emceeRank_all1, $this->cache_time);

		foreach ($emceeRank_all1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,broadcasting,earnbean") -> find($vo['uid']);
			$emceeRank_all1[$k]['userinfo'] = $userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_all1[$k]['emceelevel'] = $emceelevel;

		}

		$this -> assign("emceeRank_all1", $emceeRank_all1);

		//富豪榜
		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：富豪日榜
		 -->*/
		$richRank_day1 = 'SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by uid order by total desc LIMIT 0,10';
		$richRank_day1 = set_memcache('richRank_day1', $richRank_day1, $this->cache_time);

		foreach ($richRank_day1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,spendcoin,broadcasting") -> find($vo['uid']);
			$richRank_day1[$k]['userinfo'] = $userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_day1[$k]['richlevel'] = $richlevel;

		}

		$this -> assign("richRank_day1", $richRank_day1);

		/*<!--
		 作者：364751598@qq.com
		 时间：2015-07-03
		 描述：富豪周榜
		 -->*/
		$richRank_week1 = 'SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by uid order by total desc LIMIT 0,10';
		$richRank_week1 = set_memcache('richRank_week1', $richRank_week1, $this->cache_time);

		foreach ($richRank_week1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,spendcoin") -> find($vo['uid']);
			$richRank_week1[$k]['userinfo'] = $userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_week1[$k]['richlevel'] = $richlevel;

		}

		$this -> assign("richRank_week1", $richRank_week1);

		$richRank_month1 = 'SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 0,10';
		$richRank_month1 = set_memcache('richRank_month1', $richRank_month1, $this->cache_time);

		foreach ($richRank_month1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,spendcoin") -> find($vo['uid']);
			$richRank_month1[$k]['userinfo'] = $userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_month1[$k]['richlevel'] = $richlevel;

		}

		$this -> assign("richRank_month1", $richRank_month1);

		//查询出富豪总榜的前5条
		$richRank_all1 = 'SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" group by uid order by total desc LIMIT 10';
		$richRank_all1 = set_memcache('richRank_all1', $richRank_all1, $this->cache_time);

		foreach ($richRank_all1 as $k => $vo) {

			$userinfo = D("Member") -> field("ucuid,curroomnum,nickname,spendcoin") -> find($vo['uid']);
			$richRank_all1[$k]['userinfo'] = $userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_all1[$k]['richlevel'] = $richlevel;

		}

		$this -> assign("richRank_all1", $richRank_all1);

		//热门家族推荐
		$zbcount = M("member") -> field("count(*) as total,agentuid") -> where("agentuid>0") -> group("agentuid") -> order("total desc") -> limit(5) -> select();

		$family = array();

		foreach ($zbcount as $k => $v) {
			$aid = $v['agentuid'];
			$agentinfo = M("agentfamily") -> where("id=$aid") -> find();
			$family[$k] = $agentinfo;
			$family[$k]['total'] = $v['total'];
			$family[$k]['zz'] = M("member")->cache(true, $this->cache_time, 'Redis')->field("ucuid")->where("id='$agentinfo[uid]'")->find();	
		}
		$this -> assign("family", $family);

		//主播分类
		$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();

		foreach ($usersorts as $n => $val) {
			$usersorts[$n]['voo'] = D("Usersort") -> where('parentid=' . $val['id']) -> order('orderno') -> select();
		}
		$this -> assign('usersorts', $usersorts);
		//轮播
		$rollpics = D('Rollpic') -> cache(true, $this->cache_time, 'Redis') -> where('') -> field('picpath,title,linkurl') -> order('orderno asc') -> limit(3) -> select();
		$this -> assign('rollpics', $rollpics);

		//获取推荐会员列表  //new 9
		$recommend1 = M("member") -> cache(true, $this->cache_time, 'Redis') -> field('curroomnum,snap,virtualguest,online,nickname') -> where("recommend = 'y' and recommendno> '0'") -> limit(7)->order('recommendno desc')  -> select();
		if ($recommend1) {
			$recommend = $recommend1;

		} else {
			$recommend = M("member") -> cache(true, $this->cache_time, 'Redis') -> field('curroomnum,snap,virtualguest,online,nickname') -> where("recommend = 'y'") -> limit(7)-> select();
		}

		//查询出等级

//		foreach ($recommend as $k => $vo) {
//
//			$emceelevel = getEmceelevel($vo['earnbean']);
//			$recommend[$k]["emceelevel"] = $emceelevel[0]['levelid'];
//			if ($vo['broadcasting'] == 'y') {
//				$recommend[$k]['live_state'] = "<em class='png24 live-tip' style='height:17px;width:54px;'><img src='/Public/images/Play.jpg' /></em>";
//			} else if ($vo['offlinevideo'] != null && $vo['offlinevideo'] != "") {
//				$recommend[$k]['live_state'] = "<em class='png24 live-tip' style='height:17px;width:54px;'><img src='/Public/images/luxiang.png' /></em>";
//			}
//
//		}

		//如果未获取到在线主播信息

		$this -> assign("recommend", $recommend);

		//在线
		$on_live = M("member") -> field('id,curroomnum,snap,virtualguest,online,nickname') -> where("broadcasting = 'y'") -> limit(15) -> select();
//		foreach($on_live as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$on_live[$k]['fans']=$count;
//		}		
		$this -> assign("on_live", $on_live);

		//调取公告
		$announce = M("announce") -> cache(true, $this->cache_time, 'Redis') -> field('id,title') -> order("addtime") -> limit(9) -> select();
		$this -> assign("announce", $announce);


		//直播预告
		$where = " canlive='y' and  date_format(FROM_UNIXTIME(starttime),'%m-%Y')=date_format(now(),'%m-%Y') and  date_format(FROM_UNIXTIME(starttime),'%H%i')>date_format(now(),'%H%i')";
		$preview = M("member") -> field("curroomnum,nickname,ucuid,starttime") -> where($where) -> order("date_format(FROM_UNIXTIME(starttime),'%H%i') asc") -> limit("4") -> select();
		$this -> assign("preview", $preview);

		//新人
		
		$newAnchor = M("member") -> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> where("broadcasting = 'y'") -> limit(6) -> order('regtime desc') -> select();
//		foreach($newAnchor as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$newAnchor[$k]['fans']=$count;
//		}
	
		$this -> assign("newAnchor", $newAnchor);
		
		//热门主播
		
		$hot = M("member") -> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> where("broadcasting = 'y'") -> limit(15) -> order('(online+virtualguest) desc') -> select();
//		foreach($hot as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$hot[$k]['fans']=$count;
//		}		
		$this -> assign("hot", $hot);
				

		$this -> display();
	}

	public function listEmceeCategoreis() {

		$virtualcount = D('Member') -> where('isvirtual="y"') -> count();
		$onlinecount = 0;
		$onlineemcee = D('Member') -> field('online,virtualguest') -> where('online>0') -> select();
		foreach ($onlineemcee as $val) {
			if ($val['broadcasting'] == "y") {
				if ($val['virtualguest'] > 0) {
					$onlinecount = $onlinecount + $val['online'] + $val['virtualguest'] + $virtualcount;
				} else {
					$onlinecount = $onlinecount + $val['online'];
				}
			} else {
				$onlinecount = $onlinecount + $val['online'];
			}
		}
		$this -> assign('onlinecount', $onlinecount);

		$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();
		foreach ($usersorts as $n => $val) {
			$usersorts[$n]['voo'] = D("Usersort") -> where('parentid=' . $val['id']) -> order('orderno') -> select();
		}
		$this -> assign('usersorts', $usersorts);

		$this -> display();
	}

	public function listEmceeCategoreis2() {
		$virtualcount = D('Member') -> where('isvirtual="y"') -> count();
		$onlinecount = 0;
		$onlineemcee = D('Member') -> field('online,virtualguest,broadcasting') -> where('online>0') -> select();
		foreach ($onlineemcee as $val) {
			if ($val['broadcasting'] == "y") {
				if ($val['virtualguest'] > 0) {
					$onlinecount = $onlinecount + $val['online'] + $val['virtualguest'] + $virtualcount;
				} else {
					$onlinecount = $onlinecount + $val['online'];
				}
			} else {
				$onlinecount = $onlinecount + $val['online'];
			}
		}
		$this -> assign('onlinecount', $onlinecount);

		$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();
		foreach ($usersorts as $n => $val) {
			$usersorts[$n]['voo'] = D("Usersort") -> where('parentid=' . $val['id']) -> order('orderno') -> select();
		}
		$this -> assign('usersorts', $usersorts);

		$this -> display();
	}

	public function listIndexEmcee_majorType() {
		if ($_GET['type'] == 'hot') {
			$users = D('Member') -> where('broadcasting="y"') -> field('nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest') -> order('online desc') -> limit(100) -> select();
			$this -> assign('users', $users);
			$this -> display();
			exit ;
		}

		if ($_GET['sid'] != '') {
			$sortinfo = D("Usersort") -> find($_GET['sid']);
			$this -> assign('sortinfo', $sortinfo);
			$condition = 'sid=' . $_GET['sid'];
		}

		if ($sortinfo['sortname'] == '特约') {

			$users = D('Member') -> where('fakeuser="y"') -> order('rand()') -> limit(20) -> select();

			$this -> assign('users', $users);
		} else {
			$users = D('Member') -> where($condition . ' and broadcasting="y"') -> field('nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest') -> order('online desc') -> limit(100) -> select();
			$this -> assign('users', $users);
		}

		$this -> display();
	}

	public function listEmceeByGrade() {
		if ($_GET['sid'] != '') {
			$sortinfo = D("Usersort") -> find($_GET['sid']);
			$this -> assign('sortinfo', $sortinfo);
			$condition = 'sid=' . $_GET['sid'];
		}

		if ($sortinfo['sortname'] == '特约') {

			$users = D('Member') -> where('fakeuser="y"') -> order('rand()') -> limit(20) -> select();

			$this -> assign('users', $users);
		} else {
			$users = D('Member') -> where($condition . ' and broadcasting="y"') -> field('nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest') -> order('online desc') -> limit(100) -> select();
			$this -> assign('users', $users);
		}

		$this -> display();
	}

	public function listEmceeByGrade2() {
		$num = $_GET['num'];
		$get_order = $_GET['order'];
		$get_map1 = $_GET['setmap1'];
		$get_province = $_GET['province'];
		if ($get_province != "" && $get_province != "所有地区") {
			$map['province'] = $get_province;
		}

		if ($get_order == "d") {
			$order = "id";
		} else {
			$order = 'broadcasting DESC';
		}
		if ($num == null)
			$num = 0;

		if ($get_map1 != "0" && $get_map1 != null) {
			//调取等级区间
			$earnbean_low = M("emceelevel") -> where("levelid = $get_map1") -> getField("earnbean_low");
			$earnbean_up = M("emceelevel") -> where("levelid = $get_map1") -> getField("earnbean_up");
			$map['earnbean'] = array("BETWEEN", "$earnbean_low,$earnbean_up");

		}

		if ($_GET['sid'] != '') {
			$sortinfo = D("Usersort") -> find($_GET['sid']);
			$this -> assign('sortinfo', $sortinfo);
			$map['sid'] = $_GET['sid'];
		}

		if ($sortinfo['sortname'] == '特约') {

			$users = D('Member') -> where('fakeuser="y"') -> order('rand()') -> limit(15) -> select();

			$this -> assign('users', $users);
		} else {
			$users = D('Member') -> where($map) -> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> order($order) -> limit("$num,15") -> select();
			
//		foreach($users as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$users[$k]['fans']=$count;
//		}

			$this -> assign('users', $users);
		}
		if ($users == NULL) {
			exit();
		}
		$this -> display();
	}

	public function findEmcees_ajaxFlag() {
		$user = D("Member");
		$count = $user -> where('bigpic<>"" and recommend="y" and broadcasting="y"') -> count();
		$listRows = 9;
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$users = $user -> where('bigpic<>"" and recommend="y" and broadcasting="y"') -> limit($p -> firstRow . "," . $p -> listRows) -> order('rand()') -> select();
		$this -> assign('users', $users);
		$this -> assign('count', $count);

		$this -> display();
	}
	
	public function xiuchang(){
		$users = D('Member') -> where("id!='0' and size!='2' and broadcasting='y'")-> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> limit("0,15") -> select();
		
//		foreach($users as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$users[$k]['fans']=$count;
//		}
				
		$this -> assign('users', $users);
	    $this -> display("listEmceeByGrade2");
	}
	public function youxi(){
		$users = D('Member') -> where("id!='0' and size='2' and broadcasting='y'")-> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> limit("0,15") -> select();
		
//		foreach($users as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$users[$k]['fans']=$count;
//		}
			
		$this -> assign('users', $users);
	    $this -> display("listEmceeByGrade2");
	}
	
	public function concern(){
		$attention=M("attention")->field("attuid")->where("uid='$_SESSION[uid]'")->select();
		
		foreach($attention as $k=>$v){			
			$touid.=$v['attuid'].',';
		}
		$touid.='0';
	
		
		$users = D('Member') -> where("id in ($touid)")-> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> order("broadcasting desc") -> limit("0,6") -> select();
		
//		foreach($users as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$users[$k]['fans']=$count;
//		}
				
		$this -> assign('users', $users);
	    $this -> display("listEmceeByGrade3");
	}
	
	public function seen(){
	
		$seen=M("seen")->field("touid")->where("uid='$_SESSION[uid]'")->select();

		foreach($seen as $k=>$v){			
			$touid.=$v['touid'].',';
		}
		$touid.='0';
	
		$users = D('Member') -> where("id in ($touid)")-> field('id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting') -> order("broadcasting desc") -> limit("0,6") -> select();
		
//		foreach($users as $k=>$v){			
//			$count=M("attention") -> cache(true, $this->cache_time, 'Redis')->where("attuid='$v[id]'")->count();
//			$users[$k]['fans']=$count;
//		}

		$this -> assign('users', $users);
	    $this -> display("listEmceeByGrade3");
	}		
}
