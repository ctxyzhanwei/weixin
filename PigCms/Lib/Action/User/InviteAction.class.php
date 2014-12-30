<?php
class InviteAction extends UserAction{

	// public function _initialize(){
	// 	parent::_initialize();
	// 	$this->canUseFunction('Invite');
	// }

	public function add(){
		$Pig=M('Invite');
		session('yid',null);
		$where['token']=session('token');
		$count=$Pig->where($where)->count();
		$page=new Page($count,25);
		$list=$Pig->where($where)->limit($page->firstRow.','.$page->listRows)->order('id')->select();
		$this->assign('page',$page->show());
		$this->assign('pig',$list);
		$this->display();
	}



	public function listdel(){
		$id = $this->_get('id');
		$Invite = M('Invite');
		$invidel = $Invite->where("id='$id'")->delete();
		$this->handleKeyword(intval($_GET['id']),'Invite','','',1);

		$Enroll = M('Invite_enroll');
		$Enroll->where("yid='$id'")->delete();

		$Meeting = M('Invite_meeting');
		$Meeting->where("yid='$id'")->delete();

		$Partner = M('Invite_partner');
		$Partner->where("yid='$id'")->delete();

		$User = M('Invite_user');
		$User->where("yid='$id'")->delete();

		if ($invidel) {
			$this->success('操作成功');				
		}else{
			$this->error('服务器繁忙，请稍候再试');
		}
	}


    public function index(){		
     	$token = $data['token'] = $this->token;
    	if (IS_POST) {
    		$Pig = M("Invite");
			$data['keyword'] = $this->_post('keyword');
			$data['title'] = $this->_post('title');
			$data['replypic'] = $this->_post('replypic');
			$data['content'] = $this->_post('content');
			$data['cover'] = $this->_post('cover');
			$data['inback'] = $this->_post('inback');
			$data['photo'] = $this->_post('photo');
			$data['meetpic'] = $this->_post('meetpic');
			$data['linkman'] = $this->_post('linkman');
			$data['site'] = $this->_post('site');
			foreach ($data as $value){
				   if($value==""){
				    $this->error('带 <font color="red">*</font> 的必须填');
				     }	
				 }
			$data['twopic'] = $this->_post('twopic');
			$data['warn'] = $this->_post('warn');
			$data['email'] = $this->_post('email');
			$data['theme'] = $this->_post('theme');
			$data['themeurl'] = $this->_post('themeurl');
			$id = $this->_get('yid')?$this->_get('yid'):session('yid');
			if ($Pig->where(array('token'=>"$token",'id'=>"$id"))->select()) {
				$rel=$Pig->where(array('token'=>"$token",'id'=>"$id"))->save($data);
				$this->handleKeyword($id,'Invite',$this->_post('keyword'));
				if($rel){
					$this->success('操作成功',U('Invite/user',array('token'=>$token,'yid'=>$id)));				
				}else{
					$this->error('服务器繁忙，请稍候再试');
				}
			}else{



				$rel=$Pig->add($data);

				$this->handleKeyword(intval($rel),'Invite',$this->_post('keyword'));

				if($rel){
					$Pig = M("Invite");
					$yid = $Pig->order('id desc')->limit(1)->getField('id');
					session('yid',"$id");
					$this->success('操作成功',U('Invite/user',array('token'=>$token,'yid'=>$yid)));
				}else{
					$this->error('服务器繁忙，请稍候再试');
				}
			}
 		}else{
			$id = $this->_get('yid')?$this->_get('yid'):session('yid');
			if ($id) {
				$Pig = M("Invite");
				$Invite = $Pig->where(array('token'=>"$token",'id'=>"$id"))->find();
				$this->assign('Invite',$Invite);
				$this->assign('yid',$id);
			}
			$this->assign('tabid',1);
    		$this->display();
    	}
    }



    public function user(){
    	$token = $data['token'] = $this->token;
    	if (IS_POST) {
    		$Pig = M("Invite_user");
			$id = $data['yid'] = $this->_get('yid');
			$data['headpic'] = $this->_post('headpic');
			$data['username'] = $this->_post('username');
			$data['position'] = $this->_post('position');
			$data['synopsis'] = $this->_post('synopsis');
			foreach ($data as $value){
				   if($value==""){
				    $this->error('数据不能为空');
				     }	
				 }
			$rel=$Pig->add($data);
				if($rel){
					$this->success('操作成功');				
				}else{
					$this->error('服务器繁忙，请稍候再试');
				}
			
    	}else{
			$id = $this->_get('yid')?$this->_get('yid'):session('yid');
			if ($id) {
				$Pig = M("Invite_user");
	    		$list = $Pig->where(array('token'=>"$token",'yid'=>"$id"))->select();
				$this->assign('list',$list);
				$this->assign('yid',$id);
			}else{
				if(session('?yid') == false){
					$this->error('请先填写配置信息');			
				}
			}
			$this->assign('tabid',2);
    		$this->display();
    	}
    }



