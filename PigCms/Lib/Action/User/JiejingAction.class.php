<?php
class JiejingAction extends UserAction{
	public $token;
	public $kefu_db;
	public function _initialize() {
		parent::_initialize();
		$this->token=$this->_session('token');
		$this->jiejing_db=M('Jiejing');
		$this->canUseFunction('Jiejing');
	}
	//配置
public function index(){
		$Jiejing=M('Jiejing')->where(array('token'=>$this->_session('token')))->find();
		//dump($Jiejing);exit;
		if(IS_POST){			
			$data=D('Jiejing');
			$_POST['token']=session('token');		
			if($data->create()!=false){
				if(empty($_POST['id'])){
					if($id=$data->add()){
						$data1['pid']=$id;
						$data1['module']='Jiejing';
						$data1['token']=session('token');
						$data1['keyword']=$_POST['keyword'];
						M('Keyword')->add($data1);
						$this->success('添加成功',U('Jiejing/index'));
					}else{
						$this->error('服务器繁忙,请稍候再试');
					}
				}else{
					$data->save();
					M('Keyword')->where(array('token'=>session('token'),'module'=>'Jiejing'))->save(array('keyword'=>$_POST['keyword']));
					$this->success('保存成功',U('Jiejing/index'));
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			$this->assign('Token',$token);
			$this->assign('Jiejing',$Jiejing);
			$this->display();
		}	
	}
}
?>