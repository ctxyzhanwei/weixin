<?php
class GameAction extends UserAction{
	public $config;
	public $cats;
	public $game;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Gamecenter');
		$this->game=new game();
		$this->cats=$this->game->gameCats();
		$this->assign('cats',$this->cats);
	}
	public function config(){
		$config=M('Game_config')->where(array('token'=>$this->token))->find();
		if (IS_POST){
			$data=array(
			'token'=>$this->token,
			'wxid'=>$this->_post('wxid'),
			'wxname'=>$this->_post('wxname'),
			'wxlogo'=>$this->_post('wxlogo'),
			'link'=>$this->_post('link'),
			);
			if (!$config){
				D('Game_config')->add($data);
			}else {
				D('Game_config')->where(array('id'=>$config['id']))->save($data);
			}
			$data['link']=str_replace(array('{wechat_id}','{siteUrl}','&amp;'),array('',$this->siteUrl,'&'),$data['link']);
			$rt=$this->game->config($this->token,$data['wxname'],$data['wxid'],$data['wxlogo'],$data['link']);
			D('Game_config')->where(array('token'=>$this->token))->save(array('uid'=>$rt['id'],'key'=>$rt['key']));
			$this->success('设置成功');
		}else {
			if (!$config){
				$config=$this->wxuser;
				$config['wxlogo']=$config['headerpic'];
			}
			$this->assign('info',$config);
			$this->display();
		}
	}
	public function index(){
		$this->_toConfig();
		//
		$where 	= array('token'=>$this->token);

		$count		= M('Games')->where($where)->count();
		$Page       = new Page($count,15);
		$list 		= M('Games')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('count',$count);
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function delGame(){
		$config=$this->_toConfig();
		$id=isset($_GET['id'])?intval($_GET['id']):0;
		$thisItem=M('games')->where(array('id'=>$id,'token'=>$this->token))->find();
		$gameid=$thisItem['gameid'];
		$thisGame=$this->game->getGame(intval($gameid));
		$this->game->gameSelfSet($config['uid'],$thisGame['id'],$id,'game',$config['key'],'');
		M('games')->where(array('id'=>$id,'token'=>$this->token))->delete();
		$this->success('删除成功',U('Game/index'));
	}
	public function gameSet(){
		$id=isset($_GET['id'])?intval($_GET['id']):0;
		$this->assign('id',$id);
		if ($id){
			$thisItem=M('games')->where(array('id'=>$id,'token'=>$this->token))->find();
			$gameid=$thisItem['gameid'];
		}else {
			$gameid=intval($_GET['gameid']);
		}
		$config=$this->_toConfig();
	
		$thisGame=$this->game->getGame(intval($gameid));
		$selfs=$this->game->gameSelfs($thisGame['id'],$config['uid'],$id,$config['key']);
		if (IS_POST){
			$data=array(
			'token'=>$this->token,
			'title'=>$this->_post('title'),
			'intro'=>$this->_post('intro'),
			'keyword'=>$this->_post('keyword'),
			'picurl'=>$this->_post('picurl'),
			'time'=>time(),
			'gameid'=>$thisGame['id'],
			);
			$selfValues=array();
			$jsonStr='{';
			if ($selfs){
				$comma='';
				foreach ($selfs as $s){
					$selfValues['self_'.$s['id']]=$this->_post('self_'.$s['id']);
					$jsonStr.=$comma.'"self_'.$s['id'].'":"'.$selfValues['self_'.$s['id']].'"';
					$comma=',';
				}
			}
			$jsonStr.='}';
			$data['selfinfo']=$jsonStr;
			
			
			if (!isset($_POST['id'])){
				$usergameid=M('Games')->add($data);
			}else {
				$usergameid=intval($_POST['id']);
				M('Games')->where(array('id'=>$usergameid))->save($data);
			}
			$this->handleKeyword($usergameid,'Game',$data['keyword'],$precisions=0,$delete=0);
			$this->game->gameSelfSet($config['uid'],$thisGame['id'],$usergameid,'game',$config['key'],$selfValues);
			$this->success('设置成功',U('Game/index'));
			//
		}else {
			
			//
			
			$this->assign('thisGame',$thisGame);
			

			
			if (!$id){
				$thisItem=array();
				$thisItem['title']=$thisGame['title'];
				$thisItem['intro']=$thisGame['intro'];
				$thisItem['keyword']=$thisGame['title'];
			}
			
			if ($id){
				if ($selfs){
					$selfValues=json_decode($thisItem['selfinfo'],1);
					$i=0;
					foreach ($selfs as $s){
						$selfs[$i]['value']=$selfValues['self_'.$s['id']];
						$i++;
					}
				}
			}
			$this->assign('selfs',$selfs);
			$this->assign('info',$thisItem);
			//
			
			$this->display();
		}
	}
	public function gameDelete(){
		
	}
	public function gameResults(){
		
	}
	public function gameLibrary(){
		$catid=isset($_GET['catid'])?intval($_GET['catid']):1;
		$games=$this->game->gameList($catid);
		
		$this->assign('games',$games);
		$this->assign('catid',$catid);
		$this->display();
	}
	function _toConfig(){
		$config=M('Game_config')->where(array('token'=>$this->token))->find();
		if (!$config){
			$this->success('请先配置游戏相关信息',U('Game/config'));
			exit();
		}else {
			return $config;
		}
	}
	
	
	function record(){
		
		$where 	= array('token'=>$this->token,'gameid'=>$this->_get('id','intval'));
		
		$count		= M('Game_records')->where($where)->count();
		$Page       = new Page($count,15);
		$list 	= M('Game_records')->where($where)->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		
		foreach ($list as $key=>$val) {
			$username	= M('Userinfo')->where(array('token'=>$this->token))->getField('wechaname');
			$list[$key]['username']	= $username;
		}

		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	function record_del(){
		$where 	= array('token'=>$this->token,'id'=>$this->_get('id','intval'));
		if(M('Game_records')->where($where)->delete()){
			$this->success('删除成功',U('Game/record',array('token'=>$this->token,'id'=>$this->_get('rid','intval'))));
		}	
	}
}


?>