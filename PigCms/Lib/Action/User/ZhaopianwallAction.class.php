<?php
class ZhaopianwallAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$function=M('Function')->where(array('funname'=>'Zhaopianwall'))->find();
		$this->canUseFunction('Zhaopianwall');
	}
	
	public function index(){
		$user=M('Users')->field('gid,activitynum')->where(array('id'=>session('uid')))->find();
		$group=M('User_group')->where(array('id'=>$user['gid']))->find();
		$this->assign('group',$group);
		$this->assign('activitynum',$user['activitynum']);
		$list=M('pic_wall')->field('id,title,joinnum,click,keyword,statdate,enddate,status')->where(array('token'=>session('token')))->select();
		//dump($list);
		$this->assign('count',M('pic_wall')->where(array('token'=>session('token')))->count());
		$this->assign('list',$list);
		$this->display();	
	}
	
	public function add(){
		
		
		if(IS_POST){		
			$data=D('PicWall');
			$_POST['statdate']=strtotime($_POST['statdate']);
			$_POST['enddate']=strtotime($_POST['enddate']);
			$_POST['token']=session('token');		
			if($data->create()!=false){				
				if($id=$data->add()){  
					//添加成功存入对应的关键字表中
					$data1['pid']=$id; 
					$data1['module']='zhaopianwall';
					$data1['token']=session('token');
					$data1['keyword']=$_POST['keyword'];
					 M('Keyword')->add($data1);
					$user=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
					$this->success('活动创建成功',U('Zhaopianwall/index'));
				}else{
					$this->error('服务器繁忙,请稍候再试');
				}
			}else{
				$this->error($data->getError());
			}
			
			
		}else{
			$this->display();
		}
	}
	
	public function setinc(){
		
		
		$id=$this->_get('id');
		$where=array('id'=>$id,'token'=>session('token'));
		$check=M('pic_wall')->where($where)->find();
		if($check==false)$this->error('非法操作');
		$user=M('Users')->field('gid,activitynum')->where(array('id'=>session('uid')))->find();
		$group=M('User_group')->where(array('id'=>$user['gid']))->find();
		
		if($user['activitynum']>=$group['activitynum']){
			$this->error('您的免费活动创建数已经全部使用完,请充值后再使用',U('Home/Index/price'));
		}
		$data=M('pic_wall')->where($where)->setInc('status');
		if($data!=false){
			$this->success('恭喜你,活动已经开始');
		}else{
			$this->error('服务器繁忙,请稍候再试');
		}

	}
	
	public function setdes(){
		$id=$this->_get('id');
		$where=array('id'=>$id,'token'=>session('token'));
		$check=M('pic_wall')->where($where)->find();
		if($check==false)$this->error('非法操作');
		$data=M('pic_wall')->where($where)->setDec('status');
		if($data!=false){
			$this->success('活动已经结束');
		}else{
			$this->error('服务器繁忙,请稍候再试');
		}
	
	}
	
	public function del(){
		$id=$this->_get('id');
		$where=array('id'=>$id,'token'=>session('token'));
		$data=M('pic_wall');
		$check=$data->where($where)->find();
		if($check==false)$this->error('非法操作');
		
		$back=$data->where($where)->delete();
		if($back==true){
			M('Keyword')->where(array('pid'=>$id,'token'=>session('token'),'module'=>'zhaopianwall'))->delete();
			$this->success('删除成功');
		}else{
			$this->error('操作失败');
		}
	
	
	}
	
	
	public function edit(){
		
		if(IS_POST){
			$data=D('PicWall');
			$_POST['id']=$this->_get('id');
			$_POST['token']=session('token');
			$where=array('id'=>$_POST['id'],'token'=>$_POST['token']);
			$_POST['statdate']=strtotime($_POST['statdate']);
			$_POST['enddate']=strtotime($_POST['enddate']);			
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($data->create()){				
				if($id=$data->where($where)->save($_POST)){
					$data1['pid']=$_POST['id'];
					$data1['module']='zhaopianwall';
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
			$data=M('pic_wall');
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			
			$lottery=$data->where($where)->find();		
			$this->assign('vo',$lottery);
			//dump($lottery);
			$this->display('add');
		}
	
	}
	
	
	public function lists(){
		$db=M('pic_walllog');
		$where['uid']=$this->_get('id');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	
	public  function delpic(){
		$id=$this->_get('id');
		$where=array('id'=>$id);
		
		$data=M('pic_walllog');
		$check=$data->where($where)->find();
		if($check==false){
			$this->error('非法操作');exit;
		}
		
		$back=$data->where($where)->delete();
		
		if($back==true){
			$this->success('删除成功');
		}else{
			$this->error('操作失败');
		}
	}
	
	public  function agreepic(){
		
		$id=$this->_get('id');
		$where=array('id'=>$id);
	
		$data=M('pic_walllog');
		$check=$data->where($where)->find();
		
		if($check==false){
			$this->error('非法操作');
			
		}
		
		$result=$data->where($where)->setInc('state');
			
		if($result!=false){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	
	}
}


?>