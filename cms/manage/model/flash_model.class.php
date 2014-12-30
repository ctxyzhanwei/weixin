<?php
bpBase::loadSysClass('model', '', 0);
class flash_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'flash';
		parent::__construct();
	}
}
?>