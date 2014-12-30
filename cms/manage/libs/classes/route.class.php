<?php
class route {
	//路由配置
	private $route_config = '';
	
	public function __construct() {
		if(!get_magic_quotes_gpc()) {
			$_POST = bpAddslashes($_POST);
			$_GET = bpAddslashes($_GET);
			$_REQUEST = bpAddslashes($_REQUEST);
			$_COOKIE = bpAddslashes($_COOKIE);
		}
		$this->route_config=array('m'=>'cron', 'c'=>'cron', 'a'=>'add');
		if(isset($_GET['page'])) $_GET['page'] = max(intval($_GET['page']),1);
		return true;
	}

	/**
	 * model
	 */
	public function routeModel() {
		$m = isset($_GET['m']) && !empty($_GET['m']) ? $_GET['m'] : (isset($_POST['m']) && !empty($_POST['m']) ? $_POST['m'] : '');
		if (empty($m)) {
			return $this->route_config['m'];
		} else {
			return $m;
		}
	}

	/**
	 * controller
	 */
	public function routeControl() {
		$c = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) && !empty($_POST['c']) ? $_POST['c'] : '');
		if (empty($c)) {
			return $this->route_config['c'];
		} else {
			return $c;
		}
	}

	/**
	 * action
	 */
	public function routeAction() {
		$a = isset($_GET['a']) && !empty($_GET['a']) ? $_GET['a'] : (isset($_POST['a']) && !empty($_POST['a']) ? $_POST['a'] : '');
		if (empty($a)) {
			return $this->route_config['a'];
		} else {
			return $a;
		}
	}
}
?>