<?php
bpBase::loadSysClass('model', '', 0);
class host_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'host';
		parent::__construct();
	}
}
?>