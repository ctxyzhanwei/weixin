<?php
bpBase::loadAppClass('manage','manage',0);
class database extends manage {
	public $dbConfig;
	public $db;
	function __construct() {
		//$this->update_log_db = bpBase::loadModel('update_log_model');
		parent::__construct();
		$checkAccess=$this->exitWithoutAccess('system','manage');
		$this->dbConfig=array (
			'default' => array (
			'hostname' => DB_HOSTNAME,
			'database' => DB_NAME,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'tablepre' => TABLE_PREFIX,
			'charset' => DB_CHARSET,
			'type' => 'mysql',
			'debug' => DEBUG,
			'pconnect' => 0,
			'autoconnect' => 0
			),
			);
		bpBase::loadSysClass('db_factory');
		$this->db = db_factory::get_instance($this->dbConfig)->get_database('default');
	}
	/**
	 * 数据库导出
	 */
	public function export() {
		$r = array();
		$tbl_show = $this->db->query("SHOW TABLE STATUS FROM `".$this->dbConfig['default']['database']."`");
		while(($rs = $this->db->fetch_next()) != false) {
			$r[] = $rs;
		}
		$infos = $this->status($r);
		$this->db->free_result($tbl_show);
		include $this->showManageTpl('databaseExport');
	}
	public function action_export(){
		$tables = $_POST['tables'] ? $_POST['tables'] : $_GET['tables'];
		$sqlcharset = '';
		$sqlcompat = '';
		$sizelimit = $_POST['sizelimit'] ? $_POST['sizelimit'] : $_GET['sizelimit'];
		$fileid = $_POST['fileid'] ? $_POST['fileid'] : trim($_GET['fileid']);
		$random = $_POST['random'] ? $_POST['random'] : trim($_GET['random']);
		$tableid = $_POST['tableid'] ? $_POST['tableid'] : trim($_GET['tableid']);
		$startfrom = $_POST['startfrom'] ? $_POST['startfrom'] : trim($_GET['startfrom']);
		$this->export_database($tables,$sqlcompat,$sqlcharset,$sizelimit,$action,$fileid,$random,$tableid,$startfrom);
	}
	/**
	 * 数据库导入
	 */
	public function import() {
		$disfun=ini_get('disable_functions');
		if (!strpos($disfun,'scandir')===false){
			showmessage('scandir函数被禁用，请配置您的php环境支持该函数','###',10000);
		}
		if($_POST['doSubmit']) {
			$files=scandir(ABS_PATH.'backup'.DIRECTORY_SEPARATOR.$_POST['dir']);
			$sqlFiles=array();
			if ($files){
				foreach ($files as $f){
					if ($f!='.'&&$f!='..'&&strExists($f,'.sql')){
						array_push($sqlFiles,ABS_PATH.'backup'.DIRECTORY_SEPARATOR.$_POST['dir'].DIRECTORY_SEPARATOR.$f);
					}
				}
			}
			$sqlFilesStr=file_put_contents(ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'importSqls.txt',serialize($sqlFiles));
			showmessage('正在执行，请勿关闭','?m=manage&c=database&a=import_database',1000);
		} else {
			$childDirsInDataDir=scandir(ABS_PATH.'backup');
			$dirs=array();//存放备份数据的文件夹
			if ($childDirsInDataDir){
				foreach ($childDirsInDataDir as $dir){
					if ($dir!='.'&&$dir!='..'&&strExists($dir,'data')&&is_dir(ABS_PATH.'backup'.DIRECTORY_SEPARATOR.$dir)){
						array_push($dirs,$dir);
					}
				}
			}
			include $this->showManageTpl('databaseImport');
		}
	}
	
	/**
	 * 备份文件下载
	 */
	public function public_down() {
		
	}
	
	/**
	 * 数据库修复、优化
	 */
	public function repair() {
		$tables = $_POST['tables'] ? $_POST['tables'] : trim($_GET['tables']);
		$operation = trim($_GET['operation']);
		$tables = is_array($tables) ? implode(',',$tables) : $tables;
		if($tables && in_array($operation,array('repair','optimize'))) {
			$this->db->query("$operation TABLE $tables");
			showmessage(L('操作完成'),'?m=manage&c=database&a=export');
		} elseif ($tables && $operation == 'showcreat') {						
			$this->db->query("SHOW CREATE TABLE $tables");
			$structure = $this->db->fetch_next();
			$structure = $structure['Create Table'];
			$show_header = true;
			include $this->admin_tpl('database_structure');					
		} else {
			showmessage(L('请选择表'),'?m=manage&c=database&a=export');
		}
	}
	
