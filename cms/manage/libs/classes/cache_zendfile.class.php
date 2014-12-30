<?php
class cache_zendfile {
	protected $permanentCache;
	/**
	 * 构造函数
	 * @return  void
	 */
	public function __construct() {
		$ZEND_CACHE_PERMANENT_FRONTEND = array('lifeTime' => null, 'automaticSerialization' => false,'automaticCleaningFactor' => 0);
	    $ZEND_CACHE_PERMANENT_BACKEND = array('File'=>array('cacheDir' => CACHE_PATH.'permanent'.DIRECTORY_SEPARATOR, 'hashedDirectoryLevel' => 2));
		ini_set('include_path',ABS_PATH.'library'.PATH_SEPARATOR.ini_get('include_path'));
		require('Zend'.DIRECTORY_SEPARATOR.'Cache.php');
		$this->permanentCache=Zend_Cache::factory('Core','File',$ZEND_CACHE_PERMANENT_FRONTEND,$ZEND_CACHE_PERMANENT_BACKEND['File']);
	}
	
	/**
	 * 写入缓存
	 * @param	string	$name		缓存名称
	 * @param	mixed	$data		缓存数据
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed				缓存路径/false
	 */

	public function set($name, $data, $setting = '', $type = 'data', $module = ROUTE_MODEL) {
		return $this->permanentCache->save($data,$name);
	}
	
	/**
	 * 获取缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed	$data		缓存数据
	 */
	public function get($name, $setting = '', $type = 'data', $module = ROUTE_MODEL) {
		return $this->permanentCache->load($name);
	}
	
	/**
	 * 删除缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  bool
	 */
	public function delete($name, $setting = '', $type = 'data', $module = ROUTE_MODEL) {
		return $this->permanentCache->remove($name,$data);
	}

	public function cacheinfo($name, $setting = '', $type = 'data', $module = ROUTE_MODEL) {
		
	}

}

?>