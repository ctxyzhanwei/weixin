<?php
class LoginAction extends Action{
	public function index(){
		if(IS_POST){
			$userName=$this->_post('userName','htmlspecialchars');
			$data['userPwd']=md5($this->_post('userPwd','htmlspecialchars'));
			if($userName==false||$data['userPwd']==false){
				$this->error('帐号必须填写');
			}
			if((!strpos($userName,'@') === FALSE)){
					$user=explode('@',$userName);
					$data['userName']=$user[0];
					$data['token']=$user[1];
					if($data['userName']==false || $data['token']==false){
						$this->error('帐号格式不正确');
					}
			}else{
				$this->error('帐号格式错误');
			}
			$back=M('service_user')->where($data)->find();
			if($back!=false){
				session('userId',$back['id']);
				session('name',$back['name']);
				session('token',$data['token']);
				session('userName',$back['userName']);
				
				$this->success('登陆成功',U('Index/index'));
			}else{
				$this->error('您的登陆信息错误<br />请核实后再登陆');
			}
		}else{		
			$this->display();
		}
	
	}
	
	
}
	?>