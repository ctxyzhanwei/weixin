<?php
class ShareAction extends WapAction{
	public function __construct(){
		parent::_initialize();
	}
	public function shareData(){
		 if(isset($_POST['wecha_id'])||isset($_GET['wecha_id'])){
		 	$row=array();
		 	$row['token']=$this->token;
		 	$row['wecha_id']=$this->wecha_id;
		 	$row['to']=$this->_post('to')?$this->_post('to'):$this->_get('to');
		 	$row['module']=$this->_post('module')?$this->_post('module'):$this->_get('module');
		 	$row['moduleid']=intval($this->_post('moduleid'))?intval($this->_post('moduleid')):intval($this->_get('moduleid'));
		 	$row['time']=time();
		 	$row['url']=$this->_post('url');
		 	M('share')->add($row);
		 	//score
		 	$shareSet=M('Share_set')->where(array('token'=>$this->token))->find();
		 	if ($shareSet){
		 		$row2=array();
		 		$row2['token']=$this->token;
		 		$row2['wecha_id']=$this->wecha_id;
		 		$where=array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cat'=>98);
		 		$now=time();
		 		$year=date('Y',$now);
		 		$month=date('m',$now);
		 		$day=date('d',$now);
		 		$firstSecond=mktime(0,0,0,$month,$day,$year);
		 		$where['time']=array('gt',$firstSecond);
		 		$recordsCount=M('Member_card_use_record')->where($where)->count();

		 		if ($recordsCount<$shareSet['daylimit']){
		 			$row2['expense']=0;
		 			$row2['time']=$now;
		 			$row2['cat']=98;
		 			$row2['staffid']=0;
		 			$row2['score']=intval($shareSet['score']);
		 			M('Member_card_use_record')->add($row2);
		 			//
		 			M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->setInc('total_score',$row2['score']);
		 		}
		 		
		 	}
		}
	}
}
	
?>