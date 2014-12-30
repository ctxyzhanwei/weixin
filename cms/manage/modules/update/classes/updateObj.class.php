<?php
class updateObj {
	function __construct() {
		$this->update_log_db = bpBase::loadModel('update_log_model');
	}
	public function shouldUpdate($updateArr){
		//获取最新更新时间(在日志表中)
		$maxUpdateTimeInLog=$this->update_log_db->get_var('','MAX(logtime)','');
		//程序的最新时间
		$programmeLastTime=$updateArr[0]['time'];
		if ($programmeLastTime<$maxUpdateTimeInLog||$programmeLastTime==$maxUpdateTimeInLog){
			return 0;
		}else {
			return $maxUpdateTimeInLog;
		}
	}
	public function taskShouldEx(){
		$exRt=$this->update_log_db->get_var(array('updatetype'=>'task','executed'=>0),'COUNT(*)','');
		return $exRt;
	}
}
?>