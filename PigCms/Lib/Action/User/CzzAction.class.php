<?php
class CzzAction extends UserAction{
		public $token;
	
	public function _initialize() {
		parent::_initialize();
		
		$this->token=session('token');
		$this->assign('token',$this->token);
	}
	public function index(){
		 $this->reply_info_model=M('czzreply_info');
		$thisInfo = $this->reply_info_model->where(array('token'=>$this->token))->find();
		
		if ($thisInfo&&$thisInfo['token']!=$this->token){
			exit();
		}
       
		if(IS_POST){
			
			$row['url']=strip_tags(htmlspecialchars_decode($_POST['url']));
			$row['title']=$this->_post('title');
			$row['info']=$this->_post('info');
			$row['picurl']=$this->_post('picurl');
			$row['picurls1']=$this->_post('picurls1');
			$row['token']=$this->_post('token');
			$row['bg']=$this->_post('bg');
			$row['wx']=$this->_post('wx');
			$row['zz']=$this->_post('zz');

		
			
			
			
			if ($thisInfo){
				
				$where=array('token'=>$this->token);
				$this->reply_info_model->where($where)->save($row);

				$keyword_model=M('Keyword');
				//$keyword_model->where(array('token'=>$this->token,'pid'=>$thisInfo['id'],'module'=>'Reply_info'))->save(array('keyword'=>$_POST['keyword']));
				$this->success('修改成功',U('Czz/index',$where));
						
			}else {
				$where=array('token'=>$this->token);
				$this->reply_info_model->add($row);
				$this->success('设置成功',U('Czz/index',$where));
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