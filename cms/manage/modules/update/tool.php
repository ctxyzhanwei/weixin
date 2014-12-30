<?php
bpBase::loadAppClass('manage','manage',0);
class tool extends manage {
	function __construct() {
		parent::__construct();
		$this->exitWithoutAccess();
	}
	public function task(){	
	}
}
?>