	/**
	 * 备份文件删除
	 */
	public function delete() {
		
	}
	/**
	 * 将字节转换为mb
	 *
	 * @param unknown_type $size
	 * @return unknown
	 */
	function calSize($size){
		if ($size==0){
			return $size;
		}
		if ($size>1024){
			return number_format($size/(1024*1024),1).' MiB';
		}else {
			return number_format($size/10,1).' KiB';
		}
	}
	/**
	 * 获取数据表
	 * @param unknown_type 数据表数组
	 * @param unknown_type 表前缀
	 */
	private function status($tables) {
		$rs = array();
		foreach($tables as $table) {
			$name = $table['Name'];
			$row = array('name'=>$name,'rows'=>$table['Rows'],'updateTime'=>$table['Update_time'],'size'=>$table['Data_length']+$row['Index_length'],'engine'=>$table['Engine'],'dataFree'=>$table['Data_free'],'collation'=>$table['Collation']);
			//if(strpos($name, AUTO_TABLE_PREFIX) === 0||strpos($name, TABLE_PREFIX) === 0) {
				$rs[] = $row;
			//}		
		}
		return $rs;
	}
	
	/**
	 * 数据库导出方法
	 * @param unknown_type $tables 数据表数据组
	 * @param unknown_type $sqlcompat 数据库兼容类型
	 * @param unknown_type $sqlcharset 数据库字符
	 * @param unknown_type $sizelimit 卷大小
	 * @param unknown_type $action 操作
	 * @param unknown_type $fileid 卷标
	 * @param unknown_type $random 随机字段
	 * @param unknown_type $tableid 
	 * @param unknown_type $startfrom 
	 */
	private function export_database($tables,$sqlcompat,$sqlcharset,$sizelimit,$action,$fileid,$random,$tableid,$startfrom) {
		$dumpcharset = $sqlcharset ? $sqlcharset : str_replace('-', '', DB_CHARSET);

		$fileid = ($fileid != '') ? $fileid : 1;		
		if($fileid==1 && $tables) {
			if(!isset($tables) || !is_array($tables)) showMessage('请选择要备份的表');
			$random = mt_rand(1000, 9999);
			setCache('backupTables',serialize($tables));
		} else {
			if(!$tables = unserialize(getCache('backupTables'))) showMessage('请选择要备份的表');
		}
		if($sqlcharset) {
			$this->db->query("SET NAMES '".$sqlcharset."';\n\n");
		}
		
		$tabledump = '';

		$tableid = ($tableid!= '') ? $tableid - 1 : 0;
		$startfrom = ($startfrom != '') ? intval($startfrom) : 0;
		for($i = $tableid; $i < count($tables) && strlen($tabledump) < $sizelimit * 1000; $i++) {
			global $startrow;
			$offset = 100;
			if(!$startfrom) {
				if($tables[$i]!=AUTO_TABLE_PREFIX.'session') {
					$tabledump .= "DROP TABLE IF EXISTS `$tables[$i]`;\n";
				}
				$createtable = $this->db->query("SHOW CREATE TABLE `$tables[$i]` ");
				$create = $this->db->fetch_next();
				$tabledump .= $create['Create Table'].";\n\n";
				$this->db->free_result($createtable);
							
				if($sqlcompat == 'MYSQL41' && $this->db->version() < '4.1') {
					$tabledump = preg_replace("/TYPE\=([a-zA-Z0-9]+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
				}
				if($this->db->version() > '4.1' && $sqlcharset) {
					$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=[a-zA-Z0-9]+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);
				}
				if($tables[$i]==AUTO_TABLE_PREFIX.'session') {
					$tabledump = str_replace("CREATE TABLE `".DB_PRE."session`", "CREATE TABLE IF NOT EXISTS `".DB_PRE."session`", $tabledump);
				}
			}
			$numrows = $offset;
			while(strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) {
				if($tables[$i]==AUTO_TABLE_PREFIX.'session') break;
				$sql = "SELECT * FROM `$tables[$i]` LIMIT $startfrom, $offset";
				$numfields = $this->db->num_fields($sql);
				$numrows = $this->db->num_rows($sql);
				$fields_name = $this->db->get_fields($tables[$i]);
				$rows = $this->db->query($sql);
				$name = array_keys($fields_name);
				$r = array();
				while ($row = $this->db->fetch_next()) {
					$r[] = $row;
					$comma = "";
					$tabledump .= "INSERT INTO `$tables[$i]` VALUES(";
					for($j = 0; $j < $numfields; $j++) {
						$tabledump .= $comma."'".mysql_real_escape_string($row[$name[$j]])."'";
						$comma = ",";
					}
					$tabledump .= ");\n";
				}
				$this->db->free_result($rows);
				$startfrom += $offset;
				
			}
			$tabledump .= "\n";
			$startrow = $startfrom;
			$startfrom = 0;
		}
		if(trim($tabledump)) {
			$tabledump = "# time:".date('Y-m-d H:i:s')."\n# bupu auto system:http://www.bupu.net\n# --------------------------------------------------------\n\n\n".$tabledump;
			$tableid = $i;
			$filename = date('Ymd').'_'.$random.'_'.$fileid.'.sql';
			$altid = $fileid;
			$fileid++;
			
			$backUpFolder=ABS_PATH.DIRECTORY_SEPARATOR.'backup';
			if (!file_exists($backUpFolder)&&!is_dir($backUpFolder)){
				mkdir($backUpFolder,0777);
			}
			$bakfile_path = ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'data'.date('Y-m-d',SYS_TIME);
			if (!file_exists($bakfile_path)&&!is_dir($bakfile_path)){
				mkdir($bakfile_path,0777);
			}
			$bakfile = $bakfile_path.DIRECTORY_SEPARATOR.$filename;
			if(!is_writable($bakfile_path)) showMessage('backup文件夹不可写');
			file_put_contents($bakfile, $tabledump);
			@chmod($bakfile, 0777);
			showmessage('正在备份，请不要关闭浏览器'." $filename ", '?m=manage&c=database&a=action_export&sizelimit='.$sizelimit.'&sqlcompat='.$sqlcompat.'&sqlcharset='.$sqlcharset.'&tableid='.$tableid.'&fileid='.$fileid.'&startfrom='.$startrow.'&random='.$random.'&allow='.$allow);
		} else {
		  $bakfile_path = ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'database';
		   //file_put_contents($bakfile_path.DIRECTORY_SEPARATOR.'index.html','');
		   delCache('backupTables');
		   showmessage('备份成功，数据备份在了“/backup/data'.date('Y-m-d',SYS_TIME).'”文件夹中');
		}
	}
	/**
	 * 数据库恢复
	 * @param unknown_type $filename
	 */
	public function import_database() {
		$sqlFilesStr=file_get_contents(ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'importSqls.txt');
		$sqlFiles=unserialize($sqlFilesStr);
		$i=isset($_GET['i'])?intval($_GET['i']):0;
		$count=count($sqlFiles);
		if ($i<$count){
			$sql = file_get_contents($sqlFiles[$i]);
			$this->sql_execute($sql);
			$i++;
			showmessage('正在恢复数据，请不要关闭浏览器，进度：'.$i.'/'.$count, '?m=manage&c=database&a=import_database&i='.$i,1000);
		}else {
			@unlink(ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'importSqls.txt');
			showmessage('数据恢复完毕');
		}
	}
	
	/**
	 * 执行SQL
	 * @param unknown_type $sql
	 */
 	private function sql_execute($sql) {
	    $sqls = $this->sql_split($sql);
		if(is_array($sqls)) {
			foreach($sqls as $sql) {
				if(trim($sql) != '') {
					$this->db->query($sql);
				}
			}
		} else {
			$this->db->query($sqls);
		}
		return true;
	}
	

 	private function sql_split($sql) {
		if($this->db->version() > '4.1' && $this->db_charset) {
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$this->db_charset,$sql);
		}
		if($this->db_tablepre != "bp_") $sql = str_replace("`bp_", '`'.$this->db_tablepre, $sql);
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query) {
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}
}
?>