<?php
bpBase::loadSysClass('model', '', 0);
class site_plugmenu_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'site_plugmenu';
		parent::__construct();
	}
}
?>