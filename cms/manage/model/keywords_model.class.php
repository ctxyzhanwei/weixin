<?php
bpBase::loadSysClass('model', '', 0);
class keywords_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_keywords';
		parent::__construct();
	}
	function keywords(){
		$cacheName='c_keywords';
		$cache=getCache($cacheName);
		if ($cache){
			return unserialize($cache);
		}else {
			$keywords=$this->select();
			setCache($cacheName,serialize($keywords));
			return $keywords;
		}
	}
}
?>