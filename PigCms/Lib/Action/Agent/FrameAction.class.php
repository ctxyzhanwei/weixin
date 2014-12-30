<?php
class FrameAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		$this->display();
	}
	public function left(){
		$menuCat='submenu_'.$_GET['type'];
		if (!method_exists('AgentAction',$menuCat)){
			$menuCat='submenu_basic';
		}
		$submenus=$this->$menuCat();
		$this->assign('submenus',$submenus);
		$this->display();
	}
}


?>