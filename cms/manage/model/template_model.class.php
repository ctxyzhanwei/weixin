<?php
bpBase::loadSysClass('model', '', 0);
class template_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_template';
		parent::__construct();
	}
}
?>