    public function userdel(){
    	$id = $this->_get('id');
    	$Pig = M("Invite_user");
    	$rel = $Pig->where("id='$id'")->delete();
    	if($rel){
			$this->success('操作成功');				
		}else{
			$this->error('服务器繁忙，请稍候再试');
		}
    }



    public function meeting(){
    	$token = $data['token'] = $this->token;
    	if (IS_POST) {
    		$Pig = M("Invite_meeting"); 
    		$data['yid'] = $this->_get('yid');
			$data['time'] = strtotime($this->_post('time'));
			$data['ytime'] = strtotime($this->_post('ytime'));	
			$data['xtime'] = strtotime($this->_post('xtime'));
			if ($data['ytime']>$data['xtime']) {
				$this->error('时间先后顺序有问题');
			}
			$data['guest'] = $this->_post('guest');
			$data['content'] = $this->_post('content');
			$data['call'] = $this->_post('call');
			$data['site'] = $this->_post('site');
			foreach ($this->_post as $value){
				   if($value==""){
				    $this->error('数据不能为空');
				     }
				 }	
			$rel=$Pig->add($data);
				if($rel){
					$this->success('操作成功');				
				}else{
					$this->error('服务器繁忙，请稍候再试');
				}
    	}else{
    		$id = $this->_get('yid')?$this->_get('yid'):session('yid');
			if ($id) {
				$Pig = M("Invite_meeting");
	    		$list = $Pig->where(array('token'=>"$token",'yid'=>"$id"))->select();
				$this->assign('list',$list);
				$this->assign('yid',$id);
			}else{
				if(session('?yid') == false){
					$this->error('请先填写配置信息');			
				}
			}
			$this->assign('tabid',3);
    		$this->display();
    	}
    }



    public function meetdel(){
    	$id = $this->_get('id');
    	$Pig = M("Invite_meeting");
    	$rel = $Pig->where("id=$id")->delete();
    	if($rel){
			$this->success('操作成功');				
		}else{
			$this->error('服务器繁忙，请稍候再试');
		}
    }



    public function partner(){
    	$token = $data['token'] = $this->token;
    	if (IS_POST) {
			$Pig = M("Invite_partner");
			$data['partnertype'] = $this->_post('partnertype');	
			$data['typepic'] = $this->_post('typepic');	
			$data['remark'] = $this->_post('remark');	
			$data['company'] = $this->_post('company');	
			$data['contact'] = $this->_post('contact');	
			$data['photo'] = $this->_post('photo');	
			$data['qq'] = $this->_post('qq');	
			$data['scheme'] = $this->_post('scheme');
    		$data['yid'] = $this->_get('yid');
			foreach ($this->_post as $value){
				if($value==""){
				$this->error('数据不能为空');
				}	
			}
			$rel=$Pig->add($data);
				if($rel){
					$this->success('操作成功');
				}else{
					$this->error('服务器繁忙，请稍候再试');
				}
    	}else{
    		$id = $this->_get('yid')?$this->_get('yid'):session('yid');
			if ($id) {
	    		$Pig = M("Invite_partner");
	    		$list = $Pig->where(array('token'=>"$token",'yid'=>"$id"))->select();
				$this->assign('list',$list);
				$this->assign('yid',$id);
			}else{
				if(session('?yid') == false){
					$this->error('请先填写配置信息');			
				}
			}
    		$this->assign('tabid',5);
    		$this->display();
    	}
    }



    public function pardel(){
    	$id = $this->_get('id');
    	$Pig = M("Invite_partner");
    	$rel = $Pig->where("id='$id'")->delete();
    	if($rel){
			$this->success('操作成功');				
		}else{
			$this->error('服务器繁忙，请稍候再试');
		}
    }



    public function enroll(){	
    	$id = $this->_get('yid')?$this->_get('yid'):session('yid');
    	$token = $this->token;
			if ($id) {
    			$Pig = M("Invite_enroll");
	    		$list = $Pig->where(array('token'=>"$token",'yid'=>"$id"))->select();
				$this->assign('list',$list);
				$this->assign('yid',$id);
			}
		$this->assign('tabid',7);
		$this->display();
    }



    public function enrdel(){
    	$id = $this->_get('id');
    	$Pig = M("Invite_enroll");
    	$rel = $Pig->where("id=$id")->delete();
    	if($rel){
			$this->success('操作成功');				
		}else{
			$this->error('服务器繁忙，请稍候再试');
		}
    }
}
?>