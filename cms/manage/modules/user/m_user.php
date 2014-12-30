<?php
bpBase::loadAppClass('manage','manage',0);
class m_user extends manage {
	function __construct() {
		parent::__construct();
	}
	public function users(){
		include $this->showManageTpl('users');
	}
}
?>