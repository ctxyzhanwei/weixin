<?php
class LotteryAction extends LotteryBaseAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('lottery');
	}
	public function cheat(){
		parent::cheat();
		$this->display();
	}
	public function index(){
		parent::index(1);
		$this->display();
	
	}
	public function sn(){
		$type=isset($_GET['type'])?intval($_GET['type']):1;
		parent::sn($type);
		$this->display();
	}
	public function add(){
		parent::add(1);
	}
	
	public function edit(){
		parent::edit(1);
	}
}


?>