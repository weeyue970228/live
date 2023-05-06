<?php
class JiazuAction extends BaseAction {

	public function sqjoinfamily() {
		$familyid = $_GET['familyid'];
		$uid = $_SESSION['uid'];
	
		//判断用户是否登录
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {

			$this -> error('您尚未登录', "__URL__/index");
		}
		//根据用户ID查询出相关的信息
		$res = M("member") -> where("id=" . $uid) -> getField("emceeagent");
		if ($res == 'y') {
			$info="您拥有自己的家族，不允许加入其它家族";
			echo json_encode(array('status'=>2,'info'=>$info));
			exit;
		}
		//判断用户是否加入过家族
		$agentuid = M("member") -> where("id=" . $uid) -> getField("agentuid");
		if ($agentuid != '0') {
			
			$info="您已经加入过家族";
			echo json_encode(array('status'=>2,'info'=>$info));
			exit;
			
		}
		//判断用户是否已经提交过申请
		$sqinfo = M("sqjoinfamily") -> where("uid=" . $uid) -> order("sqtime desc") -> limit(1) -> select();
		
		$zhuangtai = $sqinfo[0]["zhuangtai"];
		//0:未审核;1:已通过；2：未通过；
		if ($zhuangtai == "0") {
			$info="您有一条申请记录正在审核中，请等待审核";
			echo json_encode(array('status'=>2,'info'=>$info));
			exit;	
			
		}
		//符合条件  插入申请记录
		$model = M("sqjoinfamily");
		$model ->uid = $uid;
		$model ->familyid = $familyid;
		$model ->sqtime = time();
		if ($model -> add()) {
			echo json_encode(array('status'=>2,'info'=>'申请提交成功，正在等待审核'));
			exit;
			
		} else {
			
			echo json_encode(array('status'=>1,'info'=>'失败'));
			exit;
		}

	}

	//申请成为代理城建家族
	public function sqagent() {
		$uid = $_SESSION['uid'];
		$step = $_GET['step'];

		//判断用户是否登录
		if (!$_SESSION['uid'] || $_SESSION['uid'] < 0) {

			$this -> error('您尚未登录', "__URL__/index");
		}

		//根据用户ID查询出相关的信息
		$res=M("member")->where("id=".$uid)->getField("emceeagent");
		if($res=='y'){
		$this->error("您已经创建过家族","__URL__/index");

		}

		//步骤
		if ($step == '') {
			$step = 1;
		}
		//申请之后的状态判断
		$sqzhuangtai = M("agentfamily") -> where("uid='$uid'") -> getField("zhuangtai");

		if ($sqzhuangtai == '未审核') {
			$step = 3;
		} else if ($sqzhuangtai == '已通过') {
			$step = 4;
		} else if ($sqzhuangtai == "未通过") {
			$this -> assign("zhuangtai", "对不起您的上次申请未通过，请认真填写！");
			$step = 2;
		}
		//申请提交

		if (!empty($_POST))
       {
		if($sqzhuangtai=='未审核'||$sqzhuangtai=='已通过'||$sqzhuangtai=='未通过')
			 {
			 	
			 }
			 else{
		
			//获取数据 保存
			$fmodel = M("agentfamily");
			import("ORG.Net.UploadFile");
			//实例化上传类
			$upload = new UploadFile();
			$upload -> maxSize = 3145728;
			//设置文件上传类型
			$upload -> allowExts = array('jpg', 'gif', 'png', 'jpeg');
			//设置文件上传位置
			$upload -> savePath = "./Public/Familyimg/";
			//这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹
			//设置文件上传名(按照时间)
			$upload -> saveRule = "time";
			if (!$upload -> upload()) {
				$this -> error($upload -> getErrorMsg());
			} else {
				//上传成功，获取上传信息
				$info = $upload -> getUploadFileInfo();
			}
			$savename = $info[0]['savename'];

			//var_dump($savename);

			$vo = $fmodel -> create();

			$fmodel -> familyimg = $savename;
			$fmodel -> sqtime = time();
			$fmodel -> curroomnum = time();
			$fmodel -> uid = $uid;

			if (!$vo) {
				$this -> error($fmodel -> getError());
			} else {
				$annId = $fmodel -> add();
				if ($annId) {
					//$this->success("添加成功！");
					$step = 3;
				} else {
					$this -> error("添加失败！");
				}

			}
		}
		}

		$F_creProCwidth = floor(($step - 1) / 3 * 100);

		$this -> assign("step", $step);
		$this -> assign("F_creProCwidth", $F_creProCwidth);

		$this -> display();
	}

