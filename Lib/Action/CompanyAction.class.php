<?php
class CompanyAction extends BaseAction {
    public function about(){

        $site=$this->site;
        
		$this->assign('about',$site['about']);
		
        $this->display();
    }
}