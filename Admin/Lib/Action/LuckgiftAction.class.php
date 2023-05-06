<?php
/**
 * NAME:秀场直播FMSCMS
 * DATE:2016-03-19 08:34:30
 * Author: zhl （lw.zhl@qq.com）
 */
 
class LuckgiftAction extends Action {
	
	/**
	 * 幸运礼物配置
	 * 时间:2016-03-19 08:36:15
	 * 作者:zhl （lw.zhl@qq.com）
	 * 说明：配置幸运礼物的分类和幸运百分比
	 */
	 
	 public function index(){
	 	
		//获取礼物分类
		$giftSort=M('giftsort')->field("id,sortname")->order("id")->select();
		
		//获取设置内容
		$luckGift=M('luckgift')->select();
		
		$this->assign("luckgift",$luckGift[0]);
		$this->assign("giftsort",$giftSort);
		$this->display();
	 }
	 
	 public function edit(){
	 	
		$luckGift=M('luckgift');
		
		$luckGift->id=1;
		$luckGift->is_open=intval($_POST['is_open']);
		$luckGift->giftsort=intval($_POST['giftsort']);
		$luckGift->prize1=$_POST['prize1'];
		$luckGift->chance1=$_POST['chance1'];
		$luckGift->prize2=$_POST['prize2'];
		$luckGift->chance2=$_POST['chance2'];
		$luckGift->prize3=$_POST['prize3'];
		$luckGift->chance3=$_POST['chance3'];
		$luckGift->prize4=$_POST['prize4'];
		$luckGift->chance4=$_POST['chance4'];
		$luckGift->prize5=$_POST['prize5'];
		$luckGift->chance5=$_POST['chance5'];
		
		$res=$luckGift->save();
		
		if($res){
			$this->success("幸运礼物设置更新成功");
		}else{
			$this->error("幸运礼物设置更新失败");
		}
		
	 	
	 }
	 
	/**
	 * 幸运礼物奖励记录
	 * 时间:2016-03-19 15:43:21
	 * 作者:zhl （lw.zhl@qq.com）
	 * 
	 */
	 public function logs(){
	 	$condition = 'id>0';
		
		$orderby = 'id desc';
		$luckgiftlog = M("luckgiftlog");
		$count = $luckgiftlog -> where($condition) -> count();
		$listRows = 100;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$logs = $luckgiftlog -> limit($p -> firstRow . "," . $p -> listRows) -> where($condition) -> order($orderby) -> select();
		
		foreach($logs as &$val){
			$val['nickname']=M('member')->where("id=$val[uid]")->getField("nickname");
		}
		
		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);
		$this -> assign('logs', $logs);
		$this -> display();
	 }
}
?>