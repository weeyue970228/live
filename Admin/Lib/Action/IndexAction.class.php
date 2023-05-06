<?php
class IndexAction extends Action {
	
	//后台菜单管理
	public function admin_menu_manage_list(){
		$adminmenu=M('adminmenu')->where("dev_hidden=1")->order("id asc")->select();
		$this->assign('adminmenu',$adminmenu);
		$this->display();
	}
	//后台菜单添加
	public function add_admin_menu_manage(){
		$first_menu=M('adminmenu')->where("parentid=0 and dev_hidden=1")->order("id asc")->select();
		$this->assign("first_menu",$first_menu);
		$this->display();
	}
	//后台菜单添加动作
	public function do_add_admin_menu_manage(){
			
		//添加的菜单种类
		$menutype=htmlspecialchars(trim($_POST['menutype']));
		$menu_data['menuname']=htmlspecialchars(trim($_POST['menuname']));
		$menu_data['url']=htmlspecialchars(trim($_POST['url']));
		$menu_data['position']=trim($_POST['position']);
		$menu_data['is_show']=htmlspecialchars(trim($_POST['is_show']));
		$menu_data['addtime']=time();
		$menu_data['dev_hidden']=1;
		$adminmenu=M('adminmenu');
		
		if($menutype==1){
			
			$menu_data['url']='';
			$menu_data['parentid']=0;
				
		}elseif($menutype==2){
			$menu_data['url']='';
			$menu_data['parentid']=htmlspecialchars(trim($_POST['parentid']));
			
		}elseif($menutype==3){
			$menu_data['parentid']=htmlspecialchars(trim($_POST['twoid']));
		}else{
			$this->error("添加菜单失败，请选择要添加的菜单类型！");
		}
		
		
		$res=$adminmenu->add($menu_data);
		
		if($res){
			$this->success("添加菜单成功！");
		}else{
			$this->error("添加菜单失败！");
		}
		
	}



