<?php
class LuckyFruitAction extends LotteryBaseAction{
	public function index(){
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
		$id 		= $this->_get('id');
		
		$redata		= M('Lottery_record');
		$where 		= array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id);
		$record 	= $redata->where(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'islottery'=>1))->find();
		if (!$record){
			$record 	= $redata->where($where)->order('id DESC')->find();
		}
		
		$Lottery 	= M('Lottery')->where(array('id'=>$id,'token'=>$token,'type'=>4,'status'=>1))->find();
		if ($this->wecha_id&&!$this->fans&&$Lottery['needreg']){
			$this->error('请先完善个人资料再参加活动',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>MODULE_NAME.'/index|id:'.intval($id))));
		}
		
		$Lottery['renametel']=$Lottery['renametel']?$Lottery['renametel']:'手机号';
		$Lottery['renamesn']=$Lottery['renamesn']?$Lottery['renamesn']:'SN码';
		$data=$Lottery;
		//1.活动过期,显示结束
		//4.显示奖项,说明,时间
		if ($Lottery['enddate'] < time()) {
			 $data['end'] = 1;
			 $data['endinfo'] = $Lottery['endinfo'];
			 $this->assign('lottery',$data);
			 $this->display();
			 exit();
		}
		// 1. 中过奖金	
		if ($record['islottery'] == 1) {				
			$data['end'] = 5;
			$data['sn']	 	 = $record['sn'];
			$data['uname']	 = $record['wecha_name'];
			$data['prize']	 = $record['prize'];
			$data['tel'] 	 = $record['phone'];
			if (!$record['phone']){
				$this->assign('isLotteryButNotInputTel',1);
			}else {
				if ($record['sendstutas']==0){
					$this->assign('isLotteryButNotSend',1);
				}else{
					$this->assign('isLotteryAndSend',1);
				}
			}
		}
		//抽取次数
		$data['On'] 		= 1;
		$data['token'] 		= $token;
		$data['wecha_id']	= $wecha_id;		
		$data['lid']		= $Lottery['id'];
		$data['rid']		= intval($record['id']);
		$data['usenums'] 	= $record['usenums'];
		$data['info']=str_replace('&lt;br&gt;','<br>',$data['info']);
		$data['endinfo']=str_replace('&lt;br&gt;','<br>',$data['endinfo']);
		$this->assign('lottery',$data);
		$record['id']=intval($record['id']);
		$this->assign('record',$record);
		//
		$this->display();
	}
	
	
	
	public function getajax(){	
		
		$token 		=	$this->_post('token');
		$wecha_id	=	$this->_post('wechat_id');
		$id 		=	$this->_post('id');
		$rid 		= 	$this->_post('rid');
		$Lottery=M('Lottery')->where(array('id'=>$id))->find();
		if ($Lottery['statdate']>time()){
			echo '{"error":1,"msg":"活动还没开始，感谢关注"}';
			exit;
		}
		if ($Lottery['enddate']<time()){
			echo '{"error":1,"msg":"活动已经结束，感谢关注"}';
			exit;
		}
		$data=$this->prizeHandle($token,$wecha_id,$Lottery);
		if ($data['end']==3){
			$sn	 	 = $data['sn'];
			$uname	 = $data['wecha_name'];
			$prize	 = $data['prize'];
			$tel 	 = $data['phone'];
			$msg = "您已经中过了";
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		}
		if ($data['end']==-1){
			$msg = $data['winprize'];
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		}
		if ($data['end']==-2){
			$msg = $data['winprize'];
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		}
		//
		if ($data['prizetype'] >= 1 && $data['prizetype'] <= 6) {
			$fruitNum=intval($data['prizetype'])-1;
			switch ($data['prizetype']){
				case 1:
					$prizeName='一等奖'.$Lottery['fist'];
					break;
				case 2:
					$prizeName='二等奖'.$Lottery['second'];
					break;
				case 3:
					$prizeName='三等奖'.$Lottery['third'];
					break;
				case 4:
					$prizeName='四等奖'.$Lottery['four'];
					break;
				case 5:
					$prizeName='五等奖'.$Lottery['five'];
					break;
				case 6:
					$prizeName='六等奖'.$Lottery['six'];
					break;
			}
			$arr=array('success'=>1,'data'=>array('left'=>$fruitNum,'middle'=>$fruitNum,'right'=>$fruitNum,'prize_type'=>$data['prizetype'],'sn'=>$data['sncode'],'prize'=>$prizeName),'msg'=>'','usenums'=>$data['usenums']);
		}else{
			$rand1=rand(0,8);
			$rand2=rand(0,8);
			if ($rand2==$rand1){
				$rand2=rand(0,8);
			}
			$rand3=rand(0,8);
			if ($rand2==$rand3){
				$rand3=rand(0,8);
			}
			$arr=array('success'=>1,'data'=>array('left'=>$rand1,'middle'=>$rand2,'right'=>$rand3,'prize_type'=>$Lottery['aginfo'],'sn'=>'','prize'=>2,'type'=>0),'msg'=>'');
		}
		echo json_encode($arr);
		exit();
	}
}
	
?>