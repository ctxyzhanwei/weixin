<?php
class application {
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		$route = bpBase::loadSysClass('route');
		if (!defined('ROUTE_MODEL')){
			define('ROUTE_MODEL', $route->routeModel());
			define('ROUTE_CONTROL', $route->routeControl());
			define('ROUTE_ACTION', $route->routeAction());
		}
		$this->init();
		//执行计划任务
		if (loadConfig('system','cron')){
			//$classRunObj=bpBase::loadAppClass('cronRun','cron',1);
			//$classRunObj->init();
		}
	}
	
	/**
	 * 调用件事
	 */
	private function init() {
		$controller = $this->load_controller();
		if (method_exists($controller, ROUTE_ACTION)) {
			if (preg_match('/^[_]/i', ROUTE_ACTION)) {
				exit('You are visiting the action which is to protect the private action');
			} else {
				call_user_func(array($controller, ROUTE_ACTION));
			}
		} else {
			exit('Action does not exist.');
		}
	}
	
	/**
	 * 加载控制器
	 * @param string $filename
	 * @param string $m
	 * @return obj
	 */
	private function load_controller($filename = '', $m = '') {
		if (empty($filename)) $filename = ROUTE_CONTROL;
		if (empty($m)) $m = ROUTE_MODEL;
		$filepath = ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.$filename.'.php';
		if (file_exists($filepath)) {
			$classname = $filename;
			include $filepath;
			if ($mypath = bpBase::my_path($filepath)) {//加载用户的扩展
				$classname = 'MY_'.$filename;
				include $mypath;
			}
			return new $classname;
		} else {
			exit('Controller doesn\'t exist.');
		}
	}
}