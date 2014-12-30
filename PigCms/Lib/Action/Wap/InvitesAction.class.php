<?php
class InvitesAction extends BaseAction{
	public $apikey;
	public function _initialize() {
		parent::_initialize();
		$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
	}
	public function index(){
		$token	  =  $this->_get('token');
		$id 	  = $this->_get('id');
		$Invites = M('Invites')->where(array('id'=>$id,'token'=>$token))->find(); 
		$this->assign('Invites',$Invites);
		$this->assign('token',$token);
		$this->assign('id',$id);
		if($Invites['type'] =='1'){
		$this->display('./tpl/Wap/default/Invites_index.html','utf-8','text/html');
		}else{
		$this->display('./tpl/Wap/default/Invites_index2.html','utf-8','text/html');
		}
	}
	public function add(){
		if($_POST['action'] =='add'){
			$data=array();
			$data['iid'] 		= $this->_post('id');
			$data['token'] 		= $this->_post('token');
			$data['username'] = $this->_post('username');
			$data['telphone'] = $this->_post('telphone');
			$data['content'] = $this->_post('content');
			$data['rdo_go'] = $this->_post('rdo_go');
			$data['type'] = $this->_post('type');
			$result=M('Invites_info')->add($data);
			echo'提交成功';
			exit;
		}else{
			echo'提交失败';
		}
	}
}
?>