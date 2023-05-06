<?php

class UserAction extends BaseAction {

	public function getroominfo(){

		C('HTML_CACHE_ON',false);

		header('Content-Type: text/xml');

		$roominfo = D("Member")->where('curroomnum='.$_GET["roomnum"].'')->select();

		if($roominfo){


			if($roominfo[0]['fakeuser'] == 'y'){
				$body = file_get_contents('http://xiu.56.com/api/userFlvApi.php?room_user_id='.$roominfo[0]['56_room_user_id']);

				if(strstr($body,"status=1")){

					echo '<?xml version="1.0" encoding="UTF-8"?>';

					echo '<ROOT>';

					echo '<broadcasting>yy</broadcasting>';

					$bodyArray = explode("%3D",$body);

					$bodyArray2 = explode("&",$bodyArray[1]);

					$token = $bodyArray2[0];

					echo '<token>'.$token.'</token>';

					echo '</ROOT>';

				}

				else{

					echo '<?xml version="1.0" encoding="UTF-8"?>';

					echo '<ROOT>';

					echo '<broadcasting>n</broadcasting>';

					echo '<offlinevideo></offlinevideo>';

					echo '</ROOT>';

				}

			}

			else{


				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<ROOT>';

				echo '<broadcasting>'.$roominfo[0]['broadcasting'].'</broadcasting>';

				if($roominfo[0]['broadcasting'] == 'y'){



					$roomtype = $roominfo[0]['roomtype'];



					if($roomtype == 1){

						if($_SESSION['enter_'.$roominfo[0]['showId']] == 'y'){

							$roomtype = 0;

						}

					}



					if($roomtype == 2){

						if($_SESSION['enter_'.$roominfo[0]['showId']] == 'y'){

							$roomtype = 0;

						}

					}



					//判断是否VIP以及金钥匙


					$viewerinfo = D("Member")->find($_SESSION['uid']);
					
					if($roominfo[0]['online'] >= $roominfo[0]['maxonline']){


						if(((int)$viewerinfo['vip'] > 0 && $viewerinfo['vipexpire'] > time())||($viewerinfo['goldkey']=='y' && $viewerinfo['gkexpire']>time())){
							
							

						}

						else{

							$roomtype = 3;

						}

					}	



					if($_SESSION['uid'] == $roominfo[0]['id']){

						$roomtype = 0;

					}



					if($viewerinfo['showadmin'] == '1'){

						$roomtype = 0;

					}



					echo '<roomtype>'.$roomtype.'</roomtype>';

				}

				else{

					echo '<offlinevideo>'.$roominfo[0]['offlinevideo'].'</offlinevideo>';

				}

				echo '</ROOT>';

			}

		}

		else{

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '</ROOT>';

		}

	}



	public function getuserinfo(){

		C('HTML_CACHE_ON',false);

		header('Content-Type: text/xml');

		if(!$_SESSION['uid']){

			$userid = rand(1000,9999);

			$_SESSION['uid'] = -$userid;

		}



		$roominfo = D("Member")->where('curroomnum='.$_GET["roomnum"].'')->select();

		$roomrichlevel = getRichlevel($roominfo[0]['spendcoin']);

		$roomemceelevel = getEmceelevel($roominfo[0]['earnbean']);



		if((int)$roominfo[0]['virtualguest'] > 0 ){

			$virtualusers = D('Member')->where('isvirtual="y"')->order('rand()')->limit((int)$roominfo[0]['virtualguest'])->select();

			$virtualusers_str = '';

			foreach($virtualusers as $val){

				$richlevel = getRichlevel($val['spendcoin']);

				$virtualusers_str .= $val['id'].'$$'.$val['nickname'].'$$'.$val['curroomnum'].'$$'.$val['vip'].'$$'.$richlevel[0]['levelid'].'$$'.$val['spendcoin'].'***';

			}

		}

		

		if($_SESSION['uid'] < 0){

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '<err>no</err>';

			echo '<Badge></Badge>';

			echo '<familyname></familyname>';

			echo '<goodnum></goodnum>';

			echo '<h>'.$_SESSION['ucuid'].'</h>';

			echo '<level>0</level>';

			echo '<richlevel>0</richlevel>';

			echo '<spendcoin>0</spendcoin>';

			echo '<sellm>0</sellm>';

			echo '<sortnum></sortnum>';

			echo '<userType>20</userType>';

			echo '<userid>'.$_SESSION['uid'].'</userid>';

			echo '<username>游客'.$_SESSION['uid'].'</username>';

			echo '<vip>0</vip>';

			if($roominfo[0]['fakeuser'] == 'y'){

				echo '<fakeroom>y</fakeroom>';

				echo '<roomBadge></roomBadge>';

				echo '<roomfamilyname></roomfamilyname>';

				echo '<roomgoodnum>'.$roominfo[0]['curroomnum'].'</roomgoodnum>';

				echo '<roomlevel>'.$roomemceelevel[0]['levelid'].'</roomlevel>';

				echo '<roomrichlevel>'.$roomrichlevel[0]['levelid'].'</roomrichlevel>';

				echo '<roomuserid>'.$roominfo[0]['id'].'</roomuserid>';

				echo '<roomusername>'.$roominfo[0]['nickname'].'</roomusername>';

				echo '<roomvip>1</roomvip>';

			}

			else{

				echo '<fakeroom>n</fakeroom>';

			}

			if($roominfo[0]['broadcasting'] == 'y'){

				echo '<virtualguest>'.$roominfo[0]['virtualguest'].'</virtualguest>';

				echo '<virtualusers_str>'.$virtualusers_str.'</virtualusers_str>';

			}

			else{

				echo '<virtualguest>0</virtualguest>';

				echo '<virtualusers_str></virtualusers_str>';

			}

			echo '</ROOT>';

		}

		else{

			$userinfo = D("Member")->find($_SESSION['uid']);

			$richlevel = getRichlevel($userinfo['spendcoin']);

			$emceelevel = getEmceelevel($userinfo['earnbean']);

			

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '<err>no</err>';

			echo '<Badge></Badge>';

			echo '<familyname></familyname>';

			echo '<goodnum>'.$_SESSION['roomnum'].'</goodnum>';

			echo '<h>'.$_SESSION['ucuid'].'</h>';

			echo '<level>'.$emceelevel[0]['levelid'].'</level>';

			echo '<richlevel>'.$richlevel[0]['levelid'].'</richlevel>';

			echo '<spendcoin>'.$userinfo['spendcoin'].'</spendcoin>';

			echo '<sellm>'.$userinfo['sellm'].'</sellm>';

			if($_SESSION['roomnum'] == $_GET['roomnum']){

				echo '<sortnum></sortnum>';

				echo '<userType>50</userType>';

			}

			else{

				echo '<sortnum></sortnum>';

				$myshowadmin = D("Roomadmin")->where('uid='.$roominfo[0]['id'].' and adminuid='.$_SESSION['uid'])->order('id asc')->select();

				if($userinfo['showadmin'] == '1' || $myshowadmin){

					echo '<userType>40</userType>';

				}

				else{

					echo '<userType>30</userType>';

				}

			}

			echo '<userid>'.$_SESSION['uid'].'</userid>';

			echo '<username>'.$_SESSION['nickname'].'</username>';

			if($userinfo['vipexpire'] > time()){

				echo '<vip>'.$userinfo['vip'].'</vip>';

			}

			else{

				echo '<vip>0</vip>';

			}

			if($roominfo[0]['fakeuser'] == 'y'){

				echo '<fakeroom>y</fakeroom>';

				echo '<roomBadge></roomBadge>';

				echo '<roomfamilyname></roomfamilyname>';

				echo '<roomgoodnum>'.$roominfo[0]['curroomnum'].'</roomgoodnum>';

				echo '<roomlevel>'.$roomemceelevel[0]['levelid'].'</roomlevel>';

				echo '<roomrichlevel>'.$roomrichlevel[0]['levelid'].'</roomrichlevel>';

				echo '<roomuserid>'.$roominfo[0]['id'].'</roomuserid>';

				echo '<roomusername>'.$roominfo[0]['nickname'].'</roomusername>';

				echo '<roomvip>1</roomvip>';

			}

			else{

				echo '<fakeroom>n</fakeroom>';

			}

			if($roominfo[0]['broadcasting'] == 'y'){

				echo '<virtualguest>'.$roominfo[0]['virtualguest'].'</virtualguest>';

				echo '<virtualusers_str>'.$virtualusers_str.'</virtualusers_str>';

			}

			else{

				echo '<virtualguest>0</virtualguest>';

				echo '<virtualusers_str></virtualusers_str>';

			}

			echo '</ROOT>';

		}

    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
    /*nodejs初始化用户信息*/
    public function inituserinfo(){
    	C('HTML_CACHE_ON',false);
		
		if(!$_SESSION['uid']){
			$userid = rand(1000,9999);
			$_SESSION['uid'] = -$userid;
		}

		$roominfo = D("Member")->where('curroomnum='.$_GET["roomnum"].'')->select();
		$roomrichlevel = getRichlevel($roominfo[0]['spendcoin']);
		$roomemceelevel = getEmceelevel($roominfo[0]['earnbean']);

		if((int)$roominfo[0]['virtualguest'] > 0 ){
			$virtualusers = D('Member')->where('isvirtual="y"')->order('rand()')->select();
			$virtualusers_str = '';
			foreach($virtualusers as $val){
				$richlevel = getRichlevel($val['spendcoin']);
				$virtualusers_str .= $val['id'].'$$'.$val['nickname'].'$$'.$val['curroomnum'].'$$'.$val['vip'].'$$'.$richlevel[0]['levelid'].'$$'.$val['spendcoin'].'***';
			}
		}
		$user_str = '{';
		if($_SESSION['uid'] < 0){
			$user_str .= "err:'no',userBadge:'',familyname:'',goodnum:'{$_GET['roomnum']}',h:0,level:0,richlevel:0,spendcoin:0,sellm:0,sortnum:'',userid:{$_SESSION['uid']},username:'游客{$_SESSION['uid']}',vip:0";
			
			if($roominfo[0]['fakeuser'] == 'y'){
				$user_str .=",fakeroom:'y'";
				$user_str .=",roomBadge:''";
				$user_str .=",roomfamilyname:''";
				$user_str .=",roomgoodnum:'{$roominfo[0]['curroomnum']}'";
				$user_str .=",roomlevel:'{$roomemceelevel[0]['levelid']}'";
				$user_str .=",roomrichlevel:'{$roomrichlevel[0]['levelid']}'";
				$user_str .=",roomuserid:'{$roominfo[0]['id']}'";
				$user_str .=",roomusername:'{$roominfo[0]['nickname']}'";
				$user_str .=",roomvip:1";
				
			}
			else{
				$user_str .=",fakeroom:'n'";
				
			}
			if($roominfo[0]['broadcasting'] == 'y'){
				$user_str .=",virtualguest:'{$roominfo[0]['virtualguest']}'";
				$user_str .=",virtualusers_str:'{$virtualusers_str}'";
				
			}
			else{
				$user_str .=",virtualguest:0";
				$user_str .=",virtualusers_str:''";
				
			}
			echo $user_str .='}';
		
		}
		else{
			$userinfo = D("Member")->find($_SESSION['uid']);
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			
			$user_str .= "err:'no',userBadge:'',familyname:'',goodnum:'{$_GET['roomnum']}',h:'{$userinfo['ucuid']}',level:'{$emceelevel[0]['levelid']}',richlevel:'{$richlevel[0]['levelid']}',spendcoin:'{$userinfo['spendcoin']}',sellm:'{$userinfo['sellm']}',sortnum:'',userid:'{$_SESSION['uid']}',username:'游客{$_SESSION['uid']}',vip:0";
			
			if($_SESSION['roomnum'] == $_GET['roomnum']){
				$user_str .=",sortnum:''";
				$user_str .=",userType:50";	
				
			}
			else{
				$user_str .=",sortnum:''";	
				
				$myshowadmin = D("Roomadmin")->where('uid='.$roominfo[0]['id'].' and adminuid='.$_SESSION['uid'])->order('id asc')->select();
				if($userinfo['showadmin'] == '1' || $myshowadmin){
					$user_str .=",userType:40";	
				}
				else{
					$user_str .=",userType:30";	
				}
			}
			$user_str .=",userid:{$_SESSION['uid']}";	
			$user_str .=",username:'{$_SESSION['nickname']}'";	
			
			if($userinfo['vipexpire'] > time()){
				$user_str .=",vip:{$userinfo['vip']}";	
				
			}
			else{
				$user_str .=",vip:0";	
			}
			if($roominfo[0]['fakeuser'] == 'y'){
				$user_str .=",fakeroom:'y'";
				$user_str .=",roomBadge:''";
				$user_str .=",roomfamilyname:''";
				$user_str .=",roomgoodnum:'{$roominfo[0]['curroomnum']}'";
				$user_str .=",roomlevel:'{$roomemceelevel[0]['levelid']}'";
				$user_str .=",roomrichlevel:'{$roomrichlevel[0]['levelid']}'";
				$user_str .=",roomuserid:'{$roominfo[0]['id']}'";
				$user_str .=",roomusername:'{$roominfo[0]['nickname']}'";
				$user_str .=",roomvip:1";
			}
			else{
				$user_str .=",fakeroom:'n'";
			}
			if($roominfo[0]['broadcasting'] == 'y' || $_SESSION['roomnum'] == $_GET['roomnum']){
				$user_str .=",virtualguest:'{$roominfo[0]['virtualguest']}'";
				$user_str .=",virtualusers_str:'{$virtualusers_str}'";
			}
			else{
				$user_str .=",virtualguest:0";
				$user_str .=",virtualusers_str:''";
			}
			echo $user_str .='}';
		}
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function createroom(){

		C('HTML_CACHE_ON',false);

		header('Content-Type: text/xml');



		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$err = "您尚未登录，请登录后重试";

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '<err>yes</err>';

			echo '<msg>'.$err.'</msg>';

			echo '</ROOT>';

			exit;

		}



		$userinfo = D("Member")->find($_SESSION['uid']);



		if($userinfo['canlive'] == 'n'){

			$err = "您暂时没有直播权限";

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '<err>yes</err>';

			echo '<msg>'.$err.'</msg>';

			echo '</ROOT>';

			exit;

		}



		if($_REQUEST['roomtype'] == '1'){

			//判断用户虚拟币是否足够

			if($userinfo['coinbalance'] < 100){

				$err = "您的余额不足";

				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<ROOT>';

				echo '<err>yes</err>';

				echo '<msg>'.$err.'</msg>';

				echo '</ROOT>';

				exit;

			}

			else{

				//扣费

				D("Member")->execute('update ss_member set spendcoin=spendcoin+100,coinbalance=coinbalance-100 where id='.$_SESSION['uid']);

				//记入虚拟币交易明细

				$Coindetail = D("Coindetail");

				$Coindetail->create();

				$Coindetail->type = 'expend';

				$Coindetail->action = 'createspeshow';

				$Coindetail->uid = $_SESSION['uid'];

				

				$Coindetail->content = $userinfo['nickname'].' 创建了一个收费房间';

				$Coindetail->objectIcon = '/Public/images/fei.png';

				$Coindetail->coin = 100;

				

				$Coindetail->addtime = time();

				$detailId = $Coindetail->add();

			}

		}



		if($_REQUEST['roomtype'] == '2'){

			//判断用户虚拟币是否足够

			if($userinfo['coinbalance'] < 50){

				$err = "您的余额不足";

				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<ROOT>';

				echo '<err>yes</err>';

				echo '<msg>'.$err.'</msg>';

				echo '</ROOT>';

				exit;

			}

			else{

				//扣费

				D("Member")->execute('update ss_member set spendcoin=spendcoin+50,coinbalance=coinbalance-50 where id='.$_SESSION['uid']);

				//记入虚拟币交易明细

				$Coindetail = D("Coindetail");

				$Coindetail->create();

				$Coindetail->type = 'expend';

				$Coindetail->action = 'createspeshow';

				$Coindetail->uid = $_SESSION['uid'];

				

				$Coindetail->content = $userinfo['nickname'].' 创建了一个密码房间';

				$Coindetail->objectIcon = '/Public/images/fei.png';

				$Coindetail->coin = 50;

				

				$Coindetail->addtime = time();

				$detailId = $Coindetail->add();

			}

		}

		

			$User=D("Member");

			$User->create();

			$User->id = $_SESSION['uid'];

			$User->broadcasting = 'y';

			$showId = time();

			$User->showId = $showId;

			$User->starttime = time();

			$User->roomtype = $_REQUEST['roomtype'];

			if($_REQUEST['roomtype'] == '1'){

				$User->needmoney = $_REQUEST['needmoney'];

			}

			if($_REQUEST['roomtype'] == '2'){

				$User->roompsw = $_REQUEST['roompsw'];

			}

			$userId = $User->save();

			// 删除重复的 某些情况出现重复记录

		     // D("Liverecord")->execute('delete from ss_liverecord   where showId='.$showid);

			     D("Liverecord")->where('showId='.$showId)->delete();

				 //删除结束时间为空的

				 $uiid=$_SESSION['uid'];

				  D("Liverecord")->where("endtime=0 and uid='{$uiid}'")->delete();

			//新加一条直播记录

			$Liverecord=D("Liverecord");

			$Liverecord->create();

			$Liverecord->roomtype = $_REQUEST['roomtype'];

			$Liverecord->uid = $_SESSION['uid'];

			$Liverecord->showId = $showId;

			$Liverecord->starttime = $showId;

			$Liverecord->sign = $userinfo['sign'];

			$liveId = $Liverecord->add();

	

			echo '<?xml version="1.0" encoding="UTF-8"?>';

			echo '<ROOT>';

			echo '<err>no</err>';

			echo '<showId>'.$showId.'</showId>';

			echo '</ROOT>';



	}



	public function do_myfamily_edit(){

		$model=M("agentfamily");







		if($model->create()){

			$model->id=$_POST['id'];

			$model->familyname=$_POST['familyname'];

			$model->familyinfo=$_POST['familyinfo'];

			if($model->save()){

				$this->success("资料更新成功！");

			}else{

				$this->error("资料更新失败！");

			}

		}else{

			$this->error($model->getError());

		}



	}

	public function enterroom(){

		C('HTML_CACHE_ON',false);

		$userinfo = D("Member")->where('curroomnum='.$_REQUEST['roomid'].'')->select();

		D("Member")->execute('update ss_member set online=online+1 where curroomnum='.$_REQUEST['roomid']);

		//if($userinfo[0]['broadcasting'] == 'y'){

			D("Liverecord")->execute('update ss_liverecord set entercount=entercount+1 where showId='.$userinfo[0]['showId']);

		//}

	}



	public function enterroom2(){

		C('HTML_CACHE_ON',false);

		$userinfo = D("Member")->where('curroomnum='.$_REQUEST['roomid'].'')->select();

		D("Member")->execute('update ss_member set online=online+1 where curroomnum='.$_REQUEST['roomid']);

		//if($userinfo[0]['broadcasting'] == 'y'){

			D("Liverecord")->execute('update ss_liverecord set entercount=entercount+1 where showId='.$userinfo[0]['showId']);

		//}

	}



	public function exitroom(){
		C('HTML_CACHE_ON',false);
		$userinfo = D("Member")->find($_REQUEST['uid']);
		if($userinfo && $_REQUEST['roomid'] == $userinfo['curroomnum']){
			if($userinfo['broadcasting'] == 'y'){
				D("Member")->execute('update ss_member set ispublic="1",SongApply="1",broadcasting="n",showId=0,seat1_ucuid=0,seat1_nickname="",seat1_count=0,seat2_ucuid=0,seat2_nickname="",seat2_count=0,seat3_ucuid=0,seat3_nickname="",seat3_count=0,seat4_ucuid=0,seat4_nickname="",seat4_count=0,seat5_ucuid=0,seat5_nickname="",seat5_count=0 where id='.$_REQUEST['uid']);
				//写入当次直播记录的结束时间
				D("Liverecord")->execute('update ss_liverecord set endtime='.time().' where showId='.$userinfo['showId']);
			}
			else{
				D("Member")->execute('update ss_member set seat1_ucuid=0,seat1_nickname="",seat1_count=0,seat2_ucuid=0,seat2_nickname="",seat2_count=0,seat3_ucuid=0,seat3_nickname="",seat3_count=0,seat4_ucuid=0,seat4_nickname="",seat4_count=0,seat5_ucuid=0,seat5_nickname="",seat5_count=0 where id='.$_REQUEST['uid']);
			}
		}
		D("Member")->execute('update ss_member set online=online-1 where curroomnum='.$_REQUEST['roomid'].' and online > 0');
	}

	// flash 发送过来的结束
		public function exitroom2(){
		C('HTML_CACHE_ON',false);
		$uid=$_SESSION['uid'];
		$err = 	$uid."".$_REQUEST['roomnum'];
		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){
			$err = "您尚未登录，请登录后重试";
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<ROOT>';
			echo '<err>yes</err>';
			echo '<msg>'.$err.'</msg>';
			echo '</ROOT>';
			exit;
		}
	 
		$userinfo = D("Member")->find($uid);
		if($userinfo && $_REQUEST['roomnum'] == $userinfo['curroomnum']){
		 
				D("Member")->execute('update ss_member set ispublic="1",SongApply="1",broadcasting="n",showId=0,seat1_ucuid=0,seat1_nickname="",seat1_count=0,seat2_ucuid=0,seat2_nickname="",seat2_count=0,seat3_ucuid=0,seat3_nickname="",seat3_count=0,seat4_ucuid=0,seat4_nickname="",seat4_count=0,seat5_ucuid=0,seat5_nickname="",seat5_count=0 where id='.$_REQUEST['uid']);
				//写入当次直播记录的结束时间
				D("Liverecord")->execute('update ss_liverecord set endtime='.time().' where showId='.$userinfo['showId']);
			 
		 
		}
	 
	}






