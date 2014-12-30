<?php
/**
 *首页幻灯片回复
**/
class FlashAction extends UserAction{
	public $tip;
	public function _initialize(){
		parent::_initialize();
		if (isset($_GET['tip'])){
			$this->tip=$this->_get('tip','intval');
		}else {
			$this->tip=1;
		}
		$this->assign('tip',$this->tip);
	}
	public function index(){
		$db=D('Flash');

		//tip区分是幻灯片还是背景图
		$tip=$this->tip;

		$where['uid']=session('uid');
		$where['token']=session('token');
		$where['tip']=$tip;
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->assign('tip',$tip);
		$this->display();
	}
	public function add(){
		$tip=$this->tip;
		$this->assign('tip',$tip);
		$this->display();
	}
	public function edit(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$res=D('Flash')->where($where)->find();
		$this->assign('info',$res);

		$tip=$this->tip;
		$this->assign('tip',$tip);
		$this->assign('id',$this->_get('id','intval'));
		$this->display();
	}
	public function del(){
		$tip=$this->tip;
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
		if(D(MODULE_NAME)->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/index',array('tip'=>$tip)));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index',array('tip'=>$tip)));
		}
	}
	public function insert(){
		$flash=D('Flash');
		$arr=array();
		$arr['token']=$this->token;
		$arr['img']=$this->_post('img');
		$arr['url']=$this->_post('url');
		$arr['info']=$this->_post('info');
		$arr['tip']=$this->tip;
		$flash->add($arr);
		$this->success('操作成功',U(MODULE_NAME.'/index',array('tip'=>$this->tip)));

		//$this->all_insert('Flash');
	}
	public function upsave(){
		$flash=D('Flash');
		$id=$this->_get('id','intval');
		$tip=$this->tip;
		$arr=array();
		$arr['img']=$this->_post('img');
		$arr['url']=$this->_post('url');
		$arr['info']=$this->_post('info');
		$flash->where(array('id'=>$id))->save($arr);
		$this->success('操作成功',U(MODULE_NAME.'/index',array('tip'=>$this->tip)));
	}

}
?>