<?php
class Red_packetAction extends WapAction{
	public $packet_info;
	function _initialize(){
		parent::_initialize();
		$id     = $this->_get('id','intval');
		$where  = array('token'=>$this->token,'is_open'=>'1','id'=>$id);
		$this->packet_info  	= M('Red_packet')->where($where)->find();

		if(empty($this->packet_info)){
			$this->error('活动还没有开启');
		}

		$Userinfo = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
		if (empty($Userinfo)){
			$this->error('请先完善个人资料再参加活动',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>MODULE_NAME.'/index|id:'.$id)));
		}

		$this->assign('packet_info',$this->packet_info);
	}
	
	
	function index(){

		$this->assign('is_start',$this->is_start($this->packet_info['id']));
		$this->display();
	}
	
	
	function getPacket(){
		$result 	= array();
		$id 		= $this->_get('id','intval');
		
		if($this->is_start() == 1){
			$result['err'] 	= 1;
			$result['msg'] 	= '活动还没有开始，请耐心等待！';
			echo json_encode($result);
			exit;
		}
		
		if($this->is_start() == 2){
			$result['err'] 	= 2;
			$result['msg'] 	= '活动已经结束，敬请关注下一轮活动开始！';
			echo json_encode($result);
			exit;
		}
		
		$pwhere 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'packet_id'=>$id);
		$p_count 	= M('Red_packet_log')->where($pwhere)->count();
		
		/*奖品数量消耗完提示红包被领光*/
		if($p_count >= $this->packet_info['get_number']){
			$result['err'] 	= 3;
			$result['msg'] 	= '领取次数已经用光了！<br/>点击“我的红包”查看记录';
			echo json_encode($result);
			exit;
		}


		if(!$this->check_packet_type()){
				$result['err'] 	= 4;
				$result['msg'] 	= '红包已经领光啦，敬请关注下一轮活动开始！';
				echo json_encode($result);
				exit;
		}


		if($this->packet_info['packet_type'] == '1'){	
			$max 	= $this->packet_info['item_max'];//单个上限	
			if($this->packet_info['deci'] == 0){
				$prize 		= mt_rand(1,$max);
			}else if($this->packet_info['deci'] == 1){
				$prize 		= mt_rand(1,$max*10)/10;
			}else if($this->packet_info['deci'] == 2){
				//$prize 		= mt_rand(1,$max*100)/100;
				$prize 		= sprintf("%.2f", mt_rand(1,$max*100)/100);

			}
					
			$prize_name = $prize.'元';
		
		}else if($this->packet_info['packet_type'] == '2'){
			$unit 	= $this->packet_info['item_unit'];//面额
			$prize 		= $this->packet_info['item_unit'];
			$prize_name = $prize.'元';
		}

			$result['err'] 	= 0;
			$result['msg'] 	= '恭喜您抽中了'.$prize_name.',快去我的红包查看吧！';
			
			$log = array();
			$log['token'] 		= $this->token;
			$log['wecha_id'] 	= $this->wecha_id;
			$log['packet_id'] 	= $id;
			$log['prize_name'] 	= $prize_name;
			$log['worth'] 		= $prize;
			$log['add_time'] 	= time();
			$log['type'] 		= $this->packet_info['packet_type'];
			$md5 				= $this->wecha_id . $id . $prize . time();
			$log['code'] 		= substr(md5($md5),0,12); 

			$log_id 			= M('Red_packet_log')->add($log);
			if($log_id){
				echo json_encode($result);
				exit;	
			}else{
				$result['err'] 	= 5;
				$result['msg'] 	= '未知错误，请稍后再试';
				$result['type'] = $this->packet_info['packet_type'];
				$result['prize']= $prize;
				echo json_encode($result);
				exit;	
			}
		
	}
	

	/**
	 * 获取活动状态
	 */
	public function is_start($id){
		$now		= time();
		$is_start 	= 0;
		
		$where 		= array('token'=>$this->token,'packet_id'=>$id);
		$pcount 	= M('Red_packet_prize')->where($where)->sum('num');

		if($this->packet_info['start_time']>$now){
			$is_start 	= 1;
		}else if($this->packet_info['end_time']<$now){
			$is_start	= 2;
		}else if(!$this->check_packet_type()){
			$is_start	= 3;
		}


		return $is_start;
	}

	public function check_packet_type(){
		$flag 		= true;
		$where 		= array('token'=>$this->token,'packet_id'=>$this->packet_info['id']);
		
		$log		= M('Red_packet_log')->Distinct(true)->field('wecha_id')->where($where)->select();
		$pcount		= count($log);

		if($this->packet_info['people'] == 0 || $this->packet_info['people'] > $pcount){  //领取人数
		
			if($this->packet_info['packet_type'] == '1'){	
				$sum 	= $this->packet_info['item_sum'];//总额				
				$lsum	= M('Red_packet_log')->where($where)->sum('worth');
				
				if($sum <=$lsum){
					$flag 		= false;
				}
	
			}else if($this->packet_info['packet_type'] == '2'){
				$num 	= $this->packet_info['item_num'];//领取数量
				$lcount	= M('Red_packet_log')->where($where)->count();
				if($num <=$lcount){
					$flag 		= false;
				}
			}
		
			
		}else{
			$flag 		= false;
		}
		

		return $flag;
	}
/**********************/	
	public function rule(){
	
		$this->display();
	}	
	
