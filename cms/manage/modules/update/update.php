<?php
bpBase::loadAppClass('manage','manage',0);
class update extends manage {
	public static $updateArr;
	function __construct() {
		$this->update_log_db = bpBase::loadModel('update_log_model');
		parent::__construct();
		$this->exitWithoutAccess();
		if ($this->updateArr==''){
			include('updateFile'.DIRECTORY_SEPARATOR.'list.php');
			$this->updateArr=$update_arr;
		}
	}
	public function task(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$where='updatetype=\'task\' AND executed=0';	
		$logs = $this->update_log_db->listinfo($where, 'id ASC', $page, 1);
		if ($logs){
			showMessage('正在进行数据转换任务:'.$logs[0]['des'],'?m=update&c=updateTask&a='.$logs[0]['file']);
			exit();
		}else {
			showMessage('升级执行完毕','?m=update&c=update&a=logs');
			exit();
		}
		//$pages = $this->update_log_db->pages;
		//include $this->showManageTpl('task');		
	}
	public function update(){
		//检查update_log表是否存在
		//$autoDB->query("CREATE TABLE IF NOT EXISTS `auto_update_log` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`updatetype` VARCHAR( 20 ) NOT NULL DEFAULT  'sql',`des` VARCHAR( 200 ) NULL ,`logtime` INT( 10 ) NOT NULL DEFAULT  '0',`time` INT( 10 ) NOT NULL DEFAULT  '0') ENGINE = MYISAM CHARACTER SET gbk COLLATE gbk_chinese_ci");
		//检查system表是否存在
		//$autoDB->query("CREATE TABLE IF NOT EXISTS `".AUTO_TABLE_PREFIX."system` (`lastsqlupdate` INT( 10 ) NOT NULL ,`version` VARCHAR( 10 ) NOT NULL) ENGINE = MYISAM CHARACTER SET gbk COLLATE gbk_chinese_ci");
		$updateObj=bpBase::loadAppClass('updateObj','update',1);
		$maxUpdateTimeInLog=$updateObj->shouldUpdate($this->updateArr);
		if (!$maxUpdateTimeInLog){
			showMessage('数据库升级完毕，正在进行数据转换任务','?m=update&c=update&a=task');
			exit();
		}
		//对上面数组进行时间倒序排序
		$update_reverse_arr=array_reverse($this->updateArr);
		if ($update_reverse_arr){
			foreach ($update_reverse_arr as $update_item){
				if ($update_item['time']>$maxUpdateTimeInLog){
					switch ($update_item['type']){
						case 'sql':
							//运行sql
							@$this->update_log_db->query($update_item['sql']);
							break;
						case 'function':
							//执行更新函数
							$update_item['name']();
							break;
					}
					//插入更新日志
					$row['updatetype']=$update_item['type'];
					$row['des']=$update_item['des'];
					$row['logtime']=$update_item['time'];
					$row['time']=SYS_TIME;
					if ($update_item['file']){
						$row['file']=$update_item['file'];
					}
					$this->update_log_db->insert($row);
					//由于可能需要更新大量数据，每次只执行一个更新
					showMessage('升级中：'.$row['des'],'?m=update&c=update&a=update',1000);
					break;
				}
			}
		}
	}
	public function logs() {
		$updateObj=bpBase::loadAppClass('updateObj','update',1);
		$shouldUpdate=$updateObj->shouldUpdate($this->updateArr);
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		$logs = $this->update_log_db->listinfo('',$order = 'time DESC',$page, $pagesize = '20','','?m=update&c=update&a=logs&');
		$pages = $this->update_log_db->pages;
		include $this->showManageTpl('logs');
	}
	/**
	 * 升级到2013年最新的皮肤
	 *
	 */
	public function update2newskin(){
		if (!file_exists(ABS_PATH.'update2new.txt')){
			showmessage('请在网站根目录下创建一个文件“update2new.txt”，创建后再刷新该页面');
			exit();
		}
		$channel_db=bpBase::loadModel('channel_model');
		$daodianChannel=$channel_db->get_one(array('cindex'=>'city_newcar'));
		if (!$daodianChannel){
			$channel_db->query("INSERT INTO `moopha_channel` (`name`, `channeltype`, `cindex`, `link`, `externallink`, `des`, `thumb`, `metatitle`, `metakeyword`, `metades`, `thumbwidth`, `thumbheight`, `thumb2width`, `thumb2height`, `thumb3width`, `thumb3height`, `thumb4width`, `thumb4height`, `parentid`, `channeltemplate`, `contenttemplate`, `autotype`, `ex`, `iscity`, `site`, `taxis`, `lastcreate`, `pagesize`, `time`) VALUES('新车到店', 1, 'city_newcar', '', 0, '', '', '', '', '', 132, 72, 0, 0, 0, 0, 0, 0, 1, 4, 38, 'news', 0, 0, 1, 0, 1400000000, 30, 1359275172)");
		}
		//
		$channel_db->query("UPDATE `moopha_channel` SET `thumbwidth` = '308',`thumbheight` = '237' WHERE `id` =34");
		$channel_db->query("UPDATE `moopha_channel` SET `thumbwidth` = '132',`thumbheight` = '72' WHERE `id` =36");
		$channel_db->query("UPDATE `moopha_channel` SET `thumb2width` = '368',`thumb2height` = '184' WHERE `parentid` =50");
		$channel_db->query("UPDATE `moopha_channel` SET `thumb4width` = '330',`thumb4height` = '180' WHERE `parentid` =1");
		$channel_db->query("UPDATE `moopha_channel` SET `thumb2width` = '100',`thumb2height` = '76' WHERE `cindex` ='interview'");
		$channel_db->query("UPDATE `moopha_channel` SET `thumb2width` = '220',`thumb2height` = '100' WHERE `cindex` ='changshang'");
		$channel_db->query("UPDATE `moopha_channel` SET `thumbwidth` = '120',`thumbheight` = '160' WHERE `cindex` ='photos'");
		//ucar
		$ucarArr=loadConfig('ucar');
		$ucarArr['smallThumbWidth']=120;
		$ucarArr['smallThumbHeight']=90;
		$arr=var_export($ucarArr,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'ucar.config.php',$str);
		//zuche
		$zcarArr=loadConfig('carRental');
		$zcarArr['smallThumbWidth']=120;
		$zcarArr['smallThumbHeight']=90;
		$arr=var_export($zcarArr,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'carRental.config.php',$str);
		//
		@rename(ABS_PATH.'cache',ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'cache_'.SYS_TIME);
		@unlink(ABS_PATH.'update2new.txt');
		showmessage('操作完成');
	}
}
?>