	public function jiazunei() {
		$agentid = $_GET['agent'];
		$this -> assign("agentid", $agentid);
		//var_dump($agentid);
		//得到家族信息
		$familyinfo = M("agentfamily") -> where("uid='$agentid' && zhuangtai='已通过'") -> select();
		//var_dump($familyinfo);
		$this -> assign("familyinfo", $familyinfo);
		//最新加入家族的主播列表5人
		$new = M("sqjoinfamily") -> where("familyid='$agentid' && zhuangtai='1'") -> order("shtime desc") -> limit(5) -> select();
		$fix = C('DB_PREFIX');
		$field = "m.curroomnum,m.ucuid,m.nickname,sq.uid,sq.shtime";
		$newjoin = M('sqjoinfamily sq') -> field($field) -> join("{$fix}member m ON sq.uid=m.id") -> where("familyid='$agentid' && zhuangtai='1'") -> order("shtime desc") -> limit(5) -> select();
		
		$this -> assign("newjoin", $newjoin);

		//var_dump($new);
		//根据得到的id 来得到指定代理人的信息
		$agentinfo = M("member") -> where("id=$agentid") -> select();
		$this -> assign("agentinfo", $agentinfo);
		//得到当前主播的等级
		$agentlevel = getEmceelevel($agentinfo[0]['earnbean']);
		$this -> assign("agentlevel", $agentlevel);
		//当前代理下的 主播人数
		$total = M("member") -> where("agentuid=$agentid") -> count();
		$this -> assign("total", $total);
		$User = M('User');
		// 实例化User对象
		import('ORG.Util.Page');
		// 导入分页类

		$Page = new Page($total, 20);
		// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page -> show();
		// 分页显示输出
		//当前代理下的所有主播列表
		$emceeinfo = M("member") -> where("agentuid=$agentid") -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

		//得到主播的等级信息
		$a = 0;
		foreach ($emceeinfo as $k => $v) {
			$emceelevel = getEmceelevel($v['earnbean']);
			$emceeinfo[$a]['emceelevel'] = $emceelevel;
			$a++;
		}
		//var_dump($emceeinfo);
		$this -> assign("emceeinfo", $emceeinfo);
		$this -> assign("page", $show);
		//var_dump($show);

		//人气
		$rq = D('Liverecord') -> query("SELECT sum(entercount) as total FROM `ss_liverecord` where uid=$agentid");

		$rqtotal = $rq[0]["total"];

		$zbid = M("member") -> field("id") -> where("agentuid=$agentid") -> select();
		//var_dump($zbid);
		$a = 0;
		$uid = array();
		foreach ($zbid as $k => $v) {
			$uid[$a] = $v['id'];
			$a++;
		}
		//var_dump($uid);

		$a = 0;
		foreach ($zbid as $k => $v) {
			$emceeid = $v['id'];
			$emceerq = D('Liverecord') -> query("SELECT sum(entercount) as total FROM `ss_liverecord` where uid=$emceeid");
			//var_dump($emceerq);
			$rqtotal = $rqtotal + $emceerq[0][$total];
			$a++;

		}
		$this -> assign("rqtotal", $rqtotal);
		//var_dump($rqtotal);

		//当前代理下的在线人气主播主播
		$olrqzb = D('Member') -> where(" broadcasting='y' and isdelete='n' and agentuid=$agentid") -> field('nickname,curroomnum,bigpic,online,virtualguest,agentuid,online,snap') -> order('online desc') -> limit(4) -> select();
		//var_dump($olrqzb);
		$this -> assign("olrqzb", $olrqzb);
		//当前家族下的明星榜
		$emceeRank_month = D('Beandetail') -> query('SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 1');
		$emceeRank_month_new = array(); 
		$a = 0;
		foreach ($emceeRank_month as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$emceeRank_month_new[$a]['userinfo'] = $userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$emceeRank_month_new[$a]['emceelevel'] = $emceelevel;
				$a++;
			}
		}
		
