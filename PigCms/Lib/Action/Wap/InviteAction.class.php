<?php
class InviteAction extends WapAction{
	public function index(){
			$token = $this->token;
			$yid = $this->_get('yid');
			$Invite = M("Invite");
			$Invites = $Invite->where(array('token'=>"$token",'id'=>"$yid"))->find();
			$this->assign('Invite',$Invites);

			$User = M("Invite_user");
	    	$Users = $User->limit(8)->where(array('token'=>"$token",'yid'=>"$yid"))->select();
			$this->assign('Userlist',$Users);

			$Meet = M("Invite_meeting");
	    	$Meets = $Meet->where(array('token'=>"$token",'yid'=>"$yid"))->order('time')->select();
	    	$firsttime = $Meet->where(array('token'=>"$token",'yid'=>"$yid"))->order('time')->getField('time');
	    	$lasttime = $Meet->where(array('token'=>"$token",'yid'=>"$yid"))->order('time desc')->getField('time');
	    	$this->assign('firsttime',$firsttime);
	    	$this->assign('lasttime',$lasttime);
			$this->assign('Meetlist',$Meets);

			$Part = M("Invite_partner");
	    	$Parts = $Part->where(array('token'=>"$token",'yid'=>"$yid"))->select();
			$this->assign('Partlist',$Parts);

			$this->assign('token',$token);
			$this->assign('yid',$yid);
			$this->display();
	}

	public function ajax(){
		$Pig = M("Invite_enroll");
		$data['name'] = $this->_post('name');
		$data['email'] = $this->_post('email');
		$data['post'] = $this->_post('post');
		$data['mobile'] = $this->_post('mobile');
		$data['comp'] = $this->_post('comp');
		$data['token'] = $this->token;
		$data['yid'] = $this->_get('yid');
		$data['wecha_id'] = $this->_get('wecha_id');
		$Pig->add($data);
		
	}
}