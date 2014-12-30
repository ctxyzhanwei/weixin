<?php
class Greeting_cardAction extends UserAction{
	//贺卡配置
	public function index(){
		$greeting_card=M('greeting_card');
		$where['token']=session('token');
		$count=$greeting_card->where($where)->count();
		$page=new Page($count,25);
		$list=$greeting_card->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('greeting_card',$list);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$this->all_insert('Greeting_card','/index');
		}else{
			$photo=M('Photo')->where(array('token'=>session('token')))->select();
			$this->assign('photo',$photo);
			$this->display();
		}
	}
	public function edit(){
		$greeting_card=M('greeting_card')->where(array('token'=>session('token'),'id'=>$this->_get('id','intval')))->find();
		if(IS_POST){
			$_POST['id']=$greeting_card['id'];
			$this->all_save('Greeting_card','/index');	
		}else{
			
			$this->assign('greeting_card',$greeting_card);
			$this->display('add');
		}
	
	}
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if(D('Greeting_card')->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
}



?>