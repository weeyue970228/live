<?php
class XiazaiAction extends BaseAction {
    public function index(){
		

        $app_android=$this->site['app_android'];
        $app_ios=$this->site['app_ios'];
        $apppic=$this->site['apppic'];
		
        $this->assign("app_android",$app_android);
        $this->assign("app_ios",$app_ios);
        $this->assign("apppic",$apppic);
        $this->display();
    }

   //扫描二维码
    public function scanqr(){ 
		
        $app_android=$this->site['app_android'];
        $app_ios=$this->site['app_ios'];
		
        $this->assign("app_android",$app_android);
        $this->assign("app_ios",$app_ios);

        $this->display();
    }    
}