<?php
class orderAction extends BaseAction {
    public function index(){
    	//缓存时间
    	
		$cache_time=60;
		
		//明星日 周 月 总榜
		$emceeRank_day = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by uid order by total desc LIMIT 15';
		$emceeRank_day=set_memcache('emceeRank_day',$emceeRank_day,$cache_time);
		
		foreach($emceeRank_day as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$emceeRank_day[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_day[$k]['emceelevel']=$emceelevel;
		}		
		
		$this->assign('emceeRank_day', $emceeRank_day);
	
		//var_dump($emceeRank_day);
		$emceeRank_week ='SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by uid order by total desc LIMIT 15';
		$emceeRank_week=set_memcache('emceeRank_week',$emceeRank_week,$cache_time);
		
		foreach($emceeRank_week as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$emceeRank_week[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_week[$k]['emceelevel']=$emceelevel;
		}		
		//var_dump($emceeRank_week);
		$this->assign('emceeRank_week', $emceeRank_week);

		
		
		$emceeRank_month = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 15';
		$emceeRank_month=set_memcache('emceeRank_month',$emceeRank_month,$cache_time);
		foreach($emceeRank_month as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$emceeRank_month[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_month[$k]['emceelevel']=$emceelevel;
		}			
		//var_dump($emceeRank_motnth);
		$this->assign('emceeRank_month', $emceeRank_month);
		
	
		
		
		$emceeRank_all = 'SELECT uid,sum(bean) as total FROM `ss_beandetail` where type="income" and action="getgift" group by uid order by total desc LIMIT 15';
		$emceeRank_all=set_memcache('emceeRank_all',$emceeRank_all,$cache_time);
		foreach($emceeRank_all as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$emceeRank_all[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$emceeRank_all[$k]['emceelevel']=$emceelevel;
		}				
		$this->assign('emceeRank_all', $emceeRank_all);


		//富豪日 周 月 总榜
		$richRank_day ='SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by uid order by total desc LIMIT 15';
		$richRank_day=set_memcache('richRank_day',$richRank_day,$cache_time);
		foreach($richRank_day as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("spendcoin,nickname,curroomnum,ucuid")->find($vo['uid']);
			$richRank_day[$k]['userinfo']=$userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_day[$k]['richlevel']=$richlevel;
		}		
		$this->assign('richRank_day', $richRank_day);

		
		
		
		$richRank_week ='SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by uid order by total desc LIMIT 15';
		$richRank_week=set_memcache('richRank_week',$richRank_week,$cache_time);
		foreach($richRank_week as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("spendcoin,nickname,curroomnum,ucuid")->find($vo['uid']);
			$richRank_week[$k]['userinfo']=$userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_week[$k]['richlevel']=$richlevel;
		}		
		$this->assign('richRank_week', $richRank_week);

		
		
		$richRank_month ='SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 15';
		$richRank_month=set_memcache('richRank_month',$richRank_month,$cache_time);
		foreach($richRank_month as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("spendcoin,nickname,curroomnum,ucuid")->find($vo['uid']);
			$richRank_month[$k]['userinfo']=$userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_month[$k]['richlevel']=$richlevel;
		}		
		$this->assign('richRank_month', $richRank_month);

		
		
		
		$richRank_all = 'SELECT uid,sum(coin) as total FROM `ss_coindetail` where type="expend" group by uid order by total desc LIMIT 15';
		$richRank_all=set_memcache('richRank_all',$richRank_all,$cache_time);
		foreach($richRank_all as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("spendcoin,nickname,curroomnum,ucuid")->find($vo['uid']);
			$richRank_all[$k]['userinfo']=$userinfo;
			$richlevel = getRichlevel($userinfo['spendcoin']);
			$richRank_all[$k]['richlevel']=$richlevel;
		}			
		$this->assign('richRank_all', $richRank_all);


		//人气日 周 月 总榜
		$rqRank_day ='SELECT uid,sum(entercount) as total FROM `ss_liverecord` where date_format(FROM_UNIXTIME(starttime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by uid order by total desc LIMIT 15';
		$rqRank_day=set_memcache('rqRank_day',$rqRank_day,$cache_time);
		foreach($rqRank_day as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$rqRank_day[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$rqRank_day[$k]['emceelevel']=$emceelevel;
		}		
		$this->assign('rqRank_day', $rqRank_day);
		
		
		
		$rqRank_week = 'SELECT uid,sum(entercount) as total FROM `ss_liverecord` where date_format(FROM_UNIXTIME(starttime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(starttime),"%u")=date_format(now(),"%u") group by uid order by total desc LIMIT 15';
		$rqRank_week=set_memcache('rqRank_week',$rqRank_week,$cache_time);
		foreach($rqRank_day as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$rqRank_week[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$rqRank_week[$k]['emceelevel']=$emceelevel;
		}			
		$this->assign('rqRank_week', $rqRank_week);

		
		
		$rqRank_month = 'SELECT uid,sum(entercount) as total FROM `ss_liverecord` where date_format(FROM_UNIXTIME(starttime),"%m-%Y")=date_format(now(),"%m-%Y") group by uid order by total desc LIMIT 15';
		$rqRank_month=set_memcache('rqRank_month',$rqRank_month,$cache_time);
		foreach($rqRank_month as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$rqRank_month[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$rqRank_month[$k]['emceelevel']=$emceelevel;
		}			
		$this->assign('rqRank_month', $rqRank_month);

		
		
		
		$rqRank_all = 'SELECT uid,sum(entercount) as total FROM `ss_liverecord` group by uid order by total desc LIMIT 15';
		$rqRank_all=set_memcache('rqRank_all',$rqRank_all,$cache_time);
		foreach($rqRank_all as $k=>$vo){			
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum,ucuid")->find($vo['uid']);
			$rqRank_all[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$rqRank_all[$k]['emceelevel']=$emceelevel;
		}	
		
		$this->assign('rqRank_all', $rqRank_all);
		
		
		
		//获得礼物最多的人z总榜 
		$gifts_all="SELECT touid,giftid,sum(giftcount) as total,sum(coin) as money FROM `ss_coindetail` where action='sendgift' and giftid<99999 group by giftid order by money desc LIMIT 15";
		$gifts_all=set_memcache('gifts_all',$gifts_all,$cache_time);
		
		foreach($gifts_all as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($vo['touid']);
			$gifts_all[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$gifts_all[$k]['emceelevel']=$emceelevel;
			$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
			$gifts_all[$k]['giftinfo']=$giftinfo;
			
			//获取收到礼物的前3名
			$zhubo=D("coindetail")->cache(true,$cache_time,'Redis')->field("touid,sum(giftcount) as total")->where("giftid='$vo[giftid]' and action='sendgift'")->group("touid")->order("total desc")->limit("3")->select();

			foreach($zhubo as $k1=>$v1){
				$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($v1['touid']);
				$zhubo[$k1]['userinfo']=$userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$zhubo[$k1]['emceelevel']=$emceelevel;			
			}
			$gifts_all[$k]['zhubo']=$zhubo;
			
    
		}
		
		$this->assign("gifts_all",$gifts_all);		

		//礼物日榜		
		$gifts_day = 'SELECT touid,giftid,sum(giftcount) as total,sum(coin) as money FROM ss_coindetail where action="sendgift" and date_format(FROM_UNIXTIME(addtime),"%m-%d-%Y")=date_format(now(),"%m-%d-%Y") group by giftid order by money desc LIMIT 15';
		$gifts_day=set_memcache('gifts_day',$gifts_day,$cache_time);
		foreach($gifts_day as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($vo['touid']);
			$gifts_day[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$gifts_day[$k]['emceelevel']=$emceelevel;
			$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
			$gifts_day[$k]['giftinfo']=$giftinfo;
			//获取收到礼物的前3名
			$zhubo=D("coindetail")->cache(true,$cache_time,'Redis')->field("touid,sum(giftcount) as total")->where("giftid='$vo[giftid]' and action='sendgift' and date_format(FROM_UNIXTIME(addtime),'%m-%d-%Y')=date_format(now(),'%m-%d-%Y')")->group("touid")->order("total desc")->limit("3")->select();

			foreach($zhubo as $k1=>$v1){
				$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($v1['touid']);
				$zhubo[$k1]['userinfo']=$userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$zhubo[$k1]['emceelevel']=$emceelevel;			
			}
			$gifts_day[$k]['zhubo']=$zhubo;
		}
		
		$this->assign("gifts_day",$gifts_day);	
				
		//礼物周榜
		$gifts_week = 'SELECT touid,giftid,sum(giftcount) as total,sum(coin) as money FROM `ss_coindetail` where action="sendgift" and date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by giftid order by money desc LIMIT 15';
		$gifts_week=set_memcache('gifts_week',$gifts_week,$cache_time);
		foreach($gifts_week as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($vo['touid']);
			$gifts_week[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$gifts_week[$k]['emceelevel']=$emceelevel;
			$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
			$gifts_week[$k]['giftinfo']=$giftinfo;
			//获取收到礼物的前3名
			$zhubo=D("coindetail")->cache(true,$cache_time,'Redis')->field("touid,sum(giftcount) as total")->where("giftid='$vo[giftid]' and action='sendgift' and date_format(FROM_UNIXTIME(addtime),'%Y')=date_format(now(),'%Y') and date_format(FROM_UNIXTIME(addtime),'%u')=date_format(now(),'%u')")->group("touid")->order("total desc")->limit("3")->select();

			foreach($zhubo as $k1=>$v1){
				$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($v1['touid']);
				$zhubo[$k1]['userinfo']=$userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$zhubo[$k1]['emceelevel']=$emceelevel;			
			}
			$gifts_week[$k]['zhubo']=$zhubo;
		}
		
		$this->assign("gifts_week",$gifts_week);	
				
		//礼物月榜
		$gifts_month = 'SELECT touid,giftid,sum(giftcount) as total,sum(coin) as money FROM `ss_coindetail` where action="sendgift" and date_format(FROM_UNIXTIME(addtime),"%m-%Y")=date_format(now(),"%m-%Y") group by giftid order by money desc LIMIT 15';
		$gifts_month=set_memcache('gifts_month',$gifts_month,$cache_time);
		foreach($gifts_month as $k=>$vo){		
			$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($vo['touid']);
			$gifts_month[$k]['userinfo']=$userinfo;
			$emceelevel = getEmceelevel($userinfo['earnbean']);
			$gifts_month[$k]['emceelevel']=$emceelevel;
			$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
			$gifts_month[$k]['giftinfo']=$giftinfo;
			//获取收到礼物的前3名
			$zhubo=D("coindetail")->cache(true,$cache_time,'Redis')->field("touid,sum(giftcount) as total")->where("giftid='$vo[giftid]' and action='sendgift' and date_format(FROM_UNIXTIME(addtime),'%m-%Y')=date_format(now(),'%m-%Y')")->group("touid")->order("total desc")->limit("3")->select();

			foreach($zhubo as $k1=>$v1){
				$userinfo = D("Member")->cache(true,$cache_time,'Redis')->field("earnbean,nickname,curroomnum")->find($v1['touid']);
				$zhubo[$k1]['userinfo']=$userinfo;
				$emceelevel = getEmceelevel($userinfo['earnbean']);
				$zhubo[$k1]['emceelevel']=$emceelevel;			
			}
			$gifts_month[$k]['zhubo']=$zhubo;
		}
		
		$this->assign("gifts_month",$gifts_month);			
		

		
		//本周礼物周星
//		$gifts_week10 = 'SELECT touid,uid,giftid,sum(giftcount) as total FROM `ss_coindetail` where date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u") group by giftid order by total desc limit 10';
//      $gifts_week10=set_memcache('gifts_week10',$gifts_week10,$cache_time);
//      $a=0;
//		foreach($gifts_week10 as $k=>$vo){
//		$fromuser=D("Member")->cache(true,$cache_time,'Redis')->find($vo['uid']);
//		$gifts_week10[$a]['formuser']=$formuser;
//		$userinfo = D("Member")->cache(true,$cache_time,'Redis')->find($vo['touid']);
//		$gifts_week10[$a]['userinfo']=$userinfo;
//		$emceelevel = getEmceelevel($userinfo['earnbean']);
//		$gifts_week10[$a]['emceelevel']=$emceelevel;
//		$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
//		$gifts_week10[$a]['giftinfo']=$giftinfo;
//		$gifts_week10[$a]['xuhao']=($b+1);
//		$a++;
//		}
//		//var_dump($gifts_week10);
//		$this->assign("gifts_week10",$gifts_week10);
		
		//上周礼物周星
//		$shangzhougifts ='SELECT touid,uid,giftid,sum(giftcount) as total FROM `ss_coindetail` where  date_format(FROM_UNIXTIME(addtime),"%Y")=date_format(now(),"%Y") and date_format(FROM_UNIXTIME(addtime),"%u")=date_format(now(),"%u")-1 group by giftid order by total desc LIMIT 10';
//		$shangzhougifts=set_memcache('shangzhougifts',$shangzhougifts,$cache_time);
//		 $a=0;
//		foreach($shangzhougifts as $k=>$vo){
//		$fromuser=D("Member")->cache(true,$cache_time,'Redis')->find($vo['uid']);
//		$shangzhougifts[$a]['formuser']=$formuser;
//		$userinfo = D("Member")->cache(true,$cache_time,'Redis')->find($vo['touid']);
//		$shangzhougifts[$a]['userinfo']=$userinfo;
//		$emceelevel = getEmceelevel($userinfo['earnbean']);
//		$shangzhougifts[$a]['emceelevel']=$emceelevel;
//		$giftinfo=D("gift")->cache(true,$cache_time,'Redis')->find($vo['giftid']);
//		$shangzhougifts[$a]['giftinfo']=$giftinfo;
//		$shangzhougifts[$a]['xuhao']=($b+1);
//		$a++;
//		}
//		//var_dump($gifts_week10);
//		$this->assign("shangzhougifts",$shangzhougifts);
		
        $this->display();
    }
}