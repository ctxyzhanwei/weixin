<?php
bpBase::loadSysClass('model', '', 0);
class session_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_session';
		parent::__construct();
	}
}
?>