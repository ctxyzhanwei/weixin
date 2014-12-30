<?php
class siteObj {
	function __construct(){
		$this->site_db = bpBase::loadModel('site_model');
	}
	function getSiteByID($id){
		$id=intval($id);
		$crt=getCache('site'.$id);
		if ($crt&&SUB_SKIN!='zzqcw'){
			return unserialize($crt);
		}else {
			$site=$this->site_db->get_row(array('id'=>$id));
			if ($id==1){
				$site->name=SITE_NAME;
				$site->url=MAIN_URL_ROOT;
			}
			setZendCache(serialize($site),'site'.$id);
			return $site;
		}
	}
}