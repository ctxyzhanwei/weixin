<?php
class IndexAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	
	//增加研发人员入口
	public function index(){
		$this->display();
	}
}


?>