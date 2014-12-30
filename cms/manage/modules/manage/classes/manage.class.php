<?php
defined('IN_BACKGROUND') or exit('No permission'); 
$session_storage = getSessionStorageType();
bpBase::loadSysClass($session_storage);
bpBase::loadSysFunc('back');
class manage {
	public $userid;
	public $username;
	public $role_db;
	public $user_role_db;
	public static $isAdministrator;
	public $token;
	public $site;
	public $siteid;
	public function __construct() {
		bpBase::loadAppFunc('global','manage');
		//access
		//$_SESSION['token']='tokenvalue';
		if (!isset($_SESSION['token'])||!strlen($_SESSION['token'])){
			header('Location:/index.php?g=User&m=Index&a=index');
		}
		$this->token=$_SESSION['token'];
		//
		$site_db=M('site');
		$this->site=$site_db->getSiteByToken($this->token);
		$this->siteid=intval($this->site['id']);
	}
	final public static function showManageTpl($file, $m = '') {
		$m = empty($m) ? ROUTE_MODEL : $m;
		if(empty($m)) return false;
		return ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$file.'.tpl.php';
	}
	public function exitWithoutAccess(){
		//return true;
	}
}
?>