/**********************/	
	public function my_packet(){
		$packet_id 	= $this->_get('id','intval');
		$wecha_id 	= $this->_get('wecha_id','trim');
		$where	 	= array('token'=>$this->token,'packet_id'=>$packet_id,'wecha_id'=>$wecha_id);
		$count	= M('Red_packet_log')->where($where)->count();
		//$Page   = new Page($count,10);
		//$list 	= M('Red_packet_log')->where($where)->order('is_reward asc,add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$list 	= M('Red_packet_log')->where($where)->order('is_reward asc,add_time desc')->select();

		$this->assign('list',$list);
		//$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function reward_forms(){
		$id 		= $this->_get('id','intval');
		$rid 		= $this->_get('rid','intval');
		$where 		= array('token'=>$this->token,'packet_id'=>$id,'id'=>$rid);
		$reward_info= M('Red_packet_log')->where($where)->find();

		$this->assign('reward_info',$reward_info);
		$this->display();
	}
	
	public function reward_sub(){
		$data 		= array();
		$result 	= array();
		$is_one		= $this->_get('is_one','intval');
		$ptype		= $this->_get('ptype','intval');
			
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'packet_id'=>$this->_get('id','intval'),'is_reward'=>'0');	
		if($is_one == 1){
			$where['id']	= $this->_get('log_id','intval');
			$price 	= M('Red_packet_log')->where($where)->getField('worth');
		}else{
			$price 	= M('Red_packet_log')->where($where)->sum('worth');
		}
		
		if(!M('Red_packet_log')->where($where)->find()){
			$result['err']	= 1;
			$result['info']	= '请不要重复兑换';
			echo json_encode($result);
			exit;
		}
	
		if($ptype == 1){
			$pwd 	= $this->_get('pwd','trim');
			if($this->packet_info['password'] != $pwd){
				$result['err']	= 2;
				$result['info']	= '兑换密码错误';
				echo json_encode($result);
				exit;
			}
		}

		if($ptype == 2){
			$cardid 	= $this->_get('cardid','intval');
			
			$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);				
			$record['orderid'] 		= $single_orderid;
			$record['ordername'] 	= '红包兑换';
			$record['paytype'] 		= 'PacketPay';
			$record['createtime'] 	= time();
			$record['paid'] 		= 1;
			$record['price'] 		= $price;
			$record['token'] 		= $this->token;
			$record['wecha_id'] 	= $this->wecha_id;
			$record['type'] 		= 1;
			$record['module'] 		= 'Red_packet';
			
			M('Member_card_pay_record')->add($record);	
			M('Userinfo')->where("wecha_id = '$this->wecha_id' AND token = '$this->token'")->setInc('balance',$price);
		}

		$log_id 	= M('Red_packet_log')->where($where)->getField('id',true);
		$data['token'] 		= $this->token;
		$data['wecha_id'] 	= $this->wecha_id;
		$data['price'] 		= $price;
		$data['packet_id']  = $this->_get('id','intval');
		$data['status']  	= 1;
		/*$data['sncode'] 	= $this->_get('sncode','trim');
		$data['wxname'] 	= $this->_get('wxname','trim'); */
		$data['type']  		= $ptype;
		$data['time'] 		= time();
		$data['log_id']		= join(',', $log_id);
		if($ptype == 1){
			$data['type_name'] 		= '线下兑换';
		}else if($ptype == 2){
			$data['type_name'] 		= '转入会员卡';
		}else if($ptype == 3){
			$data['type_name'] 		= '手机充值';
			$data['mobile']  		= $this->_get('mobile','trim');
			$data['status']  		= 0;
		}
		
		if(M('red_packet_exchange')->add($data)){
			M('Red_packet_log')->where($where)->save(array('is_reward'=>'2'));
		
			$result['err']	= 0;
			$result['info']	= '兑奖成功！';
		}
		//$log		= M('Red_packet_log')->where($cwhere)->field('id','code','is_reward')->find();
	
		echo json_encode($result);
/* 		if($log['is_reward'] != 0){
			$result['err']	= 1;
			$result['info']	= '你已经领过奖项啦，请不要重复兑奖！';
			echo json_encode($result);
			exit;
		}

		if($data['sncode'] == $log['code']){
			$result['err']	= 0;
			$result['info']	= '兑奖成功，等待确认！';
			if(M('Red_packet_reward')->add($data)){
				M('Red_packet_log')->where($cwhere)->save(array('is_reward'=>'1'));
			}
		}else{
			$result['err']	= 2;
			$result['info']	= '请输入有效的兑奖码!';
		}	 */
	}
	
	public function get_sum() {
		$id  		= $this->_get('id','intval');
		$log_id 	= $this->_get('log_id','intval');
		$is_one		= $this->_get('is_one');
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'packet_id'=>$id,'is_reward'=>'0');
		if($is_one == 1){
			$where['id']	= $log_id;
			$price 	= M('Red_packet_log')->where($where)->getField('prize_name');
		}else{
			$count 	= M('Red_packet_log')->where($where)->sum('worth');
			$price 	= $count.'元';
		}
		$result['err']	= 0;
		$result['price']= $price;
		echo json_encode($result);
	}
	
	public function get_card(){
		$where 	= $where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id);
		
		$card 	= M('Member_card_create')->where($where)->select();
		$str 	= '<option value="">请选择要转入的会员卡</option>';
		foreach ($card as $key=>$value){
			$card_name	= M('Member_card_set')->where(array('id'=>$value['cardid']))->getField('cardname');
			$str .= '<option value="'.$value['cardid'].'">'.$card_name.'</option>';
		}
		
		$result['err']	= 0;
		$result['option']= $str;
		echo json_encode($result);
	}
	
	
}