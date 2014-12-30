<?php
bpBase::loadSysClass('model', '', 0);
class article_model extends model {
	public $table_name;
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'moopha_article';
		parent::__construct();
	}
	public function previousArticle($id,$channelid){
		$id=intval($id);
		$channelid=intval($channelid);
		$rt=$this->get_row('`id`<'.$id.' AND channel_id='.$channelid, '*', 'id DESC');
		if (!$rt){
			//$rt=$this->get_row('channel_id='.$channelid,'*','id DESC');
		}
		return $rt;
	}
	function nextArticle($id,$channelid){
		$id=intval($id);
		$channelid=intval($channelid);
		$rt=$this->get_row('`id`>'.$id.' AND channel_id='.$channelid, '*', 'id ASC');
		if (!$rt){
			//$rt=$this->get_row('channel_id='.$channelid,'*','id ASC');
		}
		return $rt;
	}
	public function getContentByID($id){
		$id=intval($id);
		$crt=getCache('c_content'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			$content=$this->get_row(array('id'=>$id));
			setZendCache(serialize($content),'c_content'.$id);
			return $content;
		}
	}
	public function getContentsByChannel($channelid){
		$channelid=intval($channelid);
		$crt=getCache('c_contentsOf'.$channelid);
		if ($crt){
			return unserialize($crt);
		}else {
			$contents=$this->select(array('channel_id'=>$channelid),'*','0,30','taxis DESC');
			setZendCache(serialize($contents),'c_contentsOf'.$channelid);
			return $contents;
		}
	}
}
?>