<?php
class ImgAction extends ImgBaseAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		parent::index();
		$this->display();

	}
	
	public function add(){
		parent::add();
		$this->display();
	}

	
	
	public function edit(){
		parent::edit();
		$this->display();
	}
	
	
	public function del(){
		parent::del();
	}
	public function insert(){
		parent::insert();
	}
	public function upsave(){
		parent::upsave();
	}
	
	public function editClass(){
		parent::editClass();
		$this->display();
	}
	
	public function editUsort(){
		parent::editUsort();
	}
	
	public function multiImgDel(){
		parent::multiImgDel();
	}
	
	public function multi(){
		parent::multi();
		$this->display();
	}
	
	
	public function multiSave(){
		parent::multiSave();
	}
	
	
	
}
?>