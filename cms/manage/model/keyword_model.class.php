<?php
bpBase::loadSysClass('model', '', 0);
class keyword_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'keyword';
		parent::__construct();
	}
}
?>