		$this -> assign("emceeRank_month", $emceeRank_month_new);

		$emceeRank_month4 = D('Beandetail') -> query('SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 1,4');
		$emceeRank_month4_new = array();
		$a = 0;
		$b = 1;
		foreach ($emceeRank_month4 as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$emceeRank_month4_new[$a]['userinfo'] = $userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$emceeRank_month4_new[$a]['emceelevel'] = $emceelevel;
				$emceeRank_month4_new[$a]['xuhao'] = ($b + 1);
				$b++;
				$a++;
			}
		}

		$this -> assign("emceeRank_month4", $emceeRank_month4_new);
		$emceeRank_all = D('Beandetail') -> query('SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" group by uid order by total desc LIMIT 1');
		$emceeRank_all_new = array();
		$a = 0;
		foreach ($emceeRank_all as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$emceeRank_all_new[$a]['userinfo'] = $userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$emceeRank_all_new[$a]['emceelevel'] = $emceelevel;
				$a++;
			}
		}
		
		$this -> assign("emceeRank_all", $emceeRank_all_new);
		$emceeRank_all4 = D('Beandetail') -> query('SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" group by uid order by total desc LIMIT 1,4');
		$emceeRank_all4_new = array();
		$a = 0;
		$b = 1;
		foreach ($emceeRank_all4 as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$emceeRank_all4_new[$a]['userinfo'] = $userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$emceeRank_all4_new[$a]['emceelevel'] = $emceelevel;
				$emceeRank_all4_new[$a]['xuhao'] = ($b + 1);
				$b++;
				$a++;
			}
		}

		$this -> assign("emceeRank_all4", $emceeRank_all4_new);

		//当前家族下的富豪榜
		//查询出富豪月榜的前5条
		$richRank_month = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 1');
		$richRank_month_new = array();
		$a = 0;
		foreach ($richRank_month as $k => $vo) {
				
			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$richRank_month_new[$a]['userinfo'] = $userinfo;
				$richlevel = getRichlevel($userinfo['spendcoin']);
				$richRank_month_new[$a]['richlecel'] = $richlevel;
				$a++;
			}
		}

		$this -> assign("richRank_month", $richRank_month_new);
		$richRank_month4 = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 1,4');
		$richRank_month4_new = array();
		$a = 0;
		$b = 1;
		foreach ($richRank_month4 as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$richRank_month4_new[$a]['userinfo'] = $userinfo;
				$richlevel = getRichlevel($userinfo['spendcoin']);
				$richRank_month4_new[$a]['richlecel'] = $richlevel;
				$richRank_month4_new[$a]['xuhao'] = ($b + 1);
				$b++;
				$a++;
			}
		}
		
		$this -> assign("richRank_month4", $richRank_month4_new);
		$richRank_all = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" group by uid order by total desc LIMIT 1');
		$richRank_all_new = array();
		$a = 0;
		foreach ($richRank_all as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$richRank_all_new[$a]['userinfo'] = $userinfo;
				$richlevel = getRichlevel($userinfo['spendcoin']);
				$richRank_all_new[$a]['richlecel'] = $richlevel;
				$a++;
			}
		}

		$this -> assign("richRank_all", $richRank_all_new);
		$richRank_all4 = D('Coindetail') -> query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" group by uid order by total desc LIMIT 1,4');
		$richRank_all4_new = array();
		
		$a = 0;
		$b = 1;
		foreach ($richRank_all4 as $k => $vo) {

			$userinfo = D("Member") -> find($vo['uid']);
			if($userinfo['agentuid'] == $agentid){
				$richRank_all4_new[$a]['userinfo'] = $userinfo;
				$richlevel = getRichlevel($userinfo['spendcoin']);
				$richRank_all4_new[$a]['richlecel'] = $richlevel;
				$richRank_all4_new[$a]['xuhao'] = ($b + 1);
				$b++;
				$a++;
			}
		}

		$this -> assign("richRank_all4", $richRank_all4_new);

		$this -> display();
	}

	public function index() {

		//热门家族
		$memberModel =  M("member");
		$agentfamilyModel = M('agentfamily');
		
		$hotfamily = $memberModel -> query('select count(*) as total,agentuid from `ss_member` where agentuid>0 group by agentuid order by total desc limit 6');
		foreach ($hotfamily as $k => $v) {
			$familyinfo = $agentfamilyModel-> where("id='$v[agentuid]'") -> find();
			$agentinfo=$memberModel->field("nickname")->where("id='$familyinfo[uid]'")->find();
			$hotfamily[$k]['agentinfo'] = $agentinfo;
			$hotfamily[$k]['familyinfo'] = $familyinfo;
		}
		
		$this -> assign("hotfamily", $hotfamily);
		$this -> assign("zbcount", $zbcount);

		//最新主播代理三人
		//最新家族
		$res = $agentfamilyModel -> where("zhuangtai='已通过'") -> order("shtime desc") -> limit(3) -> select();
		foreach ($res as $k => $v) {
			$zbcount = $memberModel -> where("agentuid='$v[id]'") -> count();			
			$agentinfo=$memberModel->field("nickname")->where("id='$v[uid]'")->find();			
			$res[$k]['agentinfo'] = $agentinfo;
			$res[$k]['zbtotal'] = $zbcount;
		}
		$this -> assign("newagent", $res);
		

		
		$myfamilyinfo = $memberModel->where(array('id'=>session('uid')))->field('id ,ucuid, agentuid')->find();
		
        $myfamilyinfo['familyinfo']=$agentfamilyModel->where("id='$myfamilyinfo[agentuid]'")->find();
		$myfamilyinfo['jiazurenshu']  = $memberModel->where(array('agentuid'=>$myfamilyinfo['agentuid']))->count();
		$myfamilyinfo['emceeagent']  = $memberModel->where(array('sign'=>'y','agentuid'=>$myfamilyinfo['agentuid']))->count();

		//var_dump($myfamilyinfo);
		$this->assign('myfamilyinfo',$myfamilyinfo);
		
		//判断是否在 申请家族
		
		if($myfamilyinfo['agentuid']=='0'){
			 $sqfamily=$agentfamilyModel->where("uid=".$_SESSION['uid'])->find();
			
		}
        $this->assign('sqfamily',$sqfamily);

		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
		
		
		if(session('uid') > 0 ){
			$richlevelModel = M('richlevel');
			$userinfo = $memberModel->field("spendcoin,agentuid,emceeagent")->where(array('id'=>session('uid')))->find();

			$this->assign('userinfo',$userinfo);
			
			//$where['spendcoin_low'] = array('gt',)
			$level = $richlevelModel->where($userinfo['spendcoin'].' >= spendcoin_low and '.$userinfo['spendcoin'].' <= spendcoin_up ')->find();
			$this->assign('level',$level['levelid']);
		}
		

		$this -> display();
	}
	
	protected function publicheader(){
		$id=intval($_GET['id']);
		$agentfamilyModel = M('agentfamily');
		$memberModel = M('member');

		if(!$id){
			
			$id=$memberModel->where("id='".session('uid')."'")->getField("agentuid");
		}

		//家族人数
		$myfamily['jiazurenshu']=$memberModel->where("agentuid='$id'")->count();
		//主播人数
		$myfamily['emceeagent'] =$memberModel->where("agentuid='$id' and sign='y'")->count();
		//家族信息
		$myfamily['agentfamilyinfo'] =$agentfamilyModel->where("id='$id'")->find();

		$this->assign('myfamily',$myfamily);

	}
	public function familyMenu(){
		$memberModel = M('member');
		$myfamilyinfo = $memberModel->where(array('id'=>session('uid')))->field('id , agentuid ,nickname ,emceeagent')->find();
		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
		$this->assign('myfamilyinfo',$myfamilyinfo);
		$this->display();
	}

	public function family_detail(){
		if(session('uid') <= 0 ){
			$this->error('请登录');
		}
		if(!(int)$_GET['id']){
			$this->error('请选择家族');
		}
		$memberModel = M('member');
		$agentfamilyModel = M('agentfamily');

		$this->publicheader();
		//家族信息
		
		$familyinfo=$agentfamilyModel->find($_GET['id']);
		$this->assign('familyinfo',$familyinfo);
		//获取族长信息
		$zuzhanginfo=$memberModel->where("id='$familyinfo[uid]'")->field("nickname,id,ucuid")->find();
		$this->assign("zuzhanginfo",$zuzhanginfo);
		
		import('ORG.Util.Page');// 导入分页类


		if($_GET['p']){
			$p = (int)$_GET['P'];
		}else{
			$p = 0 ;
		}
		
		$info = $memberModel->where("agentuid='$_GET[id]' and broadcasting='y'")->field('id,nickname,agentuid,bigpic,curroomnum,online,virtualguest')->page($p.',12')->select();
		

		$count      = count($info);// 查询满足要求的总记录数
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出

		$this->assign('info',$info);
		$this->assign('page',$show);// 赋值分页输出
		
		
		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
		
		
		$is_joinagent_sign=M('member')->where("id=".session('uid'))->field('agentuid,emceeagent')->find();
		$this->assign('is_joinagent_sign',$is_joinagent_sign);


		$this -> display();	
	}
	public function family_gift(){
		
		
		if(session('uid') <= 0){
			$this->error('请登录');
		}
		$this->publicheader();
		$zuzhanginfo=M('member')->where("id=".session('uid'))->field("nickname,id,ucuid")->find();
		$this->assign("zuzhanginfo",$zuzhanginfo);
		
		$is_joinagent_sign=M('member')->where("id=".session('uid'))->field('agentuid,emceeagent')->find();
		$this->assign('is_joinagent_sign',$is_joinagent_sign);
		
		import('ORG.Util.Page');// 导入分页类
		$giftModel = M('gift');
		$memberModel = M('Member');
		
		$is_agent=M('member')->where("id=".session('uid'))->getField("emceeagent");
		
		//仅家族长可以管理
		if($is_agent=='y'){
			$agentuid=session('uid');
		}else{
			$agentuid=0;
		}
		
		if($agentuid<= 0){
			$this->error('仅家族长可以管理');
		}
		$agentfamilyModel = M('agentfamily');
		$giftModel = M('gift');
		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
		$coindetailModel = M('coindetail');
		//$jiazuid = $memberModel->where(array('id'=>session('uid')))->field('agentuid')->find();
		$jiazuid=$agentuid;
		$usersinfo = $memberModel->where(array('agentuid'=>$jiazuid))->field('agentuid,nickname,id')->select();
		
		$ids = getArrayKey($usersinfo,'id');
		$map['touid'] = array('in',implode(',', $ids));
		
		//家族无主播
		if(!$ids){
			$map['touid'] = array('lt',0);
		}
		//礼物id 1-99999间
		$map['giftid']=array('between','1,99998');
		
		
		if($_GET['p']){
			$p=$_GET['p'];
		}else{
			$p = 0 ;
		}
		if($_POST['search']){
			$map['content'] =array('like','%'.$_POST['search'].'%') ;
		}
		$count      = $coindetailModel->where($map)->count();// 查询满足要求的总记录数
		$coindetail = $coindetailModel->where($map)->page( $p.',25')->select();
		
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		foreach ($coindetail as &$value) {			
			$giftinfo = $giftModel->where(array('id'=>$value['giftid']))->find();
			$value['giftname'] = $giftinfo['giftname'] ;
			$uname = $memberModel->where(array('id'=>$value['uid']))->find();
			$value['uname'] = $uname['nickname'];
			$toname = $memberModel->where(array('id'=>$value['touid']))->find();
			$value['toname'] = $toname['nickname'] ;
		}
		$memberModel = M('member');
		$myid = $memberModel->where(array('id'=>session('uid')))->field('agentuid,id')->find();
		$this->assign('myid',$myid);
		$myfamilyinfo = $memberModel->where(array('id'=>session('uid')))->field('id , agentuid')->find();
		$this->assign('myfamilyinfo',$myfamilyinfo);
		$this->assign('show',$show);
		$this->assign('coindetail',$coindetail);
		$this -> display();	
	}
	public function family_live_data(){
		if(session('uid') <= 0 ){
			$this->error('请登录');
		}

		$zuzhanginfo=M('member')->where("id=".session('uid'))->field("nickname,id,ucuid")->find();
		$this->assign("zuzhanginfo",$zuzhanginfo);
		
		$is_joinagent_sign=M('member')->where("id=".session('uid'))->field('agentuid,emceeagent')->find();
		$this->assign('is_joinagent_sign',$is_joinagent_sign);
		
		$this->publicheader();
		$agentfamilyModel = M('agentfamily');
		$memberModel = M('member');
		
		$is_agent=M('member')->where("id=".session('uid'))->getField("emceeagent");
		
		//仅家族长可以管理
		if($is_agent=='y'){
			$agentuid=session('uid');
		}else{
			$agentuid=0;
		}
		
		if($agentuid<= 0){
			$this->error('请创建家族或申请主播');
		}
		$liverecordModel = M('liverecord');
		$myfamilyinfo = $memberModel->where(array('id'=>session('uid')))->field('id , agentuid ,nickname ,emceeagent')->find();
		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
		if($_POST['search']){
			$map2['nickname'] = $_POST['search'] ;

		}
		
		$map2['agentuid'] = $agentuid; ;
		$familypopel = $memberModel->where($map2)->field('nickname,id')->select();
		$popelid = getArrayKey($familypopel,'id');
		$popelid  = implode(',', $popelid);
		
		$map['uid'] =  array('in',$popelid);
		import('ORG.Util.Page');// 导入分页类
		$count      = $liverecordModel->where($map)->count();// 查询满足要求的总记录数

		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出

		$liverecordinfo = $liverecordModel->where($map)->select();
		
		$timetotal = 0 ;
		foreach ($liverecordinfo as &$value) {
			
			$userinfo = $memberModel->where(array('id'=>$value['uid']))->field('nickname')->find();
			
			$value['nickname'] = $userinfo['nickname'] ;
			if($value['endtime']){
				$timetotal +=($value['endtime'] - $value['starttime']);
			}else{
				$timetotal +=0;
			}
			
		}
		
		$timetotal=floor(($timetotal)/3600)."小时".floor(($timetotal)%3600/60)."分钟";
		
		$this->assign('timetotal',$timetotal);
		$this->assign('show',$show);
		$this->assign('liverecordinfo',$liverecordinfo);
		$this->assign('myfamilyinfo',$myfamilyinfo);

		$memberModel = M('member');
		$myid = $memberModel->where(array('id'=>session('uid')))->field('agentuid,id')->find();
		$this->assign('myid',$myid);
		$this -> display();	
	}
	public function family_personnel(){
		if(session('uid') <= 0 ){
			$this->error('请登录');
		}
		
		$zuzhanginfo=M('member')->where("id=".session('uid'))->field("nickname,id,ucuid")->find();
		$this->assign("zuzhanginfo",$zuzhanginfo);		
		
		$is_joinagent_sign=M('member')->where("id=".session('uid'))->field('agentuid,emceeagent')->find();
		$this->assign('is_joinagent_sign',$is_joinagent_sign);
		
		$this->publicheader();
		import('ORG.Util.Page');// 导入分页类
		$memberModel = M('member');
		$agentfamilyModel = M('agentfamily');
		
		$is_agent=M('member')->where("id=".session('uid'))->getField("emceeagent");
		
		//仅家族长可以管理
		if($is_agent=='y'){
			$agentuid=$is_joinagent_sign['agentuid'];
		}else{
			$agentuid=0;
		}
		
		if($agentuid['agentuid'] <= 0){
			$this->error('请创建家族或申请主播');
		}
		
		//获取主播人数
		$zhubo_count=M('member')->where("sign='y' and agentuid=".$agentuid)->count();
		$this->assign("zhubo_count",$zhubo_count);
		
		//获取群众人数
		$qunzhong_count=M('member')->where("sign='n' and agentuid=".$agentuid)->count();
		$this->assign("qunzhong_count",$qunzhong_count);
		
		if($_POST['search']){
			$map = array('nickname'=>$_POST['search'],'agentuid'=>$agentuid);
		}else{
			$map = array('agentuid'=>$agentuid) ;
		}
		$zuzhangid = $agentfamilyModel->where("id='".$agentuid."'")->getField("uid");
		
		$count      = $memberModel->where(array('agentuid'=>$agentuid['agentuid']))->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		if($_GET['p']){
			$p = $_GET['p'] ;
		}else{
			$p = 0 ;
		}

		$zhubo =$memberModel->field("id,nickname,lastlogtime,sign")->where($map)->page($p.',25')->select();
		
		//echo M()->getLastsql();
		foreach ($zhubo as &$value) {
			
			if($value['id'] == $zuzhangid){
				$value['zuzhang'] = 1 ;//族长
				continue ;
			}
			if($value['id'] != $zuzhangid && $value['sign']=='y'){
				$value['zuzhang'] = 2 ;//主播
				continue ;
			}
			if($value['id'] != $zuzhangid && $value['sign']=='n'){
				$value['zuzhang'] = 3 ;//群众
				continue ;
			}
		}
		
		
		$paihang = M()->query('SELECT COUNT(`agentuid`) AS tatol , agentuid , id FROM `ss_member` where agentuid!=0 GROUP BY agentuid ORDER BY tatol DESC LIMIT 5 ');
		
		foreach ($paihang as $k=>$v) {
			$familyinfo = $agentfamilyModel->where(array('id'=>$v['agentuid'],'zhuangtai'=>'已通过'))->find();
			$paihang[$k]['familyinfo'] = $familyinfo;
		}
		
		$this->assign('paihang',$paihang);
  //var_dump($zhubo);

		$this->assign('zhubo',$zhubo);
		$this->assign('info',$info);
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();	
	}

	public function delZhubo(){
		$id = $_POST['id'] ;
		$memberModel = M('member');
		$result = $memberModel->data(array('agentuid'=>'0','emceeagent'=>'n'))->where(array('id'=>(int)$id))->save();
		if($result !== false){
			M("sqjoinfamily")->where("uid='$id'")->delete();
			echo json_encode(array('stats'=>0,'error'=>'成功'));
			exit;
		}else{
			echo json_encode(array('stats'=>1,'error'=>'失败'));
			exit;
		}
	}
	public function del_admin(){
		$id = $_POST['id'] ;
		$memberModel = M('member');
		$result = $memberModel->data(array('isadmin'=>'0'))->where(array('id'=>(int)$id))->save();
		if($result !== false){
			echo json_encode(array('stats'=>0,'error'=>'成功'));
			exit;
		}else{
			echo json_encode(array('stats'=>1,'error'=>'失败'));
			exit;
		}
	}
	public function set_admin(){
		$id = $_POST['id'] ;
		$memberModel = M('member');
		$result = $memberModel->data(array('isadmin'=>'1'))->where(array('id'=>(int)$id))->save();
		if($result !== false){
			echo json_encode(array('stats'=>0,'error'=>'成功'));
			exit;
		}else{
			echo json_encode(array('stats'=>1,'error'=>'失败'));
			exit;
		}
	}		
	
	
	public function gonggao(){
		$sqjoinfamilyModel = M('sqjoinfamily');
		
		$result = $sqjoinfamilyModel->data(array('announcement'=>$_POST['content']))->where(array('id'=>(int)$_POST['familyid']))->save();
		//echo M()->getLastsql();
		if($result !== false){
			echo json_encode(array('stats'=>0,'error'=>'成功'));
			exit;
		}else{
			echo json_encode(array('stats'=>1,'error'=>'成功'));
			exit;
		}
	}
}
