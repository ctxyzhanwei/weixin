<?php
if (!defined('SYS_TIME')){
	define('SYS_TIME',time());
}
/**
 *  session mysql 数据库存储类
 */
class session_mysql {
	var $lifetime = 3600;
	var $db;
	var $table;
	var $oldSys=0;//兼容老系统
/**
 * 构造函数
 * 
 */
    public function __construct($dbObj=null) {
    	if (!$dbObj){
    		$this->db = bpBase::loadModel('session_model');
    	}else {//autoDB;
    		$this->db=$dbObj;
    		$this->oldSys=1;
    		$this->table=TABLE_PREFIX.'session';
    	}
    	$this->lifetime = loadConfig('site','session_ttl');
    	$this->lifetime = $this->lifetime == ''?3600:$this->lifetime;
    	session_set_save_handler(array(&$this,'open'), array(&$this,'close'), array(&$this,'read'), array(&$this,'write'), array(&$this,'destroy'), array(&$this,'gc'));
    	session_start();
    }
/**
 * session_set_save_handler  open方法
 * @param $save_path
 * @param $session_name
 * @return true
 */
    public function open($save_path, $session_name) {
		
		return true;
    }
/**
 * session_set_save_handler  close方法
 * @return bool
 */
    public function close() {
        return $this->gc($this->lifetime);
    } 
/**
 * 读取session_id
 * session_set_save_handler  read方法
 * @return string 读取session_id
 */
    public function read($id) {
    	if (!$this->oldSys){
		$r = $this->db->get_one(array('sessionid'=>$id), 'data');
    	}else {
    		$r=$this->db->get_row('SELECT data FROM '.$this->table.' WHERE sessionid=\''.$id.'\'',ARRAY_A);
    	}
		return $r ? $r['data'] : '';
    } 
/**
 * 写入session_id 的值
 * 
 * @param $id session
 * @param $data 值
 * @return mixed query 执行结果
 */
    public function write($id, $data) {
    	$uid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
    	$m = defined('ROUTE_MODEL') ? ROUTE_MODEL : '';
    	$c = defined('ROUTE_CONTROL') ? ROUTE_CONTROL : '';
    	$a = defined('ROUTE_ACTION') ? ROUTE_ACTION : '';
    	if(strlen($data) > 255) $data = '';
    	$ip = ip();
    	$sessiondata = array(
    	'sessionid'=>$id,
    	'userid'=>$uid,
    	'ip'=>$ip,
    	'lastvisit'=>SYS_TIME,
    	'm'=>$m,
    	'c'=>$c,
    	'a'=>$a,
    	'data'=>$data,
    	);
    	if (!$this->oldSys){
    		return $this->db->insert($sessiondata, 1, 1);
    	}else {
    		$exist=$this->db->get_row('SELECT * FROM '.$this->table.' WHERE sessionid=\''.$id.'\'');
    		if (!$exist){
    			return @$this->db->query($this->db->get_insert_sql($this->table,$sessiondata));
    		}else {
    			return @$this->db->query('UPDATE '.$this->table.' SET ip=\''.$sessiondata['ip'].'\',lastvisit=\''.$sessiondata['lastvisit'].'\',m=\''.$sessiondata['m'].'\',c=\''.$sessiondata['c'].'\',a=\''.$sessiondata['a'].'\',data=\''.$sessiondata['data'].'\' WHERE sessionid=\''.$id.'\'');
    		}
    	}
    }
/** 
 * 删除指定的session_id
 * 
 * @param $id session
 * @return bool
 */
    public function destroy($id) {
    	if (!$this->oldSys){
    		return $this->db->delete(array('sessionid'=>$id));
    	}else {
    		return $this->db->query('DELETE FROM '.$this->table.' WHERE sessionid=\''.$id.'\'');
    	}
    }
/**
 * 删除过期的 session
 * 
 * @param $maxlifetime 存活期时间
 * @return bool
 */
   public function gc($maxlifetime) {
		$expiretime = SYS_TIME - $maxlifetime;
		if (!$this->oldSys){
			return $this->db->delete("`lastvisit`<$expiretime");
		}else{
			return $this->db->get_row('DELETE FROM '.$this->table.' WHERE lastvisit<'.$expiretime);
		}
    }
}
?>