<?php
class GametAction extends UserAction{
		public $token;
	
	public function _initialize() {
		parent::_initialize();
		
		$this->token=session('token');
		$this->assign('token',$this->token);
	}
	public function index(){
		 $this->reply_info_model=M('Gametreply_info');
		$thisInfo = $this->reply_info_model->where(array('token'=>$this->token))->find();
		
		if ($thisInfo&&$thisInfo['token']!=$this->token){
			exit();
		}

		if(IS_POST){
			$row['title']=$this->_post('title');
			$row['info']=$this->_post('info');
			$row['picurl']=$this->_post('picurl');
			$row['picurls1']=$this->_post('picurls1');
			$row['token']=$this->_post('token');
			$row['ad']=$this->_post('ad');
			
			if ($thisInfo){
				$where=array('token'=>$this->token);
				$this->reply_info_model->where($where)->save($row);

				$keyword_model=M('Keyword');
				//$keyword_model->where(array('token'=>$this->token,'pid'=>$thisInfo['id'],'module'=>'Reply_info'))->save(array('keyword'=>$_POST['keyword']));
				$this->success('修改成功',U('Gamet/index',$where));
						
			}else {
				$where=array('token'=>$this->token);
				$this->reply_info_model->add($row);
				$this->success('设置成功',U('Gamet/index',$where));
			}
		}else{
			//
			
		
			//
			$this->assign('set',$thisInfo);
			
			$this->display();
		}
	}
}
	

?>