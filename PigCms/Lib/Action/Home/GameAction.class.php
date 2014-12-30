<?php
class GameAction extends BaseAction{
	public $token;
	public $gameConfig;
	public $uid;

	public function _initialize() {
		parent::_initialize();
		$this->uid=intval($_GET['uid']);
		$this->gameConfig=M('Game_config')->where(array('uid'=>$this->uid))->find();
		$this->token=$this->gameConfig['token'];
		//
	}
	function api_playuser(){
		$wecha_id=$_GET['openid'];
		$score=$_GET['score'];
		$gameid=intval($_GET['gameid']);
		
		if ($_GET['key']==$this->gameConfig['key']){
			$data=array(
			'token'=>$this->token,
			'gameid'=>$gameid,
			'wecha_id'=>$wecha_id,
			'score'=>$score,
			'time'=>time()
			);
			M('game_records')->add($data);
		}
	}
	function api_playcount(){
		if ($_GET['key']==$this->gameConfig['key']){
			M('games')->where(array('id'=>intval($_GET['gameid'])))->setInc('playcount');
		}
	}
	
	function api_user_game(){
		$uid 	= $this->_post('uid','intval');
		$key 	= $this->_post('key','trim');
		$wxid 	= $this->_post('wxid','trim');
		$where 	= array('uid'=>$uid);
		
		$conf	= M('Game_config')->where($where)->find();

		if(empty($conf)){
			echo '{"success":"-1","msg":"uid not exist"}';
			exit();
		}
		
		if($conf['key'] != $key){
			echo '{"success":"-2","msg":"key error"}';
			exit();
		}
		
		$list	= M('Games')->where(array('token'=>$conf['token']))->field('id as ugameid,gameid,time,intro,token')->select();
		$game 	= array();
		foreach($list as $key=>$value){
			$where 	= array('token'=>$value['token'],'gameid'=>$value['ugameid']);
			$value['score_max'] 	= M('Game_records')->where($where)->max('score');
			$user	=	M('Game_records')->where($where)->group('wecha_id')->getField('id');
			$value['user_count'] 	= count($user);
			$game[$value['gameid']]	= $value;	
		}
		echo json_encode($game);
	}
	
	function api_game_record(){
		$uid 	= $this->_post('uid','intval');
		$key 	= $this->_post('key','trim');
		$gid 	= $this->_post('gid','trim');

		$where 	= array('uid'=>$uid);
		
		$conf	= M('Game_config')->where($where)->find();

		if(empty($conf)){
			echo '{"success":"-1","msg":"uid not exist"}';
			exit();
		}
		
		if($conf['key'] != $key){
			echo '{"success":"-2","msg":"key error"}';
			exit();
		}
		
		$data 	= array(
			'token'		=> $conf['token'],
			'gameid'	=> $this->_post('gid','intval'),
			'score'		=> $this->_post('score','trim'),
			'wecha_id'	=> $this->_post('openid','trim'),
			'time'		=> time(),
		);
		
		if(M('Game_records')->add($data)){
			echo '{"success":"1","msg":"record ok"}';
			exit();
		}
	}
}


?>