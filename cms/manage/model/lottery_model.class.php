<?php
bpBase::loadSysClass('model', '', 0);
class lottery_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'lottery';
		parent::__construct();
	}
}
?>