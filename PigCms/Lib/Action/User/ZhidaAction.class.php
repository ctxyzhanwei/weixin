<?php
class ZhidaAction extends UserAction{
	public $token;
	public function _initialize(){
		parent::_initialize();
		$this->token=$this->_session('token');
		$this->assign('token',$this->token);
		$this->canUseFunction('Zhida');
	}

	public function index(){
		$db = M('Zhida');
		if(IS_POST){
			//$_POST['code'] = $this->_post('code');
			$_POST['token'] = $this->token;
			if(stripos($_POST['code'],'eval') === false && stripos($_POST['code'],'alert') === false && stripos($_POST['code'],'php') === false){
				$_POST['code'] = base64_encode($_POST['code']);
				if($db->where(array('token'=>$this->token))->getField('id')){
					if($db->where(array('token'=>$this->token))->save($_POST)){
						$this->success('保存成功');
					}else{
						$this->error('保存失败');
					}
				}else{
					if($db->create() !== false){
						if($db->add()){
							$this->success('保存成功');
						}else{
							$this->error('保存失败');
						}
					}else{

						$this->error('发生了点小问题，请稍后再试');
					}
				}
			}else{
				$this->error('抱歉，代码存在不安全因素，请检查后再试');
			}

		}else{
			$info = $db->where(array('token'=>$this->token))->find();
			$info['code'] = htmlspecialchars_decode(base64_decode($info['code']));
			$this->assign('info',$info);
			$this->display();
		}
	}



}
?>