<?php
class AuthAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
	}
        public function index(){
		$this->assign('info',$this->wxuser);
		if (IS_POST){
			M('Wxuser')->where(array('token'=>$this->token))->save(array('oauth'=>intval($_POST['oauth'])));
			$this->success('设置成功');
		}else {
			//
			$this->assign('tab','index');
			$this->display();
		}
	}
        public function advantage(){
		$this->assign('tab','advantage');
		$this->display();
	}
        public function help(){
		$this->assign('tab','help');
		$this->display();
	}
}

?>