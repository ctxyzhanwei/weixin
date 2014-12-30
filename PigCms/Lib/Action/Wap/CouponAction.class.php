<?php
class CouponAction extends LotteryBaseAction{
	public $token;
	public $wecha_id;
	public $lottory_record_db;
	public $lottory_db;

	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$this->token=$this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		$this->lottory_record_db=M('Lottery_record');
		$this->lottory_db=M('Lottery');
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		if (!defined('STATICS')){
			define('STATICS',TMPL_PATH.'static');
		}
		
		
		$token		= $this->token;
		$wecha_id	= $this->wecha_id;
		$id 		= $this->_get('id');
		$Lottery 	= $this->lottory_db->where(array('id'=>$id,'token'=>$token,'type'=>3,'status'=>1))->find();
		$Lottery['renametel']=$Lottery['renametel']?$Lottery['renametel']:'手机号';
		$Lottery['renamesn']=$Lottery['renamesn']?$Lottery['renamesn']:'SN码';
		$this->assign('lottery',$Lottery);
		//var_dump($Lottery);
		//0. 判断优惠券是否领完了
		if ($Lottery['statdate']>time()){
			$data['usenums']=0;
		}else {
			
			$data=$this->prizeHandle($token,$wecha_id,$Lottery);
		}
	
		$data['token'] 		= $token;
		$data['wecha_id']	= $wecha_id;		
		$data['lid']		= $Lottery['id'];
		$data['id']		= $Lottery['id'];
		$data['keyword']		= $Lottery['keyword'];
		$data['title']		= $Lottery['title'];
		$data['startpicurl']		= $Lottery['startpicurl'];
		
		$data['phone']		= $data['phone']; 
		$data['usenums']	= $data['usenums'];
		$data['sendtime']	= $data['sendtime'];
		$data['canrqnums']	= $Lottery['canrqnums'];
		$data['fist'] 		= $Lottery['fist'];
		$data['second'] 	= $Lottery['second'];
		$data['third'] 		= $Lottery['third'];
		$data['fistnums'] 	= $Lottery['fistnums'];
		$data['secondnums'] = $Lottery['secondnums'];
		$data['thirdnums'] 	= $Lottery['thirdnums'];	
		$data['info']		= $Lottery['info'];
		$data['aginfo']		= $Lottery['aginfo'];
		$data['txt']		= $Lottery['txt'];
		$data['sttxt']		= $Lottery['sttxt'];
		$data['title']		= $Lottery['title'];
		$data['statdate']	= $Lottery['statdate'];
		$data['enddate']	= $Lottery['enddate'];
		$data['info']=nl2br($data['info']);
		$data['endinfo']=nl2br($data['endinfo']);	
		$this->assign('Coupon',$data);
		//var_dump($data);exit();
		$this->display();
	}
}
	
?>