	public function resetonline(){

		C('HTML_CACHE_ON',false);

		D("Member")->execute('update ss_member set online=0 where host="'.$_REQUEST['host'].'"');

	}



	

	public function makesnap2(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '&err=nologin';

			exit;

		}



		$prefix = date('Y-m');

		$uploadPath = '/Public/snap/'.$prefix.'/';

		if(!is_dir('.'.$uploadPath)){

        	mkdir('.'.$uploadPath);

		}

		$filename = md5($_SESSION['roomnum']).'.jpg';



		if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))  

		{  

			$png = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);   

			$file = fopen('.'.$uploadPath.$filename,"w");//打开文件准备写入  

			fwrite($file,$png);  

			fclose($file); 

			

			D("Member")->query('update ss_member set snap="'.$uploadPath.$filename.'" where id='.$_SESSION['uid']);

			echo "ok";

		}

	}



	public function makesnap(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '&err=nologin';

			exit;

		}



		$w = 160;

		$h = 120;



		$img = imagecreatetruecolor($w, $h);



		imagefill($img, 0, 0, 0xFFFFFF);



		$rows = 0;

		$cols = 0;



		$dataArr = explode("|", $_POST['imgdata']);



		for($rows = 0; $rows < $h; $rows++){

			$c_row = explode(",", $dataArr[$rows]);

			for($cols = 0; $cols < $w; $cols++){

				$value = $c_row[$cols];

				if($value != ""){

					$hex = $value;

					while(strlen($hex) < 6){

						$hex = "0" . $hex;

					}

					$r = hexdec(substr($hex, 0, 2));

					$g = hexdec(substr($hex, 2, 2));

					$b = hexdec(substr($hex, 4, 2));

					$test = imagecolorallocate($img, $r, $g, $b);

					imagesetpixel($img, $cols, $rows, $test);

				}

			}

		}

		//D("Siteconfig")->query('update ss_siteconfig set imgdata="'.$tmpstr.'" where id=1');



		$prefix = date('Y-m');

		$uploadPath = '/Public/snap/'.$prefix.'/';

		if(!is_dir('.'.$uploadPath)){

        	mkdir('.'.$uploadPath);

		}

		$filename = md5($_SESSION['roomnum']).'.jpg';



		imagejpeg($img, '.'.$uploadPath.$filename, 90);



		D("Member")->query('update ss_member set snap="'.$uploadPath.$filename.'" where id='.$_SESSION['uid']);

		echo '&snap='.$uploadPath.$filename.'?t='.time();

		exit;

	}



	public function setBulletin(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"info":"您尚未登录"}';

			exit;

		}
		$User=D("Member");
	    $userinfo = $User -> find($_SESSION['uid']);
		if($_SESSION['uid'] != $_REQUEST['eid']&&$userinfo['showadmin'] != 1){

			echo '{"info":"您不是该房间主人"}';

			exit;

		}



		

		$User->create();

		$User->id = $_SESSION['uid'];

		if($_REQUEST['bt'] == 2){

			$User->announce = $_REQUEST['t'];

			$User->annlink = $_REQUEST['u'];

		}

		if($_REQUEST['bt'] == 3){

			$User->announce2 = $_REQUEST['t'];

			$User->ann2link = $_REQUEST['u'];

		}

		$userId = $User->save();



		echo '{"code":"0"}';

		exit;

	}



	public function setBackground(){

		C('HTML_CACHE_ON',false);

		header("Content-type: text/html; charset=utf-8"); 

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo "<script>alert('您尚未登录');</script>";

			exit;

		}
		$User=D("Member");
	    $userinfo = $User -> find($_SESSION['uid']);
		if($_SESSION['uid'] != $_REQUEST['eid']&&$userinfo['showadmin'] != 1){

			echo "<script>alert('您不是该房间主人');</script>";

			exit;

		}


		//上传缩略图

		import("@.ORG.UploadFile");

		$upload = new UploadFile();

		//设置上传文件大小

		$upload->maxSize  = 1048576 ;

		//设置上传文件类型

		$upload->allowExts  = explode(',','jpg');

		//设置上传目录

		//每个用户一个文件夹

		$prefix = date('Y-m');

		$uploadPath =  './Public/bgimg/'.$prefix.'/';

		if(!is_dir($uploadPath)){

        	mkdir($uploadPath);

		}

		$upload->savePath =  $uploadPath;

		$upload->saveRule = uniqid;

		//执行上传操作

		if(!$upload->upload()) {

			// 捕获上传异常

			echo "<script>alert('".$upload->getErrorMsg()."');</script>";

			exit;

		}

		else{

			$uploadList = $upload->getUploadFileInfo();

			$picpath = '/Public/bgimg/'.$prefix.'/'.$uploadList[0]['savename'];

		}



		D("Member")->execute('update ss_member set bgimg="'.$picpath.'" where id='.$_SESSION['uid']);

		

		echo "<script>document.domain='".$this->domainroot."';alert('上传成功');window.parent.playerMenu.setBackground2('".$picpath."');</script>";

		exit;

	}
   //设置背景
	public function changeBackground(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"code":"1"}';

			exit;

		}



		if($_SESSION['uid'] != $_REQUEST['eid']){

			echo '{"code":"2"}';

			exit;

		}

        $bgimg=$_GET['bgimg'];


		D("Member")->execute('update ss_member set bgimg="'.$bgimg.'" where id='.$_SESSION['uid']);



		echo '{"code":"0"}';

		exit;

	}   
	
	//



	public function cancelBackground(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"code":"1"}';

			exit;

		}



		if($_SESSION['uid'] != $_REQUEST['eid']){

			echo '{"code":"2"}';

			exit;

		}



		D("Member")->execute('update ss_member set bgimg="" where id='.$_SESSION['uid']);



		echo '{"code":"0"}';

		exit;

	}



	public function setOfflineVideo(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"code":"1","info":"您尚未登录"}';

			exit;

		}
        $User=D("Member");
	    $userinfo = $User -> find($_SESSION['uid']);

		if($_SESSION['uid'] != $_REQUEST['eid']&&$userinfo['showadmin']!=1){

			echo '{"code":"2","info":"您不是该房间主人"}';

			exit;

		}



		D("Member")->execute('update ss_member set offlinevideo="'.$_REQUEST['url'].'" where id='.$_SESSION['uid']);



		echo '{"code":"0"}';

		exit;

	}



	public function cancelOfflineVideo(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"code":"1","info":"您尚未登录"}';

			exit;

		}
		$User=D("Member");
	    $userinfo = $User -> find($_SESSION['uid']);

		if($_SESSION['uid'] != $_REQUEST['eid']&&$userinfo['showadmin']!=1){

			echo '{"code":"2","info":"您不是该房间主人"}';

			exit;

		}



		D("Member")->execute('update ss_member set offlinevideo="" where id='.$_SESSION['uid']);



		echo '{"code":"0"}';

		exit;

	}



	public function setPublicChat(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"state":"3","info":"您尚未登录"}';

			exit;

		}
		$User=D("Member");
	    $userinfo = $User -> find($_SESSION['uid']);

		if($_SESSION['uid'] != $_REQUEST['eid']&&$userinfo['showadmin'] != 1){

			echo '{"state":"3","info":"您不是该房间主人"}';

			exit;

		}



		D("Member")->execute('update ss_member set ispublic="'.$_REQUEST['flag'].'" where id='.$_SESSION['uid']);



		echo '{"state":"'.$_REQUEST['flag'].'"}';

		exit;

	}



	public function wishing(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"state":"3","info":"您尚未登录"}';

			exit;

		}



		if($_REQUEST['action'] == 'isWished'){

			/*

			$userinfo = D("Member")->find($_SESSION['uid']);

			if($userinfo){

				if(date('Y-m-d',$userinfo['wishtime']) == date('Y-m-d',time())){

					echo '1';

					exit;

				}

				else{

					echo '0';

					exit;

				}

			}

			*/

			$userwishs = D("Wish")->where('uid='.$_SESSION['uid'].' and date_format(FROM_UNIXTIME(wishtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y")')->order('id asc')->select();

			if($userwishs){

				echo '1';

				exit;

			}

			else{

				echo '0';

				exit;

			}

		}

		

		if($_REQUEST['action'] == 'save'){

			//判断虚拟币是否足够



			//添加许愿

			/*

			$User=D("Member");

			$User->create();

			$User->id = $_SESSION['uid'];

			if($_REQUEST['type'] == '1'){

				$User->wish = '<strong class="p1">我的心愿：</strong>我今天希望得到<strong class="p2">'.$_REQUEST['num'].'</strong>个'.$_REQUEST['giftName'];

			}

			if($_REQUEST['type'] == '2'){

				$User->wish = '<strong class="p1">我的心愿：</strong>我今天希望得到<strong class="p2">'.$_REQUEST['num'].'</strong>人热捧';

			}

			$User->wishtime = time();

			$userId = $User->save();

			*/

			$Wish=D("Wish");

			$Wish->create();

			$Wish->uid = $_SESSION['uid'];

			if($_REQUEST['type'] == '1'){

				$Wish->wish = '<strong class="p1">我的心愿：</strong>我今天希望得到<strong class="p2">'.$_REQUEST['num'].'</strong>个'.$_REQUEST['giftName'];

			}

			if($_REQUEST['type'] == '2'){

				$Wish->wish = '<strong class="p1">我的心愿：</strong>我今天希望得到<strong class="p2">'.$_REQUEST['num'].'</strong>人热捧';

			}

			$Wish->wishtime = time();

			$wishId = $Wish->add();



			echo '{"wishedFlag":"1","wishType":"'.$_REQUEST['type'].'","count":"'.$_REQUEST['num'].'","giftName":"'.$_REQUEST['giftName'].'"}';

			exit;

		}

	}



	public function sign_view(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		if($userinfo['sign'] == 'y'){

			$this->assign('jumpUrl',__APP__);

			$this->error('您已是签约主播，更改资料请联系客服');

		}



		$this->display();

	}



	public function do_sign_view(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		//上传缩略图

		import("@.ORG.UploadFile");

		$upload = new UploadFile();

		//设置上传文件大小

		$upload->maxSize  = 1048576 ;

		//设置上传文件类型

		$upload->allowExts  = explode(',','jpg,png');

		//设置上传目录

		//每个用户一个文件夹

		$prefix = date('Y-m');

		$uploadPath =  './Public/bigpic/'.$prefix.'/';

		if(!is_dir($uploadPath)){

        	mkdir($uploadPath);

		}

		$upload->savePath =  $uploadPath;

		$upload->saveRule = uniqid;

		//执行上传操作

		if(!$upload->upload()) {

			// 捕获上传异常 

			if($upload->getErrorMsg() != '没有选择上传文件'){

				//echo "<script>alert('".$upload->getErrorMsg()."');<///script>";

				//exit;

				$this->error($upload->getErrorMsg());

				exit;

			}

		}

		else{

			$uploadList = $upload->getUploadFileInfo();

			$picpath = '/Public/bigpic/'.$prefix.'/'.$uploadList[0]['savename'];

		}



		$User = D('Member');

		$vo = $User->create();

		if(!$vo) {

			$this->error($User->getError());

		}else{

			$User->sign = 'i';

			$User->bigpic = $picpath;

			$User->save();

			

			$this->assign('jumpUrl',__APP__);

			$this->success('签约审核中，请等待管理员与您联系');

		}



		//$this->display();

	}



	public function index(){

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');
				
		}
		$userinfo = D("Member")->find($_SESSION['uid']);
		$this->assign('userinfo', $userinfo);
		$emceeagent = M("member")->where("id='".$_SESSION['uid']."'")->getField("emceeagent");
		$this->assign('emceeagent',$emceeagent);
		$this->display();

	}



	public function myfavor(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$favors = D("Favor")->where("uid=".$_SESSION['uid'])->order('addtime desc')->select();

		foreach($favors as $n=> $val){

			$favors[$n]['voo']=D("Member")->where('id='.$val['favoruid'])->select();

		}

		$this->assign('favors', $favors);

        $userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);

		$this->display();

	}



	public function delfavor(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$fidArr = explode(",", $_GET['fid']);

		foreach ($fidArr as $k){

			$favorinfo = D("Favor")->find($k);

			if($favorinfo && $favorinfo['uid'] == $_SESSION['uid']){

				D("Favor")->where('id='.$k)->delete();

			}

		}



		$this->assign('jumpUrl',__URL__."/myfavor/");

		$this->success('操作成功');

	}



	public function bookmark_add(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"state":"1"}';

			exit;

		}



		$favors = D("Favor")->where('uid='.$_SESSION['uid'].' and favoruid='.$_REQUEST['emceeid'])->order('id asc')->select();

		if($favors){

			echo '{"state":"0","op":"repeat"}';

			exit;

		}

		else{

			$Favor=D("Favor");

			$Favor->create();

			$Favor->uid = $_SESSION['uid'];

			$Favor->favoruid = $_REQUEST['emceeid'];

			$favorId = $Favor->add();



			if($favorId > 0){

				echo '{"state":"0","op":"cancle"}';

				exit;

			}

			else{

				echo '{"state":"1"}';

				exit;

			}

		}

	}



	public function bookmark_cancle(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"state":"1"}';

			exit;

		}

		

		D("Favor")->where('uid='.$_SESSION['uid'].' and favoruid='.$_REQUEST['emceeid'])->delete();

		

		echo '{"state":"0","op":""}';

		exit;

	}



	public function interestToList(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		//$attentions = D("Attention")->where("uid=".$_SESSION['uid'])->order('addtime desc')->select();

		//foreach($attentions as $n=> $val){

			//$attentions[$n]['voo']=D("Member")->where('id='.$val['attuid'])->select();

		//}

		//$this->assign('attentions', $attentions);



		$Attention = D("Attention");

		$count = $Attention->where("uid=".$_SESSION['uid'])->count();

		$listRows = 12;

		import("@.ORG.Page2");

		$p = new Page($count,$listRows,$linkFront);

		$attentions = $Attention->where("uid=".$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($attentions as $n=> $val){

			$attentions[$n]['voo']=D("Member")->where('id='.$val['attuid'])->select();

		}

		$page = $p->show();

		$this->assign('attentions',$attentions);

		$this->assign('count',$count);

		$this->assign('page',$page);



		//我捧的人

		$mypengusers = D('Coindetail')->query('SELECT touid,sum(coin) as total FROM `ss_coindetail` where type="expend" and uid='.$_SESSION['uid'].' and touid>0 group by touid order by total desc LIMIT 5');

		foreach($mypengusers as $n=> $val){

			$mypengusers[$n]['voo']=D("Member")->where('id='.$val['touid'])->select();

		}

		$this->assign('mypengusers', $mypengusers);
		//判断
        $userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);
	
		//我守护的人
		
		$guard = M("guard g")->where("g.userid = $_SESSION[uid] and g.maturitytime >".time())->join("ss_member m on m.id = g.anchorid")->field("g.*,m.curroomnum,m.nickname,ucuid")->select();

		$this->assign('guard', $guard);
		$this->display();

	}



	public function cancelInterest(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}

		

		D("Attention")->where('uid='.$_SESSION['uid'].' and attuid='.$_REQUEST['uid'])->delete();

		

		//$this->assign('jumpUrl',__URL__."/interestToList/");

		//$this->success('操作成功');

		echo '1';

		exit;

	}



	public function interest(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Attention=D("Attention");

		$Attention->create();

		$Attention->uid = $_SESSION['uid'];

		$Attention->attuid = $_REQUEST['uid'];

		$attId = $Attention->add();


		if($attId > 0){

			echo '1';

			exit;

		}

		else{

			echo '0';

			exit;

		}

	}


	//查询关注状态
	public function Attention()
	{
	
		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){
			$arr['code'] = "-1";
		}  
		$uid = $_SESSION['uid'];
		$attuid =  $_GET['attuid'];
		$data = M("attention")->where("uid = $uid and attuid=$attuid")->find();
		if($data==null)
		{
			$arr['code'] = 0;
		}
		else
		{ 
			$arr['code'] = 1;
		}
		echo json_encode($arr); 
	}



	public function myNos(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);



		$mynos = D("Roomnum")->where("uid=".$_SESSION['uid'])->order('addtime asc')->select();

		foreach ($mynos as $key => $value) {

			if($value['expiretime']!=0 and $value['expiretime']<time()){

				M('Roomnum')->where(array('uid'=>$value['uid'],'expiretime'=>$value['expiretime']))->delete();

				$num = M('Roomnum')->where(array('uid'=>$value['uid'],'original'=>'y'))->find();

				$data['curroomnum'] = $num['num'];

                D('Member')->where(array('id'=>$value['uid']))->save($data);          

                session('roomnum',$num['num']);

                cookie('roomnum',$num['num'],360000);

			}

		}

		$myno = D("Roomnum")->where("uid=".$_SESSION['uid'])->order('addtime asc')->select();

		$this->assign('mynos', $myno);



		$attentions = D("Attention")->where("uid=".$_SESSION['uid'])->order('addtime desc')->select();

		foreach($attentions as $n=> $val){

			$attentions[$n]['voo']=D("Member")->where('id='.$val['attuid'])->select();

		}

		$this->assign('attentions', $attentions);



		$this->display();

	}



	public function setcurroomnum(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET["roomnum"] == '')

		{

			$this->assign('jumpUrl',__APP__.'/User/');

			$this->error('缺少参数或参数不正确');

		}

		else{

			$numinfo = D("Roomnum")->where('num='.$_GET["roomnum"].'')->select();

			if($numinfo){

				if($numinfo[0]['uid'] == $_SESSION['uid']){

					D("Member")->execute('update ss_member set curroomnum='.$_GET["roomnum"].' where id='.$_SESSION['uid']);

					session('roomnum',$_GET["roomnum"]);

					cookie('roomnum',$_GET["roomnum"],3600000);

					$this->assign('jumpUrl',__APP__.'/User/myNos/');

					$this->success('启用成功');

				}

				else{

					$this->assign('jumpUrl',__APP__.'/User/myNos/');

					$this->error('您不是该房间号的主人');

				}

			}

			else{

				$this->assign('jumpUrl',__APP__.'/User/myNos/');

				$this->error('没有该房间号');

			}

		}

	}



	public function transroomnum(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo 'error';

			exit;

		}



		if($_GET["roomnum"] == '' || $_GET["grantId"] == '')

		{

			echo 'error';

			exit;

		}

		else{

			$numinfo = D("Roomnum")->where('num='.$_GET["roomnum"].'')->select();

			if($numinfo){

				if($numinfo[0]['uid'] == $_SESSION['uid']){

					if($_GET["grantId"] == $_SESSION['uid']){

						echo 'error';

						exit;

					}

					else{

						D("Roomnum")->execute('update ss_roomnum set uid='.$_GET["grantId"].' where num='.$_GET["roomnum"]);

						//写一条记录到ss_giveaway

						$Giveaway = D("Giveaway");

						$Giveaway->create();

						$Giveaway->uid = $_SESSION['uid'];

						$Giveaway->touid = $_GET["grantId"];

						$Giveaway->content = '('.$_GET["roomnum"].')';

						$Giveaway->objectIcon = '/Public/images/gnum.png';

						$giveId = $Giveaway->add();

						echo 'success';

						exit;

					}

				}

				else{

					echo 'error';

					exit;

				}

			}

			else{

				echo 'error';

				exit;

			}

		}

	}



	public function toolinuse(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);
		$carList = M('Membercar m')->where("m.uid = $_SESSION[uid]")->join('ss_car c  ON m.carID = c.id')->select();
		$this->assign('carList',$carList);
		$this->assign('userinfo', $userinfo);



		$this->display();

	}



	public function toolItem(){

		$carList = M('car')->select();
		$this->assign('carList',$carList);
		$this->display();

	}



	public function buyTool(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo '{"msg":"请重新登录"}';

			exit;

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		$richlevel = getRichlevel($userinfo['spendcoin']);



		switch ($_GET['toolid']){

			case '1':

				//判断用户富豪级别

				if($richlevel[0]['levelid'] < 10){

					echo '{"msg":"限10富及以上等级购买"}';

					exit;

				}
				
				if($userinfo['vip'] == '2' && $userinfo['vipexpire'] > time()){

					echo '{"msg":"您已经是黄金VIP了"}';

					exit;

				}
				



				if($_GET['toolsubid']  == 1){

					$needcoin = 20000;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}

				else if($_GET['toolsubid']  == 2){

					$needcoin = 4800;

					$duration = 3600 * 24 * 30 * 3;

					$duration2 = '三个月';

				}

				else if($_GET['toolsubid']  == 3){

					$needcoin = 8400;

					$duration = 3600 * 24 * 30 * 6;

					$duration2 = '六个月';

				}

				else if($_GET['toolsubid']  == 4){

					$needcoin = 120000;

					$duration = 3600 * 24 * 30 * 12;

					$duration2 = '十二个月';

				}



				if($userinfo['coinbalance'] < $needcoin){
					
					
				echo '{"msg":"您的余额不足"}';
					

					exit;

				}

				else{

					if($userinfo['vipexpire'] < time()){

						D("Member")->execute('update ss_member set vip="1",vipexpire=vipexpire+'.(time() + $duration).',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					else{

						D("Member")->execute('update ss_member set vip="1",vipexpire=vipexpire+'.$duration.',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					//写入消费明细

					$Coindetail = D("Coindetail");

					$Coindetail->create();

					$Coindetail->type = 'expend';

					$Coindetail->action = 'buy';

					$Coindetail->uid = $_SESSION['uid'];

					$Coindetail->giftcount = 1;

					$Coindetail->content = '您购买了 '.$duration2.' 至尊VIP';

					$Coindetail->objectIcon = '/Public/images/vip1.png';

					$Coindetail->coin = $needcoin;

					$Coindetail->addtime = time();

					$detailId = $Coindetail->add();

					

					echo '{"msg":"购买成功"}';

					exit;

				}

				break;

			case '2':

				//判断用户富豪级别

				if($richlevel[0]['levelid'] < 3){

					echo '{"msg":"限3富及以上等级购买"}';

					exit;

				}

				if($userinfo['vip'] == '1' && $userinfo['vipexpire'] > time()){

					echo '{"msg":"您已经是至尊VIP了"}';

					exit;

				}



				if($_GET['toolsubid']  == 5){

					$needcoin = 15000;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}

				else if($_GET['toolsubid']  == 6){

					$needcoin = 4000;

					$duration = 3600 * 24 * 30 * 3;

					$duration2 = '三个月';

				}

				else if($_GET['toolsubid']  == 7){

					$needcoin = 6500;

					$duration = 3600 * 24 * 30 * 6;

					$duration2 = '六个月';

				}

				else if($_GET['toolsubid']  == 8){

					$needcoin = 100000;

					$duration = 3600 * 24 * 30 * 12;

					$duration2 = '十二个月';

				}

				

				if($userinfo['coinbalance'] < $needcoin){
					
					echo '{"msg":"您的余额不足"}';

					exit;

				}

				else{

					if($userinfo['vipexpire'] < time()){

						D("Member")->execute('update ss_member set vip="2",vipexpire='.(time() + $duration).',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					else{

						D("Member")->execute('update ss_member set vip="2",vipexpire=vipexpire+'.$duration.',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					//写入消费明细

					$Coindetail = D("Coindetail");

					$Coindetail->create();

					$Coindetail->type = 'expend';

					$Coindetail->action = 'buy';

					$Coindetail->uid = $_SESSION['uid'];

					$Coindetail->giftcount = 1;

					$Coindetail->content = '您购买了 '.$duration2.' VIP';

					$Coindetail->objectIcon = '/Public/images/vip2.png';

					$Coindetail->coin = $needcoin;

					$Coindetail->addtime = time();

					$detailId = $Coindetail->add();

					

					echo '{"msg":"购买成功"}';

					exit;

				}

				break;

			case '3':

				if($_GET['toolsubid']  == 9){

					$needcoin = 15000;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}

				

				if($userinfo['coinbalance'] < $needcoin){

					echo '{"msg":"您的余额不足"}';

					exit;

				}

				else{

					if($userinfo['gkexpire'] < time()){

						D("Member")->execute('update ss_member set goldkey="y",gkexpire='.(time() + $duration).',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					else{

						D("Member")->execute('update ss_member set goldkey="y",gkexpire=gkexpire+'.$duration.',spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);

					}

					//写入消费明细

					$Coindetail = D("Coindetail");

					$Coindetail->create();

					$Coindetail->type = 'expend';

					$Coindetail->action = 'buy';

					$Coindetail->uid = $_SESSION['uid'];

					$Coindetail->giftcount = 1;

					$Coindetail->content = '您购买了 '.$duration2.' 金钥匙';

					$Coindetail->objectIcon = '/Public/images/goldkey.png';

					$Coindetail->coin = $needcoin;

					$Coindetail->addtime = time();

					$detailId = $Coindetail->add();

					

					echo '{"msg":"购买成功"}';

					exit;

				}

				break;

			case '4':

				if($_GET['toolsubid']  == 10){

					$needcoin = 50000;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}elseif($_GET['toolsubid'] == 11){

					$needcoin = 50000;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}else{

					$needcoin = 88888;

					$duration = 3600 * 24 * 30 * 1;

					$duration2 = '一个月';

				}



				

				if($userinfo['coinbalance'] < $needcoin){

					echo '{"msg":"您的余额不足"}';

					exit;

				}

				else{

					if($needcoin==50000) {

						if ($userinfo['awexpire'] < time()) {

							D("Member")->execute('update ss_member set atwill="y",awexpire=' . (time() + $duration) . ',spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);

						} else {

							D("Member")->execute('update ss_member set atwill="y",awexpire=awexpire+' . $duration . ',spendcoin=spendcoin+' . $needcoin . ',coinbalance=coinbalance-' . $needcoin . ' where id=' . $_SESSION['uid']);

						}

					}

					//写入消费明细

					$Coindetail = D("Coindetail");

					$Coindetail->create();

					$Coindetail->type = 'expend';

					$Coindetail->action = 'buy';

					$Coindetail->uid = $_SESSION['uid'];

					$Coindetail->giftcount = 1;

					$Coindetail->content = '您购买了 '.$duration2.' 随意说';

					$Coindetail->objectIcon = '/Public/carpic/jin.png';

					$Coindetail->coin = $needcoin;

					$Coindetail->addtime = time();

					$detailId = $Coindetail->add();

					

					echo '{"msg":"购买成功"}';

					exit;

				}

				break;

//新道具开始

			case '5':


				$carInfo = M('car')->getById($_GET['toolsubid']);
				$needcoin = $carInfo['coin'];
				if($userinfo['coinbalance'] < $needcoin){
					

					echo '{"msg":"您的余额不足"}';

					exit;

				}

				else
				{
					
					//写消费
					D("Member")->execute('update ss_member set spendcoin=spendcoin+'.$needcoin.',coinbalance=coinbalance-'.$needcoin.' where id='.$_SESSION['uid']);
					$carRes = M('Membercar')->where("uid=$_SESSION[uid] and carID = $_GET[toolsubid] ")->find();
					if($carRes)
					{
						$car = M('Membercar');
						$car->where("uid = $_SESSION[uid] and carID = $_GET[toolsubid]")->setInc('endtime',3600*24*30);
	             
					}
					else
					{
						$car = M('Membercar');
						$car->uid = $_SESSION['uid'];
						$car->carID = (int)$_GET['toolsubid'];
						$car->endtime = time() + 3600*24*30;
						$car->updatetime = time();
						$res = $car->add();
						
					}




					//写入消费明细
                    
					$Coindetail = D("Coindetail");

					$Coindetail->create();

					$Coindetail->type = 'expend';

					$Coindetail->action = 'buy';

					$Coindetail->uid = $_SESSION['uid'];

					$Coindetail->giftcount = 1;

					$Coindetail->content = '您购买了座驾'.$carInfo['name'].'一个月';

					$Coindetail->objectIcon = $carInfo['image'];//名字前面显示的图标

					$Coindetail->coin = $needcoin;

					$Coindetail->addtime = time();

					$detailId = $Coindetail->add();
                    
					//echo M()->getLastsql();

					echo '{"msg":"购买成功请我的道具中启用"}';

					exit;

				}

				break;


//新道具结束



		}

	}



	public function wishing_wishing(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		//礼物

		$gifts = D('Giftsort')->query('select * from ss_giftsort order by orderno asc');

		foreach($gifts as $n=> $val){

			$gifts[$n]['voo']=D("Gift")->where('sid='.$val['id'])->select();

		}

		$this->assign('gifts',$gifts);



		$this->display();

	}



	public function showadmin(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$roomadmins = D("Roomadmin")->where("uid=".$_SESSION['uid'])->order('addtime desc')->select();

		foreach($roomadmins as $n=> $val){

			$roomadmins[$n]['voo']=D("Member")->where('id='.$val['adminuid'])->select();

		}

		$this->assign('roomadmins', $roomadmins);
        $userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);


		$this->display();

	}



	public function toggleEmceeShowAdmin(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$myshowadmin = D("Roomadmin")->where('uid='.$_SESSION['uid'].' and adminuid='.$_REQUEST['userid'])->order('id asc')->select();

		if($myshowadmin){

			D("Roomadmin")->where('uid='.$_SESSION['uid'].' and adminuid='.$_REQUEST['userid'])->delete();

			echo '1';

			exit;

		}

		else{

			$Roomadmin=D("Roomadmin");

			$Roomadmin->create();

			$Roomadmin->uid = $_SESSION['uid'];

			$Roomadmin->adminuid = $_REQUEST['userid'];

			$Roomadmin->add();



			echo '0';

			exit;

		}

	}



	public function familyIJoin(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function familyICreate(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function familyBadge(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function familyPrerogative(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function familyOperationLog(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function interestByList(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Attention = D("Attention");

		$count = $Attention->where("attuid=".$_SESSION['uid'])->count();

		$listRows = 12;

		import("@.ORG.Page2");

		$p = new Page($count,$listRows,$linkFront);

		$attentions = $Attention->where("attuid=".$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($attentions as $n=> $val){

			$attentions[$n]['voo']=D("Member")->where('id='.$val['uid'])->select();

		}

		$page = $p->show();

		$this->assign('attentions',$attentions);

		$this->assign('count',$count);

		$this->assign('page',$page);
         //判断
        $userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);

		//捧我的人

		$mypengusers = D('Coindetail')->query('SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and uid>0 and touid='.$_SESSION['uid'].' group by uid order by total desc LIMIT 5');

		foreach($mypengusers as $n=> $val){

			$mypengusers[$n]['voo']=D("Member")->where('id='.$val['uid'])->select();

		}

		$this->assign('mypengusers', $mypengusers);



		//我守护的人
		
		$guard = M("guard g")->where("g.anchorid = $_SESSION[uid] and g.maturitytime >".time())->join("ss_member m on m.id = g.userid")->field("g.*,m.curroomnum,m.nickname,ucuid")->select();

		$this->assign('guard', $guard);
		$this->display();

		

	}



	public function info_edit(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo',$userinfo);



		$this->display();

	}



	public function do_info_edit(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$User = D('Member');

		$vo = $User->create();

		if(!$vo) {

			$this->error($User->getError());

		}else{

			if($_POST['province'] != '请选择...'){

				$User->province = $_POST['province'];

			}

			if($_POST['city'] != '请选择...'){

				$User->city = $_POST['city'];

			}

			$User->save();



			session('nickname',$_POST["nickname"]);

			cookie('nickname',$_POST["nickname"],3600000);

			

			$this->assign('jumpUrl',__APP__.'/User/info_edit/');

			$this->success('保存成功');

		}

	}



	public function info_icon(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function info_changepass(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function do_info_changepass()
	{

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$User = D('Member');

		$vo = $User->create();

		if(!$vo) {

			$this->error($User->getError());

		}else{

			if($_POST['newpass'] != ''){

				if($_POST['oldpass'] == ''){

					$this->error('原始密码不能为空');

				}

				if($_POST['newpass'] != $_POST['newpwd_1']){

					$this->error('两次新密码不一致');

				}

include './config.inc.php';

include './uc_client/client.php';

$ucresult = uc_user_edit($_SESSION['username'], $_POST['oldpass'], $_POST['newpass']);

if($ucresult == -1) {

	$this->error('旧密码不正确');

} elseif($ucresult == -4) {

	$this->error('Email 格式有误');

} elseif($ucresult == -5) {

	$this->error('不允许注册');

} elseif($ucresult == -6) {

	$this->error('该 Email 已经被注册');

}



			}

			$User->password = md5($_POST['newpass']);

			$User->password2 = $this->pswencode($_POST['newpass']);

			$User->save();



			$this->assign('jumpUrl',__APP__."/User/info_changepass/");

			$this->success('修改成功');

		}

	}

	

	public function getGiftStat(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$getgifts = D('Coindetail')->query('SELECT objectIcon,sum(giftcount) as total FROM `ss_coindetail` where type="expend" and action="sendgift" and touid='.$_SESSION['uid'].' group by giftid order by total desc');

		$this->assign('getgifts', $getgifts);



		$sendgifts = D('Coindetail')->query('SELECT objectIcon,sum(giftcount) as total FROM `ss_coindetail` where type="expend" and action="sendgift" and uid='.$_SESSION['uid'].' group by giftid order by total desc');

		$this->assign('sendgifts', $sendgifts);



		$this->display();

	}



	public function getTakedGift(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET['begin'] != '' && $_GET['end'] != ''){

			$beginArr = explode("-", $_GET['begin']);

			$starttime = mktime(0,0,0,$beginArr[1],$beginArr[2],$beginArr[0]);

			$endArr = explode("-", $_GET['end']);

			$endtime = mktime(0,0,0,$endArr[1],$endArr[2],$endArr[0]);

			$condition = 'addtime>='.$starttime.' and addtime<='.$endtime;

		}

		else{

			$condition = 'date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y")';

		}



		$Coindetail = D("Coindetail");

		$count = $Coindetail->where('type="expend" and action="sendgift" and touid='.$_SESSION['uid'].' and '.$condition)->count();

		$listRows = 20;

		import("@.ORG.Page2");

		$p = new Page($count,$listRows,$linkFront);

		$getgifts = $Coindetail->where('type="expend" and action="sendgift" and touid='.$_SESSION['uid'].' and '.$condition)->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($getgifts as $n=> $val){

			$getgifts[$n]['voo']=D("Member")->where('id='.$val['uid'])->select();

		}

		$page = $p->show();

		$this->assign('getgifts',$getgifts);

		$this->assign('count',$count);

		$pagecount = ceil($count/$listRows);

		if($pagecount == 0){$pagecount = 1;}

		$this->assign('pagecount',$pagecount);

		$this->assign('page',$page);



		$this->display();

	}



	public function getBuyedGift(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET['begin'] != '' && $_GET['end'] != ''){

			$beginArr = explode("-", $_GET['begin']);

			$starttime = mktime(0,0,0,$beginArr[1],$beginArr[2],$beginArr[0]);

			$endArr = explode("-", $_GET['end']);

			$endtime = mktime(0,0,0,$endArr[1],$endArr[2],$endArr[0]);

			$condition = 'addtime>='.$starttime.' and addtime<='.$endtime;

		}

		else{

			$condition = 'date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y")';

		}



		$Coindetail = D("Coindetail");

		$count = $Coindetail->where('type="expend" and action="sendgift" and uid='.$_SESSION['uid'].' and '.$condition)->count();

		$listRows = 20;

		import("@.ORG.Page2");

		$p = new Page($count,$listRows,$linkFront);

		$sendgifts = $Coindetail->where('type="expend" and action="sendgift" and uid='.$_SESSION['uid'].' and '.$condition)->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($sendgifts as $n=> $val){

			$sendgifts[$n]['voo']=D("Member")->where('id='.$val['touid'])->select();

		}

		$page = $p->show();

		$this->assign('sendgifts',$sendgifts);

		$this->assign('count',$count);

		$pagecount = ceil($count/$listRows);

		if($pagecount == 0){$pagecount = 1;}

		$this->assign('pagecount',$pagecount);

		$this->assign('page',$page);



		$this->display();

	}



	public function getConsume(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET['begin'] != '' && $_GET['end'] != ''){

			$beginArr = explode("-", $_GET['begin']);

			$starttime = mktime(0,0,0,$beginArr[1],$beginArr[2],$beginArr[0]);

			$endArr = explode("-", $_GET['end']);

			$endtime = mktime(0,0,0,$endArr[1],$endArr[2],$endArr[0]);

			$condition = 'addtime>='.$starttime.' and addtime<='.$endtime;

		}

		else{

			$condition = 'date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y")';

		}



		$Coindetail = D("Coindetail");

		$count = $Coindetail->where('type="expend" and uid='.$_SESSION['uid'].' and '.$condition)->count();

		$listRows = 20;

		import("@.ORG.Page2");

		$p = new Page($count,$listRows,$linkFront);

		$consumes = $Coindetail->where('type="expend" and uid='.$_SESSION['uid'].' and '.$condition)->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		$page = $p->show();

		$this->assign('consumes',$consumes);

		$this->assign('count',$count);

		$pagecount = ceil($count/$listRows);

		if($pagecount == 0){$pagecount = 1;}

		$this->assign('pagecount',$pagecount);

		$this->assign('page',$page);
        $userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);


		$this->display();

	}



	public function getPresentation(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Giveaway = D("Giveaway");

		$count = $Giveaway->where('uid=0 and touid='.$_SESSION['uid'])->count();

		$listRows = 10;

		import("@.ORG.Page3");

		$p = new Page($count,$listRows,$linkFront);

		$systemsendtome = $Giveaway->where('uid=0 and touid='.$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		$page = $p->show();

		$this->assign('systemsendtome',$systemsendtome);

		$this->assign('page',$page);



		$count2 = $Giveaway->where('uid>0 and touid='.$_SESSION['uid'])->count();

		$listRows2 = 10;

		import("@.ORG.Page4");

		$p = new Page4($count2,$listRows2,$linkFront);

		$othersendtome = $Giveaway->where('uid>0 and touid='.$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($othersendtome as $n=> $val){

			$othersendtome[$n]['voo']=D("Member")->where('id='.$val['uid'])->select();

		}

		$page2 = $p->show();

		$this->assign('othersendtome',$othersendtome);

		$this->assign('page2',$page2);



		$this->display();

	}



	public function getSystemPresentation(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Giveaway = D("Giveaway");

		$count = $Giveaway->where('uid=0 and touid='.$_SESSION['uid'])->count();

		$listRows = 10;

		import("@.ORG.Page3");

		$p = new Page($count,$listRows,$linkFront);

		$systemsendtome = $Giveaway->where('uid=0 and touid='.$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		$page = $p->show();

		$this->assign('systemsendtome',$systemsendtome);

		$this->assign('page',$page);

		

		$this->display();

	}



	public function getEmceenoPresentation(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Giveaway = D("Giveaway");

		$count2 = $Giveaway->where('uid>0 and touid='.$_SESSION['uid'])->count();

		$listRows2 = 10;

		import("@.ORG.Page4");

		$p = new Page4($count2,$listRows2,$linkFront);

		$othersendtome = $Giveaway->where('uid>0 and touid='.$_SESSION['uid'])->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($othersendtome as $n=> $val){

			$othersendtome[$n]['voo']=D("Member")->where('id='.$val['uid'])->select();

		}

		$page2 = $p->show();

		$this->assign('othersendtome',$othersendtome);

		$this->assign('page2',$page2);



		$this->display();

	}



	public function getShowList(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET['date'] != ''){

			$condition = 'date_format(FROM_UNIXTIME(starttime),"%Y%m")="'.$_GET['date'].'"';

		}

		else{

			$condition = 'date_format(FROM_UNIXTIME(starttime),"%m-%Y")=date_format(now(),"%m-%Y")';

		}



		$liverecords = D('Liverecord')->query('SELECT date_format(FROM_UNIXTIME(starttime),"%Y年%m月%d日") as livedate FROM `ss_liverecord` where uid='.$_SESSION['uid'].' and '.$condition.' group by livedate order by livedate desc');

		$this->assign('liverecords', $liverecords);



		$this->display();

	}



	public function listAward(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function bl_list(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function charge(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_GET['ProxyUserID'] != ''){

			$proxyuserinfo = D("Member")->find($_GET['ProxyUserID']);

			if($proxyuserinfo){

				$proxyusername = $proxyuserinfo['nickname'];

				$proxyuserid = $proxyuserinfo['id'];

			}

			else{

				$proxyusername = '无';

				$proxyuserid = 0;

			}

			$this->assign('proxyusername', $proxyusername);

			$this->assign('proxyuserid', $proxyuserid);

		}



		$proxyusers = D('Member')->where('sellm="1"')->field('id,nickname')->order('id desc')->select();

		$this->assign('proxyusers', $proxyusers);



		$this->display();

	}



	public function ajaxcheckuser(){

		C('HTML_CACHE_ON',false);

		header("Content-type: text/html; charset=utf-8"); 

		$User = D("Member");

		if($_GET["roomnum"] == '')

		{

			exit;

		}

		else{

			$userinfo = $User->where('curroomnum='.$_GET["roomnum"].'')->select();

			if($userinfo){

				echo $userinfo[0]['nickname'];

			}

			else{

				exit;

			}

		}

	}



	public function chargepay(){

		C('HTML_CACHE_ON',false);

		header("Content-type: text/html; charset=utf-8"); 

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		if($_POST['c_ChargeType'] == '1'){

			$chargetouid = $_SESSION['uid'];

		}	

		else{

			$touserinfo = D("Member")->where('curroomnum='.$_POST["c_DestUserName"].'')->select();

			if($touserinfo){

				$chargetouid = $touserinfo[0]['id'];

			}

			else{

				$chargetouid = $_SESSION['uid'];

			}

		}


		//环迅支付


		if($_POST['c_PPPayID'] == 'huanxun'){
                     header("Content-type:text/html; charset=utf-8");
                     $siteconfig=M("siteconfig")->field("huanxun_shanghu,huanxun_key,huanxun_shanghuming,huanxun_zhanghu")->where("id='1'")->find();

					///支付记录

					$Chargedetail = D("Chargedetail");

					$Chargedetail->create();

					$Chargedetail->uid = $_SESSION['uid'];

					$Chargedetail->touid = $chargetouid;

					$Chargedetail->rmb = $_POST['c_Money1'];

					$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

					$Chargedetail->status = '订购未完成';

					$Chargedetail->addtime = time();

					$Chargedetail->orderno = $_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis');

					if($_GET['ProxyUserID'] != ''){

						$Chargedetail->proxyuid = $_GET['ProxyUserID'];

					}

					$detailId = $Chargedetail->add();	
                    //支付记录	
 

				    $payWayurl="http://newpay.ips.com.cn/psfp-entry/gateway/payment.do";
				    //$paytestWayurl="http://bankbackuat.ips.com.cn/psfp-entry/gateway/payment.html";

					 //获取输入参数
					$pVersion = 'v1.0.0';//版本号
					$pMerCode = $siteconfig["huanxun_shanghu"];//商户号
					$pMerName = $siteconfig["huanxun_shanghuming"];//商户名
					$pMerCert = $siteconfig["huanxun_key"];//商户证书
					$pAccount = $siteconfig["huanxun_zhanghu"];//交易账户号
					$pMsgId = 'msg'.date("YmdHis");//消息编号
					$pReqDate = date("YmdHis");//商户请求时间

					$pMerBillNo = $detailId.date('YmdHis');//商户订单号
					$pAmount = number_format($_POST['c_Money1'], 2, '.', '');//订单金额  
					$pDate = date("Ymd");//订单日期
					$pCurrencyType = '156';//币种
					$pGatewayType = '01';//支付方式
					$pLang = 'GB';//语言
					$pMerchanturl = $this->siteurl ."/index.php/User/chargelist";//支付结果成功返回的商户URL 
					$pFailUrl = "";//支付结果失败返回的商户URL 
					$pAttach = $detailId.'x'.$_SESSION['uid'].'x'.$chargetouid;//商户数据包
					$pOrderEncodeTyp = '5';//订单支付接口加密方式 默认为5#md5
					$pRetEncodeType = '17';//交易返回接口加密方式
					$pRetType = '1';//返回方式 
					$pServerUrl = $this->siteurl ."/index.php/User/huanxun_notify";//Server to Server返回页面 
					$pBillEXP = 1;//订单有效期(过期时间设置为1小时)
					$pGoodsName =$this->sitename."在线充值";//商品名称
					$pIsCredit = '';//直连选项
					$pBankCode ='';//银行号
					$pProductType= '';//产品类型


					 //请求报文的消息体
					  $strbodyxml= "<body>"
						         ."<MerBillNo>".$pMerBillNo."</MerBillNo>"
						         ."<Amount>".$pAmount."</Amount>"
						         ."<Date>".$pDate."</Date>"
						         ."<CurrencyType>".$pCurrencyType."</CurrencyType>"
						         ."<GatewayType>".$pGatewayType."</GatewayType>"
					                 ."<Lang>".$pLang."</Lang>"
						         ."<Merchanturl>".$pMerchanturl."</Merchanturl>"
						         ."<FailUrl>".$pFailUrl."</FailUrl>"
					                 ."<Attach>".$pAttach."</Attach>"
					                 ."<OrderEncodeType>".$pOrderEncodeTyp."</OrderEncodeType>"
					                 ."<RetEncodeType>".$pRetEncodeType."</RetEncodeType>"
					                 ."<RetType>".$pRetType."</RetType>"
					                 ."<ServerUrl>".$pServerUrl."</ServerUrl>"
					                 ."<BillEXP>".$pBillEXP."</BillEXP>"
					                 ."<GoodsName>".$pGoodsName."</GoodsName>"
					                 ."<IsCredit>".$pIsCredit."</IsCredit>"
					                 ."<BankCode>".$pBankCode."</BankCode>"
					                 ."<ProductType>".$pProductType."</ProductType>"
						      ."</body>";
					  
					  $Sign=$strbodyxml.$pMerCode.$pMerCert;//签名明文

				
					  $pSignature = md5($Sign);//数字签名  
					  //请求报文的消息头
					  $strheaderxml= "<head>"
					                   ."<Version>".$pVersion."</Version>"
					                   ."<MerCode>".$pMerCode."</MerCode>"
					                   ."<MerName>".$pMerName."</MerName>"
					                   ."<Account>".$pAccount."</Account>"
					                   ."<MsgId>".$pMsgId."</MsgId>"
					                   ."<ReqDate>".$pReqDate."</ReqDate>"
					                   ."<Signature>".$pSignature."</Signature>"
					              ."</head>";
					 
					//提交给网关的报文
					$strsubmitxml =  "<Ips>"
					              ."<GateWayReq>"
					              .$strheaderxml
					              .$strbodyxml
						      ."</GateWayReq>"
					            ."</Ips>";
	          

				echo '<html>
				          <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
				          <body>
	
				<form name="form1" id="form1" method="post" action="'. $payWayurl.'" target="_self">
						<input type="hidden" name="pGateWayReq" value="'. $strsubmitxml.'" />
						<script language="javascript">document.form1.submit();</script>
						</form></body></html>
						';	
				exit();		            


		}

		//环迅支付

		
      // 新增支付宝 即时到帐  lzh
		if($_POST['c_PPPayID'] == 'alipay_d'){
			     //获取后台设置的 配置信息
				 
				 $siteconfig=M("siteconfig")->where("id='1'")->find();
			
				//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
				//合作身份者id，以2088开头的16位纯数字
				$alipay_config['partner']		= $siteconfig['alipay_d_partner'];

				//安全检验码，以数字和字母组成的32位字符
				$alipay_config['key']			= $siteconfig['alipay_d_key'];

				$alipay_config['seller_email'] =$siteconfig['alipay_d_email'];

				//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


				//签名方式 不需修改
				$alipay_config['sign_type']    = strtoupper('MD5');

				//字符编码格式 目前支持 gbk 或 utf-8
				$alipay_config['input_charset']= strtolower('utf-8');

				//ca证书路径地址，用于curl中ssl校验
				//请保证cacert.pem文件在当前文件夹目录中
				$alipay_config['cacert']    = getcwd().'\\cacert.pem';

				//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
				$alipay_config['transport']    = 'http';
				//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

					require_once "./alipay_d/lib/alipay_submit.class.php";
					
					///支付记录

					$Chargedetail = D("Chargedetail");

					$Chargedetail->create();

					$Chargedetail->uid = $_SESSION['uid'];

					$Chargedetail->touid = $chargetouid;

					$Chargedetail->rmb = $_POST['c_Money1'];

					$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

					$Chargedetail->status = '订购未完成';

					$Chargedetail->addtime = time();

					$Chargedetail->orderno = $_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis');

					if($_GET['ProxyUserID'] != ''){

						$Chargedetail->proxyuid = $_GET['ProxyUserID'];

					}

					$detailId = $Chargedetail->add();	
                    //支付记录						

			/**************************请求参数**************************/

				//支付类型
				$payment_type = "1";
				//必填，不能修改
				//服务器异步通知页面路径
				$notify_url = $this->siteurl ."/index.php/User/alipay_d_notify/";
				//需http://格式的完整路径，不能加?id=123这类自定义参数

				//页面跳转同步通知页面路径
				$return_url =  $this->siteurl ."/index.php/User/chargelist/";
				//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

				//必填

				//商户订单号  //结构 充值记录 id _  充值用户uid _ 充值到的账户 UID  _ 时间戳
				$out_trade_no = $detailId.'_'.$_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis');  
				//商户网站订单系统中唯一订单号，必填

				//订单名称
				$subject =$_SESSION['username'];
				//必填

				//付款金额
				$total_fee = $_POST['c_Money1'];
				//必填

				//订单描述

				$body = $this->sitename."在线充值";
				//商品展示地址
				$show_url = $this->sitename;
				//需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

				//防钓鱼时间戳
				$anti_phishing_key = "";
				//若要使用请调用类文件submit中的query_timestamp函数

				//客户端的IP地址
				$exter_invoke_ip = "";
				//非局域网的外网IP地址，如：221.0.0.1
				/************************************************************/

				//构造要请求的参数数组，无需改动
				$parameter = array(
						"service" => "create_direct_pay_by_user",
						"partner" => trim($alipay_config['partner']),
						"payment_type"	=> $payment_type,
						"notify_url"	=> $notify_url,
						"return_url"	=> $return_url,
						"seller_email"	=> trim($alipay_config['seller_email']),
						"out_trade_no"	=> $out_trade_no,
						"subject"	=> $subject,
						"total_fee"	=> $total_fee,
						"body"	=> $body,
						"show_url"	=> $show_url,
						"anti_phishing_key"	=> $anti_phishing_key,
						"exter_invoke_ip"	=> $exter_invoke_ip,
						"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
				);

				//建立请求
				$alipaySubmit = new AlipaySubmit($alipay_config);
				$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
				echo $html_text;


			
		}	  
	  
	  
      // 新增支付宝 即时到帐		 

      // 新增 易宝网银  lzh
		if($_POST['setYeepay'] == '1'){
			     //获取后台设置的 配置信息
				 
				 $siteconfig=M("siteconfig")->where("id='1'")->find();
			

				#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
				$p1_MerId		= $siteconfig['yeepay_p1_MerId'];																									#测试使用
				$merchantKey	= $siteconfig['yeepay_merchantKey'];	#测试使用
				#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
				//$p1_MerId			= "10068558696";																										#测试使用
				//$merchantKey	= "69cl522AV6q613Ii4Wgyv769220IuYe9u37N4y7rI4Pl";		#测试使用
				$logName	= "YeePay_HTML.log";



					require_once "./yeepay/yeepayCommon.php";
					
					///支付记录

					$Chargedetail = D("Chargedetail");

					$Chargedetail->create();

					$Chargedetail->uid = $_SESSION['uid'];

					$Chargedetail->touid = $chargetouid;

					$Chargedetail->rmb = $_POST['c_Money1'];

					$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

					$Chargedetail->status = '订购未完成';

					$Chargedetail->addtime = time();

					$Chargedetail->orderno = $_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis');

					if($_GET['ProxyUserID'] != ''){

						$Chargedetail->proxyuid = $_GET['ProxyUserID'];

					}

					$detailId = $Chargedetail->add();	
                    //支付记录						
							
					#	商家设置用户购买商品的支付信息.
					##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码

					#	商户订单号,选填.
					##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
					$p2_Order	= $detailId.'_'.$_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis');  ;
					//4位随机数

					#	支付金额,必填.
					##单位:元，精确到分.
					$money= number_format( $_POST['c_Money1'], 2, ".", "" );
					
					//$p3_Amt						= $_REQUEST['p3_Amt'];
					$p3_Amt						= $money;

					#	交易币种,固定值"CNY".
					$p4_Cur						= "CNY";

					#	商品名称
					##用于支付时显示在易宝支付网关左侧的订单产品信息.
					$p5_Pid						= $this->sitename."在线充值";

					#	商品种类
					$p6_Pcat					= $this->sitename."在线充值";

					#	商品描述
					$p7_Pdesc					= $this->sitename."在线充值";

					#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
					$p8_Url						= $this->siteurl ."/index.php/User/yeepay_callback/";	

					#	商户扩展信息
					##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
					$pa_MP						= $this->sitename."在线充值";

					#	支付通道编码
					##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
					$pd_FrpId					= $_REQUEST['c_PPPayID'];

					#	应答机制
					##默认为"1": 需要应答机制;
					$pr_NeedResponse	= "1";

					#调用签名函数生成签名串
					$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
					  
					echo "<html>
							<head>
							<title>To YeePay Page</title>
							</head>
							<body onLoad='document.yeepay.submit();'>
							<form name='yeepay' action='".$reqURL_onLine."' method='post'>
							<input type='hidden' name='p0_Cmd'					value='".$p0_Cmd."'>
							<input type='hidden' name='p1_MerId'				value='".$p1_MerId."'>
							<input type='hidden' name='p2_Order'				value='".$p2_Order."'>
							<input type='hidden' name='p3_Amt'					value='".$p3_Amt."'>
							<input type='hidden' name='p4_Cur'					value='".$p4_Cur."'>
							<input type='hidden' name='p5_Pid'					value='".$p5_Pid."'>
							<input type='hidden' name='p6_Pcat'					value='".$p6_Pcat."'>
							<input type='hidden' name='p7_Pdesc'				value='".$p7_Pdesc."'>
							<input type='hidden' name='p8_Url'					value='".$p8_Url."'>
							<input type='hidden' name='p9_SAF'					value='".$p9_SAF."'>
							<input type='hidden' name='pa_MP'					value='".$pa_MP."'>
							<input type='hidden' name='pd_FrpId'				value='".$pd_FrpId."'>
							<input type='hidden' name='pr_NeedResponse'	        value='".$pr_NeedResponse."'>
							<input type='hidden' name='hmac'					value='".$hmac."'>
							</form>
							</body>
							</html>";
			
		}	 


	  
	  
      // 新增 易宝网银	
      
      
      
          //快钱    原 if 条件   1_ICBC-NET-B2C
		if($_POST['c_PPPayID'] == '99bill'){

			$Chargedetail = D("Chargedetail");

			$Chargedetail->create();

			$Chargedetail->uid = $_SESSION['uid'];

			$Chargedetail->touid = $chargetouid;

			$Chargedetail->rmb = $_POST['c_Money1'];

			$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

			$Chargedetail->status = '订购未完成';

			$Chargedetail->addtime = time();

			$Chargedetail->orderno = $orderId;

			if($_GET['ProxyUserID'] != ''){

				$Chargedetail->proxyuid = $_GET['ProxyUserID'];

			}

			$detailId = $Chargedetail->add();



			//人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
			//$merchantAcctId = "1001213884201";
			$merchantAcctId=$this->bill_MerchantAcctID;
			//编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
			$inputCharset = "1";
			//接收支付结果的页面地址，该参数一般置为空即可。
			$pageUrl = $this->siteurl ."/index.php/User/chargelist/";
			//服务器接收支付结果的后台地址，该参数务必填写，不能为空。
			$bgUrl = $this->siteurl ."/index.php/User/bill_callback/";
			//网关版本，固定值：v2.0,该参数必填。
			$version =  "v2.0";
			//语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
			$language =  "1";
			//签名类型,该值为4，代表PKI加密方式,该参数必填。
			$signType =  "4";
			//支付人姓名,可以为空。
			$payerName= $_SESSION['username'];
			//支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
			$payerContactType =  "1";
			//支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
			$payerContact =  "";
			//商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
			$orderId = $detailId.'_'.$_SESSION['uid'].'_'.$chargetouid.'_'.date('YmdHis'); 
			//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
			$orderAmount = $_POST['c_Money1'] * 100;
			//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
			$orderTime = date("YmdHis");
			//商品名称，可以为空。
			$productName= $this->sitename."在线充值"; 
			//商品数量，可以为空。
			$productNum =$_POST['c_Money1'];
			//商品代码，可以为空。
			$productId = "";
			//商品描述，可以为空。
			$productDesc = $this->sitename."在线充值";
			//扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
			$ext1 = "";
			//扩展自段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
			$ext2 = "";
			//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10，必填。
			$payType = "00";
			//银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
			$bankId = "";
			//同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
			$redoFlag = "";
			//快钱合作伙伴的帐户号，即商户编号，可为空。
			$pid = "";
			// signMsg 签名字符串 不可空，生成加密签名串

			function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}


			$kq_all_para=kq_ck_null($inputCharset,'inputCharset');
			$kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
			$kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
			$kq_all_para.=kq_ck_null($version,'version');
			$kq_all_para.=kq_ck_null($language,'language');
			$kq_all_para.=kq_ck_null($signType,'signType');
			$kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
			$kq_all_para.=kq_ck_null($payerName,'payerName');
			$kq_all_para.=kq_ck_null($payerContactType,'payerContactType');
			$kq_all_para.=kq_ck_null($payerContact,'payerContact');
			$kq_all_para.=kq_ck_null($orderId,'orderId');
			$kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
			$kq_all_para.=kq_ck_null($orderTime,'orderTime');
			$kq_all_para.=kq_ck_null($productName,'productName');
			$kq_all_para.=kq_ck_null($productNum,'productNum');
			$kq_all_para.=kq_ck_null($productId,'productId');
			$kq_all_para.=kq_ck_null($productDesc,'productDesc');
			$kq_all_para.=kq_ck_null($ext1,'ext1');
			$kq_all_para.=kq_ck_null($ext2,'ext2');
			$kq_all_para.=kq_ck_null($payType,'payType');
			$kq_all_para.=kq_ck_null($bankId,'bankId');
			$kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
			$kq_all_para.=kq_ck_null($pid,'pid');
			

			$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);


			
			/////////////  RSA 签名计算 ///////// 开始 //
			$fp = fopen("./99bill/99bill-rsa.pem", "r");
			$priv_key = fread($fp, 123456);
			fclose($fp);
			$pkeyid = openssl_get_privatekey($priv_key);

			// compute signature
			openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

			// free the key from memory
			openssl_free_key($pkeyid);

			 $signMsg = base64_encode($signMsg);
			/////////////  RSA 签名计算 ///////// 结束 //
		

			echo '			
			<form name="kqPay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">
				<input type="hidden" name="inputCharset" value="'.$inputCharset.'" />
				<input type="hidden" name="pageUrl" value="'.$pageUrl.'" />
				<input type="hidden" name="bgUrl" value="'.$bgUrl.'" />
				<input type="hidden" name="version" value="'.$version.'" />
				<input type="hidden" name="language" value="'.$language.'" />
				<input type="hidden" name="signType" value="'.$signType.'" />
				<input type="hidden" name="signMsg" value="'.$signMsg.'" />
				<input type="hidden" name="merchantAcctId" value="'.$merchantAcctId.'" />
				<input type="hidden" name="payerName" value="'.$payerName.'" />
				<input type="hidden" name="payerContactType" value="'.$payerContactType.'" />
				<input type="hidden" name="payerContact" value="'.$payerContact.'" />
				<input type="hidden" name="orderId" value="'.$orderId.'" />
				<input type="hidden" name="orderAmount" value="'.$orderAmount.'" />
				<input type="hidden" name="orderTime" value="'.$orderTime.'" />
				<input type="hidden" name="productName" value="'.$productName.'" />
				<input type="hidden" name="productNum" value="'.$productNum.'" />
				<input type="hidden" name="productId" value="'.$productId.'" />
				<input type="hidden" name="productDesc" value="'.$productDesc.'" />
				<input type="hidden" name="ext1" value="'.$ext1.'" />
				<input type="hidden" name="ext2" value="'.$ext2.'" />
				<input type="hidden" name="payType" value="'.$payType.'" />
				<input type="hidden" name="bankId" value="'.$bankId.'" />
				<input type="hidden" name="redoFlag" value="'.$redoFlag.'" />
				<input type="hidden" name="pid" value="'.$pid.'" />
			</form>';



			echo '<script type="text/javascript">';

			echo "	document.forms['kqPay'].submit();";

			echo '</script>';

		}

		// 快钱

//--------------支付宝--------------------------------------------------------------



if($_POST['c_PPPayID'] == '17_JIUYOU-NET'){



include "./alipay/alipay.config.php";

include "./alipay/lib/alipay_submit.class.php";

			 //支付类型

		$service="create_direct_pay_by_user";	 

        $payment_type = "1";

        //必填，不能修改

        //服务器异步通知页面路径

         $notify_url = $this->siteurl ."/index.php/User/apayreceive/";

        //需http://格式的完整路径，不能加?id=123这类自定义参数



        //页面跳转同步通知页面路径

        $return_url = $this->siteurl ."/index.php/User/apayreceive/";

        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/



        //商户订单号

        $out_trade_no = date('YmdHis');

        //商户网站订单系统中唯一订单号，必填



        //订单名称

        $subject = $_SESSION['username'];

        //必填



        //付款金额

        $total_fee = $_POST['c_Money1'];

        //必填



        //订单描述



        $body = $this->sitename."在线充值";

        //商品展示地址

        $show_url = $this->sitename;

        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html



        //防钓鱼时间戳

        $anti_phishing_key = "";

        //若要使用请调用类文件submit中的query_timestamp函数



        //客户端的IP地址

        $exter_invoke_ip = "";

        //非局域网的外网IP地址，如：221.0.0.1



			

		

			$Chargedetail = D("Chargedetail");

			$Chargedetail->create();

			$Chargedetail->uid = $_SESSION['uid'];

			$Chargedetail->touid = $chargetouid;

			$Chargedetail->rmb = $_POST['c_Money1'];

			$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

			$Chargedetail->status = '订购未完成';

			$Chargedetail->addtime = time();

			$Chargedetail->orderno = $out_trade_no;

			if($_GET['ProxyUserID'] != ''){

				$Chargedetail->proxyuid = $_GET['ProxyUserID'];

			}

			$detailId = $Chargedetail->add();

		

			//构造要请求的参数数组，无需改动

$parameter = array(

		"service" => "create_direct_pay_by_user",

		"partner" => trim($alipay_config['partner']),

		"seller_email" => trim($alipay_config['seller_email']),

		"payment_type"	=> $payment_type,

		"notify_url"	=> $notify_url,

		"return_url"	=> $return_url,

		"out_trade_no"	=> $out_trade_no,

		"subject"	=> $subject,

		"total_fee"	=> $total_fee,

		"body"	=> $body,

		"show_url"	=> $show_url,

		"anti_phishing_key"	=> $anti_phishing_key,

		"exter_invoke_ip"	=> $exter_invoke_ip,

		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))

);



//建立请求

$alipaySubmit = new AlipaySubmit($alipay_config);

$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认支付");

echo $html_text;

		}





//-----------------------------------------------------------------

		if($_POST['c_PPPayID'] == '14_SZX-NET' ){

			if($_POST['c_PPPayID'] == '14_SZX-NET'){

				if($_POST['paycardType'] == 'chinamobile'){

					$merchantAcctId="1002225194002";

					$key="J6B5GECXJTK7CJFS";

				}

				if($_POST['paycardType'] == 'chinaunion'){

					$merchantAcctId="1002225194003";

					$key="5CD8UKG7I8LGRWCM";

				}

				if($_POST['paycardType'] == 'chinatelecom'){

					$merchantAcctId="1002225194004";

					$key="LH4RAD7NXSDNYF5B";

				}

			}

			if($_POST['c_PPPayID'] == '17_JIUYOU-NET'){

				if($_POST['gamecardType'] == 'zongyou'){

					$merchantAcctId="1002225194010";

					$key="54HHYTGSII9ZW2HW";

				}

				if($_POST['gamecardType'] == 'netease'){

					$merchantAcctId="1002225194009";

					$key="YF6MWZW4Q35EXEQX";

				}

				if($_POST['gamecardType'] == 'sohu'){

					$merchantAcctId="1002225194008";

					$key="YDI8US7J97FSKR7F";

				}

				if($_POST['gamecardType'] == 'wanmei'){

					$merchantAcctId="1002225194007";

					$key="7S94QYTU4EXWUUF8";

				}

				if($_POST['gamecardType'] == 'snda'){

					$merchantAcctId="1002225194006";

					$key="Z2HYNHZYR4GRFMNS";

				}

				if($_POST['gamecardType'] == 'junnet'){

					$merchantAcctId="1002225194005";

					$key="SDD9JIUHJFNQJK7J";

				}

			}

			$inputCharset="1";

			$bgUrl=$this->siteurl ."/index.php/User/card_payreceive/";

			$pageUrl="";

			$version="v2.0";

			$language="1";

			$signType="1";	

			$payerName=$_SESSION['username'];

			$payerContactType="1";

			$payerContact="";

			$orderId=date('YmdHis');

			$orderAmount=$_POST['c_Money1'] * 100;

			$payType="42";

			//$cardNumber=$this->encrypt($_POST['paycard_num'],$key);

			//$cardPwd=$this->encrypt($_POST['paycard_psw'],$key);

			$cardNumber="";

			$cardPwd="";

			$fullAmountFlag="0";

			$orderTime=date('YmdHis');

			$productName=urlencode($this->sitename.'在线充值');

			$productNum="1";

			$productId="";

			$productDesc=urlencode($this->sitename.'在线充值');

			$ext1="";

			$ext2="";

			if($_POST['c_PPPayID'] == '14_SZX-NET'){

				if($_POST['paycardType'] == 'chinamobile'){

					$bossType="0";

				}

				if($_POST['paycardType'] == 'chinaunion'){

					$bossType="1";

				}

				if($_POST['paycardType'] == 'chinatelecom'){

					$bossType="3";

				}

			}

			if($_POST['c_PPPayID'] == '17_JIUYOU-NET'){

				if($_POST['gamecardType'] == 'zongyou'){

					$bossType="15";

				}

				if($_POST['gamecardType'] == 'netease'){

					$bossType="14";

				}

				if($_POST['gamecardType'] == 'sohu'){

					$bossType="13";

				}

				if($_POST['gamecardType'] == 'wanmei'){

					$bossType="12";

				}

				if($_POST['gamecardType'] == 'snda'){

					$bossType="10";

				}

				if($_POST['gamecardType'] == 'junnet'){

					$bossType="4";

				}

			}

			//echo $merchantAcctId.'|'.$key.'|'.$bossType.'|'.$_POST['gamecardType'];

			//exit;

			//$bossType="9";



			$signMsgVal=$this->appendParam($signMsgVal,"inputCharset",$inputCharset);

			$signMsgVal=$this->appendParam($signMsgVal,"bgUrl",$bgUrl);

			$signMsgVal=$this->appendParam($signMsgVal,"pageUrl",$pageUrl);

			$signMsgVal=$this->appendParam($signMsgVal,"version",$version);

			$signMsgVal=$this->appendParam($signMsgVal,"language",$language);

			$signMsgVal=$this->appendParam($signMsgVal,"signType",$signType);

			$signMsgVal=$this->appendParam($signMsgVal,"merchantAcctId",$merchantAcctId);

			$signMsgVal=$this->appendParam($signMsgVal,"payerName",$payerName);

			$signMsgVal=$this->appendParam($signMsgVal,"payerContactType",$payerContactType);

			$signMsgVal=$this->appendParam($signMsgVal,"payerContact",$payerContact);

			$signMsgVal=$this->appendParam($signMsgVal,"orderId",$orderId);

			$signMsgVal=$this->appendParam($signMsgVal,"orderAmount",$orderAmount);

			$signMsgVal=$this->appendParam($signMsgVal,"payType",$payType);

			$signMsgVal=$this->appendParam($signMsgVal,"cardNumber",$cardNumber);

			$signMsgVal=$this->appendParam($signMsgVal,"cardPwd",$cardPwd);

			$signMsgVal=$this->appendParam($signMsgVal,"fullAmountFlag",$fullAmountFlag);

			$signMsgVal=$this->appendParam($signMsgVal,"orderTime",$orderTime);

			$signMsgVal=$this->appendParam($signMsgVal,"productName",$productName);

			$signMsgVal=$this->appendParam($signMsgVal,"productNum",$productNum);

			$signMsgVal=$this->appendParam($signMsgVal,"productId",$productId);

			$signMsgVal=$this->appendParam($signMsgVal,"productDesc",$productDesc);

			$signMsgVal=$this->appendParam($signMsgVal,"ext1",$ext1);

			$signMsgVal=$this->appendParam($signMsgVal,"ext2",$ext2);

			$signMsgVal=$this->appendParam($signMsgVal,"bossType",$bossType);

			$signMsgVal=$this->appendParam($signMsgVal,"key",$key);

			//echo $signMsgVal;

			//exit;

			$signMsg= strtoupper(md5($signMsgVal));

		

			$Chargedetail = D("Chargedetail");

			$Chargedetail->create();

			$Chargedetail->uid = $_SESSION['uid'];

			$Chargedetail->touid = $chargetouid;

			$Chargedetail->rmb = $_POST['c_Money1'];

			$Chargedetail->coin = $_POST['c_Money1'] * $this->ratio;

			$Chargedetail->status = '订购未完成';

			$Chargedetail->addtime = time();

			$Chargedetail->orderno = $orderId;

			if($_GET['ProxyUserID'] != ''){

				$Chargedetail->proxyuid = $_GET['ProxyUserID'];

			}

			$detailId = $Chargedetail->add();

		

			echo '<form name="kqPay" method="post" action="http://www.99bill.com/szxgateway/recvMerchantInfoAction.htm">';

			echo '	<input type="hidden" name="inputCharset" value="'.$inputCharset.'"/>';

			echo '	<input type="hidden" name="bgUrl" value="'.$bgUrl.'"/>';

			echo '	<input type="hidden" name="pageUrl" value="'.$pageUrl.'">';

			echo '	<input type="hidden" name="version" value="'.$version.'"/>';

			echo '	<input type="hidden" name="language" value="'.$language.'"/>';

			echo '	<input type="hidden" name="signType" value="'.$signType.'"/>';

			echo '	<input type="hidden" name="merchantAcctId" value="'.$merchantAcctId.'"/>';

			echo '	<input type="hidden" name="payerName" value="'.$payerName.'"/>';

			echo '	<input type="hidden" name="payerContactType" value="'.$payerContactType.'"/>';

			echo '	<input type="hidden" name="payerContact" value="'.$payerContact.'"/>';

			echo '	<input type="hidden" name="orderId" value="'.$orderId.'"/>';

			echo '	<input type="hidden" name="orderAmount" value="'.$orderAmount.'"/>';

			echo '	<input type="hidden" name="payType" value="'.$payType.'"/>';

			echo '	<input type="hidden" name="cardNumber" value="'.$cardNumber.'">';

			echo '	<input type="hidden" name="cardPwd" value="'.$cardPwd.'">';

			echo '	<input type="hidden" name="fullAmountFlag" value="'.$fullAmountFlag.'">';

			echo '	<input type="hidden" name="orderTime" value="'.$orderTime.'"/>';

			echo '	<input type="hidden" name="productName" value="'.$productName.'"/>';

			echo '	<input type="hidden" name="productNum" value="'.$productNum.'"/>';

			echo '	<input type="hidden" name="productId" value="'.$productId.'"/>';

			echo '	<input type="hidden" name="productDesc" value="'.$productDesc.'"/>';

			echo '	<input type="hidden" name="ext1" value="'.$ext1.'"/>';

			echo '	<input type="hidden" name="ext2" value="'.$ext2.'"/>';

			echo '	<input type="hidden" name="bossType" value="'.$bossType.'"/>';

			echo '	<input type="hidden" name="signMsg" value="'.$signMsg.'"/>';



			echo '</form>';



			echo '<script type="text/javascript">';

			echo "	document.forms['kqPay'].submit();";

			echo '</script>';

		}



	}



	public function dumppost(){

		dump($_POST);

	}

	//环迅
public function huanxun_notify(){

			$paymentResult = $_POST["paymentResult"];//获取信息
			//file_put_contents('./huanxun.txt',date('y-m-d h:i:s')."S2S接收到的报文信息:".$paymentResult."\r\n",FILE_APPEND);
			$xml=simplexml_load_string($paymentResult,'SimpleXMLElement', LIBXML_NOCDATA); 

			  //读取相关xml中信息
			   $ReferenceIDs = $xml->xpath("GateWayRsp/head/ReferenceID");//关联号
			   //var_dump($ReferenceIDs); 
			   $ReferenceID = $ReferenceIDs[0];//关联号
			   $RspCodes = $xml->xpath("GateWayRsp/head/RspCode");//响应编码
			   $RspCode=$RspCodes[0];
			   $RspMsgs = $xml->xpath("GateWayRsp/head/RspMsg"); //响应说明
			   $RspMsg=$RspMsgs[0];
			   $ReqDates = $xml->xpath("GateWayRsp/head/ReqDate"); // 接受时间
			    $ReqDate=$ReqDates[0];
			   $RspDates = $xml->xpath("GateWayRsp/head/RspDate");// 响应时间
			    $RspDate=$RspDates[0];
			   $Signatures = $xml->xpath("GateWayRsp/head/Signature"); //数字签名
			    $Signature=$Signatures[0];
			   $MerBillNos = $xml->xpath("GateWayRsp/body/MerBillNo"); // 商户订单号
			    $MerBillNo=$MerBillNos[0];
			   $CurrencyTypes = $xml->xpath("GateWayRsp/body/CurrencyType");//币种
			    $CurrencyType=$CurrencyTypes[0];
			   $Amounts = $xml->xpath("GateWayRsp/body/Amount"); //订单金额
			    $Amount=$Amounts[0];
			   $Dates = $xml->xpath("GateWayRsp/body/Date");    //订单日期
			    $Date=$Dates[0];
			   $Statuss = $xml->xpath("GateWayRsp/body/Status");  //交易状态
			    $Status=$Statuss[0];
			   $Msgs = $xml->xpath("GateWayRsp/body/Msg");    //发卡行返回信息
			    $Msg=$Msgs[0];
			   $Attachs = $xml->xpath("GateWayRsp/body/Attach");    //数据包
			    $Attach=$Attachs[0];
			   $IpsBillNos = $xml->xpath("GateWayRsp/body/IpsBillNo"); //IPS订单号
			    $IpsBillNo=$IpsBillNos[0];
			   $IpsTradeNos = $xml->xpath("GateWayRsp/body/IpsTradeNo"); //IPS交易流水号
			    $IpsTradeNo=$IpsTradeNos[0];
			   $RetEncodeTypes = $xml->xpath("GateWayRsp/body/RetEncodeType");    //交易返回方式
			    $RetEncodeType=$RetEncodeTypes[0];
			   $BankBillNos = $xml->xpath("GateWayRsp/body/BankBillNo"); //银行订单号
			    $BankBillNo=$BankBillNos[0];
			   $ResultTypes = $xml->xpath("GateWayRsp/body/ResultType"); //支付返回方式
			    $ResultType=$ResultTypes[0];
			   $IpsBillTimes = $xml->xpath("GateWayRsp/body/IpsBillTime"); //IPS处理时间
			    $IpsBillTime=$IpsBillTimes[0];
				
			//验签明文
			//billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】
			 $sbReq = "<body>"
			                          . "<MerBillNo>" . $MerBillNo . "</MerBillNo>"
			                          . "<CurrencyType>" . $CurrencyType . "</CurrencyType>"
			                          . "<Amount>" . $Amount . "</Amount>"
			                          . "<Date>" . $Date . "</Date>"
			                          . "<Status>" . $Status . "</Status>"
			                          . "<Msg><![CDATA[" . $Msg . "]]></Msg>"
			                          . "<Attach><![CDATA[" . $Attach . "]]></Attach>"
			                          . "<IpsBillNo>" . $IpsBillNo . "</IpsBillNo>"
			                          . "<IpsTradeNo>" . $IpsTradeNo . "</IpsTradeNo>"
			                          . "<RetEncodeType>" . $RetEncodeType . "</RetEncodeType>"
			                          . "<BankBillNo>" . $BankBillNo . "</BankBillNo>"
			                          . "<ResultType>" . $ResultType . "</ResultType>"
			                          . "<IpsBillTime>" . $IpsBillTime . "</IpsBillTime>"
			                       . "</body>";

			 $siteconfig=M("siteconfig")->field("huanxun_shanghu,huanxun_key")->where("id='1'")->find();                         
			$sign=$sbReq.$siteconfig['huanxun_shanghu'].$siteconfig['huanxun_key'];
			//file_put_contents('./huanxun.txt',date('y-m-d h:i:s').'S2S验签明文:'.$sign."\r\n",FILE_APPEND);
			$md5sign=  md5($sign);
			//file_put_contents('./huanxun.txt',date('y-m-d h:i:s').'S2S验签密文:'.$md5sign."\r\n",FILE_APPEND);

			//判断签名
			if($Signature==$md5sign)
			{
			    //file_put_contents('./huanxun.txt',date('y-m-d h:i:s')."S2S验签成功.\r\n",FILE_APPEND);
			      if($RspCode=='000000')
			    {

					$orderid=explode("x",$Attach);
					
					$id=$orderid[0];
					$uid=$orderid[1];
					$touid=$orderid[2];
					
					$data=array(); 
					$data['status']='订购完成';
					$data['dealId']=$trade_no;
					//更新记录
					M("Chargedetail")->where("id='$id' and uid='$uid' and touid='$touid' ")->save($data);
					
					D("Member")->execute('update ss_member set coinbalance=coinbalance+'.($Amount*$this->ratio).' where id='.$touid);
					
					//更新会员余额
					echo '订单支付成功';			    	
				//file_put_contents('./huanxun.txt',date('y-m-d h:i:s')."S2S订单支付成功.\r\n",FILE_APPEND);	
			    }
			    
			 }
			else
			{
			// file_put_contents('./huanxun.txt',date('y-m-d h:i:s')."S2S验签失败.\r\n",FILE_APPEND);
			    echo "订单签名错误";
			}	
	
}	

//环迅	
	
	//支付宝即时到帐  返回处理
public function alipay_d_return(){
	
	
	
	
}	

public function alipay_d_notify(){
	
			     //获取后台设置的 配置信息
				 
				 $siteconfig=M("siteconfig")->where("id='1'")->find();
			
				//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
				//合作身份者id，以2088开头的16位纯数字
				$alipay_config['partner']		= $siteconfig['alipay_d_partner'];

				//安全检验码，以数字和字母组成的32位字符
				$alipay_config['key']			= $siteconfig['alipay_d_key'];

				$alipay_config['seller_email'] =$siteconfig['alipay_d_email'];

				//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


				//签名方式 不需修改
				$alipay_config['sign_type']    = strtoupper('MD5');

				//字符编码格式 目前支持 gbk 或 utf-8
				$alipay_config['input_charset']= strtolower('utf-8');

				//ca证书路径地址，用于curl中ssl校验
				//请保证cacert.pem文件在当前文件夹目录中
				$alipay_config['cacert']    = getcwd().'\\cacert.pem';

				//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
				$alipay_config['transport']    = 'http';
				//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
				
				
		require_once("./alipay_d/lib/alipay_notify.class.php");

		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功

			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号

			$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号

			$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];
			//交易金额
			$total_fee = $_POST['total_fee'];


            //充值判断修改状态
			$orderid=explode("_",$out_trade_no);
			
			$id=$orderid[0];
			$uid=$orderid[1];
			$touid=$orderid[2];
			
			$data=array();
			$data['status']='订购完成';
			$data['dealId']=$trade_no;
			//更新记录
			M("Chargedetail")->where("id='$id' and uid='$uid' and touid='$touid' ")->save($data);
			
			D("Member")->execute('update ss_member set coinbalance=coinbalance+'.($total_fee*$this->ratio).' where id='.$touid);
			
			//更新会员余额
			
			

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
				
			echo "success";		//请不要修改或删除
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			//验证失败
			echo "fail";

			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}	
	
	
}	
	//支付宝即时到帐  返回处理	



 //易宝 支付 返回
 
 public function yeepay_callback(){

		 //获取后台设置的 配置信息
		 
		 $siteconfig=M("siteconfig")->where("id='1'")->find();
	

		#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
		$p1_MerId		= $siteconfig['yeepay_p1_MerId'];																									#测试使用
		$merchantKey	= $siteconfig['yeepay_merchantKey'];	#测试使用
		#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
		//$p1_MerId			= "10068558696";																										#测试使用
		//$merchantKey	= "69cl522AV6q613Ii4Wgyv769220IuYe9u37N4y7rI4Pl";		#测试使用
		$logName	= "YeePay_HTML.log";
				
		require_once "./yeepay/yeepayCommon.php";	
			
		#	只有支付成功时易宝支付才会通知商户.
		##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

		#	解析返回参数.
		$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

		#	判断返回签名是否正确（True/False）
		$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
		#	以上代码和变量不需要修改.
				
		#	校验码正确.
		if($bRet){
			if($r1_Code=="1"){
				
			#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
			#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.      	  	
				
				if($r9_BType=="1"){
					
						//echo "交易成功";
							//echo  "<br />在线支付页面返回";
							//数据库链接处理数据开始-----------
					
					//充值判断修改状态
					$orderid=explode("_",$r6_Order); 
					
					$id=$orderid[0];
					$uid=$orderid[1];
					$touid=$orderid[2];
					
					$data=array();
					$data['status']='订购完成';
					$data['dealId']=$r0_Cmd;
					//更新记录
					M("Chargedetail")->where("id='$id' and uid='$uid' and touid='$touid' ")->save($data);
					
					D("Member")->execute('update ss_member set coinbalance=coinbalance+'.($r3_Amt*$this->ratio).' where id='.$touid);					
					    
	                  die("<script language='javascript' type='text/javascript'>alert('充值成功');location.href='".$Siteurl."';</script>");
						  	//处理结束	
				}elseif($r9_BType=="2"){
					#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
					echo "success";
					echo "<br />交易成功";
					echo  "<br />在线支付服务器返回";      			 
				}
			}
			
		}else{
			echo "交易信息被篡改";
		}
			 
			 
	 
	 
	 
 }

 //易宝 返回 操作成		

 //快钱 返回通知
 public function bill_callback(){


	function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
	$kq_check_all_para=kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
	//网关版本，固定值：v2.0,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[version],'version');
	//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[language],'language');
	//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[signType],'signType');
	//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[payType],'payType');
	//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankId],'bankId');
	//商户订单号，,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderId],'orderId');
	//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderTime],'orderTime');
	//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderAmount],'orderAmount');
	// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealId],'dealId');
	//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankDealId],'bankDealId');
	//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealTime],'dealTime');
	//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
	$kq_check_all_para.=kq_ck_null($_REQUEST[payAmount],'payAmount');
	//费用，快钱收取商户的手续费，单位为分。
	$kq_check_all_para.=kq_ck_null($_REQUEST[fee],'fee');
	//扩展字段1，该值与提交时相同
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext1],'ext1');
	//扩展字段2，该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext2],'ext2');
	//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
	$kq_check_all_para.=kq_ck_null($_REQUEST[payResult],'payResult');
	//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
	$kq_check_all_para.=kq_ck_null($_REQUEST[errCode],'errCode');



	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
	$MAC=base64_decode($_REQUEST[signMsg]);

	$fp = fopen("./99bill/99bill[1].cert.rsa.20140803.cer", "r"); 
	$cert = fread($fp, 8192); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 


	if ($ok == 1) { 
		switch($_REQUEST[payResult]){
				case '10':
						//此处做商户逻辑处理
					//充值判断修改状态
						$orderid=explode("_",$_REQUEST[orderId]); 
						
						$id=$orderid[0];
						$uid=$orderid[1];
						$touid=$orderid[2];
						
						$data=array();
						$data['status']='订购完成';
						$data['dealId']=$r0_Cmd;
						//更新记录
						M("Chargedetail")->where("id='$id' and uid='$uid' and touid='$touid' ")->save($data);
						
						D("Member")->execute('update ss_member set coinbalance=coinbalance+'.($r3_Amt*$this->ratio).' where id='.$touid);					
						    
						break;
				default:
						$rtnOK=0; 
						//以下是我们快钱设置的show页面，商户需要自己定义该页面。
						//$rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=false";
						break;	
		
		}

	}else{
						$rtnOK=0;
						//以下是我们快钱设置的show页面，商户需要自己定义该页面。
						//$rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=error";
							
	}

	echo '<result>'.$rtnOK.'</result>';


 }

 //快钱 返回通知
	
//------------------------支付宝返回处理----------------------------------------------



public function apayreceive(){

		C('HTML_CACHE_ON',false);

include "./alipay/alipay.config.php";

include "./alipay/lib/alipay_notify.class.php";

$alipayNotify = new AlipayNotify($alipay_config);

$verify_result = $alipayNotify->verifyNotify();		



		$rtnOk=0;

		$rtnUrl="";



		if($verify_result) {//验证成功

		//商户订单号



	$out_trade_no = $_REQUEST['out_trade_no'];

$orderId=$out_trade_no;

	//支付宝交易号



	$trade_no = $_REQUEST['trade_no'];

	$dealId=$trade_no;

    $total_fee = $_REQUEST['total_fee'];

	$payAmount=$total_fee;

	//交易状态

	$trade_status = $_REQUEST['trade_status'];

		echo $trade_status;

			switch($trade_status){

				case "TRADE_SUCCESS":

					$chargeinfo = D("Chargedetail")->where('orderno="'.$orderId.'"')->select();

					//$rows = M('Chargedetail')->where('uid="'.$chargeinfo[0]["uid"].'"')->select();

					$rows = M('Chargedetail')->where(array('uid'=>$chargeinfo[0]["uid"],'dealId'=>array('neq','')))->select();

					if($chargeinfo && $chargeinfo[0]['status'] == '订购未完成'){

						if(!count($rows)){//判断是不是首充

							$cartime = 24*3600*30;

							if($chargeinfo[0]['rmb']<200){

								$dutime = 24*3600*7;

								if($userinfo['Daoju14expire'] < time()){//用于更新时间

								D("Member")->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju7="y",Daoju7expire='.(time()+$cartime).'  where id='.$chargeinfo[0]['touid']);

							}else{

								D("Member")->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju7="y",Daoju7expire=Daoju14expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

							}

							}elseif($chargeinfo[0]['rmb']>199 and $chargeinfo[0]['rmb']<500){//充值200元送小毛驴,注意每个道具在数据库有对应的字段

									$dutime = 24*3600*30;

									if($userinfo['Daoju12expire'] < time()){//用于更新时间

									D('Member')->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju12="y",Daoju12expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

								}else{

									D('Member')->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju12="y",Daoju12expire=Daoju12expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

								}

							}elseif($chargeinfo[0]['rmb']>499 and $chargeinfo[0]['rmb']<1000){//充值500元送悍马,注意每个道具在数据库有对应的字段

									$dutime = 24*3600*30;

									if($userinfo['Daoju10expire'] < time()){//用于更新时间

									D('Member')->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju4="y",Daoju4expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

								}else{

									D('Member')->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju4="y",Daoju4expire=Daoju4expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

								}

						}else{

							    $this->sendLiang($chargeinfo[0]['touid']);//送靓号



								if($userinfo['daoju5expire'] < time()){//用于更新时间

								D("Member")->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju1=y,Daoju1expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

							}else{

								D("Member")->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju1=y,Daoju1expire=Daoju5expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

							}

						}

					}

						D("Chargedetail")->execute('update ss_chargedetail set dealId="'.$dealId.'",status="订购完成" where orderno="'.$orderId.'"');

						D("Member")->execute('update ss_member set coinbalance=coinbalance+'.($payAmount*$this->ratio).' where id='.$chargeinfo[0]['touid']);

						if($chargeinfo[0]['touid'] != $chargeinfo[0]['uid']){

							$Giveaway = D("Giveaway");

							$Giveaway->create();

							$Giveaway->uid = $chargeinfo[0]['uid'];

							$Giveaway->touid = $chargeinfo[0]['touid'];

							$Giveaway->content = ($payAmount*$this->ratio).'梦想币';

							$Giveaway->objectIcon = '/Public/images/coin.png';

							$giveId = $Giveaway->add();

						}

						//充值代理

						if($chargeinfo[0]['proxyuid'] != 0){

							$beannum = ceil((($payAmount)*$this->ratio) * ($this->payagentdeduct / 100));

							//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							D("Member")->execute('update ss_member set beanbalance3=beanbalance3+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							$Payagentbeandetail = D("Payagentbeandetail");

							$Payagentbeandetail->create();

							$Payagentbeandetail->type = 'income';

							$Payagentbeandetail->action = 'charge';

							$Payagentbeandetail->uid = $chargeinfo[0]['proxyuid'];

							$Payagentbeandetail->content = '充值代理收入';

							$Payagentbeandetail->bean = $beannum;

							$Payagentbeandetail->addtime = time();

							$detailId = $Payagentbeandetail->add();

						}

					}

					

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/success/";

					break;

				default:

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

					break;

			}

		}

		//else{

			//$rtnOk=2;

			//$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

		//}



		echo "<script>alert('会员充值成功');window.close();</script>";

		exit;

	}





//-----------------------------------------------------------------------

	public function payreceive(){

		C('HTML_CACHE_ON',false);



		$merchantAcctId=trim($_REQUEST['merchantAcctId']);

		$key=$this->bill_key;

		$version=trim($_REQUEST['version']);

		$language=trim($_REQUEST['language']);

		$signType=trim($_REQUEST['signType']);

		$payType=trim($_REQUEST['payType']);

		$bankId=trim($_REQUEST['bankId']);

		$orderId=trim($_REQUEST['orderId']);

		$orderTime=trim($_REQUEST['orderTime']);

		$orderAmount=trim($_REQUEST['orderAmount']);

		$dealId=trim($_REQUEST['dealId']);

		$bankDealId=trim($_REQUEST['bankDealId']);

		$dealTime=trim($_REQUEST['dealTime']);

		$payAmount=trim($_REQUEST['payAmount']);

		$fee=trim($_REQUEST['fee']);

		$ext1=trim($_REQUEST['ext1']);

		$ext2=trim($_REQUEST['ext2']);

		$payResult=trim($_REQUEST['payResult']);

		$errCode=trim($_REQUEST['errCode']);

		$signMsg=trim($_REQUEST['signMsg']);



		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"version",$version);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"language",$language);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"signType",$signType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payType",$payType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"bankId",$bankId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderId",$orderId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderTime",$orderTime);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderAmount",$orderAmount);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"dealId",$dealId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"bankDealId",$bankDealId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"dealTime",$dealTime);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payAmount",$payAmount);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"fee",$fee);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"ext1",$ext1);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"ext2",$ext2);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payResult",$payResult);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"errCode",$errCode);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"key",$key);

		$merchantSignMsg= md5($merchantSignMsgVal);



		$rtnOk=0;

		$rtnUrl="";



		if(strtoupper($signMsg)==strtoupper($merchantSignMsg)){

			switch($payResult){

				case "10":

					$chargeinfo = D("Chargedetail")->where('orderno="'.$orderId.'"')->select();

					$rows = M('Chargedetail')->where(array('uid'=>$chargeinfo[0]["uid"],'dealId'=>array('neq','')))->select();

					if($chargeinfo && $chargeinfo[0]['status'] == '订购未完成'){

							if(!count($rows)){//判断是不是首充

								$cartime = 24*3600*30;

								if($chargeinfo[0]['rmb']<200){

									$dutime = 24*3600*7;

									if($userinfo['Daoju14expire'] < time()){//用于更新时间

									D("Member")->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju7="y",Daoju7expire='.(time()+$cartime).'  where id='.$chargeinfo[0]['touid']);

								}else{

									D("Member")->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju7="y",Daoju7expire=Daoju14expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

								}

								}elseif($chargeinfo[0]['rmb']>199 and $chargeinfo[0]['rmb']<500){//充值200元送小毛驴,注意每个道具在数据库有对应的字段

										$dutime = 24*3600*30;

										if($userinfo['Daoju12expire'] < time()){//用于更新时间

										D('Member')->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju12="y",Daoju12expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

									}else{

										D('Member')->execute('update ss_member set vip=2,vipexpire='.(time()+$dutime).',Daoju12="y",Daoju12expire=Daoju12expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

									}

								}elseif($chargeinfo[0]['rmb']>500 and $chargeinfo[0]['rmb']<1000){//充值500元送悍马,注意每个道具在数据库有对应的字段

										$dutime = 24*3600*30;

										if($userinfo['Daoju10expire'] < time()){//用于更新时间

										D('Member')->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju4="y",Daoju4expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

									}else{

										D('Member')->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju4="y",Daoju4expire=Daoju4expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

									}

							}else{

								    $this->sendLiang($chargeinfo[0]['touid']);//送靓号



									if($userinfo['daoju5expire'] < time()){//用于更新时间

									D("Member")->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju1=y,Daoju1expire='.(time()+$cartime).' where id='.$chargeinfo[0]['touid']);

								}else{

									D("Member")->execute('update ss_member set vip=1,vipexpire='.(time()+$dutime).',Daoju1=y,Daoju1expire=Daoju5expire+'.$cartime.' where id='.$chargeinfo[0]['touid']);

								}

							}

						}//充值赠送代码结束

						D("Chargedetail")->execute('update ss_chargedetail set dealId="'.$dealId.'",status="订购完成" where orderno="'.$orderId.'"');

						D("Member")->execute('update ss_member set coinbalance=coinbalance+'.(($payAmount/100)*$this->ratio).' where id='.$chargeinfo[0]['touid']);

						if($chargeinfo[0]['touid'] != $chargeinfo[0]['uid']){

							$Giveaway = D("Giveaway");

							$Giveaway->create();

							$Giveaway->uid = $chargeinfo[0]['uid'];

							$Giveaway->touid = $chargeinfo[0]['touid'];

							$Giveaway->content = (($payAmount/100)*$this->ratio).'梦想币';

							$Giveaway->objectIcon = '/Public/images/coin.png';

							$giveId = $Giveaway->add();

						}

						//充值代理

						if($chargeinfo[0]['proxyuid'] != 0){

							$beannum = ceil((($payAmount/100)*$this->ratio) * ($this->payagentdeduct / 100));

							//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							D("Member")->execute('update ss_member set beanbalance3=beanbalance3+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							$Payagentbeandetail = D("Payagentbeandetail");

							$Payagentbeandetail->create();

							$Payagentbeandetail->type = 'income';

							$Payagentbeandetail->action = 'charge';

							$Payagentbeandetail->uid = $chargeinfo[0]['proxyuid'];

							$Payagentbeandetail->content = '充值代理收入';

							$Payagentbeandetail->bean = $beannum;

							$Payagentbeandetail->addtime = time();

							$detailId = $Payagentbeandetail->add();

						}

					}

					

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/success/";

					break;

				default:

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

					break;

			}

		}

		else{

			$rtnOk=1;

			$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

		}



		echo '<result>'.$rtnOk.'</result><redirecturl>'.$rtnUrl.'</redirecturl>';

		exit;

	}



	public function card_payreceive(){

		C('HTML_CACHE_ON',false);



		$merchantAcctId=trim($_REQUEST['merchantAcctId']);

		if($_REQUEST['merchantAcctId'] == '1002225194010'){

			$key='54HHYTGSII9ZW2HW';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194009'){

			$key='YF6MWZW4Q35EXEQX';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194008'){

			$key='YDI8US7J97FSKR7F';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194007'){

			$key='7S94QYTU4EXWUUF8';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194006'){

			$key='Z2HYNHZYR4GRFMNS';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194004'){

			$key='LH4RAD7NXSDNYF5B';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194005'){

			$key='SDD9JIUHJFNQJK7J';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194003'){

			$key='5CD8UKG7I8LGRWCM';

		}

		if($_REQUEST['merchantAcctId'] == '1002225194002'){

			$key='J6B5GECXJTK7CJFS';

		}

		$version=trim($_REQUEST['version']);

		$language=trim($_REQUEST['language']);

		$payType=trim($_REQUEST['payType']);

		$cardNumber=trim($_REQUEST['cardNumber']);

		$cardPwd=trim($_REQUEST['cardPwd']);

		$orderId=trim($_REQUEST['orderId']);

		$orderAmount=trim($_REQUEST['orderAmount']);

		$dealId=trim($_REQUEST['dealId']);

		$orderTime=trim($_REQUEST['orderTime']);

		$ext1=trim($_REQUEST['ext1']);

		$ext2=trim($_REQUEST['ext2']);

		$payAmount=trim($_REQUEST['payAmount']);

		$billOrderTime=trim($_REQUEST['billOrderTime']);

		$payResult=trim($_REQUEST['payResult']);

		$bossType=trim($_REQUEST['bossType']);

		$receiveBossType=trim($_REQUEST['receiveBossType']);

		$receiverAcctId=trim($_REQUEST['receiverAcctId']);

		$signType=trim($_REQUEST['signType']);

		$signMsg=trim($_REQUEST['signMsg']);



		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"version",$version);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"language",$language);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payType",$payType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"cardNumber",$cardNumber);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"cardPwd",$cardPwd);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderId",$orderId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderAmount",$orderAmount);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"dealId",$dealId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"orderTime",$orderTime);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"ext1",$ext1);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"ext2",$ext2);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payAmount",$payAmount);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"billOrderTime",$billOrderTime);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"payResult",$payResult);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"signType",$signType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"bossType",$bossType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"receiveBossType",$receiveBossType);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"receiverAcctId",$receiverAcctId);

		$merchantSignMsgVal=$this->appendParam($merchantSignMsgVal,"key",$key);

		

		$merchantSignMsg= md5($merchantSignMsgVal);



		$rtnOk=0;

		$rtnUrl="";



		if(strtoupper($signMsg)==strtoupper($merchantSignMsg)){

			switch($payResult){

				case "10":

					$chargeinfo = D("Chargedetail")->where('orderno="'.$orderId.'"')->select();

					if($chargeinfo && $chargeinfo[0]['status'] == '订购未完成'){

						D("Chargedetail")->execute('update ss_chargedetail set dealId="'.$dealId.'",status="订购完成" where orderno="'.$orderId.'"');

						D("Member")->execute('update ss_member set coinbalance=coinbalance+'.(($payAmount/100)*$this->ratio).' where id='.$chargeinfo[0]['touid']);

						if($chargeinfo[0]['touid'] != $chargeinfo[0]['uid']){

							$Giveaway = D("Giveaway");

							$Giveaway->create();

							$Giveaway->uid = $chargeinfo[0]['uid'];

							$Giveaway->touid = $chargeinfo[0]['touid'];

							$Giveaway->content = (($payAmount/100)*$this->ratio).'梦想币';

							$Giveaway->objectIcon = '/Public/images/coin.png';

							$giveId = $Giveaway->add();

						}

						//充值代理

						if($chargeinfo[0]['proxyuid'] != 0){

							$beannum = ceil((($payAmount/100)*$this->ratio) * ($this->payagentdeduct / 100));

							//D("Member")->execute('update ss_member set earnbean=earnbean+'.$beannum.',beanbalance=beanbalance+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							D("Member")->execute('update ss_member set beanbalance3=beanbalance3+'.$beannum.' where id='.$chargeinfo[0]['proxyuid']);

							$Payagentbeandetail = D("Payagentbeandetail");

							$Payagentbeandetail->create();

							$Payagentbeandetail->type = 'income';

							$Payagentbeandetail->action = 'charge';

							$Payagentbeandetail->uid = $chargeinfo[0]['proxyuid'];

							$Payagentbeandetail->content = '充值代理收入';

							$Payagentbeandetail->bean = $beannum;

							$Payagentbeandetail->addtime = time();

							$detailId = $Payagentbeandetail->add();

						}

					}

					

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/success/";

					break;

				default:

					$rtnOk=1;

					$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

					break;

			}

		}

		else{

			$rtnOk=1;

			$rtnUrl=$this->siteurl."/index.php/User/payresult/type/error/";

		}



		echo '<result>'.$rtnOk.'</result><redirecturl>'.$rtnUrl.'</redirecturl>';

		exit;

	}



	public function payresult(){

		C('HTML_CACHE_ON',false);

		header("Content-type: text/html; charset=utf-8");

		if($_GET['type'] == 'success'){

			echo '充值成功 <a href="'.__URL__.'/chargelist/">返回</a>';

		}

		else{

			echo '充值失败 <a href="'.__URL__.'/chargelist/">返回</a>';

		}

	}



	private function appendParam($returnStr,$paramId,$paramValue){

		C('HTML_CACHE_ON',false);



		if($returnStr!=""){

			if($paramValue!=""){	

				$returnStr.="&".$paramId."=".$paramValue;

			}

		}else{

			If($paramValue!=""){

				$returnStr=$paramId."=".$paramValue;

			}

		}

		

		return $returnStr;

	}



	public function encrypt($encrypt,$key="") {

		$iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );

		$passcrypt = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv );

		$encode = base64_encode ( $passcrypt );

		return $encode;

	}



	public function decrypt($decrypt,$key="") {

		$decoded = base64_decode ( $decrypt );

		$iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );

		$decrypted = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv );

		return $decrypted;

	}



	public function userbalance(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function chargelist(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$Chargedetail = D("Chargedetail");

		$condition = "uid=".$_SESSION['uid'];

		if($_GET['c_StartTime'] != ''){

			$timeArr = explode("-", $_GET['c_StartTime']);

			$unixtime = mktime(0,0,0,$timeArr[1],$timeArr[2],$timeArr[0]);

			$condition .= ' and addtime>='.$unixtime;

		}

		if($_GET['c_EndTime'] != ''){

			$timeArr = explode("-", $_GET['c_EndTime']);

			$unixtime = mktime(23,59,59,$timeArr[1],$timeArr[2],$timeArr[0]);

			$condition .= ' and addtime<='.$unixtime;

		}



		$count = $Chargedetail->where($condition)->count();

		$listRows = 20;

		import("@.ORG.Page");

		$p = new Page($count,$listRows,$linkFront);

		$charges = $Chargedetail->where($condition)->limit($p->firstRow.",".$p->listRows)->order('addtime desc')->select();

		foreach($charges as $n=> $val){

			$charges[$n]['voo']=D("Member")->where('id='.$val['touid'])->select();

		}

		$page = $p->show();

		$this->assign('charges',$charges);

		$this->assign('count',$count);

		$pagecount = ceil($count/$listRows);

		if($pagecount == 0){$pagecount = 1;}

		$this->assign('pagecount',$pagecount);

		$this->assign('page',$page);



		$totalcharge = D("Chargedetail")->query('select sum(rmb) as total from ss_chargedetail where uid='.$_SESSION['uid'].' and status="订购完成"');

		if($totalcharge[0]['total'] != ''){

			$totalpay = $totalcharge[0]['total'];

		}

		else{

			$totalpay = 0;

		}

		$this->assign('totalpay',$totalpay);



		$this->display();

	}



	public function securityset(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function securitypassbind(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function securityfindpassbind(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function securityemailbind(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function securityqabind(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function helplist(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function helpview(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$this->display();

	}



	public function exchange(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);



		$exchanges = D("Beandetail")->where("uid=".$_SESSION['uid'].' and type="expend" and action="exchange"')->order('addtime desc')->select();

		$this->assign('exchanges', $exchanges);



		$this->display();

	}

	

	public function doExchange(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo 'notlogin';

			exit;

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		if($userinfo['beanbalance'] < $_REQUEST['changelimit']){

			echo 'noenoughbean';

			exit;

		}



		D("Member")->execute('update ss_member set coinbalance=coinbalance+'.$_REQUEST['changelimit'].',beanbalance=beanbalance-'.$_REQUEST['changelimit'].' where id='.$_SESSION['uid']);

		$Beandetail = D("Beandetail");

		$Beandetail->create();

		$Beandetail->type = 'expend';

		$Beandetail->action = 'exchange';

		$Beandetail->uid = $_SESSION['uid'];

		$Beandetail->content = '兑换秀币';

		$Beandetail->bean = $_REQUEST['changelimit'];

		$Beandetail->addtime = time();

		$detailId = $Beandetail->add();



		$Coindetail = D("Coindetail");

		$Coindetail->create();

		$Coindetail->type = 'income';

		$Coindetail->action = 'exchange';

		$Coindetail->uid = $_SESSION['uid'];

		$Coindetail->content = $_REQUEST['changelimit'].'个秀豆兑换';

		$Coindetail->coin = $_REQUEST['changelimit'];

		$Coindetail->addtime = time();

		$detailId = $Coindetail->add();



		echo '000000';

		exit;

	}



	public function settlement(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			$this->assign('jumpUrl',__APP__);

			$this->error('您尚未登录');

		}



		$userinfo = D("Member")->find($_SESSION['uid']);

		$this->assign('userinfo', $userinfo);



		$settlements = D("Beandetail")->where("uid=".$_SESSION['uid'].' and type="expend" and action="settlement"')->order('addtime desc')->select();

		$this->assign('settlements', $settlements);



		$this->display();

	}



	public function freezeIncome(){

		C('HTML_CACHE_ON',false);

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo 'notlogin';

			exit;

		}



		D("Member")->execute('update ss_member set freezeincome='.$_REQUEST['freezeincome'].',freezestatus="'.$_REQUEST['freezestatus'].'" where id='.$_SESSION['uid']);



		echo '000000';

		exit;

	}



	public function activity(){



		$this->display();

	}



	public function zaegg(){

		C('HTML_CACHE_ON',false);

		header("Content-type: text/html; charset=utf-8"); 

		if(!$_SESSION['uid'] || $_SESSION['uid'] < 0){

			echo "echostr=nologin";

			exit;

		}



		$eggset=D('Eggset');

		$eggsetinfo=$eggset->find(1);

		if(!$eggsetinfo) {

			echo "echostr=syserror";

			exit;

		}



		$userinfo = D("Member")->find($_SESSION['uid']);



		if($userinfo['coinbalance'] < $eggsetinfo['onceneedcoin']){

			echo "echostr=coinnotenough&needcoin=".$eggsetinfo['onceneedcoin'];

			exit;

		}

		else{

			//扣费

			D("Member")->execute('update ss_member set spendcoin=spendcoin+'.$eggsetinfo['onceneedcoin'].',coinbalance=coinbalance-'.$eggsetinfo['onceneedcoin'].' where id='.$_SESSION['uid']);

			//记入虚拟币交易明细

			$Coindetail = D("Coindetail");

			$Coindetail->create();

			$Coindetail->type = 'expend';

			$Coindetail->action = 'zaegg';

			$Coindetail->uid = $_SESSION['uid'];

				

			$Coindetail->content = '砸蛋1次花费';

			$Coindetail->objectIcon = '/Public/images/fei.png';

			$Coindetail->coin = $eggsetinfo['onceneedcoin'];

				

			$Coindetail->addtime = time();

			$detailId = $Coindetail->add();



			$randKey = mt_rand(1, 100);

			if ($randKey <= $eggsetinfo['wincoin_odds']) {

				$wincoin = $eggsetinfo['wincoin'];

			} elseif ($randKey <= $eggsetinfo['wincoin_odds'] + $eggsetinfo['wincoin2_odds']) {

				$wincoin = $eggsetinfo['wincoin2'];

			} elseif ($randKey <= $eggsetinfo['wincoin_odds'] + $eggsetinfo['wincoin2_odds'] + $eggsetinfo['wincoin3_odds']) {

				$wincoin = $eggsetinfo['wincoin3'];

			} elseif ($randKey <= $eggsetinfo['wincoin_odds'] + $eggsetinfo['wincoin2_odds'] + $eggsetinfo['wincoin3_odds'] + $eggsetinfo['wincoin4_odds']) {

				$wincoin = $eggsetinfo['wincoin4'];

			} else {

				$wincoin = 0;

			}



			if($wincoin == 0){

				echo "echostr=failed";

				exit;

			}

			else{

				//给用户赠送相应奖励

				D("Member")->execute('update ss_member set coinbalance=coinbalance+'.$wincoin.' where id='.$_SESSION['uid']);



				D("Giveaway")->execute('insert into ss_giveaway(uid,touid,content,remark,objectIcon,addtime) values(0,'.$_SESSION['uid'].',"'.$wincoin.'","砸蛋奖励","/Public/images/coin.png",'.time().')');



				echo "echostr=win&wincoin=".$wincoin;

				exit;

			}



		}



	}

//充值1000元送靓号

	public function sendLiang($uid){

		    $dutime = time()+(24*3600*30);

		    $liang = rand(100000,999999);

		    $data['uid'] = $uid;

		    $data['num'] = $liang;

		    $data['addtime'] = time();

		    $data['expiretime'] = $dutime;

			$data['original'] = 'y';

			//$res=M('roomnum')->add($data);

			$res=D("Roomnum")->execute('insert into ss_roomnum(uid,num,addtime,expiretime,original) values('.$uid.','.$liang.','.time().','.$dutime.',"n")');

			if(!$res){

				$this->sendLiang($uid);

			}

	}



	public function car(){

		$this->display();

	}



	public function myfamily(){

		$uid=$_SESSION['uid'];

		$res=M("agentfamily")->where("uid='$uid' && zhuangtai='已通过'")->select();



		$this->assign("jzinfo",$res);



		$this->display();

	}



	public function myfamilyimg(){

		$uid=$_SESSION['uid'];

		$res=M("agentfamily")->where("uid='$uid' && zhuangtai='已通过'")->select();

		//var_dump($res);

		$this->assign("jzinfo",$res);

		//var_dump($_POST);

		if(!empty($_POST)){

			import("ORG.Net.UploadFile");

			//实例化上传类

			$upload = new UploadFile();

			$upload->maxSize = 3145728;

			//设置文件上传类型

			$upload->allowExts = array('jpg','gif','png','jpeg');

			//设置文件上传位置

			$upload->savePath = "./Public/Familyimg/";//这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹

			//设置文件上传名(按照时间)

			$upload->saveRule = "time";

			if (!$upload->upload()){

				$this->error($upload->getErrorMsg());

			}else{

				//上传成功，获取上传信息

				$info = $upload->getUploadFileInfo();

			}

			$savename = $info[0]['savename'];

			$model=M("agentfamily");

			if($model->create()){

				$model->id=$_POST['id'];

				$model->familyimg=$savename;

				if($model->save()){

					$this->success("封面更新成功！");

				}else{

					$this->error("封面更新失败！");

				}

			}else{

				$this->error($model->getError());

			}





		}

		$this->display();

	}



	//我的家族成员列表

	public  function  myfamilyemcee(){
		
		//获取我的家族id
		$jiazu_id=M('agentfamily')->where("uid=$_SESSION[uid]")->getField("id");
		$condition = 'agentuid='.$jiazu_id;

		if($_GET['start_time'] != ''){

			$timeArr = explode("-", $_GET['start_time']);

			$unixtime = mktime(0,0,0,$timeArr[1],$timeArr[2],$timeArr[0]);

			$condition .= ' and addtime>='.$unixtime;

		}

		if($_GET['end_time'] != ''){

			$timeArr = explode("-", $_GET['end_time']);

			$unixtime = mktime(0,0,0,$timeArr[1],$timeArr[2],$timeArr[0]);

			$condition .= ' and addtime<='.$unixtime;

		}

		if($_GET['keyword'] != '' && $_GET['keyword'] != '请输入用户ID或用户名'){

			if(preg_match("/^\d*$/",$_GET['keyword'])){

				$condition .= ' and (id='.$_GET['keyword'].' or username like \'%'.$_GET['keyword'].'%\')';

			}

			else{

				$condition .= ' and username like \'%'.$_GET['keyword'].'%\'';

			}

		}



		$orderby = 'id desc';

		$member = D("Member");



		$count = $member->where($condition)->count();



		$listRows = 20;

		$linkFront = '';

		import("@.ORG.Page");

		$p = new Page($count,$listRows,$linkFront);

		$members = $member->limit($p->firstRow.",".$p->listRows)->where($condition)->order($orderby)->select();

		$p->setConfig('header','条');

		$page = $p->show();



		$this->assign('page',$page);

		$this->assign('members',$members);



		$this->display();

	}



	//我的家族管理

	public function  sqmyfamily(){

		$agentid=M('member')->where("id=$_SESSION[uid]")->getField("agentuid");



		$count=M("sqjoinfamily")->where("familyid=".$agentid." and zhuangtai=0")->count();





		//带分页关联用户信息

		import("ORG.Util.Page");

		$p = new Page($count,15);

	/*	$p->setConfig('header','条');*/

		$fix= C('DB_PREFIX');

		$field="m.nickname,m.curroomnum,m.earnbean,sq.*";

		$res = M('sqjoinfamily sq')->field($field)->join("{$fix}member m ON m.id=sq.uid")->where("familyid=".$agentid." and zhuangtai=0")->limit($p->firstRow.",".$p->listRows)->select();

		/*$page = $p->show();*/

		$a=0;

		foreach($res as $k=>$vo){

			$emceelevel = getEmceelevel($vo['earnbean']);

			$res[$a]['emceelevel']=$emceelevel;

			$a++;

		}

		/*$this->assign("page",$page);*/

		$this->assign("lists",$res);

		$this->page = $p->show();

		$this->display();



	}



	public function edit_sqmyfamily(){

		$sqid=$_GET['sqid'];



		//根据申请id 得到申请用户的相关信息

		$sqinfo=M("sqjoinfamily")->where("id=".$sqid)->select();

		$userid=$sqinfo[0]['uid'];



		$zhuangtai=$sqinfo[0]['zhuangtai'];

		if($zhuangtai==0){

			$dqzhuangtai="未审核";

		}elseif($zhuangtai==1){

			$dqzhuangtai="已通过";

		}elseif($zhuangtai==2){

			$dqzhuangtai="未通过";

		}

		$userinfo=M("member")->where("id=".$userid)->select();

		$emceelevel = getEmceelevel($userinfo[0]['earnbean']);

		$userinfo[0]["emceelevel"]=$emceelevel;





		$this->assign("dqzhuangtai",$dqzhuangtai);

		$this->assign("userinfo",$userinfo);

		$this->assign("sqinfo",$sqinfo);

		//接收提交信息更改状态

		if(!empty($_POST)){

			$agentuid=$sqinfo[0]['familyid'];


			$squid=$_POST['uid'];

			$sqid=$_POST['id'];

			$newzhuangtai=$_POST['zhuangtai'];



			$sqmodel=M("sqjoinfamily");

			$mmodel=M("member");

			$sqmodel->id=$sqid;

			$sqmodel->shtime=time();

			$sqmodel->zhuangtai=$newzhuangtai;

			if($sqmodel->save()){

				$mmodel->id=$squid;

				if($newzhuangtai=='1'){

					$mmodel->agentuid=	$agentuid;

//					$mmodel->sharingratio = 40;

				}else{

					$mmodel->agentuid=0;

				}
  
				if($mmodel->save()){

					$this->success("更新成功");

				}else{

					$this->error("更新失败");

				}



			}else{

				$this->error("更新失败");

			}



		}

		$this->display();



	}



	public function del_sqmyfamily(){

		$sqid=$_GET['sqid'];



		$uid=M("sqjoinfamily")->where("id=".$sqid)->getField("uid");





		$res=M("sqjoinfamily")->where("id=".$sqid)->delete();

		if($res){

			$this->success("删除成功");

			/*

			$mmodel=M("member");

			$mmodel->id=$uid;

			$mmodel->agentuid=0;

			$mmodel->sharingratio = 0;

			if($mmodel->save()){

				$this->success("删除成功");

			}else{

				$this->error("删除失败 uid=".$uid);

			}*/

		}else{

			$this->error("删除失败");

		}

	}



	public function setCar(){

		$carinfo = $_GET['carinfo'];

		$data['Daoju1'] = 'n';$data['Daoju2'] = 'n';$data['Daoju3'] = 'n';

		$data['Daoju4'] = 'n';$data['Daoju5'] = 'n';$data['Daoju6'] = 'n';$data['Daoju7'] = 'n';$data['Daoju8'] = 'n';

		$data['Daoju9'] = 'n';$data['Daoju10'] = 'n';$data['Daoju11'] = 'n';$data['Daoju12'] = 'n';$data['Daoju13'] = 'n';$data['Daoju14'] = 'n';

         M('member')->where('id='.$_SESSION['uid'])->save($data);

		$setcar[$carinfo] = 'y';

		M('member')->where('id='.$_SESSION['uid'])->save($setcar);

		$this->success('启用成功');

	}
	
	//坐骑启动
	
		//坐骑启动
	
	public function enableMounts()
	{
	
		if($_SESSION['uid']==NULL&&$_SESSION['uid']<=0)
		{
			echo "-2";
		}
		$mountsID = (int)$_GET['mountsID'];
		$m = M("member");
		$userinfo = $m->where("id = {$_SESSION['uid']}")->find();
		//判断如果道具是否拥有
		$iscar = M('membercar')->where('uid = '.$_SESSION['uid'].' and CarID = '.$mountsID);
		if($iscar)
		{
			$m->enableMounts = $mountsID;
			$res = $m->where("id = {$_SESSION['uid']}")->save();
			if($res)
			{
				echo "1";
			}
			else
			{
				echo "2";
			}
		}
		else
		{
			echo "-1";
		}
	}
	
	//查询装备
	public function queryMounts()
	{

		$now_carid=M('member')->where("id={$_SESSION['uid']}")->getField("enableMounts");
		$nowtime=time();
		$mid = M("membercar")->where("uid = {$_SESSION['uid']} and carID=$now_carid and endtime>'$nowtime'")->getField("id");
		if($mid==NULL || $mid==" ") $now_carid = 0;
		echo $now_carid;
	}
	
	public function search()
	{
		$SearchType = $_GET['type'];
		$keyWord = $_GET['keyWord'];
		$key = $_GET['key'];
		$m = M("member");
		if($key!=''){
			
			$map['nickname|curroomnum'] = array("like","%$key%");
		}else if($keyWord!=null)
		{
			// 导入分页类
			
			if($SearchType=="nick")
			{
				$map['nickname'] = array("like","%$keyWord%");
			}
			
			if($SearchType=="room")
			{
				$map['curroomnum'] = $keyWord;
			}
			
			

		}
        $map['canlive']='y';
					
		//var_dump($searchData);
		import('ORG.Util.Page');
		$count      = $m->where($map)->count();
		$Page       = new Page($count,30);
		$show       = $Page->show();// 分页显示输出
		$searchData = $m->field("id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting")->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		if($searchData==null)
		{
			$map = null;
			$map['broadcasting'] = 'y';
			$searchState = "推荐直播";
			$searchNot = "未发现相关内容，请重新搜索关键词";
			$searchData = $m->field("id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting")->where($map)->limit(12)->order("recommend DESC")->select();	
		}
		else
		{
			$searchState = "搜索结果";
		}
		//获取等级信息
//		foreach($searchData as $k=>$vo){
//		
//			$userinfo = D("Member")->find($vo['id']);
//			$emceelevel = getEmceelevel($userinfo['earnbean']);
//			$searchData[$k]['emceelevel']=$emceelevel;
//			
//			if($vo['broadcasting'] =='y')
//			{
//				$searchData[$k]['live_state'] = "<em class='png24 live-tip'>直播中</em>";
//			}
//			else if($vo['offlinevideo']!=null&&$vo['offlinevideo']!="")
//			{	
//				$searchData[$k]['live_state'] = "<em class='png24 live-tip' style='height:17px;width:54px;'><img src='/Public/images/luxiang.png' /></em>";
//
//			}
//			
//		}
		$this->assign("searchNot",$searchNot);
		$this->assign("searchState",$searchState);
		$this->assign("data",$searchData);
		$this->assign("page",$show);
		$this->display();
	}

	//直播时间
	public function info_live()
	{
		$starttime = D('member')->where('id = '.$_SESSION['uid'])->getField("starttime");
		$starttime = date("H:m:s",$starttime);
		$this->assign('starttime',$starttime);
		$this->display();	
	}
	
	public function do_info_live()
	{
		$starttime = strtotime($_POST['time']);
		$m = D('member');
		$m->starttime = $starttime;
		$res = $m->where("id = $_SESSION[uid]")->save();
		if($res)
		{
			$this->success('保存成功');
		}
		else
		{
			$this->error('保存出错了~');
		}
	}
	

}