<?php
class LinksAction extends BackAction{
	public function index(){
		$where='';
		if (!C('agent_version')){
		}else {
			$where=array('agentid'=>0);
		}
		$db=D('Links');
		S('links',null);
		$links=M('Links')->where($where)->select();
		S('links',$links);
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();
	}
	
	public function edit(){
		$where['id']=$this->_get('id','intval');
		$db=D('Links');
		$info=$db->where($where)->find();
		$this->assign('info',$info);
		$this->display('add');
	}
	
	public function add(){
		$this->display();
	}
	
	public function insert(){
		$this->all_insert('Links');
	}
	
	public function upsave(){
		$this->all_save('Links');
	}
	
	public function del(){
		$id=$this->_get('id','intval');
		$db=D('Links');
		if($db->delete($id)){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	
}