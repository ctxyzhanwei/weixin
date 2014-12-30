<?php
bpBase::loadSysClass('model', '', 0);
class attachement_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_attachement';
		parent::__construct();
	}
	public function add($row){
		$row['ip']=ip();
		$row['time']=SYS_TIME;
		return $this->insert($row,1);
	}

	public function url2path($fileurl){
		$s2=explode('?',$fileurl);
		$filePath=str_replace(array(MAIN_URL_ROOT.'/','/'),array(ABS_PATH,DIRECTORY_SEPARATOR),$s2[0]);
		return $filePath;
	}
}
?>