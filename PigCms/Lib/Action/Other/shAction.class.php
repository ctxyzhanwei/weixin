<?php
class shAction extends Action{
	public function _initialize() {
		
		/*
		$this->token=$this->_get('token');
		$this->assign('token',$this->token);
		$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		*/
		
	}
	//公司静态地图
	public function index(){
		$token=md5($this->_post('token'));
		if ($token!='25d7186fe598a394919623186ca325e2'){
			exit('非法操作');
		}
		//
		$viptime=time()+5*24*3600;
		if (M('Users')->where(array('username'=>$this->_post('username')))->save(array('viptime'=>$viptime,'status'=>1,'gid'=>4))){
		echo '操作成功';
		}else{
			echo '操作失败';
		}
	}
	
}


?>