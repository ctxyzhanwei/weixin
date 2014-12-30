<?php
class PhpmaAction extends BackAction{

	public function _initialize() {
		parent::_initialize();
	}

	public function index(){
		$this->assign('flag',$_SESSION['administrator']);
		$this->assign('verify',$_SESSION['verify']);
		$this->assign('str',time());
		$this->display();
	}



}