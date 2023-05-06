<?php
class xiuchangAction extends BaseAction {
    public function index(){

    	//轮播
    	$rollpics = D('xiuchang_voterollpic')->where('')->field('picpath,title,linkurl')->order('orderno asc')->limit(3)->select();
		$this->assign('rollpics', $rollpics);
		

		//秀场
		
		$condition="id!='0' and size!='2' and canlive='y'";
		$orderby="broadcasting desc,recommend desc";
		$member = D("Member");
		$count = $member->where($condition)->count();
		$listRows = 30;
		$linkFront = '';
		import("@.ORG.Page5");
		$p = new Page($count,$listRows,$linkFront);
		$members = $member->field("id,nickname,curroomnum,maxonline,earnbean,snap,starttime,online,virtualguest,offlinevideo,broadcasting")->limit($p->firstRow.",".$p->listRows)->where($condition)->order($orderby)->select();
		
		//查询出等级
//		foreach($members as $k=>$vo){
//			$emceelevel = getEmceelevel($vo['earnbean']);
//			$members[$k]["emceelevel"]=$emceelevel[0]['levelid'];
//		}
		
		$p->setConfig('header','条');
		$page = $p->show();
		
		$this->assign('page',$page);
		$this->assign('members',$members);		
		



		

        $this->display();
    }
}