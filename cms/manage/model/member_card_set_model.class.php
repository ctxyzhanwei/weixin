<?php
bpBase::loadSysClass('model', '', 0);
class member_card_set_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'member_card_set';
		parent::__construct();
	}
}
?>