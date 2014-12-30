<?php
class LotteryBaseAction extends WapAction{
	public function __construct(){
		parent::_initialize();
		$infoWhere['wecha_id']=$this->_get('wecha_id');
		$infoWhere['token']=$this->_get('token');
		$userInfoExist=M('Userinfo')->where($infoWhere)->find();
		
		if ($userInfoExist){
			unset($data['id']);
			//M('Userinfo')->where($infoWhere)->save($data);
		}else {
			M('Userinfo')->add($data);
		}
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $proArr
	 * @param unknown_type $total 预计参与人数
	 * @return unknown
	 */
	protected function get_rand($proArr,$total) {
		    $result = 7; 
		    $randNum = mt_rand(1, $total); 
		    foreach ($proArr as $k => $v) {
		    	
		    	if ($v['v']>0){//奖项存在或者奖项之外
		    		if ($randNum>$v['start']&&$randNum<=$v['end']){
		    			$result=$k;
		    			break;
		    		}
		    	}
		    }

		    return $result; 
	}
	public function prizeHandle($token,$wecha_id,$Lottery){
		$this->lottory_record_db=M('Lottery_record');
		$this->lottory_db=M('Lottery');
		$now=time();
		$id 		= $Lottery['id'];
		//
		$redata		= $this->lottory_record_db;
		$where 		= array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id);
		
		$record 	= $redata->where($where)->find();
		
		
		//0.判断是否有该用户记录,没有则记录
		if($record == Null){
			$record = $where;
			$record['usenums']=0;
		}
		//0. 判断优惠券是否领完了
			if ($Lottery['enddate'] < $now) { //过期
				$data['end'] = 2;
				$data['endinfo'] = $Lottery['endinfo'];
				$data['endimg']  = empty($Lottery['endpicurl']) ? 1 : $Lottery['endpicurl'];
			}else{
				//最多抽奖次数
				$LotteryedRecordWhere=array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'islottery'=>1);
				$prizedCount=$redata->where($LotteryedRecordWhere)->count();
				$maxPrizeCount=intval($Lottery['maxgetprizenum']);
				$maxPrizeCount=$maxPrizeCount?$maxPrizeCount:1;
				if (!$prizedCount<$maxPrizeCount){
					$data['end'] = 3;
					$data['msg'] = "您已经中过奖了，不能再领取了，谢谢";
					$data['wxname']=$record['wecha_name'];
					$data['wecha_name']=$record['wecha_name'];
					$data['sn']  = $record['sn'];
					$data['myprize'] 	= $this->getPrizeName($Lottery,$record['prize']);
					$data['prize'] 	= $record['prize'];
				}else {
					//是否已经够次数
					if ($record['usenums'] >= $Lottery['canrqnums'] ) {
						$data['end'] = -1;
						$data['prizetype'] = 4; //啥都没了
						$data['zjl']	  = 0;
						$data['usenums']  = $record['usenums'];
						$data['winprize']	   = "抽奖次数已经用完";
						//exit;
					}else{
						//当天的次数
						$year=date('Y',$now);
						$month=date('m',$now);
						$day=date('d',$now);
						$firstSecond=mktime(0,0,0,$month,$day,$year);
						$lastSecond=mktime(23,59,59,$month,$day,$year);
						$dayWhere='wecha_id=\''.$wecha_id.'\' AND lid='.$id.' AND time>'.$firstSecond.' AND time<'.$lastSecond;
						$thisDayNums=$redata->where($dayWhere)->count();
						$thisDayNums=intval($thisDayNums);
						if ($Lottery['daynums']&&$thisDayNums>=$Lottery['daynums']){
							$data['end'] = -2; //
							$data['zjl']	  = 0;
							$data['winprize']	   = "今天已经抽了".$thisDayNums."次了，没名额了，明天再来吧";
						}else {
							//3.没有领过,次数没达到,开始随机发放优惠券
							$this->lottory_db->where(array('id'=>$id))->setInc('joinnum');
							if($Lottery['fistlucknums']  == $Lottery['fistnums'] &&
							$Lottery['secondlucknums'] == $Lottery['secondnums'] &&
							$Lottery['thirdlucknums']  == $Lottery['thirdnums'] &&
							$Lottery['fourlucknums']  == $Lottery['fournums'] &&
							$Lottery['fivelucknums']  == $Lottery['fivenums'] &&
							$Lottery['sixlucknums']  == $Lottery['sixnums']
							){
								$prizeType=7;

							}else{
								//cheat
								$cheat=M('Lottery_cheat')->where(array('lid'=>$id,'wecha_id'=>$wecha_id))->find();
								if ($cheat){
									$prizeType=intval($cheat['prizetype']);
								}else {
									$prizeType=intval($this->get_prize($Lottery,$wecha_id));
								}
							}
							//排除没有设置的优惠券
							//奖品数 != 已经领取该奖品数 => 还有奖品

							switch ($prizeType){
								default:
									$data['prizetype'] = 7; //啥都没了
									$data['zjl']	  = 0;
									$data['winprize']	   = "谢谢参与";
									$isLottery=0;
									$data['sncode']    = '';
									break;
								case 1:
									$data['prizetype'] = 1;
									$data['sncode'] = uniqid();
									$data['winprize']	   = $Lottery['fist'];
									$data['zjl']	   = 1;
									$this->lottory_db->where(array('id'=>$id))->setInc('fistlucknums');
									$isLottery=1;
									break;
								case 2:
									$data['prizetype'] = 2;
									$data['winprize']  = $Lottery['second'];
									$data['zjl']	   = 1;
									$data['sncode']    = uniqid();
									$this->lottory_db->where(array('id'=>$id))->setInc('secondlucknums');
									$isLottery=1;
									break;
								case 3:
									$data['prizetype'] = 3;
									$data['winprize']	   = $Lottery['third'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$this->lottory_db->where(array('id'=>$id))->setInc('thirdlucknums');
									$isLottery=1;
									break;
								case 4:
									$data['prizetype'] = 4;
									$data['winprize']	   = $Lottery['four'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$this->lottory_db->where(array('id'=>$id))->setInc('fourlucknums');
									$isLottery=1;
									break;
								case 5:
									$data['prizetype'] = 5;
									$data['winprize']	   = $Lottery['five'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$this->lottory_db->where(array('id'=>$id))->setInc('fivelucknums');
									$isLottery=1;
									break;
								case 6:
									$data['prizetype'] = 6;
									$data['winprize']	   = $Lottery['six'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$this->lottory_db->where(array('id'=>$id))->setInc('sixlucknums');
									$isLottery=1;
									break;
							}
							//
							$snwhere=array('token'=>$token,'lid'=>intval($id),'wecha_id'=>'','islottery'=>0,'time'=>0,'prize'=>intval($data['prizetype']));
							$check=$this->lottory_record_db->where($snwhere)->find();
							if ($isLottery&&$check){
								$data['sncode']=$check['sn'];
								$this->lottory_record_db->where(array('sn'=>$data['sncode'],'lid'=>$id,'token'=>$token))->save(array('wecha_id'=>$wecha_id,'usenums'=>$record['usenums'],'islottery'=>$isLottery,'wecha_name'=>'','phone'=>'','sn'=>$data['sncode'],'time'=>$now,'sendtime'=>0));
							}else {
								$this->lottory_record_db->add(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'usenums'=>$record['usenums'],'islottery'=>$isLottery,'wecha_name'=>'','phone'=>'','sn'=>$data['sncode'],'time'=>$now,'prize'=>intval($data['prizetype']),'sendtime'=>0));
							}
							//
							$this->lottory_record_db->where(array('lid'=>$id,'wecha_id'=>$wecha_id))->setInc('usenums');
							$record['usenums']=intval($record['usenums'])+1;
						}
					
				}//以上没领过
			}
		}
		$record 	= $redata->where(array('wecha_id'=>$wecha_id,'islottery'=>1,'lid'=>$id))->find();
		if (!$record){
			$record 	= $redata->where(array('wecha_id'=>$wecha_id,'islottery'=>0,'lid'=>$id))->find();
		}
		$data['rid']		= intval($record['id']);
		$data['phone']		= $record['phone'];
		$data['sn']		= $record['sn'];
		$data['usenums']	= $record['usenums'];
		$data['sendtime']	= $record['sendtime'];
		
		return $data;
	}
	protected function get_prize($Lottery,$wecha_id){
		$id=intval($Lottery['id']);
		$lottery_db=M('Lottery');

		$joinNum=$Lottery['joinnum'];
		//
		$firstNum=intval($Lottery['fistnums'])-intval($Lottery['fistlucknums']);
		$secondNum=intval($Lottery['secondnums'])-intval($Lottery['secondlucknums']);
		$thirdNum=intval($Lottery['thirdnums'])-intval($Lottery['thirdlucknums']);
		$fourthNum=intval($Lottery['fournums'])-intval($Lottery['fourlucknums']);
		$fifthNum=intval($Lottery['fivenums'])-intval($Lottery['fivelucknums']);
		$sixthNum=intval($Lottery['sixnums'])-intval($Lottery['sixlucknums']);
		$multi=intval($Lottery['canrqnums']);//最多抽奖次数
		$prize_arr = array(
			'0' => array('id'=>1,'prize'=>'一等奖','v'=>$firstNum,'start'=>0,'end'=>$firstNum), 
			'1' => array('id'=>2,'prize'=>'二等奖','v'=>$secondNum,'start'=>$firstNum,'end'=>$firstNum+$secondNum), 
			'2' => array('id'=>3,'prize'=>'三等奖','v'=>$thirdNum,'start'=>$firstNum+$secondNum,'end'=>$firstNum+$secondNum+$thirdNum),
			'3' => array('id'=>4,'prize'=>'四等奖','v'=>$fourthNum,'start'=>$firstNum+$secondNum+$thirdNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum),
			'4' => array('id'=>5,'prize'=>'五等奖','v'=>$fifthNum,'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum),
			'5' => array('id'=>6,'prize'=>'六等奖','v'=>$sixthNum,'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum),
			'6' => array('id'=>7,'prize'=>'谢谢参与','v'=>(intval($Lottery['allpeople']))*$multi-($firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum),'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum,'end'=>intval($Lottery['allpeople'])*$multi)
		);
		//
		foreach ($prize_arr as $key => $val) { 
			$arr[$val['id']] = $val; 
		} 
		//-------------------------------	 
		//随机抽奖[如果预计活动的人数为1为各个奖项100%中奖]
		//-------------------------------	 
		if ($Lottery['allpeople'] == 1) {
	 
			if ($Lottery['fistlucknums'] <= $Lottery['fistnums']) {
				$prizetype = 1;	
			}else{
				$prizetype = 7;	
			}			
			 
		}else{
			$prizetype = $this->get_rand($arr,(intval($Lottery['allpeople'])*$multi)-$joinNum); 
		}
		 
		//$winprize = $prize_arr[$rid-1]['prize'];
		switch($prizetype){
			case 1:
					 
				if ($Lottery['fistlucknums'] >= $Lottery['fistnums']) {
					 $prizetype = ''; 
					 //$winprize = '谢谢参与'; 
				}else{
					 
					$prizetype = 1; 					
				    //$lottery_db->where(array('id'=>$id))->setInc('fistlucknums');
				}
			break;
				
			case 2:
				if ($Lottery['secondlucknums'] >= $Lottery['secondnums']) {
						$prizetype = ''; 
						//$winprize = '谢谢参与';
				}else{
					//判断是否设置了2等奖&&数量
					if(empty($Lottery['second']) && empty($Lottery['secondnums'])){
						$prizetype = ''; 
						//$winprize = '谢谢参与';
					}else{ //输出中了二等奖
						$prizetype = 2; 					
						//$lottery_db->where(array('id'=>$id))->setInc('secondlucknums');
					}	 
					
				}
				break;
							
			case 3:
				if ($Lottery['thirdlucknums'] >= $Lottery['thirdnums']) {
					 $prizetype = ''; 
					// $winprize = '谢谢参与';
				}else{
					if(empty($Lottery['third']) && empty($Lottery['thirdnums'])){
						 $prizetype = ''; 
						// $winprize = '谢谢参与';
					}else{
						$prizetype = 3; 					
						//$lottery_db->where(array('id'=>$id))->setInc('thirdlucknums');
					} 
					
				}
				break;
						
			case 4:
				if ($Lottery['fourlucknums'] >= $Lottery['fournums']) {
					  $prizetype =  ''; 
					// $winprize = '谢谢参与';
				}else{
					 if(empty($Lottery['four']) && empty($Lottery['fournums'])){
					   	$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					 }else{
					 	$prizetype = 4; 					
						//$lottery_db->where(array('id'=>$id))->setInc('fourlucknums');
					 }					
				}
			break;
			
			case 5:
				if ($Lottery['fivelucknums'] >= $Lottery['fivenums']) {
					 $prizetype =  ''; 
					 //$winprize = '谢谢参与';
				}else{
					if(empty($Lottery['five']) && empty($Lottery['fivenums'])){
						$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					}else{
						$prizetype = 5; 					
						//$lottery_db->where(array('id'=>$id))->setInc('fivelucknums');
					} 
				}
			break;
			
			case 6:
				if ($Lottery['sixlucknums'] >= $Lottery['sixnums']) {
					 $prizetype =  ''; 
					// $winprize = '谢谢参与';
				}else{
					 if(empty($Lottery['six']) && empty($Lottery['sixnums'])){
					 	$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					 }else{
					 	$prizetype = 6; 					
						//$lottery_db->where(array('id'=>$id))->setInc('sixlucknums');
					 }
					
				}
			break;
							
			default:
					$prizetype =  ''; 
					//$winprize = '谢谢参与';
					
					break;
		}
		if (intval($prizetype) && $prizetype<7 && $prizetype>0){
			//中奖了 - 模板消息通知

			  $model = new templateNews();

			  $tempKey = 'TM00785';
			  foreach ($prize_arr as $kk => $vv){
			  	if($vv['id'] == $prizetype){
			  		$result = '恭喜您在本次活动中获得了'.$prize;
			  	}
			  }
			 
			 $href = C('site_url').U('Wap/Lottery/index',array('token'=>$this->_get('token'),'wecha_id'=>$wecha_id,'id'=>$Lottery['id']));
			  $dataArr = array(
				'href' => '',
				'wecha_id' => $wecha_id,
				'first' => '恭喜您中奖了！',
				'program' => $Lottery['title'],
				'result' => $result,
				'remark' => '请您尽快来领奖！'
			);


			  $model->sendTempMsg($tempKey,$dataArr);



		}

		
		return $prizetype;
	}
	//中奖后填写信息
	public function add(){
		$this->lottory_record_db=M('Lottery_record');
		$this->lottory_db=M('Lottery');
		 if($_POST['action'] ==  'add' ){
			$lid = $this->_post('lid');
			$wechaid = $this->_post('wechaid');
			$data['phone'] = $this->_post('tel');
			$data['wecha_name'] = $this->_post('wxname');
			$rid=intval($this->_post('rid'));
			if (!$rid){
				$thisRecord=$this->lottory_record_db->where(array('lid'=>$lid,'wecha_id'=>$wechaid,'islottery'=>1))->find();
				$rid=$thisRecord['id'];
			}
			$rollback = $this->lottory_record_db->where(array('lid'=> $lid,
				'wecha_id'=>$wechaid,'id'=>$rid))->save($data);
				
			$record=$this->lottory_record_db->where(array('id'=>$rid))->find();
			echo'{"success":1,"msg":"恭喜！尊敬的<font color=red>'.$data['wecha_name'].'</font>请您保持手机通畅！你的领奖序号:<font color=red>'.$record['sn'].'</font>"}';
			exit;	
		}
	}
	public function exchange(){
		$this->lottory_record_db=M('Lottery_record');
		$this->lottory_db=M('Lottery');
		 if(IS_POST){
		 	$Lottery 	= $this->lottory_db->where(array('id'=>intval($_POST['id'])))->find();
		 	if ($Lottery['parssword']!=trim($this->_post('parssword'))){
		 		echo'{"success":0,"msg":"兑奖密码不正确"}';exit;
		 	}else {
		 		$data['sendtime']		= time(); 
		 		$data['sendstutas']	= 1;
		 		$this->lottory_record_db->where(array('id'=> intval($_POST['rid'])))->save($data);
		 		echo'{"success":1,"msg":"兑奖成功","changed":1}';
		 	}
		}
	}
	function getPrizeName($Lottery,$prizetype){
		switch ($prizetype){
			default:
				return $prizetype;
				break;
			case 1:
				return $Lottery['fist'];
				break;
			case 2:
				return $Lottery['second'];
				break;
			case 3:
				return $Lottery['third'];
				break;
			case 4:
				return $Lottery['four'];
				break;
			case 5:
				return $Lottery['five'];
				break;
			case 6:
				return $Lottery['six'];
				break;
		}
	}
}
	
?>