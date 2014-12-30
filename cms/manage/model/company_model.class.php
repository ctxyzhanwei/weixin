<?php
bpBase::loadSysClass('model', '', 0);
class company_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'company';
		parent::__construct();
	}
	public function getCompany($token){
		$token=htmlspecialchars($token,ENT_QUOTES);
		$crt=getCache('company_'.$token);
		if (0&&$crt){
			return unserialize($crt);
		}else {
			$ps=$this->get_one(array('token'=>$token,'isbranch'=>0));
			setCache('company_'.$token,serialize($ps));
			return $ps;
		}
	}
	
}
?>