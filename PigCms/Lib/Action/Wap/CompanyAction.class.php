<?php
class CompanyAction extends WapAction{
	public $token;
	public $apikey;
	public $isamap;
	public function _initialize() {
		parent::_initialize();
		$this->token=$this->_get('token');
		$this->assign('token',$this->token);
		$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		$this->assign('staticFilePath',str_replace('./','/',THEME_PATH.'common/css/product'));
		
		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}
	public function map(){
		
		//店铺信息
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}else {
			$where['isbranch']=0;
		}
		
		$thisCompany=$company_model->where($where)->find();
		/*
		$this->assign('thisCompany',$thisCompany);
		if (!isset($_GET['companyid'])){
		//分店信息
		$branchStores=$company_model->where(array('token'=>$this->token,'isbranch'=>1))->order('taxis ASC')->select();
		$branchStoreCount=count($branchStores);
		$this->assign('branchStoreCount',$branchStoreCount);
		$this->assign('branchStores',$branchStores);
		}
		/*
		$this->assign('metaTitle','地图');
		
		*/
		//$this->display();
		if (!$this->isamap){
			$link='http://api.map.baidu.com/marker?location='.$thisCompany['latitude'].','.$thisCompany['longitude'].'&title='.urlencode($thisCompany['name']).'&content='.urlencode($thisCompany['address']).'&output=html&src=yourComponyName|yourAppName';
		}else {
			$link=$this->amap->getPointMapLink($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
		}
		
		header('Location:'.$link);
		
	}
	public function walk($display=1){
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		$this->assign('metaTitle','步行路线');
		if ($display){
		$this->display();
		}
	}
	public function bus(){
		$this->walk(0);
		$this->assign('metaTitle','公交地铁路线');
		$this->display('bus');
	}
	public function drive(){
		$this->walk(0);
		$this->assign('metaTitle','开车路线');
		$this->display('drive');
	}
}


?>