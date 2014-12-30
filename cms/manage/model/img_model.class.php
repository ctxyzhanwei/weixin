<?php
bpBase::loadSysClass('model', '', 0);
class img_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'img';
		parent::__construct();
	}
}
?>