<?php
class Demo_textAction extends UserAction
{
	public function index()
	{
			$where['id']=$this->_get('id','intval');
			$where['uid']=session('uid');
			$where['token']=session('token');
			$res=D('Text')->where($where)->find();
			$this->assign('info',$res);
			$this->display();
	}
}
?>