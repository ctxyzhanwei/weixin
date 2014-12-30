<?php
bpBase::loadSysClass('model', '', 0);
class site_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_site';
		parent::__construct();
	}
	function sites(){
		$sql='SELECT * FROM '.TABLE_PREFIX.'site ORDER BY taxis ASC';
		$sites=$this->get_resultsBySql($sql);
		return $sites;
	}
	function getSiteByToken($token){
		$cacheName='siteByToken'.$token;
		$cache=getCache($cacheName);
		if ($cache){
			return unserialize($cache);
		}else {
			$site=$this->get_one(array('token'=>$token));
			setCache($cacheName,serialize($site));
			return $site;
		}
	}
}
?>