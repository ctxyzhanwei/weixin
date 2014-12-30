<?php
class access{
	var $querynum = 0;
	var $conn;
	var $insertid = 0;
	var $cursor = 0;
	//var $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
	/**
	 * 最近一次查询资源句柄
	 */
	public $lastqueryid = null;

	function connect($dbhost, $dbuser = '', $dbpw = '', $dbname = '', $pconnect = 0)
	{
		$this->conn = new com('adodb.connection');
		if(!$this->conn) return false;
 		$this->conn->open("DRIVER={Microsoft Access Driver (*.mdb)};dbq=$dbhost;uid=$dbuser;pwd=$dbpw");
 		if($this->conn->state == 0)
		{
			$this->conn->open("Provider=Microsoft.Jet.OLEDB.4.0; Data Source=$dbhost");
			if($this->conn->state == 0)	return false;;
		}
		define('NUM', 1);
		define('ASSOC', 2);
		define('BOTH', 3);
		return $this->conn->state;
	}


	function select_db($dbname)
	{
		return $this->conn->state;
	}
 	
	function query($sql, $type = '', $expires = 3600, $dbname = '') {
		$this->querynum++;
		$sql = trim($sql);
		if(preg_match("/^(select.*)limit ([0-9]+)(,([0-9]+))?$/i", $sql, $matchs)){
 			$sql = $matchs[1];
			$offset = $matchs[2];
			$pagesize = $matchs[4];
			$query = $this->conn->Execute($sql);
 			return $this->limit($query, $offset, $pagesize);
		} else{
 			return $this->conn->Execute($sql);
		}
	}

	function get_one($query) {
		$this->querynum++;
	    $rs = $this->conn->Execute($query);
 		$r = $this->fetch_array($rs);
		$this->free_result($rs);
		return $r;
	}

	function fetch_array($rs, $result_type = 3) {
		if(is_array($rs)){
			return $this->cursor < count($rs) ? $rs[$this->cursor++] : FALSE;
		} else{
			if($rs->EOF) return FALSE;
			$array = array();
			for($i = 0; $i < $this->num_fields($rs); $i++){
				$fielddata = $rs->Fields[$i]->Value;
			    $array[$rs->Fields[$i]->Name] = $fielddata;
			}
			$rs->MoveNext();
			return $array;
		}
	}
	 
	/*
	function select($sql, $keyfield = ''){
		$array = array();
		$result = $this->query($sql);
		while($r = $this->fetch_array($result)){
			if($keyfield){
				$key = $r[$keyfield];
				$array[$key] = $r;
			}else{
				$array[] = $r;
			}
		}
		$this->free_result($result);
		return $array;
	}
	*/
	/**
	 * 执行sql查询
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $table 		数据表
	 * @param $where 		查询条件[例`name`='$name']
	 * @param $limit 		返回结果范围[例：10或10,10 默认为空]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @param $key 			返回数组按键名排序
	 * @return array		查询结果集数组
	 */
	public function select($data, $table, $where = '', $limit = '', $order = '', $group = '', $key = '') {
		$where = $where == '' ? '' : ' WHERE '.$where;
		$order = $order == '' ? '' : ' ORDER BY '.$order;
		$group = $group == '' ? '' : ' GROUP BY '.$group;
		$limit = $limit == '' ? '' : ' LIMIT '.$limit;
		$field = explode(',', $data);
		array_walk($field, array($this, 'add_special_char'));
		$data = implode(',', $field);

		$sql = 'SELECT '.$data.' FROM `'.$this->config['database'].'`.`'.$table.'`'.$where.$group.$order.$limit;
		$this->execute($sql);
		if(!is_resource($this->lastqueryid)) {
			return $this->lastqueryid;
		}

		$datalist = array();
		while(($rs = $this->fetch_next()) != false) {
			if($key) {
				$datalist[$rs[$key]] = $rs;
			} else {
				$datalist[] = $rs;
			}
		}
		$this->free_result();
		return $datalist;
	}

	function num_rows($rs){
	    return is_array($rs) ? count($rs) : $rs->recordcount;
	}

	function num_fields($rs){
	    return $rs->Fields->Count;
	}

	function fetch_assoc($rs){
	    return $this->fetch_array($rs, ASSOC);
	}

	function fetch_row($rs){
	    return $this->fetch_array($rs, NUM);
	}

	function free_result($rs){
	    if(is_resource($rs)) $rs->close();
	}

	function error(){
	    return $this->conn->Errors[$this->conn->Errors->Count-1]->Number;
	}

	function errormsg(){
	    return $this->conn->Errors[$this->conn->Errors->Count-1]->Description;
	}

	function close(){
	    $this->conn->close();
	}

	function limit($rs, $offset, $pagesize = 0){
		if($pagesize > 0){
			$rs->Move($offset);
		}else{
			$pagesize = $offset;
		}
		$info = array();
		for($i = 0; $i < $pagesize; $i++){
			$r = $this->fetch_array($rs);
			if(!$r) break;
			$info[] = $r;
		}
		$this->free_result($rs);
		$this->cursor = 0;
		return $info;
	}
}
?>