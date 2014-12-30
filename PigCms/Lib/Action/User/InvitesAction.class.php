<?php
class InvitesAction extends UserAction{
	public function index(){
		$db=D('Invites');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('id ASC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('list',$info);
		$this->display();
	}	
	public function add(){	
		$token	  =  $this->_get('token');
		if(IS_POST){			
			$data=D('Invites');
			$_POST['token']=session('token');		
			if($data->create()!=false){				
				if($id=$data->add()){
					$data1['pid']=$id;
					$data1['module']='Invites';
					$data1['token']=session('token');
					$data1['keyword']=$_POST['keyword'];
					M('Keyword')->add($data1);
					$this->success('添加成功',U('Invites/index'));
				}else{
					$this->error('服务器繁忙,请稍候再试');
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			$this->assign('Token',$token);
			$this->display();
		}	
	}
	public function edit(){
	    $id=intval($_GET['id']);
		$where['id']=$id;
		$where['token']=session('token');
		$Invites=M('Invites')->where($where)->find();
		if(IS_POST){
			$data=D('Invites');
		    $token	  =  $this->_get('token');
	        	$id=intval($_GET['id']);
			$_POST['id']=$this->_get('id');
			$_POST['token']=session('token');
			$where=array('id'=>$_POST['id'],'token'=>$_POST['token']);			
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($data->create()){				
				if($id=$data->where($where)->save($_POST)){
					$data1['pid']=$_POST['id'];
					$data1['module']='Invites';
					$data1['token']=session('token');
					$da['keyword']=$_POST['keyword'];
					M('Keyword')->where($data1)->save($da);
					$this->success('修改成功');
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			$id=$this->_get('id');
			$where=array('id'=>$id,'token'=>session('token'));
			$data=M('Invites');
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			$xitie=$data->where($where)->find();		
			$this->assign('info',$Invites);
			$this->display();
		}		
	}	
	public function info(){
	    $where['iid'] = intval($_GET['id']);
		$where['type'] = intval($_GET['type']);
		$where['token']	  =  $this->_get('token');
		$types = intval($_GET['type']);
		$count=M('Invites_info')->where($where)->count();
		$page=new Page($count,25);
		$info=M('Invites_info')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->assign('types',$types);
		$this->display();
    }
	public function del(){		
		$id=$this->_get('id');
		$where=array('id'=>$id,'token'=>session('token'));
		$data=M('Invites');
		$check=$data->where($where)->find();
		if($check==false)$this->error('非法操作');
		$back=$data->where($wehre)->delete();
		if($back==true){
			M('Keyword')->where(array('pid'=>$id,'token'=>session('token'),'module'=>'Invites'))->delete();
			M('Invites_info')->where(array('iid'=>$id,'token'=>session('token')))->delete();
			$this->success('删除成功');
		}else{
			$this->error('操作失败');
		}	
	} 
	public function info_del(){
		$where['id']=$this->_get('id','intval');
		$where['type']=$this->_get('type','intval');
		$where['token']=$this->_get('token','intval');
		if(D('Invites_info')->where($where)->delete()){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	} 
}
?>