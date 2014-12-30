<?php
class GuajiangAction extends LotteryBaseAction{
	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
	 
		$token	  =  $this->_get('token');
		$wecha_id = $this->_get('wecha_id');
		if (!$wecha_id){
			//$wecha_id='null';
		}
		$id 	  = $this->_get('id');
		$redata	  = M('Lottery_record');
		$where	  = array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id);
		$record 	= $redata->where(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'islottery'=>1))->find();
		if (!$record){
			$record 	= $redata->where($where)->order('id DESC')->find();
		}
		if (!$record){
			$record['id']=0;
			$record['lid']=$id;
		}
		$record['wecha_id']=$wecha_id;
		$this->assign('record',$record);
		$Lottery =	M('Lottery')->where(array('id'=>$id,'token'=>$token,'type'=>2))->find(); 
		if ($this->wecha_id&&!$this->fans&&$Lottery['needreg']){
			$this->error('请先完善个人资料再参加活动',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>MODULE_NAME.'/index|id:'.intval($id))));
		}
		
		$Lottery['renametel']=$Lottery['renametel']?$Lottery['renametel']:'手机号';
		$Lottery['renamesn']=$Lottery['renamesn']?$Lottery['renamesn']:'SN码';
		$data = $Lottery;
		$data['info']=nl2br($data['info']);
		$data['endinfo']=nl2br($data['endinfo']);
		$data['info']=str_replace('&lt;br&gt;','<br>',$data['info']);
		$data['endinfo']=str_replace('&lt;br&gt;','<br>',$data['endinfo']);
		$this->assign('Guajiang',$data);
		//
		if ($Lottery['statdate']>time()){
			$data['usenums'] = 1;
			$data['winprize']	= '还没开始';
		}else {
			if ($this->wecha_id){
			$return=$this->prizeHandle($token,$wecha_id,$Lottery);
			}
			//
			if ($return['end']==2){
				$data['usenums'] = 3;
				$data['endinfo'] = $Lottery['endinfo'];
				$this->assign('Guajiang',$data);
				$this->display();
				exit();
			}
			if ($return['end']==3){//中过奖了，抽奖次数已经用完
				$data['usenums'] = 2;
				$data['sncode']	 = $record['sn'];
				$data['uname']	 = $record['wecha_name'];
				$data['winprize']	= $this->getPrizeName($Lottery,$record['prize']);
			}else {
				if ($return['end']==-1){//抽奖次数已经用完
					//次数已经达到限定
					$data['usenums'] = 1;
					$data['winprize']	= '抽奖次数已用完';
				}else if ($return['end']==-2){//
					//次数已经达到限定
					$data['usenums'] = 1;
					$data['winprize']	= '当天次数已用完';
				}else{
					$data['zjl'] 		= $return['zjl'];
					$data['wecha_id']	= $wecha_id;
					$data['lid']		= $id;
					$data['winprize']	= $this->getPrizeName($Lottery,$return['winprize']);
				}
			}
		}
		$data['usecout'] 	= intval($record['usenums']);
		

		$this->assign('Guajiang',$data);

		$prizeStr='<p>一等奖: '.$Lottery['fist'];
		if ($Lottery['displayjpnums']){
			$prizeStr.='奖品数量:'.$Lottery['fistnums'];
		}
		$prizeStr.='</p>';
		if ($Lottery['second']){
			$prizeStr.='<p>二等奖: '.$Lottery['second'];
			if ($Lottery['displayjpnums']){
				$prizeStr.='奖品数量:'.$Lottery['secondnums'];
			}
			$prizeStr.='</p>';
		}
		if ($Lottery['third']){
			$prizeStr.='<p>三等奖: '.$Lottery['third'];
			if ($Lottery['displayjpnums']){
				$prizeStr.='奖品数量:'.$Lottery['thirdnums'];
			}
			$prizeStr.='</p>';
		}
		$this->assign('prizeStr',$prizeStr);
		$this->display();

	}
}
?>