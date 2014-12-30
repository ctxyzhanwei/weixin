<?php
class LoginAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		if (IS_POST){
			$username=trim($this->_post('email'));
			if (!$username){
				$this->error('请输入账号');
			}
			$username=str_replace(array("'"),array(''),$username);
			//
			$username=htmlspecialchars($username);
			$thisUser=M('Agent')->where(array('name'=>$username))->find();
			if (!$thisUser){
				$this->error('账号和密码不匹配哦');
			}
			if (md5(md5(trim($_POST['password'])).$thisUser['salt'])!=$thisUser['password']){
				$this->error('账号和密码不匹配');
			}
			$_SESSION['agentid']=$thisUser['id'];
			$this->success('登录成功',U('Admin/index'));
		}else {
			$this->display();
		}
	}
}


?>