	public function opt_close() 
	{
		
		$dao = D("Close");
		$username=$dao->where("id=$_GET[userid]")->getField("username");
		switch ($_GET['action']) 
		{
			case 'disaudit' :
				if ($_GET['userid'] != '') {
					$dao -> query('update ss_close set enabled="0" where id=' . $_GET['userid']);
				}
				userLog(session('adminid'), "修改注册用户状态为禁止，用户名：".$username);
				$this -> assign('jumpUrl', base64_decode($_REQUEST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
			case 'audit' :
				if ($_GET['userid'] != '') {
					$dao -> query('update ss_close set enabled="1" where id=' . $_GET['userid']);
				}
				userLog(session('adminid'), "修改注册用户状态为启用，用户名：".$username);
				$this -> assign('jumpUrl', base64_decode($_REQUEST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		
		
		}
	}

public function admin_close()
{
	    $condition = 'id>0';
	    $orderby = 'id desc';
		$goodnum = D("Close");
		$count = $goodnum -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$goodnums = $goodnum -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition)  -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('goodnums', $goodnums);

		$this -> display();
	
}
//秀币转盘消费排行榜
public function admin_xiubitongji()
{
	$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}
		}
		$beandetail = D("Coindetail");
		$condition .= ' and giftid="7878" ' ;
		$count = $beandetail -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$orderby = 'sum(coin) desc';
		$details = $beandetail -> field('id,uid,touid,sum(coin) as coin,sum(giftcount) as giftcount')->limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> group("uid")->select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		
		
		$this -> display();
}
//礼物转盘消费排行榜
public function admin_liwutongji()
{
	 $condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}
		}
		$beandetail = D("Coindetail");
		$condition .= ' and giftid="5656" ' ;
		$count = $beandetail -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		if($_GET['start_time'] != ''&&$_GET['end_time'] != '')
		{
		$orderby = 'sum(coin) desc';
		$details = $beandetail -> field('id,uid,touid,sum(coin) as coin,count(uid) as content')->limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> group("uid")->select();
       //var_dump($details);
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);
//	    $shijian= date("Y-m-d h:i:s");
//		$_GET['shijian']=date("Y-m-d h:i:s");
		}
		else
			{
	    $orderby = 'id desc';
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);  
			}
		$this -> display();

}
//砸蛋游戏中奖排行榜

  public function admin_famehall()
  {
	  
	 $condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') 
		{
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and touid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}
		}
		$beandetail = D("Giveaway");
		$orderby = 'sum(content) desc';	
		$condition .= ' and remark="砸蛋奖励" ' ;
		$count = $beandetail -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> field('id,uid,touid,sum(content) as content,count(id) as count')->limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> group("touid")->select();
		foreach ($details as $n => $val) 
		{
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['touid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);  
		$this -> display();
  }



//==================
	//后台菜单编辑
	public function edit_admin_menu_manage(){
		$id=$_GET['id'];
		
		
		$menu=M('adminmenu')->where("id=$id")->select();
		
		$menu_data=$menu[0];
		
		//菜单种类区分
		//一级菜单 parentid=0 url=''
		//二级菜单 parentid>0 url=''
		//三级菜单 parentid>0 url!=''
		if($menu_data['parentid']==0 && $menu_data['url']==''){
			$menutype=1;
		}elseif($menu_data['parentid']>0 && $menu_data['url']==''){
			$menutype=2;
		}elseif($menu_data['parentid']>0 && $menu_data['url']!=''){
			$menutype=3;		
		}
		
		if($menutype==1){
			$first_id=$id;
			$two_id=0;
		}elseif($menutype==2){
			$first_id=$menu_data['parentid'];
			$two_id=$id;
		}elseif($menutype==3){
			$first_id=M('adminmenu')->where("id=$menu_data[parentid]")->getField("parentid");
			$two_id=$menu_data['parentid'];
		}
		$first_menu=M('adminmenu')->where("parentid=0 and url=''")->order("id ASC")->select();
		$two_menu=M('adminmenu')->where("parentid>0 and url=''")->order("id ASC")->select();	
		
		$this->assign("menu",$menu_data);
		$this->assign("first_menu",$first_menu);
		$this->assign("two_menu",$two_menu);
		$this->assign("id",$id);
		$this->assign("first_id",$first_id);
		$this->assign("two_id",$two_id);
		$this->assign("menutype",$menutype);
	
		$this->display();
	}
	//后台菜单编辑动作
	public function do_edit_admin_menu_manage(){
		$id=htmlspecialchars(trim($_POST['id']));
		$menu_data['is_show']=htmlspecialchars(trim($_POST['is_show']));
		$menu_data['menuname']=htmlspecialchars(trim($_POST['menuname']));
		$menu_data['position']=trim($_POST['position']);
		
		$res=M('adminmenu')->where("id=$id")->save($menu_data);
		
		if($res){
			$this->success("菜单修改成功");
		}else{
			$this->error("菜单修改失败");
		}
	}
	//获取二级菜单（url为空）
	public function get_two_adminmenu(){
		$first_menu=$_POST['first_menu'];
		
		$two_menu=M('adminmenu')->where("parentid=$first_menu and dev_hidden=1")->select();
		$this->ajaxReturn($two_menu);
	}
	//家族代理申请相关
	public function del_sqagent() {
		$sqid = $_GET['sqid'];
		$res = M("agentfamily") -> where("id=" . $sqid) -> delete();
		if ($res) {
			userLog(session('adminid'), "删除家族");
			$this -> success("删除成功");
		} else {
			$this -> error("删除失败");
		}
	}
	//审核家族
	public function edit_sqagent() {
		$sqid = $_GET['sqid'];
		$uid = M("agentfamily") -> where("id=" . $sqid) -> getField('uid');
		$fix = C('DB_PREFIX');
		$field = "m.nickname,m.earnbean,af.*";
		$sqinfo = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> where("m.id=" . $uid) -> select();
		$emceelevel = getEmceelevel($sqinfo[0]['earnbean']);
		$sqinfo[0]['emceelevel'] = $emceelevel;
		$this -> assign("sqinfo", $sqinfo);

		$this -> display();
	}
	//审核家族动作
	public function do_edit_sqagent() {
		
		//根据接收到的信心更新数据库 需要更新 agentfamily表中的状态字段 以及member 表中的emceeagent字段
		$zhuangtai = $_POST['zhuangtai'];
		
		$afmodel = M("agentfamily");
		$mmodel = M("member");
		if (!empty($_POST)) {
			if ($afmodel -> create()) {
				$afmodel -> id = $_POST['id'];
				$afmodel -> shtime = time();
				$afmodel -> zhuangtai = $zhuangtai;
				

				if ($afmodel -> save()) {

					$mmodel -> id = $_POST['uid'];
					if ($zhuangtai == "已通过") {
						$mmodel -> emceeagent = "y";
					} else {

						$mmodel -> emceeagent = "n";
					}

					$mmodel -> emceeagenttime = time();
					$mmodel -> agentuid = $_POST['id'];
					if ($mmodel -> save()) {
						userLog(session('adminid'), "审核家族");
						$this -> success("审核成功");
					} else {
						$this -> error("审核失败，重新审核");
					}

				} else {
					$this -> error("审核失败");
				}
			} else {
				$this -> error($afmodel -> getError());
			}

		}
	}
	//未审核家族列表
	public function admin_sqagentwsh() {
		$count = M("agentfamily") -> where("zhuangtai='未审核'") -> count();
		//使用联合查询带分页 查询出申请用户的相关信息
		import("@.ORG.Page");
		$p = new Page($count, 20);
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$fix = C('DB_PREFIX');
		$field = "m.nickname,m.earnbean,af.*";
		$res = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> where("zhuangtai='未审核'") -> limit($p -> firstRow . "," . $p -> listRows) -> select();
		//根据查到的earnbean 查询用户等级
		$a = 0;
		foreach ($res as $k => $vo) {
			$emceelevel = getEmceelevel($vo['earnbean']);
			$res[$a]['emceelevel'] = $emceelevel;
			$a++;
		}
		$this -> assign("page", $page);
		$this -> assign("lists", $res);
		$this -> display();
	}
	//审核通过的家族列表
	public function admin_sqagentpass() {

		$dao = D("agentfamily");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$anninfo = $dao -> getById($array[$i]);
						if ($anninfo) {
								$m = M("member");
					$m->agentuid = 0;
					$m->where('agentuid=' .$anninfo['uid'])->save();
							
							$xiugai = M("member");
							$xiugai -> emceeagent = "n";
							$res2 = $xiugai -> where("id=" . $anninfo['uid']) -> save();
							$dao -> where('id=' . $array[$i]) -> delete();
						}
					}
				}
				userLog(session('adminid'), "删除家族");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				exit ;
				break;
		}

		$count = M("agentfamily") -> where("zhuangtai='已通过'") -> count();
		//使用联合查询带分页 查询出申请用户的相关信息
		import("@.ORG.Page");
		$p = new Page($count, 20);
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$fix = C('DB_PREFIX');
		$field = "m.nickname,m.earnbean,af.*";
		$res = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> where("zhuangtai='已通过'") -> limit($p -> firstRow . "," . $p -> listRows) -> select();
		//根据查到的earnbean 查询用户等级
		$a = 0;
		foreach ($res as $k => $vo) {
			$emceelevel = getEmceelevel($vo['earnbean']);
			$res[$a]['emceelevel'] = $emceelevel;
			$a++;
		}
		$this -> assign("page", $page);
		$this -> assign("lists", $res);
		$this -> display();

	}
	//审核未通过的家族列表
	public function admin_sqagentnopass() {
		$count = M("agentfamily") -> where("zhuangtai='未通过'") -> count();
		//使用联合查询带分页 查询出申请用户的相关信息
		import("@.ORG.Page");
		$p = new Page($count, 20);
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$fix = C('DB_PREFIX');
		$field = "m.nickname,m.earnbean,af.*";
		$res = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> where("zhuangtai='未通过'") -> limit($p -> firstRow . "," . $p -> listRows) -> select();
		//根据查到的earnbean 查询用户等级
		$a = 0;
		foreach ($res as $k => $vo) {
			$emceelevel = getEmceelevel($vo['earnbean']);
			$res[$a]['emceelevel'] = $emceelevel;
			$a++;
		}
		$this -> assign("page", $page);
		$this -> assign("lists", $res);
		$this -> display();
	}
   //所有家族成员列表
   public function admin_clansman()
   {
   	 $condition = 'id>0'; 
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and shtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and shtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入家族名字') 
		{
			
			$keyuinfo = D("agentfamily") -> where('familyname="' . $_GET['keyword'] . '"') -> select();
			$keyuinfom =D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and familyid=' . $keyuinfo[0]['uid'];
			
			} 
			else if($keyuinfom)
			{
				$condition .= ' and uid=' . $keyuinfom[0]['id'];
			}
			else {
				$this -> error('没有该家族的记录');
			}
		}
		$beandetail = D("Sqjoinfamily");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
	    $orderby = 'id desc';
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) 
		{
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();	
		}
		foreach ($details as $n => $val)
		{
		$details[$n]['vi'] = D("agentfamily") -> where('uid=' . $val['familyid']) -> select();
		}
		switch ($_GET['action']) {
			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$anninfo = M("Sqjoinfamily") -> getById($array[$i]);
						if ($anninfo) {
							M("Sqjoinfamily") -> where('id=' . $array[$i]) -> delete();
							M("agentfamily") -> where('uid=' . $array[$i]) -> delete();
						}
					}
				}
				userLog(session('adminid'), "删除家族");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				exit ;
				break;
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);  
		$this -> display();
   }
	//活动页面轮播管理
	public function admin_huodongrollpic() {
		$hdrollpics = M("huodongrollpic") -> where("") -> order('orderno') -> select();
		$this -> assign("hdrollpics", $hdrollpics);
		$this -> display();
	}
	//修改活动页面轮播
	public function save_huodongrollpic() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/huodongrollpic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/huodongrollpic/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		$Edit_Linkurl = $_POST['linkurl'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			M("huodongrollpic") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			M("huodongrollpic") -> execute('update ss_huodongrollpic set picpath="' . $Edit_Picpath[$i] . '",linkurl="' . $Edit_Linkurl[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}
		if ($_POST['add_orderno'] != '' && $rollpicpath != '' && $_POST['add_linkurl'] != '') {
			$Rollpic = M("huodongrollpic");
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> linkurl = $_POST['add_linkurl'];
			$Rollpic -> addtime = time();

			$rollpicID = $Rollpic -> add();

		}
		userLog(session('adminid'), "修改活动页面轮播");
		$this -> assign('jumpUrl', __URL__ . "/admin_huodongrollpic/");
		$this -> success('操作成功');
	}

	//活动分类管理
	public function del_huodongfenlei() {
		$fenleiid = $_GET["fenleiid"];

		$res = M("announce") -> where("fid=" . $fenleiid) -> select();

		if (!empty($res)) {
			$this -> error("请先删除当前分类下的文章！");

		} else {
			$del = M("huodongfenlei") -> where("id=" . $fenleiid) -> delete();
			if ($del) {
				userLog(session('adminid'), "删除活动分类");
				$this -> success("删除成功！");
			} else {
				$this -> error("删除失败！");
			}
		}
	}
	//编辑活动分类
	public function edit_huodongfenlei() {
		$fenleiid = $_GET["fenleiid"];
		$res = M("huodongfenlei") -> where("id=" . $fenleiid) -> find();

		$hmodel = M("huodongfenlei");
		if (!empty($_POST)) {
			if ($hmodel -> create()) {
				if ($hmodel -> save()) {

					$this -> assign('jumpUrl', __URL__ . "/admin_huodongfenlei/");
					$this -> success('修改成功');
				} else {
					$this -> error("修改失败！");
				}
			} else {
				$this -> error($hmodel -> getError());
			}
		}
		$this -> assign("fenlei", $res);
		$this -> display();
	}
	//活动分类查看
	public function admin_huodongfenlei() {
		//查询出所有的活动分类
		$res = M("huodongfenlei") -> select();
		$this -> assign("huodongfenleis", $res);

		$this -> display();
	}
	//添加活动分类
	public function add_huodongfenlei() 
	{
        
		$hmodel = M("huodongfenlei");
		if (!empty($_POST)) 
		{
			if (!empty($_POST['title'])) 
			{
				if ($hmodel -> create()) 
				{
					$hmodel -> addtime = time();
					if ($hmodel -> add()) 
					{
						userLog(session('adminid'), "添加活动分类");
						$this -> assign('jumpUrl', __URL__ . "/admin_huodongfenlei/");
		                $this -> success('添加成功');
					} 
					else 
					{
						$this -> error("添加失败");
					}
				} 
				else 
				{
					$this -> error($hmodel -> getError());
				}
			} 
			else 
			{
				$this -> error("分类标题不能为空！");
			}
		}

		$this -> display();
	}


	function _initialize() {
		C('HTML_CACHE_ON', false);

		$curUrl = base64_encode($_SERVER["REQUEST_URI"]);
		if ($_SESSION['lock_screen'] == 1 && !strpos($_SERVER["REQUEST_URI"], 'login')) {
			session('manager', null);
			session('lock_screen', 0);
			session('trytimes', 0);

			$this -> assign('jumpUrl', __URL__ . "/login/return/" . $curUrl);
			$this -> error('请登录后操作');
		}

		if (!strpos($_SERVER["REQUEST_URI"], 'login') && !strpos($_SERVER["REQUEST_URI"], 'verify') && !strpos($_SERVER["REQUEST_URI"], 'logout') && !$_SESSION['manager']) {
			$this -> assign('jumpUrl', __URL__ . "/login/return/" . $curUrl);
			$this -> error('请登录后操作');
		}
	}

	// 空操作定义
	public function _empty() {
		$this -> assign('jumpUrl', __URL__ . '/mainFrame');
		$this -> error('此操作不存在');
	}
	//验证码
	public function verify() {
		import("ORG.Util.Image");
		ob_clean();
		Image::buildImageVerify(4, 1, 'png', 130, 50);
	}
	//登录
	public function login() {
		
		//验证ip,禁止则会返回1
		if(checkLoginIp()){
			$this->assign('banip',1);
		}else{
			$this->assign('banip',0);
		}
		
		if ($_GET['return'] != '') {
			$this -> assign('returnurl', $_GET['return']);
		}
		$this -> display();
	}
	//登陆验证当前账户状态
	public function login_check(){
		
	
		$username=htmlspecialchars(trim($_POST['username']));
		$password=md5(htmlspecialchars(trim($_POST['password'])));
		
		$status=M('admin')->where("adminname='" . $username . "' and password='" . $password . "'")->getField("status");
		
		$this->ajaxReturn($status);
	}
	//登录动作
	public function dologin() {
		
		if (md5($_POST['code']) != $_SESSION['verify']) {
			$this -> error('验证码错误,请检查!', __URL__ . '/login/');
		}
		if($_POST['userstatus']==='0'){
			$this -> error('当前用户已被禁用，请联系管理员处理!', __URL__ . '/login/');
		}
		
		$username = $_POST["username"];
		$password = md5($_POST["password"]);

		$adminDao = D('Admin');
		$admin = $adminDao -> where("adminname='" . $username . "' and password='" . $password . "'") -> select();
		if ($admin) {
			//写入本次登录时间及IP
			$adminDao -> execute('update ss_admin set lastlogtime=' . time() . ',lastlogip="' . get_client_ip() . '" where id=' . $admin[0]['id']);
			
			//获取当前用户的用户组权限
			$grouprules=M('admin_group')->where("id=".$admin[0]['usergroup'])->getField("grouprules");
			$groupname=M('admin_group')->where("id=".$admin[0]['usergroup'])->getField("groupname");
			//写入SESSION
			session('adminid', $admin[0]['id']);
			session('adminname', $_POST["username"]);
			session('manager', 'y');
			session('grouprules',$grouprules);
			session('groupname',$groupname);
			
			//写入登录日志
			
			userLog($admin[0]['id'],"登录后台");
			

			if ($_POST['next_action'] != '') {
				$this -> assign('jumpUrl', base64_decode($_POST['next_action']));
			} else {
				$this -> assign('jumpUrl', __URL__);
			}
			$this -> success('登录成功');
		} else {
			$this -> error('用户名或密码错误,请重新登录');
		}
	}
	//退出登录
	function logout() {
		session('adminid', null);
		session('adminname', null);
		session('manager', null);
		$this -> assign('jumpUrl', __URL__ . '/login/');
		$this -> success('退出成功');
	}
	//后台主页
	public function index() {
		
		//验证ip,禁止的会跳到首页
		if(checkLoginIp()){
			
			header("location: http://".$_SERVER['HTTP_HOST']);
			exit;
		}

		//根据用户权限展示菜单
		$grouprules=session('grouprules');
		$adminmenus=M('adminmenu')->where("parentid=0 AND is_show=1 AND dev_hidden=1 AND id in(".$grouprules.")")->order("id ASC")->select();
		
		$adminqmenus = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid']) -> order('addtime') -> select();
		$this -> assign("adminqmenus", $adminqmenus);
		//查询出授权站点用户名
		$siteconfig = M("siteconfig") -> find(1);
		$username = $siteconfig['username'];
		$this -> assign("username", $username);
		$this -> assign("adminmenus", $adminmenus);

		$this -> display();
	}
	//后台左侧边栏
	public function leftFrame() {
		
		$grouprules=session('grouprules');
		$adminmenus = D("Adminmenu") -> where("parentid=" . $_GET['menuid']." AND is_show=1 AND dev_hidden=1 and id in(".$grouprules.")") -> order('id') -> select();
		
		foreach ($adminmenus as $n => $val) {
			$adminmenus[$n]['voo'] = D("Adminmenu") -> where('is_show=1 AND dev_hidden=1 and parentid=' . $val['id']) -> order('id') -> select();

		}

		if ($_GET['menuid'] == 1) {
			$adminqmenus = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid']) -> order('addtime') -> select();
			$this -> assign("adminqmenus", $adminqmenus);
		}

		$this -> assign("adminmenus", $adminmenus);

		$this -> display();
	}
	//后台主框架
	public function mainFrame() {
		$admin = D("Admin") -> find($_SESSION["adminid"]);
		$this -> assign('admin', $admin);
		$adminqmenus = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid']) -> order('addtime') -> select();
		$this -> assign("adminqmenus", $adminqmenus);

		$this -> display();
	}

	public function public_map() {
		$adminmenus = D("Adminmenu") -> where("parentid=0") -> order('id') -> select();
		foreach ($adminmenus as $n => $val) {
			$adminmenus[$n]['voo'] = D("Adminmenu") -> where('parentid=' . $val['id']) -> order('id') -> select();
			foreach ($adminmenus[$n]['voo'] as $n2 => $val2) {
				$adminmenus[$n]['voo'][$n2]['voo2'] = D("Adminmenu") -> where('parentid=' . $val2['id']) -> order('id') -> select();
			}
		}
		$this -> assign("adminmenus", $adminmenus);
		$this -> display();
	}

	public function public_current_pos() {
		$menu = D("Adminmenu") -> find($_GET["menuid"]);
		if ($menu) {
			echo $menu['position'];
		}
	}

	public function public_ajax_add_panel() {
		$menu = D("Adminmenu") -> find($_POST["menuid"]);
		if ($menu) {
			$qmenu = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid'] . " and menuid=" . $_POST["menuid"]) -> select();
			if (!$qmenu && $menu['url'] != '') {
				$qmenu = D("Adminqmenu") -> execute("insert into ss_adminqmenu(adminid,menuid,menuname,url,addtime) values(" . $_SESSION['adminid'] . "," . $_POST["menuid"] . ",'" . $menu['menuname'] . "','" . $menu['url'] . "'," . time() . ")");
			}
		}

		$adminqmenus = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid']) -> order('addtime') -> select();
		foreach ($adminqmenus as $n => $val) {
			echo "<span><a onclick='paneladdclass(this);' target='right' href='" . $val['url'] . "'>" . $val['menuname'] . "</a>  <a class='panel-delete' href='javascript:delete_panel(" . $val['menuid'] . ");'></a></span>";
		}
	}

	public function public_ajax_delete_panel() {
		D("Adminqmenu") -> where('adminid=' . $_SESSION["adminid"] . ' and menuid=' . $_POST["menuid"]) -> delete();

		$adminqmenus = D("Adminqmenu") -> where("adminid=" . $_SESSION['adminid']) -> order('addtime') -> select();
		foreach ($adminqmenus as $n => $val) {
			echo "<span><a onclick='paneladdclass(this);' target='right' href='" . $val['url'] . "'>" . $val['menuname'] . "</a>  <a class='panel-delete' href='javascript:delete_panel(" . $val['menuid'] . ");'></a></span>";
		}
	}

	public function public_session_life() {
		session('adminid', $_SESSION['adminid']);
		session('adminname', $_SESSION['adminname']);
		session('manager', 'y');
	}

	public function public_lock_screen() {
		session('lock_screen', 1);
	}

	public function public_login_screenlock() {
		$password = md5($_REQUEST["lock_password"]);

		$adminDao = D('Admin');
		$admin = $adminDao -> where("adminname='" . $_SESSION['adminname'] . "' and password='" . $password . "'") -> select();
		if ($admin) {
			echo '1';
			session('lock_screen', 0);
			session('trytimes', 0);
			exit ;
		} else {
			if ($_SESSION['trytimes'] == 3) {
				echo '3';
				exit ;
			}

			if ($_SESSION['trytimes'] == '') {
				echo '2|2';
				session('trytimes', 1);
				exit ;
			} else {
				echo '2|' . (2 - $_SESSION['trytimes']);
				session('trytimes', ($_SESSION['trytimes'] + 1));
				exit ;
			}
		}
	}
	//修改管理员密码
	public function edit_pwd() {
		if ($_GET['action'] == 'public_password_ajx') {
			$password = md5($_GET["old_password"]);
			$admin = D("Admin") -> where("adminname='" . $_SESSION["adminname"] . "' and password='" . $password . "'") -> select();
			if ($admin) {
				echo '1';
			} else {
				echo '0';
			}
			exit ;
		}
	
		$admin = D("Admin") -> find($_SESSION["adminid"]);
		$this -> assign('admin', $admin);

		$this -> display();
	}
	//修改管理员密码动作
	public function do_edit_pwd() {
		if ($_POST['new_password'] == '') {
			$this -> assign('jumpUrl', __URL__ . "/edit_pwd/");
			$this -> success('修改成功');
		}

		$oldpassword = md5($_POST["old_password"]);
		$adminDao = D('Admin');
		$admininfo = $adminDao -> where("adminname='" . $_SESSION["adminname"] . "' and password='" . $oldpassword . "'") -> select();
		if ($admininfo) {
			$vo = $adminDao -> create();
			if (!$vo) {
				$this -> error($adminDao -> getError());
			} else {
				$adminDao -> password = md5($_POST['new_password']);
				$adminDao -> save();
				userLog(session('adminid'), "修改自身的登录密码");
				$this -> assign('jumpUrl', __URL__ . "/edit_pwd/");
				$this -> success('修改成功');
			}
		} else {
			$this -> error('旧密码输入错误');
		}
	}

	public function cache_all() {
		chmod('../Runtime', 0777);
		chmod('../Admin/Runtime', 0777);
		$this -> deldir('../Runtime');
		$this -> deldir('../Admin/Runtime');
		$referer = $_SERVER['HTTP_REFERER'];
		$urlArr = explode("/Admin/", $referer);
		if ($urlArr[1] == '') {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
		}
		$this -> success('缓存更新成功');
	}

	public function deldir($dir) {
		//先删除目录下的文件：
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					$this -> deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if (rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}

	//查看设置
	public function admin_syspara() {
		if(!$this->AuthVerification())
		{
			$this->error("未授权，请联系www.yunbaozhibo.com","http://www.yunbaozhibo.com");
		}
		$siteconfig = D("Siteconfig") -> find(1);
		if ($siteconfig) {
			$this -> assign('siteconfig', $siteconfig);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}
	//修改设置
	public function save_syspara() {

		$siteconfig = D('Siteconfig');
		$vo = $siteconfig -> create();

		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_syspara/');
			$this -> error('修改失败');
		} else {

			$siteconfig -> save();
			$smsid = $_POST['smsid'];
			$smskey = $_POST['smskey'];
			$cdn = $_POST['cdn'];
			$fps = $_POST['fps'];
			$zddk = $_POST['zddk'];
			$pz = $_POST['pz'];
			$zjg = $_POST['zjg'];
			$cdnl = $_POST['cdnl'];
			$height = $_POST['height'];
			$width = $_POST['width'];
			$fmsPort = $_POST['fmsPort'];
			$banip=$_POST['banip'];

			$sql = "update ss_siteconfig set smsid='{$smsid}',smskey='{$smskey}',cdn='{$cdn}',fps='{$fps}',zddk='{$zddk}',pz='{$pz}',zjg='{$zjg}',cdnl='{$cdnl}',height='{$height}',width='{$width}',fmsPort='{$fmsPort}',banip='{$banip}' where id=1";
			M('siteconfig') -> execute($sql);
			userLog(session("adminid"), "修改基本参数设置");
			$this -> assign('jumpUrl', __URL__ . '/admin_syspara/');
			$this -> success('修改成功');
		}
	}
	
	//查看缓存机制设置
	public function admin_cacheset() {
		$this -> display();
	}
	//修改缓存机制设置
	public function save_cacheset() {
		$para = $_POST['para'];
		if (is_array($para)) {
			foreach ($para as $key => $val) {

				$filepath = '../config.php';
				if (file_exists($filepath)) {
					$arr =
					include $filepath;
					$arr[$key] = $val;
				} else {
					$arr = array($key => $val, 'disable' => 0, 'dirname' => $key);
				}

				$res = file_put_contents($filepath, '<?php return ' . var_export($arr, true) . ';?>');
			}
			userLog(session("adminid"), "修改缓存机制设置");
			$this -> success('保存成功');
		} else {
			$this -> error('保存失败');
		}

	}
	//聊天服务器设置
	public function admin_setchatserver(){
		$server=M('siteconfig')->where('id=1')->getField("chatserver");
		
		$this->assign("server",$server);
		
		$this->display();
	}
	//修改聊天服务器设置
	public function edit_admin_setchatserver(){
		$data['chatserver']=trim($_POST['chatserver']);
		
		$res=M('siteconfig')->where('id=1')->save($data);
		if($res){
			$this->success("更新聊天服务器成功");
		}else{
			$this->error("更新聊天服务器失败");
		}
	}
	//查看直播服务器设置
	public function admin_rtmpserver() {
		$servers = D("Server") -> where("") -> order('addtime') -> select();

		$this -> assign("servers", $servers);

		$this -> display();
	}

	//修改直播服务器设置
	public function edit_server() {
		header("Content-type: text/html; charset=utf-8");
		if ($_GET['serverid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$serverinfo = D("Server") -> find($_GET["serverid"]);
			if ($serverinfo) {
				$this -> assign('serverinfo', $serverinfo);
			} else {
				echo '<script>alert(\'找不到该服务器\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//修改直播服务器设置
	public function do_edit_server() {
		header("Content-type: text/html; charset=utf-8");

		$server = D('Server');
		$vo = $server -> create();
		if (!$vo) {
			echo '<script>alert(\'' . $server -> getError() . '\');window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			if ($_POST['updteAll'] == "y") {
				D("Member") -> where("1=1") -> save(array("host" => $_POST['server_ip'], "fmsPort" => $_POST['fmsPort']));
			} else {
				$old_serverip = $_POST["old_serverip"];
				D("Member") -> where("host='$old_serverip") -> save(array("host" => $_POST['server_ip']));
			}

			$server -> save();
			userLog(session('adminid'), "修改服务器设置");
			echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		}
	}
	//删除直播服务器
	public function del_server() {
		if ($_GET["serverid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Server");
			$serverinfo = $dao -> find($_GET["serverid"]);
			if ($serverinfo) {
				$dao -> where('id=' . $_GET["serverid"]) -> delete();
				userLog(session('adminid'), "删除直播服务器,名称：".$serverinfo['server_name']);
				$this -> assign('jumpUrl', __URL__ . '/admin_rtmpserver/');
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该服务器');
			}
		}
	}
	//添加直播服务器
	public function add_server() {
		$this -> display();
	}
	//添加直播服务器动作
	public function do_add_server() {
		if ($_POST['server_name'] == '') {
			$this -> error('服务器名称不能为空');
		}

		if ($_POST['server_ip'] == '') {
			$this -> error('访问域名或IP不能为空');
		}

		if ($_POST['fmsPort'] == '') {
			$this -> error('端口号不能为空');
		}
		$server = D('Server');
		$vo = $server -> create();
		if (!$vo) {
			$this -> error($server -> getError());
		} else {
			$server -> addtime = time();
			$server -> add();
			userLog(session("adminid"), "添加直播服务器，名称:".$_POST['server_name']);
			$this -> assign('jumpUrl', __URL__ . '/admin_rtmpserver/');
			$this -> success('添加成功');
		}
	}
	//收益分成设置查看
	public function admin_deduct() {
		$siteconfig = D("Siteconfig") -> find(1);
		if ($siteconfig) {
			$this -> assign('siteconfig', $siteconfig);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}
	//编辑收益分成设置
	public function save_deduct() {
		$siteconfig = D('Siteconfig');
		$vo = $siteconfig -> create();
		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_deduct/');
			$this -> error('修改失败');
		} else {
			$siteconfig -> save();
			userLog(session('adminid'), "修改收益分成设置");
			$this -> assign('jumpUrl', __URL__ . '/admin_deduct/');
			$this -> success('修改成功');
		}
	}
	//首页轮显设置
	public function admin_rollpic() {
		$rollpics = D("Rollpic") -> where("") -> order('orderno') -> select();
		$this -> assign("rollpics", $rollpics);
		$this -> display();
	}
	//编辑首页轮显
	public function save_rollpic() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/rollpic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/rollpic/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		$Edit_Linkurl = $_POST['linkurl'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("Rollpic") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("Rollpic") -> execute('update ss_rollpic set picpath="' . $Edit_Picpath[$i] . '",linkurl="' . $Edit_Linkurl[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $rollpicpath != '' && $_POST['add_linkurl'] != '') {
			$Rollpic = D('Rollpic');
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> linkurl = $_POST['add_linkurl'];
			$Rollpic -> addtime = time();
			$rollpicID = $Rollpic -> add();
		}
		userLog(session('adminid'), "修改首页轮显设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_rollpic/");
		$this -> success('操作成功');
	}

	//直播页面背景
	public function admin_showbg() {
		$rollpics = D("showbg") -> order('orderno') -> select();
		$this -> assign("rollpics", $rollpics);
		$this -> display();
	}
	//编辑直播页面背景
	public function save_showbg() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/showbg/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/showbg/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("showbg") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("showbg") -> execute('update ss_showbg set picpath="' . $Edit_Picpath[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $rollpicpath != '' ) {
			$Rollpic = D('showbg');
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> addtime = time();
			$rollpicID = $Rollpic -> add();
		}
		userLog(session('adminid'), "修改首页轮显设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_showbg/");
		$this -> success('操作成功');
	}
	//系统公告
	public function admin_announce() {
		$condition = '';

		$orderby = 'addtime desc';
		$announce = D("Announce");
		$count = $announce -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$announces = $announce -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('announces', $announces);

		$this -> display();
	}
	//添加系统公告
	public function add_announce() {
		//查询出当前所有的分类
		$fenleis = M("huodongfenlei") -> select();
		$this -> assign("fenlei", $fenleis);

		$this -> display();
	}
	//添加系统公告动作
	public function do_add_announce() {
		//var_dump($_POST);
		$announce = D("Announce");
		if (!empty($_POST)) 
		{
			//import("ORG.Net.UploadFile");
			//实例化上传类
			//$upload = new UploadFile();
			//$upload -> maxSize = 3145728;
			//设置文件上传类型
			//$upload -> allowExts = array('jpg', 'gif', 'png', 'jpeg');
			//设置文件上传位置
			//$upload -> savePath = "../Public/Uploads/";
			//这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹
			//设置文件上传名(按照时间)
			//$upload -> saveRule = "time";
			//if (!$upload -> upload()) {
				//$this -> error($upload -> getErrorMsg());
			//} else {
				//上传成功，获取上传信息
			//	$info = $upload -> getUploadFileInfo();
			//}
			
			//$savename = $info[0]['savename'];
			$vo = $announce -> create();
		
			//$announce -> fengmian = $savename;
			//$annId = $announce -> add();

			
		}
		$guanli=session('adminid');
		$manage=D("Admin")-> where('id='.$guanli) -> find();
		$announc = D("Announce");
		$announc->issuer = $manage['adminname'];
		//var_dump($manage['adminname']);
		$annId = $announc -> add();
		userLog(session('adminid'), "添加系统公告");
		$this -> assign('jumpUrl', __URL__ . "/admin_announce/");
		$this -> success('添加成功');
	}
	//编辑系统公告
	public function edit_announce() {
		$fenleis = M("huodongfenlei") -> select();
		$this -> assign("fenlei", $fenleis);
		if ($_GET['annid'] == '') {
			$this -> error('参数错误');
		} else {
			$anninfo = D("Announce") -> getById($_GET["annid"]);
			if ($anninfo) {
				$this -> assign('anninfo', $anninfo);
			} else {
				$this -> error('找不到该公告');
			}
		}

		$this -> display();
	}
	//编辑系统公告动作
	public function do_edit_announce() {
		if ($_POST["id"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$anninfo = D("Announce") -> getById($_POST["id"]);
			if (!$anninfo) {
				$this -> error('该公告不存在');
			}
		}

		$announce = D("Announce");
		$vo = $announce -> create();
		if (!$vo) {
			$this -> error($announce -> getError());
		} else {
			$announce -> save();
		}
		
		userLog(session('adminid'), "修改系统公告");
		$this -> assign('jumpUrl', __URL__ . "/edit_announce/annid/" . $_POST['id']);
		$this -> success('修改成功');
	}
	//删除系统公告
	public function del_announce() {
		if ($_GET["annid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Announce");
			$anninfo = $dao -> getById($_GET["annid"]);
			if ($anninfo) {
				$dao -> where('id=' . $_GET["annid"]) -> delete();
				userLog(session('adminid'), "删除系统公告");
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该公告');
			}
		}
	}
	//批量删除活动公告
	public function opt_announce() {
		$dao = D("Announce");
		switch ($_GET['action']) {
			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$anninfo = $dao -> getById($array[$i]);
						if ($anninfo) {
							$dao -> where('id=' . $array[$i]) -> delete();
						}
					}
				}
				userLog(session('adminid'), "批量删除系统公告");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//批量删除活动分类
	public function opt_huodongfenlei() {
		$dao = D("Huodongfenlei");
		switch ($_GET['action']) {
			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$anninfo = $dao -> getById($array[$i]);
						if ($anninfo) {
							$dao -> where('id=' . $array[$i]) -> delete();
						}
					}
				}
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	
	//管理员列表查看
	public function admin_admin() {
		$adminusers = D("Admin") -> where("") -> order('addtime') -> select();
		
		//用户所在用户组
		$i=0;
		foreach($adminusers as $a){
			$adminusers[$i]['usergroupname']=M('admin_group')->where("id=$a[usergroup]")->getField("groupname");
			$i++;
		}
		$this -> assign("adminusers", $adminusers);

		$this -> display();
	}
	//修改管理员信息
	public function edit_adminuser() {
		header("Content-type: text/html; charset=utf-8");
		if ($_GET['adminid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$admininfo = D("Admin") -> find($_GET["adminid"]);
			if ($admininfo) {
				$this -> assign('admininfo', $admininfo);
				$admingroup=M('admin_group')->select();
				$this -> assign('admingroup', $admingroup);
			} else {
				echo '<script>alert(\'找不到该管理员\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//修改管理员信息动作
	public function do_edit_adminuser() {
		header("Content-type: text/html; charset=utf-8");
		

		$admin = D('Admin');
		$vo = $admin -> create();
		
		if (!$vo) {
			echo '<script>alert(\'' . $admin -> getError() . '\');window.top.art.dialog({id:"edit"}).close();</script>';
		} else {

			if($_POST['password'] != ''){
				$admin -> password = md5($_POST['password']);
			}else{
				$admin -> password = M('admin')->where("id=$_POST[id]")->getField("password");
				
			}
			$adminname=$admin->where("id=$_POST[id]")->getField("adminname");
			userLog(session('adminid'), "修改管理员帐号信息，用户名：".$adminname);
			$admin -> usergroup = $_POST['usergroup'];
			$admin -> status = $_POST['status'];
			$admin -> save();
			
			echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		}
	}
	//删除管理员
	public function del_adminuser() {
		if ($_GET["adminid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Admin");
			$admininfo = $dao -> find($_GET["adminid"]);
			if ($admininfo) {
				$dao -> where('id=' . $_GET["adminid"]) -> delete();
				userLog(session('adminid'), "删除管理员帐号，用户名：".$admininfo['adminname']);
				$this -> assign('jumpUrl', __URL__ . '/admin_admin/');
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该管理员');
			}
		}
	}
	
	//添加管理员
	public function add_adminuser() {
		if ($_GET['clientid'] == 'username') {
			$admininfo = D("Admin") -> where("adminname='" . $_GET['username'] . "'") -> select();
			if ($admininfo) {
				echo '0';
				exit ;
			} else {
				echo '1';
				exit ;
			}
		}
		
		//获取用户组数据
		$admingroup=M('admin_group')->select();
		$this->assign('admingroup',$admingroup);
		$this -> display();
	}
	//添加管理员动作
	public function do_add_adminuser() {
		if ($_POST['adminname'] == '') {
			$this -> error('用户名不能为空');
		}

		if ($_POST['password'] == '') {
			$this -> error('密码不能为空');
		}
		
		
		$admin = D('Admin');
		$vo = $admin -> create();
		if (!$vo) {
			$this -> error($admin -> getError());
		} else {
			$admin -> password = md5($_POST['password']);
			$admin -> usergroup=$_POST['usergroup'];
			$admin -> add();
			userLog(session('adminid'), "添加管理员帐号，用户名：".$_POST['adminname']);
			$this -> assign('jumpUrl', __URL__ . '/admin_admin/');
			$this -> success('添加成功');
		}
	}
	
	//管理员操作日志
	public function admin_oplog(){
			
		//获取所有ip
		$action_ip=M('adminlog')->group("action_ip")->order("id DESC")->field("action_ip")->select();
		//获取所有管理员
		$admin=M('admin')->field("id,adminname")->order('id desc')->select();
		import("ORG.Util.Page");// 导入分页类
		$Log = M('adminlog');
		$condition="1";
		//筛选条件
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
			$condition.=" and createtime>=$start_time";
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time']);
			$condition.=" and createtime<=$end_time";
		}
		if($_GET['adminid']){
			$condition.=" and admin_userid=$_GET[adminid]";
		}
		
		if($_GET['action_ip']){
			$condition.=" and action_ip='$_GET[action_ip]'";
		}
		if($_GET['p']){
			$list = $Log->page($_GET['p'].',25')->where("{$condition}")->order('id desc')->select();
		
		}else{
			$list = $Log->page('1,25')->where("{$condition}")->order('id desc')->select();
		}
		
		$i=0;
		foreach($list as $a){
			$log[$i]['id']=$a['id'];
			$log[$i]['adminuser']=M('admin')->where("id=".$a['admin_userid'])->getField("adminname");
			$log[$i]['actionname']=$a['action_name'];
			$log[$i]['createtime']=date("Y-m-d H:i:s ",$a['createtime']);
			$log[$i]['ip']=$a['action_ip'];
			$i++;
		}
		
		$this->assign("log",$log);
		
		$count      = $Log->where("{$condition}")->count();
		$Page       = new Page($count,25);
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('action_ip',$action_ip);
		$this->assign('admin',$admin);
		$this->display();
	}
	//管理员用户组列表
	public function admin_group_list(){
		
		$admin_group_list=M('admin_group')->select();
		$this->assign("admin_group_list",$admin_group_list);
		$this->display();
	}
	
	//添加管理员用户组
	public function add_admin_group(){
		
		//获取所有菜单
		$adminmenu=M('adminmenu')->where("is_show=1 and dev_hidden=1")->select();
		$this->assign('adminmenu',$adminmenu);
		$this->display();
	}
	//添加管理员用户组动作
	public function do_add_admin_group(){
		
		//获取添加的用户组数据
		$g['groupname']=htmlspecialchars(trim($_POST['groupname']));
		$g['groupdesc']=htmlspecialchars(trim($_POST['groupdesc']));
		$g['groupstatus']=trim($_POST['groupstatus']);
		$grouprules_arr=$_POST['ids'];
		$grouprules="";
		foreach ($grouprules_arr as $g_a){
			$grouprules.=$g_a.",";
		}
		$grouprules.="-1";
		$g['grouprules']=$grouprules;
		//写入用户组
		$res=M('admin_group')->add($g);
		
		if($res){
			userLog(session('adminid'), "添加用户组");
			$this -> success('添加成功');
		}else{
			$this -> error('添加失败');
		}
		
		
	}
	//编辑(修改)用户组查看
	public function edit_admin_group(){
		$groupid=htmlspecialchars(trim($_REQUEST['groupid']));
		
		//获取当前用户组数据
		$group_data=M('admin_group')->where("id=$groupid")->select();
		$groupdata=$group_data[0];
		$grouprules=explode(",",$group_data[0]['grouprules']);
		//获取所有菜单
		
		$adminmenu=M('adminmenu')->select();
		
		
		$this->assign('groupdata',$groupdata);
		$this->assign('grouprules',$grouprules);
		$this->assign('adminmenu',$adminmenu);
		$this->display();
	}
	//编辑(修改)用户组
	public function do_edit_admin_group(){
		$groupid=htmlspecialchars(trim($_REQUEST['groupid']));
		$g['groupname']=htmlspecialchars(trim($_POST['groupname']));
		$g['groupdesc']=htmlspecialchars(trim($_POST['groupdesc']));
		$g['groupstatus']=trim($_POST['groupstatus']);
		$grouprules_arr=$_POST['ids'];
		$grouprules="";
		foreach ($grouprules_arr as $g_a){
			$grouprules.=$g_a.",";
		}
		$grouprules.="-1";
		$g['grouprules']=$grouprules;
		//写入用户组
		$res=M('admin_group')->where("id=$groupid")->save($g);
		
		if($res){
			userLog(session('adminid'), "修改用户组");
			$this -> success('修改成功');
		}else{
			$this -> error('修改失败');
		}
	}
	//删除用户组
	public function del_admin_group(){
		
		$groupid=htmlspecialchars(trim($_REQUEST['groupid']));
		
		$res=M('admin_group')->where("id=$groupid")->delete();
		
		if($res){
			userLog(session('adminid'), "删除用户组");
			$this -> success('删除成功');
		}else{
			$this -> error('删除失败');
		}
		
	}

	//用户
	public function admin_user() {
		if (!$this -> AuthVerification()) {
			$this -> error("未授权，请联系www.yunbaozhibo.com", "http://www.yunbaozhibo.com");
		}

		$condition = 'isdelete="n"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入ID、用户名或昵称') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\' or nickname like \'%'.$_GET['keyword'].'%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\' or nickname like \'%'.$_GET['keyword'].'%\'';
			}
		}
		if ($_GET['sign'] != '') {
			$condition .= ' and sign="' . $_GET['sign'] . '"';
		}
		if ($_GET['emceeagent'] != '') {
			$condition .= ' and emceeagent="' . $_GET['emceeagent'] . '"';
		}
		if ($_GET['payagent'] != '') {
			$condition .= ' and payagent="' . $_GET['payagent'] . '"';
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();

		echo mysql_error();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}

	//所有主播
	public function admin_zhubo() {

		$condition = 'isdelete="n" and canlive="y"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入ID、用户名或昵称') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\' or nickname like \'%'.$_GET['keyword'].'%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\' or nickname like \'%'.$_GET['keyword'].'%\'';
			}
		}
		if ($_GET['sign'] != '') {
			$condition .= ' and sign="' . $_GET['sign'] . '"';
		}
		if ($_GET['emceeagent'] != '') {
			$condition .= ' and emceeagent="' . $_GET['emceeagent'] . '"';
		}
		if ($_GET['payagent'] != '') {
			$condition .= ' and payagent="' . $_GET['payagent'] . '"';
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();

		echo mysql_error();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}
	public function admin_shenheqianyueuser() {
		$condition = 'isdelete="n" and sign="i"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户ID或用户名') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\'';
			}
		}
		if ($_GET['sign'] != '') {
			$condition .= ' and sign="' . $_GET['sign'] . '"';
		}
		if ($_GET['emceeagent'] != '') {
			$condition .= ' and emceeagent="' . $_GET['emceeagent'] . '"';
		}
		if ($_GET['payagent'] != '') {
			$condition .= ' and payagent="' . $_GET['payagent'] . '"';
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}
	//编辑普通用户
	public function edit_user() {
		if ($_GET['userid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$userinfo = D("Member") -> getById($_GET["userid"]);
			if ($userinfo) {
				$this -> assign('userinfo', $userinfo);
				//	var_dump($userinfo);
				$usersorts = D("Usersort") -> where("parentid=0") -> order('addtime') -> select();
				foreach ($usersorts as $n => $val) {
					$usersorts[$n]['voo'] = D("Usersort") -> where('parentid=' . $val['id']) -> order('addtime') -> select();
				}
				$this -> assign("usersorts", $usersorts);

				$servers = D("Server") -> where("") -> order('addtime') -> select();
				$this -> assign("servers", $servers);
			} else {
				echo '<script>alert(\'找不到该用户\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//编辑普通用户动作
	public function do_edit_user() {
		header("Content-type: text/html; charset=utf-8");
		if ($_POST["id"] == '') {
			echo '<script>alert(\'缺少参数或参数不正确\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			exit ;
		} else {
			$userinfo = D("Member") -> getById($_POST["id"]);
			if (!$userinfo) {
				echo '<script>alert(\'该用户不存在\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit ;
			}
		}

		//上传缩略图
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/bigpic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				echo '<script>alert(\'' . $upload -> getErrorMsg() . '\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit ;
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();

			foreach ($uploadList as $picval) {
				if ($picval['key'] == 0) {
					$bigpicpath = '/Public/bigpic/' . $prefix . '/' . $picval['savename'];
				}
				if ($picval['key'] == 1) {
					$snap = '/Public/bigpic/' . $prefix . '/' . $picval['savename'];
				}
			}
		}

		$Member = D("Member");
		$vo = $Member -> create();

		if (!$vo) {
			$this -> error($Member -> getError());
		} else {
			if ($bigpicpath != '') {
				$Member -> bigpic = $bigpicpath;
			}
			if ($snap != '') {
				$Member -> snap = $snap;
			}
			//密码
			if ($_POST['newpwd'] != '') {
				include '../config.inc.php';
				include '../uc_client/client.php';
				$ucresult = uc_user_edit($userinfo['username'], '', $_POST['newpwd'], $userinfo['email'], 1);
				if ($ucresult == -1) {
					$this -> error('旧密码不正确');
				} elseif ($ucresult == -4) {
					$this -> error('Email 格式有误');
				} elseif ($ucresult == -5) {
					$this -> error('不允许注册');
				} elseif ($ucresult == -6) {
					$this -> error('该 Email 已经被注册');
				}
			}
			$Member -> password = md5($_POST['newpwd']);
			$Member -> password2 = $this -> pswencode($_POST['newpwd']);

			if ($_POST['agentname'] != '') {
				if ($_POST['agentname'] == $userinfo['username']) {
					$error = '自已不能做自己的代理';
				} else {
					$agentinfo = D("Member") -> where('username="' . $_POST['agentname'] . '"') -> select();
					if ($agentinfo) {
						if ($agentinfo[0]['emceeagent'] == 'n') {
							$error = '指定的代理人没有代理权限';
						} else {
							$Member -> agentuid = $agentinfo[0]['id'];
						}
					} else {
						$error = '没有找到指定的代理人信息';
					}
				}
			} else {
				$Member -> agentuid = 0;
			}
			if ($_POST['payagent'] == 'y') {
				$Member -> sellm = '1';
			} else {
				$Member -> sellm = '0';
			}
			if ($_POST['idxrec'] == 'y') {
				$Member -> idxrec = 'y';
				$Member -> idxrectime = time();
			} else {
				$Member -> idxrec = 'n';
			}

			$vipexpire = $_POST['vipexpire'];
			$formatvip = strtotime($vipexpire);
			$Daoju1expire = strtotime($_POST['Daoju1expire']);
			$Daoju2expire = strtotime($_POST['Daoju2expire']);
			$Daoju3expire = strtotime($_POST['Daoju3expire']);
			$Daoju4expire = strtotime($_POST['Daoju4expire']);
			$Daoju5expire = strtotime($_POST['Daoju5expire']);
			$Daoju6expire = strtotime($_POST['Daoju6expire']);
			$Daoju7expire = strtotime($_POST['Daoju7expire']);
			$Daoju8expire = strtotime($_POST['Daoju8expire']);
			$Daoju9expire = strtotime($_POST['Daoju9expire']);
			$gkexpire = strtotime($_POST['gkexpire']);
			$awexpire = strtotime($_POST['awexpire']);
			$Member -> vipexpire = $formatvip;
			$Member -> Daoju1expire = $Daoju1expire;
			$Member -> Daoju2expire = $Daoju2expire;
			$Member -> Daoju3expire = $Daoju3expire;
			$Member -> Daoju4expire = $Daoju4expire;
			$Member -> Daoju5expire = $Daoju5expire;
			$Member -> Daoju6expire = $Daoju6expire;
			$Member -> Daoju7expire = $Daoju7expire;
			$Member -> Daoju8expire = $Daoju8expire;
			$Member -> Daoju9expire = $Daoju9expire;

			$Member -> save();


		}
		$zddk = $_POST['zddk'];
		$pz = $_POST['pz'];
		$fps = $_POST['fps'];
		$zjg = $_POST['zjg'];
		$height = $_POST['height'];
		$width = $_POST['width'];
        $canlive = trim($_POST['sign']) == 'y'?'y':'n';
        //在未修改之前有个roomview字段不知道寓意何为,就暂时删掉了,因为没有这个字段
		$sql = "update ss_member set pz='{$pz}',fps='{$fps}',zjg='{$zjg}',zddk='{$zddk}',height='{$height}',width='{$width}',vipexpire = {$formatvip} ,
			Daoju1expire = {$Daoju1expire},
			Daoju2expire = {$Daoju2expire} ,
			Daoju3expire = {$Daoju3expire} ,
			Daoju4expire = {$Daoju4expire} ,
			Daoju5expire = {$Daoju5expire} ,
			Daoju6expire = {$Daoju6expire},
			Daoju7expire = {$Daoju7expire} ,
			Daoju8expire = {$Daoju8expire} ,
			Daoju9expire = {$Daoju9expire},
			gkexpire = {$gkexpire},
			awexpire = {$awexpire},
            canlive = '{$canlive}'
			 where id={$_POST['id']}";
        

		$Member -> execute($sql);
		userLog(session('adminid'), "修改注册用户信息，用户名：".$userinfo['username']);
		echo '<script>alert(\'修改成功_' . $error . '\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
	}

	public function pswencode($txt, $key = 'youst') {
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+_)(*&^%$#@!~";
		$nh = rand(0, 64);
		$ch = $chars[$nh];
		$mdKey = md5($key . $ch);
		$mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
		$txt = base64_encode($txt);
		$tmp = '';
		$i = 0;
		$j = 0;
		$k = 0;
		for ($i = 0; $i < strlen($txt); $i++) {
			$k = $k == strlen($mdKey) ? 0 : $k;
			$j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
			$tmp .= $chars[$j];
		}
		return $ch . $tmp;
	}

	public function checkIt($number) {
		$modes = array('######', 'AAAAAA', 'AAABBB', 'AABBCC', 'ABCABC', 'ABBABB', 'AABAA', 'AAABB', 'AABBB', '#####', 'AAAAA', '####', 'AAAA', 'AABB', 'ABBA', 'AAAB', 'ABAB', 'AAA', '###', 'AAAAAAAB', 'AAAAAABC', 'AAAAABCD', 'AAABBBCD', 'AAABBBC', 'AABBBCDE', 'AABBBCD', 'AABBBC', 'AAABBCDE', 'AAABBCD', 'AAABBC', 'AAAABCDE', 'AAAABCD', 'AAAABC', 'AAAAB', 'AABBCDEF', 'AABBCDE', 'AABBCD', 'AABBC', 'AAABCDEF', 'AAABCDE', 'AAABCD', 'AAABC', 'AAAB', 'AABBCCDE', 'AABBCCD');
		//前后排序有优先级,只要有一个匹配,后面的就不再检索了
		$result = ' ';
		foreach ($modes as $mode) {
			$len = strlen($mode);
			$s = substr($number, -$len);
			$temp = array();
			$match = true;
			for ($i = 0; $i < $len; $i++) {
				if ($mode[$i] == '#') {
					if (!isset($temp['step'])) {
						$temp['step'] = 0;
						$temp['current'] = intval($s[$i]);
					} elseif ($temp['step'] == 0) {
						$temp['step'] = $temp['current'] - intval($s[$i]);
						if ($temp['step'] != -1 && $temp['step'] != 1) {
							$match = false;
							break;
						} else {
							$temp['current'] = intval($s[$i]);
						}
					} else {
						$step = $temp['current'] - intval($s[$i]);
						if ($step != $temp['step']) {
							$match = false;
							break;
						} else {
							$temp['current'] = intval($s[$i]);
						}
					}
				} else {
					if (isset($temp[$mode[$i]])) {
						if ($s[$i] != $temp[$mode[$i]]) {
							$match = false;
							break;
						}
					} else {
						$temp[$mode[$i]] = $s[$i];
					}
				}
			}
			if ($match) {
				$result = $mode;
				break;
			}
		}
		return $result;
	}
	//删除普通用户
	public function del_user() {
		if ($_GET["userid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Member");
			$userinfo = $dao -> getById($_GET["userid"]);
			if ($userinfo) {
				$dao -> query('update ss_member set isdelete="y" where id=' . $_GET["userid"]);
				userLog(session('adminid'), "删除注册用户，用户名：".$userinfo['username']);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该用户');
			}
		}
	}

	public function opt_user() 
	{
		$dao = D("Member");
		$username=$dao->where("id=$_GET[userid]")->getField("username");
		switch ($_GET['action']) {
			case 'disaudit' :
				if ($_GET['userid'] != '') {
					$dao -> query('update ss_member set isaudit="n" where id=' . $_GET['userid']);
				}
				userLog(session('adminid'), "修改注册用户状态为禁止，用户名：".$username);
				$this -> assign('jumpUrl', base64_decode($_REQUEST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
			case 'audit' :
				if ($_GET['userid'] != '') {
					$dao -> query('update ss_member set isaudit="y" where id=' . $_GET['userid']);
				}
				userLog(session('adminid'), "修改注册用户状态为启用，用户名：".$username);
				$this -> assign('jumpUrl', base64_decode($_REQUEST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
			case 'restore' :
				if ($_GET['userid'] != '') {
					$dao -> query('update ss_member set isdelete="n" where id=' . $_GET['userid']);
				}
				userLog(session('adminid'), "恢复已删除的注册用户，用户名：".$username);
				$this -> assign('jumpUrl', base64_decode($_REQUEST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
			case 'restorebat' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$userinfo = $dao -> getById($array[$i]);
						if ($userinfo) {
							$dao -> query('update ss_member set isdelete="n" where id=' . $array[$i]);
						}
					}
				}
				userLog(session('adminid'), "批量恢复已删除的注册用户");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$userinfo = $dao -> getById($array[$i]);
						if ($userinfo) {
							$dao -> query('update ss_member set isdelete="y" where id=' . $array[$i]);
							
						}
					}
				}
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}

	public function admin_signuser() {
		$condition = 'isdelete="n" and sign<>"n"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户ID或用户名') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\'';
			}
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}

	public function admin_onlineuser() {
		$condition = 'isdelete="n" and broadcasting="y"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户ID或用户名') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\'';
			}
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}
	//新增注册用户
	public function add_user() {
		$this -> display();
	}
	//新增注册用户动作
	public function do_add_user() {
		include '../config.inc.php';
		include '../uc_client/client.php';

		$uid = uc_user_register($_POST['username'], $_POST['password'], $_POST['email']);
		if ($uid <= 0) {
			if ($uid == -1) {
				$this -> error('用户名不合法');
			} elseif ($uid == -2) {
				$this -> error('包含不允许注册的词语');
			} elseif ($uid == -3) {
				$this -> error('用户名已经存在');
			} elseif ($uid == -4) {
				$this -> error('Email 格式有误');
			} elseif ($uid == -5) {
				$this -> error('Email 不允许注册');
			} elseif ($uid == -6) {
				$this -> error('该 Email 已经被注册');
			} else {
				$this -> error('未知错误');
			}
		} else {
			$User = D("Member");
			$User -> create();
			$User -> username = $_POST['username'];
			$User -> nickname = $_POST['username'];
			$User -> password = md5($_POST['password']);
			$User -> password2 = $this -> pswencode($_POST['password']);
			$User -> email = $_POST['email'];
			$User -> isaudit = 'y';
			$User -> regtime = time();
			$roomnum = 99999;
			do {
				$roomnum = rand(1000000000, 1999999999);
			} while ($this->checkIt($roomnum)=='');
			$User -> curroomnum = $roomnum;
			$User -> ucuid = $uid;
			$defaultserver = D("Server") -> where('isdefault="y"') -> select();
			if ($defaultserver) {
				$User -> host = $defaultserver[0]['server_ip'];
			}
			$userId = $User -> add();

			D("Roomnum") -> execute('insert into ss_roomnum(uid,num,addtime) values(' . $userId . ',' . $roomnum . ',' . time() . ')');
			userLog(session('adminid'), "添加注册用户，用户名：".$_POST['username']);
			$this -> assign('jumpUrl', __URL__ . '/admin_user/');
			$this -> success('添加成功');
		}
	}
	
	public function admin_deluser() {
		$condition = 'isdelete="y"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and regtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户ID或用户名') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and (id=' . $_GET['keyword'] . ' or username like \'%' . $_GET['keyword'] . '%\')';
			} else {
				$condition .= ' and username like \'%' . $_GET['keyword'] . '%\'';
			}
		}
		$orderby = 'id desc';
		$member = D("Member");
		$count = $member -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$members = $member -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('members', $members);

		$this -> display();
	}

	public function view_liverecord() {
		$condition = 'uid=' . $_GET['userid'];
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and starttime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and starttime<=' . $unixtime;
		}

		$orderby = 'id desc';
		$liverecord = D("Liverecord");
		$count = $liverecord -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$liverecords = $liverecord -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('liverecords', $liverecords);

		$liverecords_all = $liverecord -> where($condition) -> order($orderby) -> select();
		$this -> assign('liverecords_all', $liverecords_all);

		$this -> display();
	}

	public function admin_usersort() {
		$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();
		foreach ($usersorts as $n => $val) {
			$usersorts[$n]['voo'] = D("Usersort") -> where('parentid=' . $val['id']) -> order('orderno') -> select();
		}
		$this -> assign("usersorts", $usersorts);
		$this -> display();
	}

	public function usersortlistorder() {
		$Edit_ID = $_POST['id'];
		$Edit_OrderID = $_POST['orderno'];

		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("Usersort") -> execute('update ss_usersort set orderno=' . $Edit_OrderID[$i] . ' where id=' . $Edit_ID[$i]);
		}

		$this -> assign('jumpUrl', __URL__ . "/admin_usersort/");
		$this -> success('修改成功');
	}
	//删除主播分类
	public function del_usersort() {
		$sortname=M("usersort")->where("id=$_GET[sid]")->getField("sortname");
		D("Usersort") -> where('id=' . $_GET['sid'] . ' or parentid=' . $_GET['sid']) -> delete();
		if ($_GET['type'] == 'sub') {
			D("Member") -> execute('update ss_member set sid=0 where sid=' . $_GET['sid']);
		} else {
			D("Member") -> execute('update ss_member set sid=0 where sid in (select id from ss_usersort where parentid=' . $_GET['sid'] . ')');
		}
		
		userLog(session('adminid'), "删除主播分类，分类名：".$sortname);
		$this -> assign('jumpUrl', __URL__ . "/admin_usersort/");
		$this -> success('删除成功');
	}
	//添加主播分类
	public function add_usersort() {
		$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();

		$this -> assign("usersorts", $usersorts);
		$this -> display();
	}
	//添加主播分类动作
	public function do_add_usersort() {
		if ($_POST['sortname'] != '') {
			$Usersort = D('Usersort');
			$Usersort -> create();
			$Usersort -> parentid = $_POST['parentid'];
			$Usersort -> sortname = $_POST['sortname'];
			$Usersort -> addtime = time();
			$sortID = $Usersort -> add();
		}

		if ($sortID) {
			userLog(session('adminid'), "添加主播分类，分类名：".$_POST['sortname']);
			$this -> assign('jumpUrl', __URL__ . "/admin_usersort/");
			$this -> success('添加成功');
		} else {
			$this -> error('添加失败');
		}
	}
	//修改主播分类
	public function edit_usersort() {
		if ($_GET["sid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Usersort");
			$sortinfo = $dao -> getById($_GET["sid"]);
			if ($sortinfo) {
				$usersorts = D("Usersort") -> where("parentid=0") -> order('orderno') -> select();
				$this -> assign("usersorts", $usersorts);

				$this -> assign('sortinfo', $sortinfo);
			} else {
				$this -> error('找不到该类别');
			}
		}

		$this -> display();
	}
	//修改主播分类动作
	public function do_edit_usersort() {
		if ($_POST["id"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Usersort");
			$sortinfo = $dao -> getById($_POST["id"]);
			if ($sortinfo) {
				$vo = $dao -> create();
				if (!$vo) {
					$this -> error($dao -> getError());
				} else {
					$dao -> save();
					userLog(session('adminid'), "修改主播分类，分类名：".$sortinfo['sortname']);
					$this -> assign('jumpUrl', __URL__ . "/edit_usersort/sid/" . $_POST["id"]);
					$this -> success('修改成功');
				}
			} else {
				$this -> error('找不到该类别');
			}
		}
	}
	//主播等级设置
	public function admin_emceelevel() {
		$emceelevels = D("Emceelevel") -> where("") -> order('levelid asc') -> select();
		$this -> assign("emceelevels", $emceelevels);
		$this -> display();
	}
	//主播等级设置修改
	public function save_emceelevel() {
		$Edit_ID = $_POST['id'];
		$Edit_levelid = $_POST['levelid'];
		$Edit_levelname = $_POST['levelname'];
		$Edit_earnbean_low = $_POST['earnbean_low'];
		$Edit_earnbean_up = $_POST['earnbean_up'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("Emceelevel") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("Emceelevel") -> execute('update ss_emceelevel set levelid=' . $Edit_levelid[$i] . ',levelname="' . $Edit_levelname[$i] . '",earnbean_low=' . $Edit_earnbean_low[$i] . ',earnbean_up=' . $Edit_earnbean_up[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_levelid'] != '' && $_POST['add_levelname'] != '' && $_POST['add_earnbean_low'] != '' && $_POST['add_earnbean_up'] != '') {
			$EmceeLevel = D('Emceelevel');
			$EmceeLevel -> create();
			$EmceeLevel -> levelid = $_POST['add_levelid'];
			$EmceeLevel -> levelname = $_POST['add_levelname'];
			$EmceeLevel -> earnbean_low = $_POST['add_earnbean_low'];
			$EmceeLevel -> earnbean_up = $_POST['add_earnbean_up'];
			$EmceeLevel -> addtime = time();
			$levelID = $EmceeLevel -> add();
		}
		userLog(session('adminid'), "修改主播等级设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_emceelevel/");
		$this -> success('操作成功');
	}
	//富豪等级设置
	public function admin_richlevel() {
		$richlevels = D("Richlevel") -> where("") -> order('levelid asc') -> select();
		$this -> assign("richlevels", $richlevels);
		$this -> display();
	}
	//修改富豪等级设置
	public function save_richlevel() {
		$Edit_ID = $_POST['id'];
		$Edit_levelid = $_POST['levelid'];
		$Edit_levelname = $_POST['levelname'];
		$Edit_spendcoin_low = $_POST['spendcoin_low'];
		$Edit_spendcoin_up = $_POST['spendcoin_up'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("Richlevel") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("Richlevel") -> execute('update ss_richlevel set levelid=' . $Edit_levelid[$i] . ',levelname="' . $Edit_levelname[$i] . '",spendcoin_low=' . $Edit_spendcoin_low[$i] . ',spendcoin_up=' . $Edit_spendcoin_up[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_levelid'] != '' && $_POST['add_levelname'] != '' && $_POST['spendcoin_low'] != '' && $_POST['spendcoin_up'] != '') {
			$RichLevel = D('Richlevel');
			$RichLevel -> create();
			$RichLevel -> levelid = $_POST['add_levelid'];
			$RichLevel -> levelname = $_POST['add_levelname'];
			$RichLevel -> earnbean_low = $_POST['add_spendcoin_low'];
			$RichLevel -> earnbean_up = $_POST['add_spendcoin_up'];
			$RichLevel -> addtime = time();
			$levelID = $RichLevel -> add();
		}
		userLog(session('adminid'), "修改富豪等级设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_richlevel/");
		$this -> success('操作成功');
	}
	//礼物分类设置
	public function admin_giftsort() {
		$giftsorts = D("Giftsort") -> where("") -> order('orderno asc') -> select();
		$this -> assign("giftsorts", $giftsorts);
		$this -> display();
	}
	//修改礼物分类设置
	public function save_giftsort() {
		$Edit_ID = $_POST['id'];
		$Edit_orderno = $_POST['orderno'];
		$Edit_sortname = $_POST['sortname'];
		$Edit_rate = $_POST['rate'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("Giftsort") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("Giftsort") -> execute('update ss_giftsort set orderno=' . $Edit_orderno[$i] . ',sortname="' . $Edit_sortname[$i] . '",rate = '.$Edit_rate[$i].' where id=' . $Edit_ID[$i]);
			D("Gift") -> execute('update ss_gift set sid=0 where sid=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $_POST['add_sortname'] != '') {
			$Giftsort = D('Giftsort');
			$Giftsort -> create();
			$Giftsort -> orderno = $_POST['add_orderno'];
			$Giftsort -> sortname = $_POST['add_sortname'];
			$Giftsort -> rate = $_POST['add_rate']; 
			$Giftsort -> addtime = time();
			$sortID = $Giftsort -> add();
		}
		userLog(session('adminid'), "修改礼物分类设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_giftsort/");
		$this -> success('操作成功');
	}
	//礼物设置
	public function admin_gift() {
		$giftsorts = D("Giftsort") -> where("") -> order('orderno asc') -> select();
		$this -> assign("giftsorts", $giftsorts);

		$gifts = D("Gift") -> where("") -> order('sid asc,needcoin asc') -> select();
		$this -> assign("gifts", $gifts);

		$this -> display();
	}
	//修改礼物设置
	public function save_gift() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'gif,jpg,png,swf');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = 'gift';
		$uploadPath = '../Public/images/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			foreach ($uploadList as $picval) {
				if ($picval['key'] == 0) {
					$giftIcon_25 = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
				if ($picval['key'] == 1) {
					$giftIcon = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
				if ($picval['key'] == 2) {
					$giftSwf = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
			}
		}

		$Edit_ID = $_POST['id'];
		$Edit_sid = $_POST['sid'];
		$Edit_giftname = $_POST['giftname'];
		$Edit_needcoin = $_POST['needcoin'];
		$Edit_giftIcon_25 = $_POST['giftIcon_25'];
		$Edit_giftIcon = $_POST['giftIcon'];
		$Edit_giftSwf = $_POST['giftSwf'];
		$Edit_DelID = $_POST['ids'];
		$Edit_rate2 = $_POST['rate2'];
		$Edit_ShowTime = $_POST['ShowTime'];
		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("Gift") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);

		for ($i = 0; $i < $num; $i++) {
			if($Edit_ShowTime[$i]==NULL) $Edit_ShowTime[$i] = 20;
			D("Gift") -> execute('update ss_gift set sid=' . $Edit_sid[$i] . ',giftname="' . $Edit_giftname[$i] . '",needcoin=' . $Edit_needcoin[$i] . ',giftIcon_25="' . $Edit_giftIcon_25[$i] . '",giftIcon="' . $Edit_giftIcon[$i] . '",giftSwf="' . $Edit_giftSwf[$i] . '",ShowTime=' . $Edit_ShowTime[$i] .',rate='.$Edit_rate2[$i].' where id=' . $Edit_ID[$i]);
			
		}

		if ($_POST['add_giftname'] != '' && $_POST['add_needcoin'] != '' && $giftIcon_25 != '' && $giftIcon != '') {
			if($_POST['AddShowTime']==NULL) $_POST['AddShowTime'] = 20;
			$Gift = D('Gift');
			$Gift -> create();
			$Gift -> sid = $_POST['add_sid'];
			$Gift -> giftname = $_POST['add_giftname'];
			$Gift -> needcoin = $_POST['add_needcoin'];
			$Gift -> showTime = $_POST['AddShowTime'];
			$Gift -> giftIcon_25 = $giftIcon_25;
			$Gift -> giftIcon = $giftIcon;
			if ($giftSwf != '') {
				$Gift -> giftSwf = $giftSwf;
			}
			$Gift -> addtime = time();
			$Gift -> rate = $_POST['rate'];
			$giftID = $Gift -> add();
		}
		userLog(session('adminid'), "修改礼物设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_gift/");
		$this -> success('操作成功');
	}
	//靓号设置
	public function admin_goodnum() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入靓号号码') {
			$condition .= ' and num like \'%' . $_GET['keyword'] . '%\'';
		}
		if ($_GET['length'] != '') {
			$condition .= ' and length=' . $_GET['length'];
		}
		if ($_GET['issale'] != '') {
			$condition .= ' and issale="' . $_GET['issale'] . '"';
		}
		if ($_GET['owneruid'] != '' && $_GET['owneruid'] != '请输入用户UID') {
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				$condition .= ' and owneruid=' . $_GET['owneruid'];
			}
		}

		$orderby = 'id desc';
		$goodnum = D("Goodnum");
		$count = $goodnum -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$goodnums = $goodnum -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('goodnums', $goodnums);

		$this -> display();
	}
	//修改靓号
	public function edit_goodnum() {
		if ($_GET['numid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$numinfo = D("Goodnum") -> getById($_GET["numid"]);
			if ($numinfo) {
				if ($numinfo['issale'] == 'y') {
					echo '<script>alert(\'该靓号已销售不可修改\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				}
				$this -> assign('numinfo', $numinfo);
			} else {
				echo '<script>alert(\'找不到该靓号\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//修改靓号动作
	public function do_edit_goodnum() {
		header("Content-type: text/html; charset=utf-8");
		if ($_POST["id"] == '') {
			echo '<script>alert(\'缺少参数或参数不正确\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			exit ;
		} else {
			$numinfo = D("Goodnum") -> getById($_POST["id"]);
			if (!$numinfo) {
				echo '<script>alert(\'该靓号不存在\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit ;
			}
		}
		
		$Goodnum = D("Goodnum");
		$goodnum=$Goodnum->where("id=$_POST[id]")->getField("num");
		$vo = $Goodnum -> create();
		if (!$vo) {
			$this -> error($Goodnum -> getError());
		} else {

			$Goodnum -> save();
		}
		userLog(session('adminid'), "修改靓号，号码：".$goodnum);
		echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';

	}
	//赠送靓号
	public function give_goodnum() {
		if ($_GET['numid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$numinfo = D("Goodnum") -> getById($_GET["numid"]);
			if ($numinfo) {
				if ($numinfo['issale'] == 'y') {
					echo '<script>alert(\'该靓号已销售不可赠送\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				}
				$this -> assign('numinfo', $numinfo);
			} else {
				echo '<script>alert(\'找不到该靓号\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//赠送靓号动作
	public function do_give_goodnum() {
		header("Content-type: text/html; charset=utf-8");
		if ($_POST["id"] == '') {
			echo '<script>alert(\'缺少参数或参数不正确\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			exit ;
		} else {
			$numinfo = D("Goodnum") -> getById($_POST["id"]);
			if (!$numinfo) {
				echo '<script>alert(\'该靓号不存在\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit ;
			}
		}

		if ($_POST['givetouid'] == '') {
			echo '<script>alert(\'赠送对象UID不能为空\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			exit ;
		} else {
			$emceeinfo = D("Member") -> getById($_POST['givetouid']);
			if ($emceeinfo) {
				D("Roomnum") -> execute('delete from ss_roomnum where num="' . $numinfo['num'] . '"');
				D("Roomnum") -> execute('insert into ss_roomnum(uid,num,addtime,expiretime,original) values(' . $_POST['givetouid'] . ',' . $numinfo['num'] . ',' . time() . ',0,"n")');
				D("Goodnum") -> execute('update ss_goodnum set issale="y",owneruid=' . $_POST['givetouid'] . ',remark="管理员赠送" where id=' . $_POST["id"]);
				D("Giveaway") -> execute('insert into ss_giveaway(uid,touid,content,remark,objectIcon,addtime) values(0,' . $_POST['givetouid'] . ',"(' . $numinfo['num'] . ')","系统赠送","/Public/images/gnum.png",' . time() . ')');
			} else {
				echo '<script>alert(\'找不到该赠送对象\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit ;
			}
		}
		userLog(session('adminid'), "赠送靓号，用户id：".$_POST['givetouid']);
		echo '<script>alert(\'赠送成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';

	}
	//删除靓号
	public function del_goodnum() {
		if ($_GET["numid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Goodnum");
			$numinfo = $dao -> getById($_GET["numid"]);
			if ($numinfo) {
				if ($numinfo['issale'] == 'y') {
					$this -> error('该靓号已销售不可删除');
				}
				$dao -> where('id=' . $_GET["numid"]) -> delete();
				userLog(session('adminid'), "删除靓号，号码：".$numinfo['num']);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该靓号');
			}
		}
	}

	public function opt_goodnum() {
		$dao = D("Goodnum");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$numinfo = $dao -> getById($array[$i]);
						if ($numinfo) {
							if ($numinfo['issale'] == 'n') {
								$dao -> where('id=' . $array[$i]) -> delete();
							}
						}
					}
				}
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//收回靓号
	public function recycle_goodnum() {
		if ($_GET["numid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Goodnum");
			$numinfo = $dao -> getById($_GET["numid"]);
			if ($numinfo) {
				$emceeoldnum = D("Roomnum") -> where('uid=' . $numinfo['owneruid'] . ' and original="y"') -> select();
				D("Roomnum") -> execute('delete from ss_roomnum where num="' . $numinfo['num'] . '"');
				$dao -> execute('update ss_goodnum set issale="n",owneruid=0,remark="" where id=' . $_GET["numid"]);
				D("Member") -> execute('update ss_member set curroomnum=' . $emceeoldnum[0]['num'] . ' where id=' . $numinfo['owneruid']);
				userLog(session('adminid'), "收回靓号，号码：".$numinfo['num']);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功收回');
			} else {
				$this -> error('找不到该靓号');
			}
		}
	}
	//添加靓号
	public function add_goodnum() {
		$this -> display();
	}
	//添加靓号动作
	public function do_add_goodnum() {
		if ($_POST['num'] == '') {
			$this -> error('靓号不能为空');
		}

		if ($_POST['price'] == '') {
			$this -> error('价格不能为空');
		}

		$numinfo = D("Goodnum") -> where('num=' . $_POST['num']) -> select();
		if ($numinfo) {
			$this -> error('该靓号已存在');
		}

		$goodnum = D('Goodnum');
		$vo = $goodnum -> create();
		if (!$vo) {
			$this -> error($goodnum -> getError());
		} else {
			$goodnum -> length = strlen($_POST['num']);
			$goodnum -> add();
			userLog(session('adminid'), "添加靓号，号码：".$_POST['num']);
			$this -> assign('jumpUrl', __URL__ . '/admin_goodnum/');
			$this -> success('添加成功');
		}
	}
	//批量添加靓号
	public function add_goodnum_bat() {
		$this -> display();
	}
	//批量添加靓号动作
	public function do_add_goodnum_bat() {
		set_time_limit(0);

		header('Content-Type: text/html;charset=utf-8');
		ob_end_flush();
		echo '<style>body { font:normal 12px/20px Arial, Verdana, Lucida, Helvetica, simsun, sans-serif; color:#313131; }</style>';
		echo str_pad("", 1000);
		echo '准备开始添加...<br>';
		flush();

		for ($i = (int)$_POST['startnum']; $i <= (int)$_POST['endnum']; $i++) {
			echo '正在添加靓号' . $i . ' ';
			$numinfo = D("Goodnum") -> where('num=' . $i) -> select();
			if ($numinfo) {
				echo '已存在';
			} else {
				D("Goodnum") -> execute('insert into ss_goodnum(num,length,price,addtime) values(' . $i . ',' . strlen($i) . ',' . $_POST['price'] . ',' . time() . ')');
				echo '添加成功';
			}
			echo '<br>';
		}
		userLog(session('adminid'), "批量添加靓号");
		echo '批量添加完毕';
	}
	//砸蛋游戏设置
	public function admin_eggset() {
		$eggsetinfo = D("Eggset") -> find(1);
		if ($eggsetinfo) {
			$this -> assign('eggsetinfo', $eggsetinfo);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}
	//修改砸蛋游戏设置
	public function save_eggset() {
		$eggset = D('Eggset');
		$vo = $eggset -> create();
		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_eggset/');
			$this -> error('修改失败');
		} else {
			$eggset -> save();
			userLog(session('adminid'),"修改砸蛋游戏设置");
			$this -> assign('jumpUrl', __URL__ . '/admin_eggset/');
			$this -> success('修改成功');
		}
	}

	public function admin_eggwinrecord() {
		$condition = 'remark="砸蛋奖励"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '') {

			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and touid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

		}

		$orderby = 'id desc';
		$giveaway = D("Giveaway");
		$count = $giveaway -> where($condition) -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$giveaways = $giveaway -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();

		echo mysql_error();
		foreach ($giveaways as $n => $val) {
			$giveaways[$n]['voo'] = D("Member") -> where('id=' . $val['touid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('giveaways', $giveaways);

		$this -> display();
	}

	//财务
	//接口参数设置
	public function admin_onlinepay() {
		$siteconfig = D("Siteconfig") -> find(1);
		if ($siteconfig) {
			$this -> assign('siteconfig', $siteconfig);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}
	//修改接口参数设置
	public function save_onlinepay() {
		$siteconfig = D('Siteconfig');
		$vo = $siteconfig -> create();
		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_onlinepay/');
			$this -> error('修改失败');
		} else {
			$siteconfig -> save();
			userLog(session('adminid'), "修改在线支付接口参数");
			$this -> assign('jumpUrl', __URL__ . '/admin_onlinepay/');
			$this -> success('修改成功');
		}
	}
	//用户充值记录
	public function admin_chargerecord() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名或交易号') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if (preg_match("/^\d*$/", $_GET['keyword'])) {
				if ($keyuinfo) {
					$condition .= ' and (uid=' . $keyuinfo[0]['id'] . ' or orderno="' . $_GET['keyword'] . '")';
				} else {
					$condition .= ' and orderno="' . $_GET['keyword'] . '"';
				}
			} else {
				if ($keyuinfo) {
					$condition .= ' and uid=' . $keyuinfo[0]['id'];
				} else {
					$this -> error('没有该用户的记录');
				}
			}

			
		}
		if ($_GET['status'] != '') {
			$condition .= ' and status="' . $_GET['status'] . '"';
		}
		$orderby = 'id desc';
		$chargedetail = D("Chargedetail");
		$count = $chargedetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$charges = $chargedetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($charges as $n => $val) {
			$charges[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
			if(!$charges[$n]['voo']){
				$charges[$n]['voo'] = array(
				   0 => ''
				);
			}
			$charges[$n]['voo2'] = D("Member") -> where('id=' . $val['touid']) -> select();
			if(!$charges[$n]['voo2']){
				$charges[$n]['voo2'] = array(
				   0 => ''
				);
			}
			if ($val['touid'] != 0) {
				$charges[$n]['voo3'] = D("Member") -> where('id=' . $val['proxyuid']) -> select();
			}
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('charges', $charges);

		$charges_all = $chargedetail -> where($condition) -> order($orderby) -> select();
		$this -> assign('charges_all', $charges_all);

		$this -> display();
	}
	//删除用户充值记录
	public function del_chargerecord() {
		if ($_GET["chargeid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Chargedetail");
			$chargeinfo = $dao -> getById($_GET["chargeid"]);
			if ($chargeinfo) {
				$dao -> where('id=' . $_GET["chargeid"]) -> delete();
				userLog(session('adminid'), "删除用户充值记录，记录id：".$_GET['chargeid']);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该交易记录');
			}
		}
	}
	//批量删除用户充值记录
	public function opt_chargerecord() {
		$dao = D("Chargedetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$chargeinfo = $dao -> getById($array[$i]);
						if ($chargeinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "批量删除用户充值记录");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//给用户手动充值
	public function addcointouser() {
		$this -> display();
	}
	//给用户手动充值动作
	public function do_addcointouser() {
		if ($_POST['username'] != '') {
			$userinfo = D("Member") -> where('username="' . $_POST['username'] . '"') -> select();
			if ($userinfo) {
				if ($_POST['math'] == 'plus') {
					D("Member") -> execute('update ss_member set coinbalance=coinbalance+' . $_POST['addcoin'] . ' where id=' . $userinfo[0]['id']);

					D("Giveaway") -> execute('insert into ss_giveaway(uid,touid,content,remark,objectIcon,addtime,operator,operatorip) values(0,' . $userinfo[0]['id'] . ',"' . $_POST['addcoin'] . '","系统赠送","/Public/images/coin.png",' . time() . ',"' . $_SESSION['adminname'] . '","' . get_client_ip() . '")');
				}
				if ($_POST['math'] == 'subtract') {
					D("Member") -> execute('update ss_member set coinbalance=coinbalance-' . $_POST['addcoin'] . ' where id=' . $userinfo[0]['id']);

					D("Giveaway") -> execute('insert into ss_giveaway(uid,touid,content,remark,objectIcon,addtime,operator,operatorip) values(0,' . $userinfo[0]['id'] . ',"-' . $_POST['addcoin'] . '","系统抵扣","/Public/images/coin.png",' . time() . ',"' . $_SESSION['adminname'] . '","' . get_client_ip() . '")');
				}
				
				if($_POST['math']=='plus'){
					$msg="手动充值，用户名:".$_POST['username']." 金额：加".$_POST['addcoin'];
				}
				
				if($_POST['math']=='subtract'){
					$msg="手动充值，用户名:".$_POST['username']." 金额：减".$_POST['addcoin'];
				}
				
				userLog(session('adminid'), $msg);
				
				$this -> assign('jumpUrl', __URL__ . '/addcointouser/');
				$this -> success('操作成功');
			} else {
				$this -> error('未找到该用户');
			}
		} else {
			$this -> error('请填写相关选项');
		}
	}
	//用户消费记录
	public function admin_coindetail() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		if ($_GET['keyword2'] != '' && $_GET['keyword2'] != '请输入用户名') {
			$keyuinfo2 = D("Member") -> where('username="' . $_GET['keyword2'] . '"') -> select();
			if ($keyuinfo2) {
				$condition .= ' and touid=' . $keyuinfo2[0]['id'];
			} else {
				$this -> error('没有该对象的记录');
			}

			
		}
		$orderby = 'id desc';
		$coindetail = D("Coindetail");
		$count = $coindetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $coindetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();

		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
			if ($val['touid'] != 0) {
				$details[$n]['voo2'] = D("Member") -> where('id=' . $val['touid']) -> select();
			}
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}
	//删除用户消费记录
	public function del_coindetail() {
		if ($_GET["detailid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Coindetail");
			$detailinfo = $dao -> getById($_GET["detailid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["detailid"]) -> delete();
				userLog(session('adminid'), "删除用户消费记录，记录id：".$_GET["detailid"]);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该消费记录');
			}
		}
	}
	//批量删除用户消费记录
	public function opt_coindetail() {
		$dao = D("Coindetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "删除用户消费记录");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//管理员加值记录
	public function admin_adminaddcoinrecord() {
		$condition = 'uid=0 and objectIcon="/Public/images/coin.png" and remark="系统赠送"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and touid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}

		$orderby = 'id desc';
		$giveaway = D("Giveaway");
		$count = $giveaway -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$giveaways = $giveaway -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($giveaways as $n => $val) {
			$giveaways[$n]['voo'] = D("Member") -> where('id=' . $val['touid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('giveaways', $giveaways);

		$this -> display();
	}
	//主播收支明细
	public function admin_beandetail() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		$orderby = 'id desc';
		$beandetail = D("Beandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}
	//删除主播收支明细
	public function del_beandetail() {
		if ($_GET["detailid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Beandetail");
			$detailinfo = $dao -> getById($_GET["detailid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["detailid"]) -> delete();
				userLog(session('adminid'), "删除主播收支明细，记录id:".$_GET["detailid"]);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}
	//批量删除主播收支明细
	public function opt_beandetail() {
		$dao = D("Beandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "批量删除主播收支明细");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
public function count_emceeincome() {
	$emcces = D("Member") -> where('sign="y"') -> order('regtime desc') -> select();
	echo '共有' . count($emcces) . '个签约主播<br>';
	$this -> display();
}

	//原主播收入统计函数
	public function countde_emceeincome() {
		set_time_limit(0);

		header('Content-Type: text/html;charset=utf-8');
		ob_end_flush();
		echo '<style>body { font:normal 12px/20px Arial, Verdana, Lucida, Helvetica, simsun, sans-serif; color:#313131; }</style>';
		echo str_pad("", 1000);
		echo '准备开始统计...<br>';
		flush();

		$emcces = D("Member") -> where('sign="y"') -> order('regtime desc') -> select();
		echo '共有' . count($emcces) . '个签约主播<br>';
		foreach ($emcces as $n => $val) {
			if (connection_aborted()) {
				exit ;
			}
			echo '正在统计主播 ' . $val['nickname'] . '<br>';
			if ($val['freezestatus'] == '1') {
				if (($val['beanbalance'] - $val['freezeincome']) > 0) {
					D("Member") -> execute('update ss_member set freezeincome=0,freezestatus="0" where id=' . $val['id']);
				}
			}
			if (($val['beanbalance'] - $val['freezeincome']) > 0) {
				$costbean = $val['beanbalance'] - $val['freezeincome'];

				D("Member") -> execute('update ss_member set beanbalance=beanbalance-' . $costbean . ' where id=' . $val['id']);

				$Beandetail = D("Beandetail");
				$Beandetail -> create();
				$Beandetail -> type = 'expend';
				$Beandetail -> action = 'settlement';
				$Beandetail -> uid = $val['id'];
				$Beandetail -> content = '系统结算';
				$Beandetail -> bean = $costbean;
				$Beandetail -> addtime = time();
				$detailId = $Beandetail -> add();
			}
		}
		echo '<a href="' . __URL__ . '/admin_emccepayrecord/">返回</a>';
	}
	//主播结算记录
	public function admin_emccepayrecord() {
		$condition = 'type="expend" and action="settlement"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		
		//将查询条件添加至 $_SESSION;
		$_SESSION['excel_export']['zhubojiesuan'] = $condition;
		$orderby = 'id desc';
		$beandetail = D("Beandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}
	//删除主播结算记录
	public function del_emccepayrecord() {
		if ($_GET["recordid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Beandetail");
			$detailinfo = $dao -> getById($_GET["recordid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["recordid"]) -> delete();
				userLog(session('adminid'), "删除主播结算记录，记录id：".$_GET["recordid"]);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}
	//批量删除主播结算记录
	public function opt_emccepayrecord() {
		$dao = D("Beandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "批量删除主播结算记录");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//修改主播结算记录
	public function edit_emccepayrecord() {
		header("Content-type: text/html; charset=utf-8");
		if ($_GET['recordid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$recordinfo = D("Beandetail") -> find($_GET["recordid"]);
			if ($recordinfo) {
				$this -> assign('recordinfo', $recordinfo);
				$userinfo = D("Member") -> find($recordinfo["uid"]);
				$this -> assign('userinfo', $userinfo);
			} else {
				echo '<script>alert(\'找不到该记录\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//修改主播结算记录动作
	public function do_edit_emccepayrecord() {
		header("Content-type: text/html; charset=utf-8");
		$beandetail = D('Beandetail');
		$vo = $beandetail -> create();
		if (!$vo) {
			echo '<script>alert(\'' . $admin -> getError() . '\');window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$beandetail -> save();
			userLog(session('adminid'), "修改主播结算记录，记录id：".$_POST['id']);
			echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		}
	}
	//家族收支明细
	public function admin_emceeagentbeandetail() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		$orderby = 'id desc';
		$beandetail = D("Emceeagentbeandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}
	//删除家族收支明细
	public function del_emceeagentbeandetail() {
		if ($_GET["detailid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Emceeagentbeandetail");
			$detailinfo = $dao -> getById($_GET["detailid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["detailid"]) -> delete();
				userLog(session('adminid'), "删除家族收支明细记录，记录id：".$_GET["detailid"]);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}
	//批量删除家族收支明细
	public function opt_emceeagentbeandetail() {
		$dao = D("Emceeagentbeandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "删除家族收支明细记录");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	public function count_emceeagentincome() {
		$emcces = D("Member") -> where('emceeagent="y"') -> order('regtime desc') -> select();
		echo '共有' . count($emcces) . '个家族族长<br>';
		$this -> display();
	}
	//统计家族收入
	public function countde_emceeagentincome() {
		set_time_limit(0);

		header('Content-Type: text/html;charset=utf-8');
		ob_end_flush();
		echo '<style>body { font:normal 12px/20px Arial, Verdana, Lucida, Helvetica, simsun, sans-serif; color:#313131; }</style>';
		echo str_pad("", 1000);
		echo '准备开始统计...<br>';
		flush();

		$emcces = D("Member") -> where('emceeagent="y"') -> order('regtime desc') -> select();
		echo '共有' . count($emcces) . '个家族族长<br>';
		foreach ($emcces as $n => $val) {
			if (connection_aborted()) {
				exit ;
			}
			echo '正在统计族长 ' . $val['nickname'] . '<br>';
			
			if ($val['beanbalance2'] > 0) {
				$costbean = $val['beanbalance2'];

				D("Member") -> execute('update ss_member set beanbalance2=beanbalance2-' . $costbean . ' where id=' . $val['id']);

				$Beandetail = D("Emceeagentbeandetail");
				$Beandetail -> create();
				$Beandetail -> type = 'expend';
				$Beandetail -> action = 'settlement';
				$Beandetail -> uid = $val['id'];
				$Beandetail -> content = '系统结算';
				$Beandetail -> bean = $costbean;
				$Beandetail -> addtime = time();
				$detailId = $Beandetail -> add();
			}
		}
		echo '<a href="' . __URL__ . '/admin_emcceagentpayrecord/">返回</a>';
	}
	//家族结算记录
	public function admin_emcceagentpayrecord() {
		$condition = 'type="expend" and action="settlement"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= '  and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		//将查询条件添加至 $_SESSION;
		$_SESSION['excel_export']['jiazujiesuan'] = $condition;
		$orderby = 'id desc';
		$beandetail = D("Emceeagentbeandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}
	//删除家族结算记录
	public function del_emcceagentpayrecord() {
		if ($_GET["recordid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Emceeagentbeandetail");
			$detailinfo = $dao -> getById($_GET["recordid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["recordid"]) -> delete();
				userLog(session('adminid'), "删除家族结算记录，记录id：".$_GET["recordid"]);
				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}

//家族结算 excel导出
public function export_emcceagentpayrecord() {
		//查询数据
		$condition = 'type="expend" and action="settlement"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= '  and addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		$orderby = 'id desc';
		$beandetail = D("Emceeagentbeandetail");
		$count = $beandetail -> where($_SESSION['excel_export']['jiazujiesuan']) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($_SESSION['excel_export']['jiazujiesuan']) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		
		
		
		Vendor('excel.PHPExcel');
		$objPHPExcel = new PHPExcel();
		// Set properties
		$objPHPExcel -> getProperties() -> setCreator("ctos") -> setLastModifiedBy("ctos") -> setTitle("Office 2007 XLSX Test Document") -> setSubject("Office 2007 XLSX Test Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		// set width
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(50);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);

		// 设置行高度
		$objPHPExcel -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(22);

		$objPHPExcel -> getActiveSheet() -> getRowDimension('2') -> setRowHeight(20);

		// 字体和样式
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(10);
		
		// 设置水平居中
		$objPHPExcel -> getActiveSheet() -> getStyle('A1') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('F') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('G') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		// 表头
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '用户') -> setCellValue('B1', '豆') -> setCellValue('C1', '人民币(元)') -> setCellValue('D1', '状态') -> setCellValue('E1', '备注') -> setCellValue('F1', '操作时间');

		// 内容
		
		
		for ($i = 0, $len = count($details); $i < $len; $i++) {
			//判别处理状态
			$status = $details[$i]['status'];
			if($details[$i]['status']=="")
			{
				$status="未处理";
			}
			$time = date("Y-m-d",$details[$i]['addtime']);
			//格式化时间
			
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($i + 2), "UID:".$details[$i]['voo'][0]['id'].'|'.$details[$i]['voo'][0]['username'].'('.$details[$i]['voo'][0]['curroomnum'].')');
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($i + 2), $details[$i]['bean']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($i + 2), round($details[$i]['bean'] / 100));
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($i + 2), $status);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($i + 2), $details[$i]['remark']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($i + 2), $time);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getBorders() -> getAllBorders() -> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel -> getActiveSheet() -> getRowDimension($i + 2) -> setRowHeight(16);
		}
	
	
		// Rename sheet
		$objPHPExcel -> getActiveSheet() -> setTitle("家族结算");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);

		// 输出
		$nowTime = date("Y-m-d");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . "家族结算" .$nowTime.'.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter -> save('php://output');
	}


	//excel 主播结算导出
	public function export_emccepayrecord() {
		//查询数据
		$condition = 'type="expend" and action="settlement"';
		$beandetail = D("Beandetail");
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$field = "bean,status,remark,type,addtime,uid";
		$details = $beandetail -> order("addtime DESC")->field($field)->where($_SESSION['excel_export']['zhubojiesuan']) -> select();
		foreach ($details as $n => $val) {
			  $details[$n]['voo'] = D("Member") -> where('id=' . $val['uid'])->field("username,nickname,curroomnum,id") -> select();
		}
		Vendor('excel.PHPExcel');
		$objPHPExcel = new PHPExcel();
		// Set properties
		$objPHPExcel -> getProperties() -> setCreator("ctos") -> setLastModifiedBy("ctos") -> setTitle("Office 2007 XLSX Test Document") -> setSubject("Office 2007 XLSX Test Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		// set width
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(50);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);

		// 设置行高度
		$objPHPExcel -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(22);

		$objPHPExcel -> getActiveSheet() -> getRowDimension('2') -> setRowHeight(20);

		// 字体和样式
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(10);
		
		// 设置水平居中
		$objPHPExcel -> getActiveSheet() -> getStyle('A1') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('F') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('G') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		// 表头
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '用户') -> setCellValue('B1', '豆') -> setCellValue('C1', '人民币(元)') -> setCellValue('D1', '状态') -> setCellValue('E1', '备注') -> setCellValue('F1', '操作时间');

		// 内容
		
		
		for ($i = 0, $len = count($details); $i < $len; $i++) {
			//判别处理状态
			$status = $details[$i]['status'];
			if($details[$i]['status']=="")
			{
				$status="未处理";
			}
			$time = date("Y-m-d",$details[$i]['addtime']);
			//格式化时间
			
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($i + 2), "UID:".$details[$i]['voo'][0]['id'].'|'.$details[$i]['voo'][0]['username'].'('.$details[$i]['voo'][0]['curroomnum'].')');
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($i + 2), $details[$i]['bean']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($i + 2), round($details[$i]['bean'] / 100));
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($i + 2), $status);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($i + 2), $details[$i]['remark']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($i + 2), $time);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getBorders() -> getAllBorders() -> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel -> getActiveSheet() -> getRowDimension($i + 2) -> setRowHeight(16);
		}
	
	
		// Rename sheet
		$objPHPExcel -> getActiveSheet() -> setTitle("主播结算");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);

		// 输出
		$nowTime = date("Y-m-d");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . "主播结算" .$nowTime.'.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter -> save('php://output');
	}
	//批量删除家族结算明细
	public function opt_emcceagentpayrecord() {
		$dao = D("Emceeagentbeandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				userLog(session('adminid'), "批量删除家族结算明细");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}
	//修改家族结算明细
	public function edit_emcceagentpayrecord() {
		header("Content-type: text/html; charset=utf-8");
		if ($_GET['recordid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$recordinfo = D("Emceeagentbeandetail") -> find($_GET["recordid"]);
			if ($recordinfo) {
				$this -> assign('recordinfo', $recordinfo);
				$userinfo = D("Member") -> find($recordinfo["uid"]);
				$this -> assign('userinfo', $userinfo);
			} else {
				echo '<script>alert(\'找不到该记录\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}
	//修改家族结算明细动作
	public function do_edit_emcceagentpayrecord() {
		header("Content-type: text/html; charset=utf-8");
		$beandetail = D('Emceeagentbeandetail');
		$vo = $beandetail -> create();
		if (!$vo) {
			echo '<script>alert(\'' . $admin -> getError() . '\');window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$beandetail -> save();
			userLog(session('adminid'), "修改家族结算记录，记录id：".$_POST['id']);
			echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		}
	}

	public function admin_payagentbeandetail() {
		$condition = 'id>0';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= '  and  addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

		}
		$orderby = 'id desc';
		$beandetail = D("Payagentbeandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}

	public function del_payagentbeandetail() {
		if ($_GET["detailid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Payagentbeandetail");
			$detailinfo = $dao -> getById($_GET["detailid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["detailid"]) -> delete();

				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}

	public function opt_payagentbeandetail() {
		$dao = D("Payagentbeandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}

	//统计充值代理收入
	public function count_payagentincome() {
		set_time_limit(0);

		header('Content-Type: text/html;charset=utf-8');
		ob_end_flush();
		echo '<style>body { font:normal 12px/20px Arial, Verdana, Lucida, Helvetica, simsun, sans-serif; color:#313131; }</style>';
		echo str_pad("", 1000);
		echo '准备开始统计...<br>';
		flush();

		$emcces = D("Member") -> where('payagent="y"') -> order('regtime desc') -> select();
		echo '共有' . count($emcces) . '个充值代理<br>';
		foreach ($emcces as $n => $val) {
			if (connection_aborted()) {
				exit ;
			}
			echo '正在统计充值代理 ' . $val['nickname'] . '<br>';
			
			if ($val['beanbalance3'] > 0) {
				$costbean = $val['beanbalance3'];

				D("Member") -> execute('update ss_member set beanbalance3=beanbalance3-' . $costbean . ' where id=' . $val['id']);

				$Beandetail = D("Payagentbeandetail");
				$Beandetail -> create();
				$Beandetail -> type = 'expend';
				$Beandetail -> action = 'settlement';
				$Beandetail -> uid = $val['id'];
				$Beandetail -> content = '系统结算';
				$Beandetail -> bean = $costbean;
				$Beandetail -> addtime = time();
				$detailId = $Beandetail -> add();
			}
		}
		echo '<a href="' . __URL__ . '/admin_payagentpayrecord/">返回</a>';
	}

	public function admin_payagentincome() {
		$sql = "select uid,sum(bean) as bean from ss_payagentbeandetail group by uid";
		$data = M() -> query($sql);
		foreach ($data as $k => $v) {
			$userinfo = M('Member') -> field('username') -> where('id=' . $v['uid'] . ' and payagent="y"') -> find();
			$data[$k]['username'] = $userinfo['username'];
		}
		$this -> assign('data', $data);
		$this -> display();

	}

	public function admin_payagentpayrecord() {
		$condition = 'type="expend" and action="settlement"';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= '  and  addtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户名') {
			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and uid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

			
		}
		$orderby = 'id desc';
		$beandetail = D("Payagentbeandetail");
		$count = $beandetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$details = $beandetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		foreach ($details as $n => $val) {
			$details[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('details', $details);

		$this -> display();
	}

	public function del_payagentpayrecord() {
		if ($_GET["recordid"] == '') {
			$this -> error('缺少参数或参数不正确');
		} else {
			$dao = D("Payagentbeandetail");
			$detailinfo = $dao -> getById($_GET["recordid"]);
			if ($detailinfo) {
				$dao -> where('id=' . $_GET["recordid"]) -> delete();

				$this -> assign('jumpUrl', base64_decode($_GET['return']));
				$this -> success('成功删除');
			} else {
				$this -> error('找不到该记录');
			}
		}
	}

	public function opt_payagentpayrecord() {
		$dao = D("Payagentbeandetail");
		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$detailinfo = $dao -> getById($array[$i]);
						if ($detailinfo) {
							$dao -> where('id=' . $array[$i]) -> delete();

						}
					}
				}
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				break;
		}
	}

	public function edit_payagentpayrecord() {
		header("Content-type: text/html; charset=utf-8");
		if ($_GET['recordid'] == '') {
			echo '<script>alert(\'参数错误\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$recordinfo = D("Payagentbeandetail") -> find($_GET["recordid"]);
			if ($recordinfo) {
				$this -> assign('recordinfo', $recordinfo);
				$userinfo = D("Member") -> find($recordinfo["uid"]);
				$this -> assign('userinfo', $userinfo);
			} else {
				echo '<script>alert(\'找不到该记录\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}

		$this -> display();
	}

	public function do_edit_payagentpayrecord() {
		header("Content-type: text/html; charset=utf-8");
		$beandetail = D('Payagentbeandetail');
		$vo = $beandetail -> create();
		if (!$vo) {
			echo '<script>alert(\'' . $admin -> getError() . '\');window.top.art.dialog({id:"edit"}).close();</script>';
		} else {
			$beandetail -> save();

			echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
		}
	}

	//界面
	public function admin_template() {
		$this -> display();
	}

	public function tpl_updatefilename() {
		$filepath = '../' . $_POST['style'] . '/config.php';
		if (file_exists($filepath)) {
			$style_info =
			include $filepath;
		}

		$file_explan = isset($_POST['file_explan']) ? $_POST['file_explan'] : '';
		if (!isset($style_info['file_explan']))
			$style_info['file_explan'] = array();
		$style_info['file_explan'] = array_merge($style_info['file_explan'], $file_explan);
		@file_put_contents($filepath, '<?php return ' . var_export($style_info, true) . ';?>');
		$this -> success('修改成功');
	}

	public function admin_dirlist() {
		$this -> display();
	}

	public function admin_dirlist2() {
		$this -> display();
	}

	public function edit_file() {
		$basedir = realpath("../");
		$fp = fopen($basedir . base64_decode($_GET['file']), "r");
		$contents = fread($fp, filesize($basedir . base64_decode($_GET['file'])));
		$contents = str_replace('</textarea>', '&lt;/textarea>', $contents);
		$this -> assign('contents', $contents);
		$this -> display();
	}

	public function do_edit_file() {
		$basedir = realpath("../");
		$fp = fopen($basedir . $_POST['file'], "wb");
		$contents = str_replace('&lt;/textarea>', '</textarea>', $_POST['str']);
		fputs($fp, stripslashes($contents));
		fclose($fp);
		$this -> assign('jumpUrl', __URL__ . "/admin_dirlist2/?action=chdr&file=" . base64_encode($_POST['wdir']));
		$this -> success('保存成功');
	}

	//数据库操作
	public function admin_database() {
		$model = new Model();
		$li = $model -> query("show table status");
		$count_free_data = 0;
		$count_data = 0;
		$j = 0;
		for ($i = 0; $i < count($li); $i++) {
			if (preg_match("/^ss_+[a-zA-Z0-9_-]+$/", $li[$i]['Name'])) {
				$li[$i]['Data_length'] += $li[$i]['Index_length'];
				$li[$i]['Data_length'] = round(floatval($li[$i]['Data_length'] / 1024), 2);
				$count_free_data += $li[$i]['Data_free'];
				$count_data += $li[$i]['Data_length'];
				$list[$j] -> Name = $li[$i]['Name'];
				$list[$j] -> Rows = $li[$i]['Rows'];
				$list[$j] -> Data_length = $li[$i]['Data_length'];
				$list[$j] -> Data_free = $li[$i]['Data_free'];
				$j++;
			}
		}
		$this -> assign("list", $list);
		$this -> assign("count_free_data", $count_free_data);
		$this -> assign("count_data", $count_data);
		$this -> display();
	}
	//修复数据库
	public function repair_table() {
		if (!empty($_GET['name'])) {
			$model = new Model();
			$list = $model -> query("repair table " . $_GET['name']);
			if ($list !== false) {
				userLog(session('adminid'), "修复数据表".$_GET['name']);
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> success('修复成功');
			} else {
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> error('修复失败');
			}
		} else {
			$this -> error('参数错误！');
		}
	}
	//
	public function optimize_table() {
		if (!empty($_GET['name'])) {
			$model = new Model();
			$list = $model -> query("optimize table " . $_GET['name']);
			if ($list !== false) {
				userLog(session('adminid'), "优化数据表".$_GET['name']);
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> success('优化成功');
			} else {
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> error('优化失败');
			}
		} else {
			$this -> error('参数错误！');
		}
	}
	//执行sql语句
	public function exec_sql() {
		if (!empty($_POST['sqlquery'])) {
			$model = new Model();
			$list = $model -> query($_POST['sqlquery']);
			if ($list !== false) {
				userLog(session('adminid'), "执行sql语句");
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> success('sql语句成功执行了');
			} else {
				$this -> assign('jumpUrl', __URL__ . "/admin_database/");
				$this -> error('sql语句执行失败');
			}
		} else {
			$this -> error('SQL语句不能为空！');
		}
	}
	//备份数据库
	public function backup_database() {
		$model = new Model();
		$li = $model -> query("show table status");
		$j = 0;
		for ($i = 0; $i < count($li); $i++) {
			if (preg_match("/^ss_+[a-zA-Z0-9_-]+$/", $li[$i]['Name'])) {
				$list[$j] -> Name = $li[$i]['Name'];
				$j++;
			}
		}
		$this -> assign("list", $list);

		$this -> display();
	}

	public function restore_database() {
		$this -> display();
	}
	//红包设置
	public function admin_redbagset() {
		$redbagsetinfo = D("Siteconfig") -> find(1);
		if ($redbagsetinfo) {
			$this -> assign('redbagsetinfo', $redbagsetinfo);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}

	//修改红包设置
	public function save_redbagset() {
		$redbagset = D('Siteconfig');
		$vo = $redbagset -> create();
		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_redbagset/');
			$this -> error('修改失败');
		} else {
			$redbagset -> save();
			userLog(session('adminid'), "修改红包设置");
			$this -> assign('jumpUrl', __URL__ . '/admin_redbagset/');
			$this -> success('修改成功');
		}
	}

	//统计主播收入wp写
	public function admin_statisticsanchor() {
		$sql = "select uid,sum(bean) as bean from ss_beandetail group by uid";
		$data = M() -> query($sql);
		foreach ($data as $k => $v) {
			$userinfo = M('Member') -> field('username') -> where('id=' . $v['uid']) -> find();
			$data[$k]['username'] = $userinfo['username'];
		}
		$this -> assign('data', $data);
		$this -> display();
	}

	public function admin_countagentanchor() {
		$sql = "select uid,sum(bean) as bean from ss_Emceeagentbeandetail group by uid";
		$data = M() -> query($sql);
		foreach ($data as $k => $v) {
			$userinfo = M('Member') -> field('username') -> where('id=' . $v['uid']) -> find();
			$data[$k]['username'] = $userinfo['username'];
		}
		$this -> assign('data', $data);
		$this -> display();

	}
	//彻底删除普通用户
	public function truedel() {
		$username=M('member')->where("id=$_GET[uid]")->getField("username");
		M("member") -> delete($_GET['uid']);
		userLog(session('adminid'), "彻底删除注册用户，用户名：".$username);
		$this -> success("删除成功");
	}
	//秀场轮显设置
	public function xiuchang_voterollpic() {
		$rollpics = D("xiuchang_voterollpic") -> where("") -> order('orderno') -> select();
		$this -> assign("rollpics", $rollpics);
		$this -> display();
	}
	//修改秀场轮显设置
	public function save_xiuchang_voterollpic() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/rollpic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/rollpic/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		$Edit_Linkurl = $_POST['linkurl'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("xiuchang_voterollpic") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("xiuchang_voterollpic") -> execute('update ss_xiuchang_voterollpic set picpath="' . $Edit_Picpath[$i] . '",linkurl="' . $Edit_Linkurl[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $rollpicpath != '' && $_POST['add_linkurl'] != '') {
			$Rollpic = D('xiuchang_voterollpic');
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> linkurl = $_POST['add_linkurl'];
			$Rollpic -> addtime = time();
			$rollpicID = $Rollpic -> add();
		}
		userLog(session('adminid'), "修改秀场轮显设置");
		$this -> assign('jumpUrl', __URL__ . "/xiuchang_voterollpic/");
		$this -> success('操作成功');
	}
	//游戏轮显设置
	public function youxi_voterollpic() {
		$rollpics = D("youxi_voterollpic") -> where("") -> order('orderno') -> select();
		$this -> assign("rollpics", $rollpics);
		$this -> display();
	}
	//修改游戏轮显
	public function save_youxi_voterollpic() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/rollpic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/rollpic/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		$Edit_Linkurl = $_POST['linkurl'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("youxi_voterollpic") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("youxi_voterollpic") -> execute('update ss_youxi_voterollpic set picpath="' . $Edit_Picpath[$i] . '",linkurl="' . $Edit_Linkurl[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $rollpicpath != '' && $_POST['add_linkurl'] != '') {
			$Rollpic = D('youxi_voterollpic');
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> linkurl = $_POST['add_linkurl'];
			$Rollpic -> addtime = time();
			$rollpicID = $Rollpic -> add();
		}
		userLog(session("adminid"), "修改游戏轮显设置");
		$this -> assign('jumpUrl', __URL__ . "/youxi_voterollpic/");
		$this -> success('操作成功');
	}
	//家族列表
	public function jiazulist() {

		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and shtime>=' . $unixtime;
		}
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and shtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '' && $_GET['keyword'] != '请输入家族名字') {
			$condition .= ' and (familyname like \'%' . $_GET['keyword'] . '%\' or m.username like \'%' . $_GET['keyword'] . '%\'  or m.nickname like \'%' . $_GET['keyword'] . '%\' )';
		}

		$count = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> order("shtime desc") -> where($condition) -> count();
		//使用联合查询带分页 查询出申请用户的相关信息
		import("@.ORG.Page");
		$p = new Page($count, 20);
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$fix = C('DB_PREFIX');
		$field = "m.nickname,m.earnbean,af.*";

		$res = M('agentfamily af') -> field($field) -> join("{$fix}member m ON m.id=af.uid") -> order("shtime desc") -> where($condition) -> limit($p -> firstRow . "," . $p -> listRows) -> select();
		//根据查到的earnbean 查询用户等级
		$a = 0;
		foreach ($res as $k => $vo) {
			$emceelevel = getEmceelevel($vo['earnbean']);
			$res[$a]['emceelevel'] = $emceelevel;
			$a++;
		}

		switch ($_GET['action']) {

			case 'del' :
				if (is_array($_REQUEST['ids'])) {
					$array = $_REQUEST['ids'];
					$num = count($array);
					for ($i = 0; $i < $num; $i++) {
						$anninfo = M("agentfamily") -> getById($array[$i]);
						if ($anninfo) {
							M("agentfamily") -> where('id=' . $array[$i]) -> delete();
						}
					}
				}
				userLog(session('adminid'), "删除家族");
				$this -> assign('jumpUrl', base64_decode($_POST['return']) . '#' . time());
				$this -> success('操作成功');
				exit ;
				break;
		}
		$this -> assign("page", $page);
		$this -> assign("lists", $res);
		$this -> display();
	}

	public function post() {
		$code = $_REQUEST['code'];
		$site = M("siteconfig");
		$site -> auth_code = $code;
		$site -> where("id = 1") -> save();
	}

	private function AuthVerification() {
		$d = $_SERVER['HTTP_HOST'];
		$u = "";

		if (substr($d, 0, 4) == "www.") {
			$d = substr($d, 4);
		}

		$safecode = substr(strtoupper(md5($u . $d)), 2, 12);

		$siteconfig = M("siteconfig") -> find(1);
		$auth_code = $siteconfig['auth_code'];
		if ($auth_code == $safecode)
			return true;
		else
			return false;
	}

	

   
	//大屏监控
	public function admin_monitor() {
		import("@.ORG.Page");
		$count = D('Member') -> where("broadcasting = 'y'") -> count();
		$Page = new Page($count, 30);
		$conifgInfo = D('siteconfig') -> find();
		$onlineEmceeList = D('Member') -> where("broadcasting = 'y'") -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
		$show = $Page -> show();
		$pageCount = ceil($count/1);
		$this -> assign('page', $show);
		$this -> assign('configInfo', $conifgInfo);
		$this -> assign('onlineEmceeList', $onlineEmceeList);
		$this -> assign('pageCount',$pageCount);
		$this -> display();
	}

	//app轮显设置
	public function admin_apppic() {
		$rollpics = D("apppic") -> where("") -> order('orderno') -> select();
		$this -> assign("rollpics", $rollpics);
		$this -> display();
	}

	//修改app轮显设置
	public function save_apppic() {
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'jpg,png');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Public/apppic/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			$rollpicpath = '/Public/apppic/' . $prefix . '/' . $uploadList[0]['savename'];
		}

		$Edit_ID = $_POST['id'];
		$Edit_Orderno = $_POST['orderno'];
		$Edit_Picpath = $_POST['picpath'];
		$Edit_Linkurl = $_POST['linkurl'];
		$Edit_DelID = $_POST['ids'];

		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("apppic") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			D("apppic") -> execute('update ss_apppic set picpath="' . $Edit_Picpath[$i] . '",linkurl="' . $Edit_Linkurl[$i] . '",orderno=' . $Edit_Orderno[$i] . ' where id=' . $Edit_ID[$i]);
		}

		if ($_POST['add_orderno'] != '' && $rollpicpath != '' && $_POST['add_linkurl'] != '') {
			$Rollpic = D('apppic');
			$Rollpic -> create();
			$Rollpic -> orderno = $_POST['add_orderno'];
			$Rollpic -> picpath = $rollpicpath;
			$Rollpic -> linkurl = $_POST['add_linkurl'];
			$Rollpic -> addtime = time();
			$rollpicID = $Rollpic -> add();
		}
		userLog(session('adminid'), "修改APP轮显设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_apppic/");
		$this -> success('操作成功');
	}

	//app更新
	public function admin_appupdate() {

		if ($_POST != null) {
			$update = isset($_POST['update']) ? $_POST['update'] : '';
			$nodejs = isset($_POST['nodejs']) ? $_POST['nodejs'] : '';
			$fmsstream = isset($_POST['fmsstream']) ? $_POST['fmsstream'] : '';
			$offline_video_server = isset($_POST['offline_video_server']) ? $_POST['offline_video_server'] : '';
			if ($update != '') {

				D("siteconfig") -> query("update ss_siteconfig set appupdate={$update},nodejs='{$nodejs}',fmsstream='{$fmsstream}',offline_video_server='{$offline_video_server}'");
				userLog(session("adminid"), "修改APP设置");
				$this -> success("APP设置更新成功");
			}
		} else {

			$config = D("siteconfig") -> field("appupdate,nodejs,fmsstream,offline_video_server") -> find();

			$this -> assign('fmsstream', $config['fmsstream']);
			$this -> assign('update', $config['appupdate']);
			$this -> assign('nodejs', $config['nodejs']);
			$this -> assign('offline_video_server', $config['offline_video_server']);
			$this -> display();
		}

	}

	public function admin_shieldWordImport() {
		$this -> display();
	}

	public function do_shieldWordImport() {

		//上传缩略图
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'xls,xlsx');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = date('Y-m');
		$uploadPath = '../Admin/excel/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {

			$this -> error($upload -> getErrorMsg());
		} else {
			$uploadList = $upload -> getUploadFileInfo();

			$filename = "../Admin/excel/" . $prefix . "/" . $uploadList[0]['savename'];
			Vendor('excel.PHPExcel');

			$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
			$objPHPExcel = $objReader -> load($filename);
			$sheet = $objPHPExcel -> getSheet(0);
			$highestRow = $sheet -> getHighestRow();
			// 取得总行数
			$highestColumn = $sheet -> getHighestColumn();
			// 取得总列数
			$objPHPExcel -> setActiveSheetIndex(0);
			$date;
			for ($i = 1; $i <= $highestRow; $i++) {

				$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('A' . $i) -> getValue();
				if ($objPHPExcel -> getActiveSheet() -> getCell('B' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('B' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('C' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('C' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('D' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('D' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('E' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('E' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('F' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('F' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('G' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('G' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('H' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('H' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('I' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('I' . $i) -> getValue();

				if ($objPHPExcel -> getActiveSheet() -> getCell('J' . $i) -> getValue() != NULL)
					$date = $date . "|" . $objPHPExcel -> getActiveSheet() -> getCell('J' . $i) -> getValue();

			}

			$m = M("siteconfig");
			$str = $m -> where("id = 1") -> getField("shieldWord");
			$date = $str . $date;
			$m -> shieldWord = $date;
			$res = $m -> where("id = 1") -> save();
			if ($res) {
				$this -> success("导入成功");
			}

		}

	}

	//座驾设置
	
	public function admin_carSet()
	{
		$carList = M('Car')->select();
		$this->assign('carList',$carList);
		$this->display();
	}
	//修改座驾设置
	public function save_car()
	{
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'gif,jpg,png,swf');
		//设置上传目录
		//每个用户一个文件夹
		$prefix = 'gift';
		$uploadPath = '../Public/images/' . $prefix . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();
			foreach ($uploadList as $picval) {
				if ($picval['key'] == 0) {
					$icon = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
				if ($picval['key'] == 1) {
					$image = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
				if ($picval['key'] == 2) {
					$swf = '/Public/images/' . $prefix . '/' . $picval['savename'];
				}
			}
		}

		$Edit_ID = $_POST['id'];
		//$Edit_sid = $_POST['sid'];
		$Edit_name = $_POST['name'];
		$Edit_coin = $_POST['coin'];
		$Edit_icon = $_POST['icon'];
		$Edit_image = $_POST['image'];
		$Edit_swf = $_POST['swf'];
		$Edit_DelID = $_POST['ids'];
		$Edit_ShowTime = $_POST['ShowTime'];
		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("car") -> where('id=' . $Edit_DelID[$i]) -> delete();
			
			//删除用户购买的记录
			D('membercar')->where('carID = '.$Edit_DelID[$i])->delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			if($Edit_ShowTime[$i]==NULL) $Edit_ShowTime[$i] = 20;

			D("car") -> execute('update ss_car set name="' . $Edit_name[$i] . '",coin=' . $Edit_coin[$i] . ',icon="' . $Edit_icon[$i] . '",image="' . $Edit_image[$i] . '",swf="' . $Edit_swf[$i] . '",showTime= '.$Edit_ShowTime[$i].' where id=' . $Edit_ID[$i]);
			
		}
		
		if ($_POST['add_name'] != '' && $_POST['add_coin'] != '' && $icon != '' && $image != '') {
			if($_POST['add_showTime']==NULL) $_POST['add_showTime'] = 20;
			$car = D('car');
			$car -> create();
			$car -> name = $_POST['add_name'];
			$car -> coin = $_POST['add_coin'];
			$car -> showTime = $_POST['add_showTime'];
			$car -> icon = $icon;
			$car -> image = $image;
			if ($swf != '') {
				$car -> swf = $swf;
			}
			$car -> addtime = time();
			$carID = $car -> add();

		}
		userLog(session('adminid'), "修改座驾设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_carSet/");
		$this -> success('操作成功');
	}

	//游戏转盘

	function admin_turntablet()
	{
		$turntable = M('turntable')->select();
		$this->assign('turntable',$turntable);
		$this->display();
	}
	
	//修改游戏转盘
	function save_turntable()
	{
		$Edit_ID = $_POST['id'];
		$Edit_name = $_POST['name'];
		$Edit_probability = $_POST['probability'];
		$Edit_DelID = $_POST['ids'];
		$Edit_giftIcon_25 = $_POST['giftIcon_25'];
		$Edit_giftSwf = $_POST['giftSwf'];
		$Edit_giftIcon = $_POST['giftIcon'];
		
		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("turntable") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			$turntable = D("turntable");
			$turntable -> name = $Edit_name[$i];
			$turntable -> probability = $Edit_probability[$i];
			$turntable -> giftIcon_25 = $Edit_giftIcon_25[$i];
			$turntable -> giftSwf = $Edit_giftSwf[$i];
			$turntable -> giftIcon = $Edit_giftIcon[$i];
			$turntable -> where("id = {$Edit_ID[$i]}") -> save();
			//D("turntable") -> execute('update ss_turntable set name="' . $Edit_name[$i] . '",probability="' . $Edit_probability[$i] . '" where id=' . $Edit_ID[$i]);
			
		}

		if ($_POST['add_name'] != '' && $_POST['add_probability'] != '') {
			$turntable = D('turntable');
			$turntable -> create();
			$turntable -> name = $_POST['add_name'];
			$turntable -> probability = $_POST['add_probability'];
			$turntable -> addtime = time();
			$$turntableID = $turntable -> add();
		}
		userLog(session('adminid'), "修改游戏转盘礼物");
		$this -> assign('jumpUrl', __URL__ . "/admin_turntablet/");
		$this -> success('操作成功');
	}
	
	//游戏转盘设置
	function admin_setTurntable()
	{
		$pattern = "/total_num=(\d*)&/";
		$str = file_get_contents('../game/turntable/index.html');
		preg_match($pattern, $str,$arr);
		$turntable_expense = M('siteconfig')->where("id = 1")->getField("turntable_expense");
		$this->assign('turntable_expense',$turntable_expense);
		$this->assign('count',$arr[1]);
		$this->display();
	}
	//修改游戏转盘设置
	function save_setTurntable()
	{
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'png');
		//设置上传目录
		//每个用户一个文件夹
		$uploadPath = '../Public/images/zhuanpan/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();

			$bg_path = $uploadList[0]['savename'];

			
			
			unlink('../game/turntable/bg.png');
			rename('../Public/images/zhuanpan/'.$bg_path,'../game/turntable/bg.png');
		}
		
		$count = $_POST['count'];
$con = <<<ETO
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>转盘抽奖</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="lottery.js"></script>
</head>

<body>
<object width="510" height="510" align="middle" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" id="lottery">
<param value="always" name="allowScriptAccess">
<param value="lottery.swf" name="movie">
<param value="high" name="quality">
<param value="total_num={$count}&bg=bg.png&pointer=pointer.png&btn=btn.swf&style=1&auto_play=1" name="FlashVars">
<param value="transparent" name="wmode">
<param value="false" name="menu">
<embed FlashVars="total_num={$count}&bg=bg.png&pointer=pointer.png&btn=btn.swf&style=1&auto_play=1" width="510" height="510" align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" name="lottery" menu="false" quality="high" src="lottery.swf">
</object>
</body>
</html>




ETO;

	file_put_contents('../game/turntable/index.html', $con);
	
	$site_config = M('siteconfig');
	$site_config->turntable_expense = $_POST['turntable_expense'];
	$site_config->where("id = 1")->save();
	userLog(session('adminid'), "修改游戏转盘设置");
	$this->success("修改成功");
	}
	
	
	//秀币转盘设置
	function admin_turntablet_coin()
	{
		$turntable = M('turntable_coin')->select();
		$this->assign('turntable',$turntable);
		$this->display();
	}
	
	
	
	//修改秀币转盘设置
	function save_turntable_coin()
	{
		$Edit_ID = $_POST['id'];
		$Edit_name = $_POST['name'];
		$Edit_probability = $_POST['probability'];
		$Edit_DelID = $_POST['ids'];
		$Edit_giftIcon_25 = $_POST['giftIcon_25'];
		$Edit_giftSwf = $_POST['giftSwf'];
		$Edit_giftIcon = $_POST['giftIcon'];
		
		//删除操作
		$num = count($Edit_DelID);
		for ($i = 0; $i < $num; $i++) {
			D("turntable_coin") -> where('id=' . $Edit_DelID[$i]) -> delete();
		}
		//编辑
		$num = count($Edit_ID);
		for ($i = 0; $i < $num; $i++) {
			$turntable = D("turntable_coin");
			$turntable -> name = $Edit_name[$i];
			$turntable -> probability = $Edit_probability[$i];
			//$turntable -> giftIcon_25 = $Edit_giftIcon_25[$i];
			//$turntable -> giftSwf = $Edit_giftSwf[$i];
			//$turntable -> giftIcon = $Edit_giftIcon[$i];
			$turntable -> where("id = {$Edit_ID[$i]}") -> save();
			//D("turntable") -> execute('update ss_turntable set name="' . $Edit_name[$i] . '",probability="' . $Edit_probability[$i] . '" where id=' . $Edit_ID[$i]);
			
		}

		if ($_POST['add_name'] != '' && $_POST['add_probability'] != '') {
			$turntable = D('turntable_coin');
			$turntable -> create();
			$turntable -> name = $_POST['add_name'];
			$turntable -> probability = $_POST['add_probability'];
			$turntable -> addtime = time();
			$$turntableID = $turntable -> add();
		}
		userLog(session('adminid'), "修改秀币转盘设置");
		$this -> assign('jumpUrl', __URL__ . "/admin_turntablet_coin/");
		$this -> success('操作成功');
	}
	
	
	//游戏转盘设置
	function admin_setTurntableCoin()
	{
		//正则匹配获取礼物个数
		$pattern = "/total_num=(\d*)&/";
		$str = file_get_contents('../game/turntable_coin/index.html');
		preg_match($pattern, $str,$arr);
		$turntable_expense = M('siteconfig')->where("id = 1")->getField("turntableCoin_expense");
		$this->assign('turntable_expense',$turntable_expense);
		$this->assign('count',$arr[1]);
		$this->display();
	}
	//修改游戏转盘设置
	function save_setTurntableCoin()
	{
		//上传图片
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload -> maxSize = 1048576;
		//设置上传文件类型
		$upload -> allowExts = explode(',', 'png');
		//设置上传目录
		//每个用户一个文件夹
		$uploadPath = '../Public/images/zhuanpan/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		$upload -> savePath = $uploadPath;
		$upload -> saveRule = uniqid;
		//执行上传操作
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ($upload -> getErrorMsg() != '没有选择上传文件') {
				$this -> error($upload -> getErrorMsg());
			}
		} else {
			$uploadList = $upload -> getUploadFileInfo();

			$bg_path = $uploadList[0]['savename'];
			
			$res = unlink('../game/turntable_coin/bg.png');

			rename('../Public/images/zhuanpan/'.$bg_path,'../game/turntable_coin/bg.png');
		}
		
		$count = $_POST['count'];
$con = <<<ETO
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>转盘抽奖</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="lottery.js"></script>
</head>

<body>
<object width="510" height="510" align="middle" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" id="lottery">
<param value="always" name="allowScriptAccess">
<param value="lottery.swf" name="movie">
<param value="high" name="quality">
<param value="total_num={$count}&bg=bg.png&pointer=pointer.png&btn=btn.swf&style=1&auto_play=1" name="FlashVars">
<param value="transparent" name="wmode">
<param value="false" name="menu">
<embed FlashVars="total_num={$count}&bg=bg.png&pointer=pointer.png&btn=btn.swf&style=1&auto_play=1" width="510" height="510" align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" name="lottery" menu="false" quality="high" src="lottery.swf">
</object>
</body>
</html>




ETO;

	file_put_contents('../game/turntable_coin/index.html', $con);
	
	$site_config = M('siteconfig');
	$site_config->turntableCoin_expense = $_POST['turntable_expense'];
	$res = $site_config->where("id = 1")->save();
	
	userLog(session('adminid'), "修改游戏转盘设置");
	$this->success("修改成功");
	}


    //头条设置
    
	public function admin_headlines() {
		$siteconfig = D("Siteconfig") -> find(1);
		if ($siteconfig) {
			$this -> assign('siteconfig', $siteconfig);
		} else {
			$this -> assign('jumpUrl', __URL__ . '/mainFrame');
			$this -> error('系统参数读取错误');
		}
		$this -> display();
	}
	//修改头条设置
	public function save_headlines() {
		$siteconfig = D('Siteconfig');
		$vo = $siteconfig -> create();
		if (!$vo) {
			$this -> assign('jumpUrl', __URL__ . '/admin_headlines/');
			$this -> error('修改失败');
		} else {
			$siteconfig -> save();
			userLog(session('adminid'), "修改头条设置");
			$this -> assign('jumpUrl', __URL__ . '/admin_headlines/');
			$this -> success('修改成功');
		}
	}
	
	public function admin_buyEmceeNo()
	{
		$condition = 'action="buy_emceeno" ';
		if ($_GET['start_time'] != '') {
			$timeArr = explode("-", $_GET['start_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime>=' . $unixtime;
		}
		
		if ($_GET['end_time'] != '') {
			$timeArr = explode("-", $_GET['end_time']);
			$unixtime = mktime(0, 0, 0, $timeArr[1], $timeArr[2], $timeArr[0]);
			$condition .= ' and addtime<=' . $unixtime;
		}
		if ($_GET['keyword'] != '') {

			$keyuinfo = D("Member") -> where('username="' . $_GET['keyword'] . '"') -> select();
			if ($keyuinfo) {
				$condition .= ' and touid=' . $keyuinfo[0]['id'];
			} else {
				$this -> error('没有该用户的记录');
			}

		}
		$_SESSION['excel_export']['lianghao'] = $condition;
		$orderby = 'id desc';
		$coindetail = D("coindetail");
		$count = $coindetail -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$conidata = $coindetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();

		
		foreach ($conidata as $n => $val) {
			$conidata[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('conidata', $conidata);

		$this -> display();
	}

//靓号导出记录excel

public function export_emccno() {
		//查询数据
		$orderby = 'id desc';
		$coindetail = D("coindetail");
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$conidata = $coindetail -> limit($p -> firstRow . "," . $p -> listRows) -> where($_SESSION['excel_export']['lianghao']) -> order($orderby) -> select();

		
		foreach ($conidata as $n => $val) {
			$conidata[$n]['voo'] = D("Member") -> where('id=' . $val['uid']) -> select();
		}
		
		$details = $conidata;
		Vendor('excel.PHPExcel');
		$objPHPExcel = new PHPExcel();
		// Set properties
		$objPHPExcel -> getProperties() -> setCreator("ctos") -> setLastModifiedBy("ctos") -> setTitle("Office 2007 XLSX Test Document") -> setSubject("Office 2007 XLSX Test Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		// set width
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(50);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);

		// 设置行高度
		$objPHPExcel -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(22);

		$objPHPExcel -> getActiveSheet() -> getRowDimension('2') -> setRowHeight(20);

		// 字体和样式
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(10);
		
		// 设置水平居中
		$objPHPExcel -> getActiveSheet() -> getStyle('A1') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('F') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle('G') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		// 表头
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '用户') -> setCellValue('B1', '购买靓号') -> setCellValue('C1', '金额(秀币)') -> setCellValue('D1', '时间');

		// 内容
		
		
		for ($i = 0, $len = count($details); $i < $len; $i++) {
			//判别处理状态
			$status = $details[$i]['status'];
			if($details[$i]['status']=="")
			{
				$status="未处理";
			}
			$time = date("Y-m-d",$details[$i]['addtime']);
			//格式化时间
			
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($i + 2), "UID:".$details[$i]['uid'].'|'.$details[$i]['voo'][0]['username'].'('.$details[$i]['voo'][0]['curroomnum'].')');
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($i + 2), $details[$i]['content']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($i + 2), round($details[$i]['coin'] / 100));
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($i + 2), date('Y/m/d',$details[$i]['addtime']));
			//$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($i + 2), $details[$i]['remark']);
			//$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($i + 2), $time);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . ($i + 2) . ':F' . ($i + 2)) -> getBorders() -> getAllBorders() -> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel -> getActiveSheet() -> getRowDimension($i + 2) -> setRowHeight(16);
		}
	
	
		// Rename sheet
		$objPHPExcel -> getActiveSheet() -> setTitle("靓号账单");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);

		// 输出
		$nowTime = date("Y-m-d");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . "靓号账单" .$nowTime.'.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter -> save('php://output');
	}

}
