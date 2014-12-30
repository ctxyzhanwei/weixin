<?php
bpBase::loadSysClass('model', '', 0);
class user_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_user';
		parent::__construct();
	}
}
?>