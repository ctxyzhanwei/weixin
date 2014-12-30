<?php
bpBase::loadSysClass('model', '', 0);
class picture_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_picture';
		parent::__construct();
	}
	public function getContentPictures($id){
		$id=intval($id);
		$crt=getCache('contentPictures'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			$ps=$this->get_results('*','',array('contentid'=>$id),'taxis ASC');
			setCache('contentPictures'.$id,serialize($ps));
			return $ps;
		}
	}
	
}
?>