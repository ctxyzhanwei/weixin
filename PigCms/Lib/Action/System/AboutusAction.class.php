<?php
class AboutusAction extends BackAction{
    public function index(){
	    $firstNode=M('Node')->where(array('name'=>'Aboutus','title'=>'关于我们'))->find();
		$nodeExist=M('Node')->where(array('pid'=>$firstNode['id']))->find();
		if (!$nodeExist){
			$row2=array(
			'name'=>'add',
			'title'=>'添加',
			'status'=>1,
			'remark'=>'0',
			'pid'=>$firstNode['id'],
			'level'=>3,
			'sort'=>0,
			'display'=>2
			);
			M('Node')->add($row2);
		}
        $map = array();
        $UserDB = D('Funintro');
        $map['type'] = 1;
        $list = $UserDB->where($map)->find();
        $this->assign('list',$list);
        $this->display();


    }
    public function add(){
        if(IS_POST){
            $_DB = M('Funintro');
            $_DB->add($_POST);
           $this->success('添加成功',U("Aboutus/index",array('token'=>session('token'),'type'=>1)));exit;
        }else{
            $this->assign('info',array('isnew'=>0));

        }
         $this->display();
    }
    public function edit(){
        if(IS_POST){
            $_DB = M('Funintro');
            $ok = $_DB->save($_POST);
            $this->success('修改成功',U("Aboutus/index",array('token'=>session('token'),'type'=>1)));exit;
        }else{
            $fun=M('Funintro')->where(array('id'=>intval($_GET['id']),'type'=>1))->find();
            $this->assign('info',$fun);
            $this->display('add');
        }

    }
    public function del(){
        if(IS_POST){
            $this->all_save();
        }else{
            $id=$this->_get('id','intval',0);
            if ($id == 0) {
                $this->error('非法操作');
            }
            $this->assign('tpltitle','编辑');
            $fun=M('Funintro')->where(array('id'=>$id,'type'=>1))->delete();
            if($fun==false){
                $this->error('删除失败');
            }else{
                $this->success('删除成功');
            }
        }
    }
}
?>