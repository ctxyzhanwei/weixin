<?php
/**
 * 专题
 */
bpBase::loadAppClass('front','front',0);
class special extends front {
	public $special_cat_db;
	public $special_db;
	function __construct() {
		parent::__construct();
		$this->special_cat_db=bpBase::loadModel('special_cat_model');
		$this->special_db=bpBase::loadModel('special_model');
	}
	public function index(){
		$cats=$this->special_cat_db->cats();
		$cats=arrToArrByKey($cats);
		$specials=$this->special_db->select('','id,url,name,catid,time,metadescription','','taxis DESC');
		if ($cats){
			foreach ($cats as $k=>$cat){
				$cats[$k]['specials']=array();
			}
		}
		if ($specials){
			foreach ($specials as $s){
				array_push($cats[$s['catid']]['specials'],$s);
			}
		}
		$this->assign('cats',$cats);
		$this->display();
	}
	public function specials(){
		$catindex=$_GET['catindex'];
		if (get_magic_quotes_gpc()){
			$catindex=stripslashes($catindex);
		}
		$catindex=mysql_real_escape_string($catindex);
		$thisCat=$this->special_cat_db->get_one(array('enname'=>$catindex));
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		$page=$page<1?1:$page;
		$pagesize=20;
		//
		if (!isset($_GET['keyword'])){
			$specials=$this->special_db->listinfo(array('catid'=>$thisCat['id']), $order = 'taxis DESC', $page, $pagesize);
			$pagination=foregroundPage($this->special_db->number,$page,$pagesize,'/zhuanti/'.$catindex.'/p');
		}else {
			$keyword=$_GET['keyword'];
			if (get_magic_quotes_gpc()){
				$keyword=stripslashes($keyword);
			}
			$keyword=mysql_real_escape_string($keyword);
			$this->assign('keyword',$keyword);
			$specials=$this->special_db->listinfo('`name` LIKE \'%'.$keyword.'%\'', $order = 'taxis DESC', $page, $pagesize);
			$pagination=foregroundPage($this->special_db->number,$page,$pagesize,'/zhuanti/specials.php?keyword='.$keyword.'&page=');
			//
			$thisCat=array('name'=>$keyword);
		}
		$this->thisCat=$thisCat;
		$this->assign('thisCat',$thisCat);
		$this->assign('specials',$specials);
		$this->assign('pagination',$pagination);
		$this->display();
	}
}
?>