<?php
/*
 * 相册管理控制器
 * 
 */
	class PhotoManageAction extends BaseAction{
		//首页
		public function Index(){
			$sql = "select pt.id,pt.name ,pt.createtime,pc.catname from ss_photo as pt left join ss_photocat as pc on pt.catid=pc.id where pc.uid={$_SESSION['uid']}";
			$arr_photo = D('photo') -> query($sql);
			$this -> assign("arr_photo",$arr_photo);
			$userinfo = D("Member")->find($_SESSION['uid']);
		    $this->assign('userinfo', $userinfo);
			$this -> display();
		}
		public function Album_List(){
			$arr_cate = D('photocat') -> where("uid={$_SESSION['uid']}") -> select();
			$this -> assign("arr_cate",$arr_cate);
			$userinfo = D("Member")->find($_SESSION['uid']);
		    $this->assign('userinfo', $userinfo);
			$this->display();
		}
		//上传图片
		public function Upload_Photo(){
			
			
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(!empty($_POST['img_name'])){
					import("ORG.Net.UploadFile");
					$obj_upload = new UploadFile();//实例化对象
					$obj_upload -> maxSize = 3145728;//图片大小
					$obj_upload -> uploadReplace = FALSE;
					$obj_upload -> saveRule = 'time';
					$obj_upload -> allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
					$obj_upload -> savePath   = './Public/Uploads/';// 设置附件上传目录
					if(!$obj_upload -> upload()){
						$this->error($obj_upload->getErrorMsg());
		
					}else{
						
						$arr_info =  $obj_upload->getUploadFileInfo();
						
						$arr_data = array(
						 'name'   =>  $arr_info[0]["savename"], 
						 'uid'    =>  $_SESSION['uid'],
						 'imgsrc' =>  '/Public/Uploads/'.$arr_info[0]["savename"],
						 'catid'  =>  $_POST['cate_name'],
						 'createtime' => time()
						);
						$int_res = D('photo') -> add($arr_data);
						if($int_res){
							$this -> success("上传成功!");
							
						}else{
							$this -> error("上传失败!");
						}
						
					}
				}else{
					$this -> error("图片名称不能为空!");
				}
				exit;
			}
			//查询相册
			$arr_res = D('photocat') -> where("uid={$_SESSION['uid']}") ->select();
			$this -> assign("photocat",$arr_res);
			$userinfo = D("Member")->find($_SESSION['uid']);
		    $this->assign('userinfo', $userinfo);
			$this -> display(); 
		}
		//创建相册
		public function Create_Album(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(!empty($_POST['cate_name'])){
					$arr_data = array(
					"catname"  => $_POST['cate_name'],
					"createtime" => time(),
					"uid"      => $_SESSION['uid']
					);
					$int_res = D('photocat') -> add($arr_data);
					if($int_res){
						$this -> success("相册创建成功!");
						
					}else{
						$this -> error("相册名称不能为空!");
					}
					
				}else{
					$this -> error("相册名称不能为空!");
				}
				exit;
			}
			$userinfo = D("Member")->find($_SESSION['uid']);
		    $this->assign('userinfo', $userinfo);
			$this -> display(); 
		}
		//删除图片
		public function Delete_photo(){
			if(isset($_REQUEST['id'])&&!empty($_REQUEST['id'])){
				$str_imgsrc = D('photo') -> where("id={$_REQUEST['id']}") -> getField('imgsrc');
				unlink('.'.$str_imgsrc);
				D('photo') -> delete($_REQUEST['id']);
				
				$this -> success("删除成功!");
				
			}else{
				$this -> error("删除失败!");
			}
		}
		public function Delete_Cat(){
			if(isset($_REQUEST['id'])&&!empty($_REQUEST['id'])){
				
				$arr_photo = D('photo') -> where("catid={$_REQUEST['id']}") -> select();
				D('photo') -> where("catid={$_REQUEST['id']}") -> delete();
				D('photocat') -> delete($_REQUEST['id']);
				foreach($arr_photo as $v){
					unlink('.'.$v['imgsrc']);
				}
				$this -> success("删除成功!");
			}else{
				$this -> error("删除失败!");
			}
			
		}
		
	}
