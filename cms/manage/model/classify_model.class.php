<?php
bpBase::loadSysClass('model', '', 0);
class classify_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'classify';
		parent::__construct();
	}
}
?>