<?php
class ChatAction extends Action{
	public function _initialize() {
		
		if(session('userName')==false){
			$this->error('您必须登陆后才能操作',U('Login/index'));
		}
	}
	
	
}
	?>