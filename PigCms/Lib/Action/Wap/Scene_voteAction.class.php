<?php
class Scene_voteAction extends WapAction{
	
	public function _initialize() {
		parent::_initialize();
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}


	public function index(){
		$act_id 	= $this->_get('id','intval');
		$act_type 	= $this->_get('act_type','intval');
		if($act_type != 3){
			echo '参数错误';
			exit();
		}
		$mwhere 	= array('wecha_id'=>$this->wecha_id,'token'=>$this->token,'act_id'=>$act_id,'act_type'=>$act_type);
		$member 	= M('wall_member')->where($mwhere)->find();
		
		if (!$member){
			header('location:'.U('Scene_member/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$act_id,'act_type'=>$act_type,'name'=>'vote')));
			exit();
		}

		$vid 		= M('Wechat_scene')->where(array('token'=>$this->token,'id'=>$act_id))->getField('vote_id');
		if(empty($vid)){
			echo '参数错误';
			exit();
		}


		$vote_id 	= explode(',',$vid);
		$info 		= array();

		$where 		= array('token'=>$this->token,'id'=>array('in',$vote_id),'type'=>'scene');
		$vote_info 	= M('Vote')->where($where)->select();
		$sub 		= array();

		foreach($vote_info as $key=>$value){
			$info[$key]['id'] 		= $value['id'];
			$info[$key]['img'] 		= $value['picurl'];
			$info[$key]['name'] 	= $value['title'];
			$info[$key]['status'] 	= $value['status'];
		}

		$this->assign('vid', $vid);
		$this->assign('info',$info);
		$this->display();
	}

	public function	loadStatus(){
		$vote_id 	= explode(',', $this->_get('vote_id','trim'));
		$where 		= array('token'=>$this->token,'id'=>array('in',$vote_id),'type'=>'scene');
		$status 	= M('Vote')->where($where)->field('id,status')->select();

		echo json_encode($status);
	}


}
?>