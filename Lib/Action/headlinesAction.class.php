<?php
class headlinesAction extends BaseAction {
    public function index(){
		$headlines_time=$this->site['headlines_time'];
		$headlines_money=$this->site['headlines_money'];
		
        //仅查找 赠送礼物
		$headlinesid=$_GET['headlinesid'];
		
		if($headlinesid=='0'){
			//初始加载
			$condition="coin >= '$headlines_money' and action='sendgift'";				
			$headlines=M("coindetail")->where($condition)->order("addtime desc")->limit("1")->select();			
		}else{		
			$old=$_COOKIE['headlines'];

			$oldheadlines=json_decode($old,true); 	
				
			$condition2="coin > '$oldheadlines[coin]' and action='sendgift' and addtime >'$oldheadlines[addtime]'";
						
			// 保护时间内  赠送超过的
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
	
			cookie('headlines',json_encode($headlines[0]),60*60*24);
			
		}else{
			// 没有新 满足条件的
			echo '';
			exit();
		}

		$this->assign("headlines_time",$headlines_time);
		
		$headlinesmoney=floor($headlines_money/10000);
		
		$this->assign("headlinesmoney",$headlinesmoney);
		//var_dump($headshijian);
		$this->assign("headlines",$headlines[0]);
		
		$this->display();

    }
	
    public function show(){
		

    }	
}