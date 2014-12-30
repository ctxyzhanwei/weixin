<?php
class FunintroAction extends BackAction{
	public function index(){
		$firstNode=M('Node')->where(array('name'=>'Funintro','title'=>'功能介绍'))->order('id ASC')->find();
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
		//
		$map = array();
		$map['type'] = 0;
		$UserDB = D('Funintro');
		$count = $UserDB->where($map)->count();
		$Page       = new Page($count,30);// 实例化分页类 传入总记录数
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$show       = $Page->show();// 分页显示输出
		$list = $UserDB->where($map)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();


	}
	public function add(){
		if(IS_POST){
			$this->all_insert();
		}else{
			$this->assign('info',array('isnew'=>0));
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$this->all_save();
		}else{
			$fun=M('Funintro')->where(array('id'=>intval($_GET['id'])))->find();
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
			$fun=M('Funintro')->where(array('id'=>$id))->delete();
			if($fun==false){
				$this->error('删除失败');
			}else{
				$this->success('删除成功');
			}
		}
	}
}
?>