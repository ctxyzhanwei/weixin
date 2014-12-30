<?php
class Scene_memberAction extends WapAction{
	
	public function _initialize() {
		parent::_initialize();
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
		$act_type = $this->_get('act_type','intval');
		if (!in_array($act_type, array(1,2,3))){
			$this->error('参数错误','');
		}
	}


	public function index(){
		$act_id 	= $this->_get('id','intval');
		$act_type 	= $this->_get('act_type','intval');
		$info 	= M('Userinfo')->where(array('wecha_id'=>$this->wecha_id,'token'=>$this->token))->find();
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'act_type'=>$act_type,'act_id'=>$act_id);
		$mInfo 	= M('Wall_member')->where($where)->find();

		if(empty($mInfo) && !empty($info)){
			$user['nickname'] 	= $info['wechaname'];
			$user['phone'] 		= $info['tel'];
			$user['truename'] 	= $info['truename'];
			$user['sex'] 		= $info['sex'];
			$user['portrait'] 	= $info['portrait'];
		}else if(!empty($mInfo)){
			$user = $mInfo;
		}

		$this->assign('name',$this->_get('name'));

		if(!empty($mInfo) && $this->_get('name') == 'vote'){
			header("Location:".$this->siteUrl.U('Scene_vote/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$act_id,'act_type'=>$act_type)));
		}
		
		$this->assign('act_id',$act_id);
		$this->assign('act_type',$act_type);
		$this->assign('info',$user);
		$this->display();
	}

	public function set(){	
		$data['token'] 		= $this->_get('token','trim');
		$data['wecha_id'] 	= $this->_get('wecha_id');
		$data['time'] 		= time();
		$data['act_id']		= $this->_get('act_id','intval');
		$data['act_type']	= $this->_get('act_type','intval');
		$data['sex']		= $this->_get('sex');
		$data['nickname']	= $this->_get('nickname','trim');
		$data['portrait']	= $this->_get('portrait');
		$data['phone']		= $this->_get('phone');
		$data['truename']	= $this->_get('truename');
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'act_type'=>$data['act_type'],'act_id'=>$data['act_id']);
		$info 	= M('Wall_member')->where($where)->find();
		
		$act_name 	= $this->_get('name');
		if($info){
			if(M('wall_member')->where($where)->save($data)){
				$this->result($this->token,$act_name,$data['act_id'],$where['act_type'],0);
			}
		}else{
			if(M('wall_member')->add($data)){
				$this->result($this->token,$act_name,$data['act_id'],$where['act_type'],1);
			}
		}
	}
	public function result($token,$act_name,$act_id,$act_type,$is_add){
		$this->token=$token;
		if($act_name == 'shake'){
			echo json_encode(array('err'=>0,'name'=>$act_name,'href'=>$this->siteUrl.U('Shake/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$act_id,'act_type'=>$act_type))));
			exit();
		}else if($act_name == 'wall'){ 
			$where 	  = array('token'=>$this->token,'wecha_id'=>$this->wecha_id);
			
			$rt = M('Userinfo')->where($where)->find();
			if(!$rt){
				M('Userinfo')->add(array('wallopen'=>1,'token'=>$this->token,'wecha_id'=>$this->wecha_id));
			}else{
				M('Userinfo')->where($where)->save(array('wallopen'=>1));
			}

			echo json_encode(array('err'=>0,'name'=>$act_name));
			exit();
		}else if($act_name == 'vote'){
			echo json_encode(array('err'=>0,'name'=>$act_name,'href'=>$this->siteUrl.U('Scene_vote/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$act_id,'act_type'=>$act_type))));
			exit();
		}else{
			
			echo json_encode(array('err'=>0));
			exit();
		}
	}
}
?>