<?php
/**
 * NAME:秀场直播FMSCMS
 * DATE:2016-03-19 10:29:25
 * Author: zhl （lw.zhl@qq.com）
 */
 
class LuckgiftAction extends BaseAction {
	
	/**
	 * 幸运礼物开关
	 * 时间:2016-03-19 10:30:14
	 * 作者:zhl （lw.zhl@qq.com）
	 * 返回值：1开启  0关闭
	 */
	 public function getLuckGiftStatus(){
	 	$luckGiftStatus=M('luckgift')->where("id=1")->getField("is_open");
		
		return $luckGiftStatus;
	 }
	 
	 /**
	 * 判断是否是幸运礼物
	 * 时间:2016-03-19 14:07:43
	 * 作者:zhl （lw.zhl@qq.com）
	 * 返回值：1是 0 不是
	 */
	 
	 public function isLuckGift($giftid){
	 	
		$giftSort=M('gift')->where("id=$giftid")->getField("sid");
		$luckGiftSort=M('luckgift')->where("id=1")->getField("giftsort");
		
		if($giftSort==$luckGiftSort){
			return 1;
		}else{
			return 0;
		}
	 }
	 
	 /**
	 * 获取幸运礼物的奖励倍数
	 * 时间:2016-03-19 10:37:46
	 * 作者:zhl （lw.zhl@qq.com）
	 * 返回值：奖励倍数 例如 50
	 */
	 public function getLuckgiftPrize(){
	 	
		$luckGift=M('luckgift');
		$chance_arr=$luckGift->field("chance1,chance2,chance3,chance4,chance5")->where("id=1")->select();

		$return=array();
		
		for($i1=0;$i1<$chance_arr[0]['chance1'];$i1++){
			$return[]=$luckGift->where("id=1")->getField("prize1");
		}
		for($i2=0;$i2<$chance_arr[0]['chance2'];$i2++){
			$return[]=$luckGift->where("id=1")->getField("prize2");
		}
		
		for($i3=0;$i3<$chance_arr[0]['chance3'];$i3++){
			$return[]=$luckGift->where("id=1")->getField("prize3");
		}
		
		for($i4=0;$i4<$chance_arr[0]['chance4'];$i4++){
			$return[]=$luckGift->where("id=1")->getField("prize4");
		}
		
		for($i5=0;$i5<$chance_arr[0]['chance5'];$i5++){
			$return[]=$luckGift->where("id=1")->getField("prize5");
		}
		
		shuffle($return);
		
		return $return[array_rand($return)];
	 }
	 
	 /**
	 * 奖励送礼物的观众并写入日志
	 * 时间:2016-03-19 11:06:57
	 * 作者:zhl （lw.zhl@qq.com）
	 * 返回值：0奖励失败 $arr奖励成功
	 */ 
	 
	 public function givePrize($uid,$giftname,$giftprice,$giftinfo){
	 	
		
		$prize=$this->getLuckgiftPrize();
		$givePrize= $prize * $giftprice;
		
		$res=D("Member")->execute("update ss_member set coinbalance=coinbalance+$givePrize where id=$uid");
		
		//写日志
		if($res){
			$luckGiftLog=M("luckgiftlog");
			
			$luckGiftLog->uid=$uid;
			$luckGiftLog->giftname=$giftname;
			$luckGiftLog->giftprice=$giftprice;
			$luckGiftLog->giftinfo=$giftinfo;
			$luckGiftLog->prize= $prize;
			$luckGiftLog->giveprize=$givePrize;
			$luckGiftLog->addtime=time();
			
			$res2=$luckGiftLog->add();
			$arr=array("prize"=>$prize,"giveprize"=>$givePrize);
			if($res2){
				return $arr;
			}else{
				return 0;
			}
		}
			
	 }
	 
}

?>