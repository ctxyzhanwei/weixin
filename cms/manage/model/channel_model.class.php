<?php
bpBase::loadSysClass('model', '', 0);
class channel_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_channel';
		parent::__construct();
	}
	public function navChannels($siteid){
		$siteid=intval($siteid);
		$cacheName='navChannels'.$siteid;
		$cache=getCache($cacheName);
		if ($cache){
			return unserialize($cache);
		}else {
			$channels=$this->select(array('site'=>$siteid,'isnav'=>1),'*','','taxis ASC');
			setCache($cacheName,serialize($channels));
			return $channels;
		}
	}
	function getChannelByIndex($index,$site=1){
		$index=str_replace(' ','',$index);
		$index=htmlspecialchars(trim($index),ENT_QUOTES);
		$site=intval($site);
		$crt=getCache('channelOfIndex'.$index.'Site'.$site);
		if ($crt){
			return unserialize($crt);
		}else {
			$channel=$this->get_row(array('cindex'=>$index,'site'=>$site));
			setZendCache(serialize($channel),'channelOfIndex'.$index.'Site'.$site);
			return $channel;
		}
	}
	function getChannelsByParentID($parentid,$output='OBJECT'){
		$parentid=intval($parentid);
		$crt=getCache('channelsOf'.$parentid.'o'.$output);
		if ($crt){
			return unserialize($crt);
		}else {
			$channels=$this->get_results('*','','parentid='.$parentid,'taxis ASC');
			setZendCache(serialize($channels),'channelsOf'.$parentid.'o'.$output);
			return $channels;
		}
	}

}
?>