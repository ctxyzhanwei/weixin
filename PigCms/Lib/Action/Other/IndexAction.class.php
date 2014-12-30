<?php
class IndexAction extends Action{
	public function index(){
		if($this->_get('type')>3||$this->_get('type')<1){
			$this->error('非法操作');		
		}
		if(IS_POST){
			if(cookie('time')){
				$time=time()-cookie(md5('time'));
				if($itme<3600){
					$this->error('请勿频繁提交数据');			
				}
			}
			if(strlen($this->_post('tel'))!=11){
				$this->error('手机号码格式不正确');			
			}
			$db=D('Msg');
			$_POST['time']=strtotime($_POST['time']);
			$_POST['info']=strip_tags($_POST['info']);
			if($db->create()===false){
				$this->error($db->getError());
			}else{
				
				$id=$db->add();
				if($id==true){
					cookie('time',time());
					$this->success('成功预订');
				}else{
					$this->error('操作失败');
				}
			}
		}else{
		$this->display();
	}
	}
	public function msg(){
		if(IS_POST){
			if($this->_post('domain')==false || (strlen($this->_post('domain')))<5){
				$this->error('请填写要查询的域名');
			}
			$data=M('Msg')->field('status')->where(array('domain'=>$this->_post(htmlspecialchars('domain'))))->find();
			if($data['status'] ==0){
				$su=M('Msg')->where(array('status'=>0))->count();
				
				$this->error('请耐心排队，您的前面还有'.$su.'人');
			
			}elseif($data['status'] ==1){
			$this->success('技术已经受理，请保持QQ在线');
			
			}else{
				$this->success('恭喜您，您的升级已经全面完成');
			}
		}else{
			$this->display();
		}
	}
	public function admin(){
		if(cookie('admin')==false){
			$this->error('请登陆',U('Other/Index/login'));
		}
		$data=M('Msg');
		if(!isset($_GET['status'])){
		$list=$data->order('id desc')->where('status!=2')->select();
		$count=$data->order('id desc')->where('status!=2')->count();
		}else{
		$list=$data->order('id desc')->where('status=2')->select();
		$count=$data->order('id desc')->where('status=2')->count();
		}
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('con',$data->where(array('id'=>$this->_get('id')))->find());
		$this->display();
	}
	public function setInc(){
		$data=M('Msg')->where(array('id'=>$this->_get('id')))->setInc('status');
		if($data!=false){
			$this->success('修改成功 ');
		
		}else{
			$this->error('有问题了');
		}
	
	}
	public function login(){
	if(IS_POST){
		if($this->_post('pwd')=='12368'){
			cookie('admin',100000);
			$this->success('登陆成功',U('Other/Index/admin'));
		}else{
			exit('error');
		}
	
	}else{
		$this->display();
	
	}
}
}
?>