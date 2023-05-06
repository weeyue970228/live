<?php
/*
 * APP 扩渣接口
 * 2016.03.23
 * lv
 */

 
class ConfigAction extends Action {
	
	
	function index(){
    $attribute2=M("attribute")->field("name,title,remark")->select();
		
    $config=M("config")->find(1);
    
		$this->assign('config',$config);
		$this->assign('attribute',$attribute2);
		$this->display();
	}
	
	
	function set_post(){
		if(IS_POST){
			    $config= M("config");
        		$config->create();
			
				
				if ($config->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
		
		}
	}
	
	function lists(){			
		$orderby = 'id desc';
		$attribute = M("attribute");
		$count = $attribute  -> count();
		$listRows = 20;
		$linkFront = '';
		import("@.ORG.Page");
		$p = new Page($count, $listRows, $linkFront);
		$lists = $attribute -> limit($p -> firstRow . "," . $p -> listRows) -> order($orderby) -> select();

		$p -> setConfig('header', '条');
		$page = $p -> show();
		$this -> assign('page', $page);

    	$this->assign('lists', $lists);

    	
    	$this->display();
	}
	
    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){

        $this->meta_title = '新增属性';
        $this->display('edit');
    }

    /**
     * 编辑页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
        $id = I('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        /*获取一条记录的详细数据*/
        $Model = M('Attribute');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }
   
        $this->assign('info', $data);
        $this->meta_title = '编辑属性';
        $this->display();
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        $res = D('Attribute')->update();
        if(!$res){
            $this->error(D('Attribute')->getError());
        }else{
            $this->success($res['id']?'更新成功':'新增成功',U('Config/lists'));
        }
    }
    /**
     * 删除一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function delete(){
        $id = I('id');
        empty($id) && $this->error('参数错误！');

        $Model = D('Attribute');

        $info = $Model->getById($id);
        empty($info) && $this->error('该字段不存在！');

        //删除属性数据
        $res = $Model->delete($id);

        //删除表字段
        $Model->deleteField($info);
        if(!$res){
            $this->error(D('Attribute')->getError());
        }else{
            $this->success('删除成功');
        }
    }	
}
?>