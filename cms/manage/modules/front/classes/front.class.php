<?php
$session_storage = getSessionStorageType();
bpBase::loadSysClass($session_storage);
bpBase::loadSysFunc('front');
class front {
	public $uid;
	public $username;
	public $email;
	public $realname;
	public $mp;
	public $qq;
	public $credits;
	public $isAdmin;
	public static $user;
	//
	public static $smarty;
	public function __construct() {
		//smarty
		if (front::$smarty==''){
			//smarty
			ini_set('include_path',ABS_PATH.'library'.DIRECTORY_SEPARATOR.'smarty'.PATH_SEPARATOR.ini_get('include_path'));
			require_once('Smarty.class.php');
			//
			$smartyInstance=new smarty();
			if (!isset($_GET['preview'])||!intval($_GET['preview'])){
				$smartyInstance->template_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
			}else{
				$smartyInstance->template_dir=ABS_PATH.'templates'.DIRECTORY_SEPARATOR;
			}
			$smartyInstance->compile_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
			$smartyInstance->config_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR;
			$smartyInstance->cache_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
			$smartyInstance->use_sub_dirs=true;
			front::$smarty=$smartyInstance;
			front::assignConstant();
		}
	}
	public function display($tpl='',$includeMeta=1){
		
		return front::$smarty->fetch($tpl, $cache_id = null, $compile_id = null, true);
	}
	public function mdisplay($tpl='',$includeMeta=1){
		if (defined('MOBILE_URL')){
			$this->assign('homeUrl',MOBILE_URL);
		}
		if ($includeMeta){
			//meta
			$this->includeMobileMeta();
		}
		//
		if (!$tpl){
			$tpl='mobile'.DIRECTORY_SEPARATOR.ROUTE_MODEL.DIRECTORY_SEPARATOR.ROUTE_CONTROL.DIRECTORY_SEPARATOR.ROUTE_ACTION.'.html';
		}
		return front::$smarty->fetch($tpl, $cache_id = null, $compile_id = null, true);
	}
	public function assign($k,$v=''){
		return front::$smarty->assign($k,$v);
	}
	public function fetch($tpl){
		return front::$smarty->fetch($tpl);
	}
	public function assignConstant(){
		$systemConfig=loadConfig('system');
		$smarty=front::$smarty;
	}
	public function includeMeta(){
		$smarty=front::$smarty;
		//include_once(ABS_PATH.'/meta/'.META_DIR.'/'.CAR_DIR.'/brand.php');
		$metaPath=$this->metaFilePath();
		if (file_exists($metaPath)){
			include $metaPath;
		}else {
			$smarty->assign('metaTitle',SITE_NAME);
		}
	}
	public function metaFilePath(){
		return $metaPath=ABS_PATH.'meta'.DIRECTORY_SEPARATOR.META_DIR.DIRECTORY_SEPARATOR.ROUTE_MODEL.DIRECTORY_SEPARATOR.ROUTE_CONTROL.DIRECTORY_SEPARATOR.ROUTE_ACTION.'.php';
	}
	public function includeMobileMeta(){
		
		$metaPath=$this->mobileMetaFilePath();
		if (file_exists($metaPath)){
			include $metaPath;
		}else {
			$this->assign('metaTitle',SITE_NAME);
		}
	}
	public function mobileMetaFilePath(){
		return $metaPath=ABS_PATH.'meta'.DIRECTORY_SEPARATOR.META_DIR.DIRECTORY_SEPARATOR.'mobile'.DIRECTORY_SEPARATOR.ROUTE_MODEL.DIRECTORY_SEPARATOR.ROUTE_CONTROL.DIRECTORY_SEPARATOR.ROUTE_ACTION.'.php';
	}
	public function show404(){
		header('HTTP/1.1 404 Not Found');
		$this->display('errors/404.tpl',0);
		exit();
	}
	public function createHtmlPageBySmarty($tpl,$htmlpath){
		$html=$this->fetch($tpl);
		//to html page
		file_putcontents_i(ABS_PATH.$htmlpath,$html);
	}
	
}