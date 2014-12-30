<?php
class GuajiangAction extends LotteryBaseAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('gua2');
	}
	public function cheat(){
		parent::cheat();
		$this->display();
	}
	public function index(){
		parent::index(2);
		$this->display();
	
	}
	public function sn(){
		parent::sn(2);
		$this->display('Lottery:sn');
	}
	public function add(){
		parent::add(2);
	}
	
	public function edit(){
		parent::edit(2);
	}
}


?>