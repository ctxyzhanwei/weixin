<?php
final class cache_factory {
	
	/**
	 * 当前缓存工厂类静态实例
	 */
	private static $cache_factory;
	
	/**
	 * 缓存配置列表
	 */
	protected $cache_config = array();

	/**
	 * 构造函数
	 */
	public function __construct() {
	}
	
	/**
	 * 返回当前终级类对象的实例
	 * @param $cache_config 缓存配置
	 * @return object
	 */
	public static function get_instance($cache_config = '') {
		if (!$cache_config){
			$cache_config=array('type'=>'zendfile');
		}
		if(cache_factory::$cache_factory == '') {
			cache_factory::$cache_factory = new cache_factory();
			if(!empty($cache_config)) {
				cache_factory::$cache_factory->cache_config = $cache_config;
			}
		}
		return cache_factory::$cache_factory;
	}
	
	/**
	 *  加载缓存驱动
	 * @param $cache_type 	缓存类型
	 * @return object
	 */
	public function load($cache_type) {
		$object = null;
		if(isset($cache_type)) {
			switch($cache_type) {
				default:
				case 'zendfile' :
					$object = bpBase::loadSysClass('cache_zendfile');
					break;
				case 'file' :
					$object = bpBase::loadSysClass('cache_file');
					break;
				case 'memcache' :
					define('MEMCACHE_HOST', $this->cache_config['hostname']);
					define('MEMCACHE_PORT', $this->cache_config['port']);
					define('MEMCACHE_TIMEOUT', $this->cache_config['timeout']);
					define('MEMCACHE_DEBUG', $this->cache_config['debug']);
					$object = bpBase::loadSysClass('cache_memcache');
					break;
				case 'apc' :
					$object = bpBase::loadSysClass('cache_apc');
					break;
			}
		} else {
			$object = bpBase::loadSysClass('cache_zendfile');
		}
		return $object;
	}

}
?>