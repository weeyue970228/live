<?php

	/*
	 *相册管理 
	 * */ 
class PhotoAdminAction extends Action{
	    //相片管理首页
	 	function index(){
	 		$sql = "select p.id,m.nickname,m.username,p.name,p.createtime from ss_photo as p left join  ss_member as m on p.uid=m.id";
			$arr_photo_res = D('') -> query($sql);
			$this -> assign("arr_photocat_res",$arr_photo_res);
			$this -> display();
	 		
	 	}
		
		//相册管理首页 
		function CateManage(){
			$sql = "select p.id,m.nickname,m.username,p.catname,p.createtime from ss_photocat as p left join ss_member as m on p.uid=m.id";
			$arr_photocat_res = D('') -> query($sql);
			foreach($arr_photocat_res as $k=>$v){
				$arr_photocat_res[$k]['count'] = D('photo') -> where('catid='.$v['id']) -> count();
			}
			$this -> assign("arr_photocat_res",$arr_photocat_res);
			$this -> display();
		}
		
		//图片删除
		function Photo_Delete(){
			if(isset($_REQUEST['id'])&&!empty($_REQUEST['id'])){
				$str_imgsrc = D('photo') -> where("id={$_REQUEST['id']}") -> getField('imgsrc');
				unlink('.'.$str_imgsrc);
				D('photo') -> delete($_REQUEST['id']);

			}else{
				echo '<script>alert(\'删除成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
		}
        function Cate_Delete(){
        	if(isset($_REQUEST['id'])&&!empty($_REQUEST['id'])){
				
				$arr_photo = D('photo') -> where("catid={$_REQUEST['id']}") -> select();
				D('photo') -> where("catid={$_REQUEST['id']}") -> delete();
				D('photocat') -> delete($_REQUEST['id']);
				foreach($arr_photo as $v){
					unlink('.'.$v['imgsrc']);
				}
				echo '<script>alert(\'删除成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}else{
				echo '<script>alert(\'删除失败\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
			}
        }
		function Edit_Cate(){
			
			if($_SERVER['REQUEST_METHOD'] == "POST"&&!empty($_REQUEST['catname'])){
				
				D('photocat') -> where('id='.$_REQUEST['id']) -> setField('catname',$_REQUEST['catname']);
			
				echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit;
			}
			$str_catname = D('photocat') -> where('id='.$_REQUEST['id']) ->getField('catname');
			$this -> assign('catname',$str_catname);
			$this -> assign('id',$_REQUEST['id']);
			$this -> display();
		}
		function Edit_Photo(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				
				import("ORG.Net.UploadFile");
				$obj_upload = new UploadFile();//实例化对象
				$obj_upload -> maxSize = 3145728;//图片大小
				$obj_upload -> uploadReplace = FALSE;
				$obj_upload -> saveRule = 'time';
				$obj_upload -> allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$obj_upload -> savePath   = '../Public/Uploads/';// 设置附件上传目录
				if($obj_upload -> upload()){
					$arr_info =  $obj_upload->getUploadFileInfo();
					$arr_data = array(
					
					 'imgsrc' =>  '/Public/Uploads/'.$arr_info[0]["savename"],
					 
					 'createtime' => time()
					);
					$str_file_src = D('photo') -> where('id='.$_REQUEST['id']) -> getField('imgsrc');
					unlink('..'.$str_file_src);
					D('photo') -> where('id='.$_REQUEST['id']) -> save($arr_data);
					
				}else{
					
					echo $_SERVER["ROOT_DOCUMENT"];
					exit;
				}
					
				
			    if(empty($_REQUEST['photoname'])){
			    	D('photo') -> where('id='.$_REQUEST['id']) -> setField('name',$_REQUEST['photoname']);
			    }
				
				echo '<script>alert(\'修改成功\');window.top.right.location.reload();window.top.art.dialog({id:"edit"}).close();</script>';
				exit;
			}
			$str_photoname = D('photo') -> where('id='.$_REQUEST['id']) ->getField('name');
			$this -> assign('name',$str_photoname);
			$this -> assign('id',$_REQUEST['id']);
			$this -> display();
		}
	 }	