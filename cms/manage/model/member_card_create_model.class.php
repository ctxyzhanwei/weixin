<?php
bpBase::loadSysClass('model', '', 0);
class member_card_create_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'member_card_create';
		parent::__construct();
	}
}
?>