<?php
class LotteryBaseAction extends UserAction{
	public function index($type){
		if(session('gid')==1){
			//$this->error('vip0无法使用该活动,请充值后再使用',U('Home/Index/price'));
		}
		$group=$this->userGroup;
		$this->assign('group',$group);
		
		$this->assign('activitynum',intval($this->user['activitynum']));
		$where=array('token'=>session('token'),'type'=>$type);
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if ($id && $type == 6) {
			$where['id'] = $id;
		}
		$list=M('Lottery')->where($where)->select();
		
		if ($type == 8 || $type == 7 || $type = 9 ) {
			foreach($list as $key=>$val){
				$list[$key]['joinnum'] = M('Lottery_record')->where(array('token'=>session('token'),'type'=>$type,'lid'=>$val['id']))->count();
				
			}
		}
		
		$this->assign('count',M('Lottery')->where($where)->count());
		$this->assign('list',$list);
	}
	public function add($type){
		
		if (session('gid') == 1) {
                        $this->error('vip0无法使用', U('Home/Index/price'));
                }
		switch ($type){
			case 1:
				$activeType='Lottery';
				break;
			case 2:
				$activeType='Guajiang';
				break;
			case 3:
				$activeType='Coupon';
				break;
			case 4:
				$activeType='LuckyFruit';
				break;
			case 5:
				$activeType='GoldenEgg';
				break;
			case 6:
				$activeType='Research';
				break;
			case 7:
				$activeType='AppleGame';
				break;
			case 8:
				$activeType='Lovers';
				break;
			case 9:
				$activeType='Autumn';
				break;
				
		}
		if(IS_POST){
			$data=D('lottery');
			$_POST['statdate']=strtotime($this->_post('statdate'));
			$_POST['enddate']=strtotime($this->_post('enddate'));
			$_POST['token']=$this->token;
			$_POST['type']=$type;
			if($_POST['enddate'] < $_POST['statdate']){
				$this->error('结束时间不能小于开始时间');
			}else{
				if($data->create()!=false){
					if($id=$data->add()){
						$data1['pid']=$id;
						$data1['module']='Lottery';
						$data1['token']=$this->token;
						$data1['keyword']=$this->_post('keyword');
						if ($type != 6) {
							M('Keyword')->add($data1);
						}
						//$user=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
						if ($_POST['statdate']<time()){
							$this->_start($id);
						}
						//微调研
						$rid = isset($_POST['researchid']) ? intval($_POST['researchid']) : 0;
						if ($type == 6 && $rid) {
							M('Research')->where(array('id' => $rid))->save(array('lid' => $id));
							$this->success('活设置成功',U($activeType.'/index'));
						} else {
							$this->success('活动创建成功，请在列表中让活动“开始”',U($activeType.'/index'));
						}
					}else{
						$this->error('服务器繁忙,请稍候再试');
					}
				}else{
					$this->error($data->getError());
				}
			}
			
		}else{
			$now=time();
			$lottery['statdate']=$now;
			$lottery['enddate']=$now+30*24*3600;
			$this->assign('vo',$lottery);
			$this->display();
		}
	}
	public function edit($type){
		switch ($type){
			case 1:
				$activeType='Lottery';
				break;
			case 2:
				$activeType='Guajiang';
				break;
			case 3:
				$activeType='Coupon';
				break;
			case 4:
				$activeType='LuckyFruit';
				break;
			case 5:
				$activeType='GoldenEgg';
				break;
			case 6:
				$activeType='Research';
				break;
			case 7:
				$activeType='AppleGame';
				break;
			case 8:
				$activeType='Lovers';
				break;
			case 9:
				$activeType='Autumn';
				break;
		
		}
		if(IS_POST){
			$data=D('Lottery');
			$_POST['id']=$this->_get('id');
			$_POST['token']=session('token');
			$_POST['statdate']=strtotime($_POST['statdate']);
			$_POST['enddate']=strtotime($_POST['enddate']);
			if($_POST['enddate'] < $_POST['statdate']){
				$this->error('结束时间不能小于开始时间');
			}else{
				$where=array('id'=>$_POST['id'],'token'=>$_POST['token'],'type'=>$type);
				$check=$data->where($where)->find();
				//echo $data->getLastSql();
				//print_r($where);die;
				if($check==false)$this->error('非法操作');
					if($data->where($where)->save($_POST)){
						$data1['pid']=$_POST['id'];
						$data1['module']='Lottery';
						$data1['token']=session('token');
						$da['keyword']=$_POST['keyword'];
						if ($type != 6) {
							M('Keyword')->where($data1)->save($da);
						}
						$this->success('修改成功',U($activeType.'/index',array('token'=>session('token'))));
					}else{
						$this->error('操作失败');
					}
				
			}
		}else{
			$id=$this->_get('id');
			$where=array('id'=>$id,'token'=>session('token'));
			$data=M('Lottery');
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			$lottery=$data->where($where)->find();		
			$this->assign('vo',$lottery);
			$this->display('add');
		}
	
	}
	public function cheat(){
		$id=intval($_GET['id']);
		$where=array('id'=>$id,'token'=>$this->token);
		$thisLottery=M('Lottery')->where($where)->find();
		$this->assign('thisLottery',$thisLottery);
		$records=M('Lottery_cheat')->where(array('lid'=>$thisLottery['id']))->order('prizetype asc')->select();
		$this->assign('records',$records);
	}
	public function deleteCheat(){
		$record=M('Lottery_cheat')->where(array('id'=>intval($_GET['id'])))->find();
		$thisLottery=M('Lottery')->where(array('id'=>$record['lid']))->find();
		if ($thisLottery['token']!=$this->token){
			$this->error('非法操作');
		}else{
			M('Lottery_cheat')->where(array('id'=>intval($record['id'])))->delete();
			$this->success('删除成功');
		}
	}
	public function sn($type){
		$Lottery_record_db=M('Lottery_record');
		$id=intval($this->_get('id'));
		$data=M('Lottery')->where(array('token'=>$this->token,'id'=>$id,'type'=>$type))->find();
		$this->assign('thisLottery',$data);
		$recordWhere='token="'.$this->token.'" and lid='.$id.' and sn!=""';
		$record=$Lottery_record_db->where($recordWhere)->select();
		$recordcount=$data['fistlucknums']+$data['secondlucknums']+$data['thirdlucknums']+$data['fourlucknums']+$data['fivelucknums']+$data['sixlucknums'];
		$datacount=$data['fistnums']+$data['secondnums']+$data['thirdnums']+$data['fournums']+$data['fivenums']+$data['sixnums'];
		$sendCount=$Lottery_record_db->where(array('lid'=>$id,'sendstutas'=>1,'islottery'=>1))->count();
		$this->assign('sendCount',$sendCount);
		$this->assign('datacount',$datacount);
		$this->assign('recordcount',$recordcount);
		if ($record){
			$i=0;
			foreach ($record as $r){
				switch (intval($r['prizetype'])){
					default:
						$record[$i]['prizeName']=$r['prize'];
						break;
					case 1:
						$record[$i]['prizeName']=$data['fist'];
						break;
					case 2:
						$record[$i]['prizeName']=$data['second'];
						break;
					case 3:
						$record[$i]['prizeName']=$data['third'];
						break;
					case 4:
						$record[$i]['prizeName']=$data['four'];
						break;
					case 5:
						$record[$i]['prizeName']=$data['five'];
						break;
					case 6:
						$record[$i]['prizeName']=$data['six'];
						break;
					case 7:
						$activeType='AppleGame';
						break;
					case 8:
						$activeType='Lovers';
						break;
					case 9:
						$activeType='Autumn';
						break;
				
				}
				$i++;
			}
		}
		$this->assign('record',$record);
	}
	public function sendnull(){
		$id=intval($this->_get('id'));
		$where=array('id'=>$id,'token'=>$this->token);
		$data['sendtime'] = '';
		$data['sendstutas'] = 0;
		$back = M('Lottery_record')->where($where)->save($data);
		if($back==true){
			$this->success('已经取消');
		}else{
			$this->error('操作失败');
		}
	}
	public function sendprize(){
		$id=$this->_get('id');
		$where=array('id'=>$id,'token'=>$this->token);
		$data['sendtime'] = time();
		$data['sendstutas'] = 1;
		$back = M('Lottery_record')->where($where)->save($data);
		if($back==true){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	public function endLottery(){
		$id=intval($this->_get('id'));
		$where=array('id'=>$id,'token'=>$this->token);
		$data=M('Lottery')->where($where)->save(array('status'=>0));
		if($data!=false){
			M('Users')->where(array('uid'=>$this->user['id']))->setDec('activitynum');
			$this->success('活动已结束');
		}else{
			$this->error('服务器繁忙,请稍候再试');
		}
	}
	public function startLottery(){
		$id=$this->_get('id');
		$rt=$this->_start($id);
		if ($rt>0){
			$this->success('活动已经开始');
		}else {
			switch ($rt){
				case -1:
					$this->error('您的免费活动创建数已经全部使用完,请充值后再使用',U('Home/Index/price'));
					break;
				case -2:
					$this->error('服务器繁忙,请稍候再试');
					break;
			}
		}
	}
	public function _start($id){
		$error=0;
		$where=array('id'=>$id,'token'=>$this->token);
		$user=M('Users')->field('gid,activitynum')->where(array('id'=>session('uid')))->find();
		$group=$this->userGroup;
		if($user['activitynum']>=$group['activitynum']){
			$error=-1;
			return $error;
		}
		$data=M('Lottery')->where($where)->save(array('status'=>1));
		M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
		if($data!=false){
			return 1;
		}else{
			$error=-2;
		}
		return $error;
	}
	public function del(){
		$id=intval($this->_get('id'));
		$where=array('id'=>$id,'token'=>$this->token);
		$data=M('Lottery');
		$check=$data->where($where)->find();
		if($check==false)$this->error('非法操作');
		$back=$data->where($wehre)->delete();
		if($back==true){
			$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
			$type == 6 && M('Research')->where(array('lid' => $id,'token' => $this->token))->save(array('lid' => 0));
			M('Keyword')->where(array('pid'=>$id,'token'=>$this->token,'module'=>'Lottery'))->delete();
			$this->success('删除成功');
		}else{
			$this->error('操作失败');
		}
	}
	public function snDelete(){
		$db=M('Lottery_record');
		$rt=$db->where(array('id'=>intval($_GET['id'])))->find();
		if ($this->token!=$rt['token']){
			exit('no permission');
		}
		switch ($rt['prize']){
			case 1:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('fistlucknums');
				break;
			case 2:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('secondlucknums');
				break;
			case 3:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('thirdlucknums');
				break;
			case 4:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('fourlucknums');
				break;
			case 5:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('fivelucknums');
				break;
			case 6:
				M('Lottery')->where(array('id'=>$rt['lid']))->setDec('sixlucknums');
				break;
			case 7:
				$activeType='AppleGame';
				break;
			case 8:
				$activeType='Lovers';
				break;
			case 9:
				$activeType='Autumn';
				break;
				
		}
		$db->where(array('id'=>intval($_GET['id'])))->delete();
		$this->success('操作成功');
	}
	public function exportSN(){
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=huizong.xls");
		//   以下\t代表横向跨越一格，\n 代表跳到下一行，可以根据自己的要求，增加相应的输出相，要和循环中的对应哈
		//字段
		$letterArr=explode(',',strtoupper('a,b,c,d,e,f,g'));
		$arr=array(
		array('en'=>'sn','cn'=>'SN码(中奖号)'),
		array('en'=>'prize','cn'=>'奖项'),
		array('en'=>'sendstutas','cn'=>'是否已发奖品'),
		array('en'=>'sendtime','cn'=>'奖品发送时间'),
		array('en'=>'phone','cn'=>'中奖者手机号'),
		array('en'=>'wecha_name','cn'=>'中奖者微信码'),
		array('en'=>'time','cn'=>'中奖时间'),
		);
		$chengItem=array('piaomianjia','shuifei','yingshoujine','yingfupiaomianjia','yingfushuifei','yingfujine','dailishouru','fandian','jichangjianshefei','ranyoufei');

		$i=0;
		$fieldCount=count($arr);
		$s=0;
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		$db=M('Lottery_record');
		$sns=$db->where(array('lid'=>intval($_GET['id']),'islottery'=>1))->order('id ASC')->select();
		if ($sns){
			if ($sns[0]['token']!=$this->token){
				exit('no permission');
			}
			foreach ($sns as $sn){
				$j=0;
				foreach ($arr as $field){
					$fieldValue=$sn[$field['en']];
					switch ($field['en']){
						default:
							break;
						case 'time':
						case 'sendtime':
							if ($fieldValue){
								$fieldValue=date('Y-m-d H:i:s',$fieldValue);
							}else {
								$fieldValue='';
							}
							break;
						case 'wecha_name':
						case 'prize':
							$fieldValue=iconv('utf-8','gbk',$fieldValue);
							break;
					}
					if ($j<$fieldCount-1){
						echo $fieldValue."\t";
					}else {
						echo $fieldValue."\n";
					}
					$j++;
				}
				$i++;
			}
		}
		exit();
	}
}


?>