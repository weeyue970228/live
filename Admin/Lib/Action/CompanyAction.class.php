<?php

	/*
	 *公司信息 
	 * */ 
class CompanyAction extends Action{
	    //关于我们
	 	function about(){
            $about=M("siteconfig")->where("id='1'")->getField("about");
			$this -> assign("about",$about);
			$this -> display();
	 		
	 	}
		
		function Edit_about(){
			$siteconfig = D('Siteconfig');
			$siteconfig -> create();
		    if ($siteconfig -> save()) {
		    	$this -> assign('jumpUrl', __URL__ . '/about/');
				$this -> success('修改成功');
			} else {
				$this -> assign('jumpUrl', __URL__ . '/about/');
				$this -> error('修改失败');
			}
	
	 }	
}