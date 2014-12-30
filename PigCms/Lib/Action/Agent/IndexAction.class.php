<?php
class IndexAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		$this->display();
	}
	
	//增加研发人员入口
	public function admin(){
		$this->display();
	}
	
	public function home(){
		$userCount=M('Users')->where($this->agentWhere)->count();
		$wxuserCount=M('Wxuser')->where($this->agentWhere)->count();
		$this->assign('userCount',$userCount);
		$this->assign('wxuserCount',$wxuserCount);
		$this->display();
	}
	public function logout(){
		unset($_SESSION['agentid']);
		$this->success('退出成功','?g=Agent&m=Login&a=index');
	}
}


?>