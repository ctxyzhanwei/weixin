<?php
bpBase::loadSysClass('model', '', 0);
class home_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'home';
		parent::__construct();
	}
}
?>