<?php
define('SYS_TIME',time());
define('BP_PATH',ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR);
//系统开始时间
define('SYS_START_TIME', microtime());
//缓存文件夹地址
if (!defined('CACHE_PATH')){
	define('CACHE_PATH', ABS_PATH.'cache'.DIRECTORY_SEPARATOR);
}

//加载公用函数库
bpBase::loadSysFunc('global');
bpBase::loadSysFunc('extention');
//系统配置
$systemConfig=loadConfig('system');
define('DEBUG', $systemConfig['debug']);
//
if (DEBUG){
	ini_set('display_errors', '1');
	error_reporting(E_ALL ^ E_NOTICE);
}else {
	ini_set('display_errors', '0');
	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT );
}
//is post
if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST'){
	$isPost=1;
}else {
	$isPost=0;
}
define('IS_POST',$isPost);
//上传大小限制
$maxUploadSize=$systemConfig['maxUploadSize']?$systemConfig['maxUploadSize']:400;
define('MAX_UPLOAD_SIZE',$maxUploadSize);
ini_set('upload_max_filesize', $maxUploadSize);
//
header('Content-type: text/html; charset='.DB_CHARSET);

if (!isset($_GET['nogzip'])){
	if(defined('GZIP') && GZIP && function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}

class bpBase {
	/**
	 * 初始化应用程序
	 */
	public static function creatApp() {
		return self::loadSysClass('application');
	}
	/**
	 * 加载系统类方法
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	public static function loadSysClass($classname, $path = '', $initialize = 1) {
			return self::_loadClass($classname, $path, $initialize);
	}
	/**
	 * 加载类文件函数
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	private static function _loadClass($classname, $path = '', $initialize = 1) {
		static $classes = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'classes';

		$key = md5($path.$classname);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}
		if (file_exists(BP_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
			include BP_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php';
			$name = $classname;
			if ($my_path = self::my_path(BP_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
				include $my_path;
				$name = 'MY_'.$classname;
			}
			if ($initialize) {
				$classes[$key] = new $name;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}
	/**
	 * 加载系统的函数库
	 * @param string $func 函数库名
	 */
	public static function loadSysFunc($func) {
		return self::_loadFunc($func);
	}
	/**
	 * 加载函数库
	 * @param string $func 函数库名
	 * @param string $path 地址
	 */
	private static function _loadFunc($func, $path = '') {
		static $funcs = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions';
		$path .= DIRECTORY_SEPARATOR.$func.'.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(BP_PATH.$path)) {
			include BP_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}
	/**
	 * 加载函数库
	 * @param string $func 函数库名
	 * @param string $path 地址
	 */
	private static function _autoLoadFunc($path = '') {
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'autoload';
		$path .= DIRECTORY_SEPARATOR.'*.func.php';
		$auto_funcs = glob(BP_PATH.DIRECTORY_SEPARATOR.$path);
		if(!empty($auto_funcs) && is_array($auto_funcs)) {
			foreach($auto_funcs as $func_path) {
				include $func_path;
			}
		}
	}
	/**
	 * 自动加载autoload目录下函数库
	 * @param string $func 函数库名
	 */
	public static function autoLoadFunc($path='') {
		return self::_autoLoadFunc($path);
	}
	/**
	 * 是否有自己的扩展文件
	 * @param string $filepath 路径
	 */
	public static function my_path($filepath) {
		$path = pathinfo($filepath);
		if (file_exists($path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'])) {
			return $path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'];
		} else {
			return false;
		}
	}
	/**
	 * 加载模板标签类方法
	 * @param string $tagName 标签名
	 * @param intger $initialize 是否初始化
	 */
	public static function loadTagClass($tagName, $initialize = 1) {
		return self::_loadClass($tagName, 'modules'.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'tags', $initialize);
	}
	/**
	 * 加载模板小标签类方法
	 * @param string $tagName 标签名
	 * @param intger $initialize 是否初始化
	 */
	public static function loadSmallTagClass($tagName, $initialize = 1) {
		return self::_loadClass($tagName, 'modules'.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'stags', $initialize);
	}
	/**
	 * 加载应用类方法
	 * @param string $classname 类名
	 * @param string $m 模块
	 * @param intger $initialize 是否初始化
	 */
	public static function loadAppClass($classname, $m = '', $initialize = 1) {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self::_loadClass($classname, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'classes', $initialize);
	}
	/**
	 * 加载模块类方法
	 * @param string $classname 类名
	 * @param string $m 模块
	 * @param intger $initialize 是否初始化
	 */
	public static function loadModuleClass($classname, $m = '', $initialize = 1) {
		return self::_loadClass($classname, 'modules'.DIRECTORY_SEPARATOR.$m, $initialize);
	}
	/**
	 * 加载应用函数库
	 * @param string $func 函数库名
	 * @param string $m 模型名
	 */
	public static function loadAppFunc($func, $m = '') {
		$m = empty($m) && defined('ROUTE_MODEL') ? ROUTE_MODEL : $m;
		if (empty($m)) return false;
		return self::_loadFunc($func, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'functions');
	}
	/**
	 * 加载数据模型
	 * @param string $classname 类名
	 */
	public static function loadModel($classname) {
		return self::_loadClass($classname,'model');
	}
	
	/**
	 * 加载插件类库
	 */
	public static function load_plugin_class($classname, $identification = '' ,$initialize = 1) {
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		if (empty($identification)) return false;
		return pc_base::load_sys_class($classname, 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'classes', $initialize);
	}
	
	/**
	 * 加载插件函数库
	 * @param string $func 函数文件名称
	 * @param string $identification 插件标识
	 */
	public static function load_plugin_func($func,$identification) {
		static $funcs = array();
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		if (empty($identification)) return false;
		$path = 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$func.'.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(BP_PATH.$path)) {
			include BP_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}
	
	/**
	 * 加载插件数据模型
	 * @param string $classname 类名
	 */
	public static function load_plugin_model($classname,$identification) {
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		$path = 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'model';
		return self::_load_class($classname,$path);
	}
}
function M($tablename){
	return bpBase::loadModel($tablename.'_model');
}
?>