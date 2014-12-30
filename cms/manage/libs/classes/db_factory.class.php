<?php
/**
 *  数据库工厂
 */

final class db_factory {
	
	/**
	 * 当前数据库工厂类静态实例
	 */
	private static $db_factory;
	
	/**
	 * 数据库配置列表
	 */
	protected $db_config = array();
	
	/**
	 * 数据库操作实例化列表
	 */
	protected $db_list = array();
	
	/**
	 * 构造函数
	 */
	public function __construct() {
	}
	
	/**
	 * 返回当前终级类对象的实例
	 * @param $db_config 数据库配置
	 * @return object
	 */
	public static function get_instance($db_config = '') {
		if($db_config == '') {
			$db_config=array (
			'default' => array (
			'hostname' => DB_HOSTNAME,
			'port' => DB_PORT,
			'database' => DB_NAME,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'tablepre' => AUTO_TABLE_PREFIX,
			'charset' => DB_CHARSET,
			'type' => 'mysql',
			'debug' => DEBUG,
			'pconnect' => 0,
			'autoconnect' => 0
			),
			);
		}
		if(db_factory::$db_factory == '') {
			db_factory::$db_factory = new db_factory();
		}
		if($db_config != '' && $db_config != db_factory::$db_factory->db_config) db_factory::$db_factory->db_config = array_merge($db_config, db_factory::$db_factory->db_config);
		return db_factory::$db_factory;
	}
	
	/**
	 * 获取数据库操作实例
	 * @param $db_config_name 数据库配置名称
	 */
	public function get_database($db_config_name) {
		if(!isset($this->db_list[$db_config_name]) || !is_object($this->db_list[$db_config_name])) {
			$this->db_list[$db_config_name] = $this->connect($db_config_name);
		}
		return $this->db_list[$db_config_name];
	}
	
	/**
	 *  加载数据库驱动
	 * @param $db_config_name 	数据库配置名称
	 * @return object
	 */
	public function connect($db_config_name) {
		$object = null;
		switch($this->db_config[$db_config_name]['type']) {
			case 'mysql' :
				bpBase::loadSysClass('mysql', '', 0);
				$object = new mysql();
				break;
			case 'mysqli' :
				$object = bpBase::loadSysClass('mysqli');
				break;
			case 'access' :
				$object = bpBase::loadSysClass('db_access');
				break;
			default :
				bpBase::load_sys_class('mysql', '', 0);
				$object = new mysql();
		}
		$object->open($this->db_config[$db_config_name]);
		return $object;
	}

	/**
	 * 关闭数据库连接
	 * @return void
	 */
	protected function close() {
		foreach($this->db_list as $db) {
			$db->close();
		}
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct() {
		$this->close();
	}
}
?>