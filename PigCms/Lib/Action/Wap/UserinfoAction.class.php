<?php
class UserinfoAction extends WapAction{
	public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}
	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$cardid=intval($this->_get('cardid'));
		$conf = M('Member_card_custom')->where(array('token'=>$this->token))->find();
		if($conf == NULL){
			$conf = array(
				'wechaname' => 1,
				'tel' => 1,
				'truename' => 1,
				'qq' => 1,
				'paypass' => 1,
				'portrait' => 1,
				'sex' => 1,
				'bornyear' => 1,
				'bornmonth' => 1,
				'bornday' => 1
			);
		}
		$this->assign('conf',$conf);
		//$sql=D('Userinfo');
		$card = D('Member_card_create'); 
		$data['wecha_id']=$this->_get('wecha_id');
		$data['token']=$this->_get('token');
		//
		$cardInfoRow['wecha_id']=$this->_get('wecha_id');
		$cardInfoRow['token']=$this->_get('token');
		$cardInfoRow['cardid']=$this->_get('cardid');
		$cardinfo=$card->where($cardInfoRow)->find(); //是否领取过
		$this->assign('cardInfo',$cardinfo);
		//
		$member_card_set_db=M('Member_card_set');
		$thisCard=$member_card_set_db->where(array('token'=>$this->_get('token'),'id'=>intval($_GET['cardid'])))->find();
		if (!$thisCard&&$cardid){
			exit();
		}
		//dump($thisCard);
		$sql=D('Userinfo');
		$userinfo=$sql->where($data)->find();
		if($thisCard['memberinfo']!=false){
			$img=$thisCard['memberinfo'];			
		}else{
			$img='tpl/Wap/default/common/images/userinfo/fans.jpg';
		}
		$this->assign('cardnum',$cardinfo['number']);
		$this->assign('is_check',$thisCard['is_check']);
		$this->assign('homepic',$img);
		$this->assign('info',$userinfo);
		$this->assign('cardid',$cardid);
		//redirect url
		if (isset($_GET['redirect'])){
			$urlinfo=explode('|',$_GET['redirect']);
			$parmArr=explode(',',$urlinfo[1]);
			$parms=array('token'=>$cardInfoRow['token'],'wecha_id'=>$cardInfoRow['wecha_id']);
			if ($parmArr){
				foreach ($parmArr as $pa){
					$pas=explode(':',$pa);
					$parms[$pas[0]]=$pas[1];
				}
			}
			$redirectUrl=U($urlinfo[0],$parms);
			$this->assign('redirectUrl',$redirectUrl);
		}
		//
		if(IS_POST){
			//如果有post提交，说明是修改
			$data['wechaname'] = $this->_post('wechaname');
			$data['tel']       = $this->_post('tel');
			if(M('Member_card_custom')->where(array('token'=>$this->token))->getField('tel')){
				if(empty($data['tel'])){
					$this->error("手机号必填。");exit;
				}
			}

			
			 $this->_post('truename')? $data['truename'] = $this->_post('truename') : $data['truename'] = '';	
			 $this->_post('sex')? $data['sex'] = $this->_post('sex') : $data['sex'] = '';	
			 
			 $this->_post('qq')? $data['qq'] = $this->_post('qq') : $data['qq'] = '';
			 $this->_post('bornyear')? $data['bornyear'] = $this->_post('bornyear') : $data['bornyear'] = '';
			 $this->_post('bornmonth')? $data['bornmonth'] = $this->_post('bornmonth') : $data['bornmonth'] = '';
			 $this->_post('bornday')? $data['bornday'] = $this->_post('bornday') : $data['bornday'] = '';
			 $this->_post('portrait')? $data['portrait'] = $this->_post('portrait') : $data['portrait'] = '';
			 

			if($this->_post('paypass') != ''){
				$data['paypass'] = md5($this->_post('paypass'));
			}
			
 			//如果会员卡不为空[更新]
 			//写入两个表 Userinfo Member_card_create 
 			if ($cardid==0){
 				
 				$infoWhere=array();
 				$infoWhere['wecha_id']=$data['wecha_id'];
 				$infoWhere['token']=$data['token'];
 				$userInfoExist=M('Userinfo')->where($infoWhere)->find();
 				if ($userInfoExist){
 					M('Userinfo')->where($infoWhere)->save($data);
 				}else {
 					M('Userinfo')->add($data);
 				}
 				S('fans_'.$this->token.'_'.$this->wecha_id,NULL);
 				echo 1;exit;
 			}else {
 				if($cardinfo){ //如果Member_card_create 不为空，说明领过卡，但是可以修改会员信息
 					$update['wecha_id']=$data['wecha_id'];
 					$update['token']=$data['token'];
 					unset($data['wecha_id']);
 					unset($data['token']);
 					if(M('Userinfo')->where($update)->save($data)){
 						S('fans_'.$this->token.'_'.$this->wecha_id,NULL);
 						echo 1;exit;
 					}else{
 						echo 0;exit;
 					}
 				}else{
 					if($thisCard['is_check'] == '1'){
 						$code 	= $this->_post('code','trim,strtolower');
 						if($this->_check_code($code) == false){
 							echo 5;exit;
 						}
 					}
 			
 					Sms::sendSms($this->token,'有新的会员领了会员卡');
 					$card=M('Member_card_create')->field('id,number')->where("token='".$this->_get('token')."' and cardid=".intval($_POST['cardid'])." and wecha_id = ''")->order('id ASC')->find();
 					//
 					$userinfo_db=M('Userinfo');
 					$userInfos=$userinfo_db->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->select();
 					$userScore=0;
 					if ($userInfos){
 						$userScore=intval($userInfos[0]['total_score']);
 						$userInfo=$userInfos[0];
 					}
 					if(!$card){
 						echo 3;exit;
 					}else {
 						//
 						if (intval($thisCard['miniscore'])==0||$userScore>intval($thisCard['minscore'])){
 							M('Member_card_create')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->delete();
 							$card_up=M('Member_card_create')->where(array('id'=>$card['id']))->save(array('wecha_id'=>$this->_get('wecha_id')));
 							$data['getcardtime']=time();
 							if ($userinfo){
 								$update['wecha_id']=$data['wecha_id'];
 								$update['token']=$data['token'];
 								M('Userinfo')->where($update)->save($data);
 							}else {
 								$uid 	= M('Userinfo')->data($data)->add();
 								if($uid){
 									$uinfo = M('Userinfo')->where(array('token'=>$this->token,'id'=>$uid))->find();
 									$now 	= time();
 									$gwhere = array('token'=>$this->token,'cardid'=>$cardid,'is_open'=>'1','start'=>array('gt',$now),'end'=>array('lt',$now));
 									$gifts 	= M('Member_card_gifts')->where($where)->select();
 									foreach($gifts as $key=>$value){
 										if($value['type'] == "1"){
 											//赠积分
 											$arr=array();
 											$arr['itemid']	= 0;
 											$arr['token']	= $this->token;
 											$arr['wecha_id']= $this->wecha_id;
 											$arr['expense']	= 0;
 											$arr['time']	= $now;
 											$arr['cat']		= 3;
 											$arr['staffid']	= 0;
 											$arr['notes']	= '开卡赠送积分';
 											$arr['score']	= $value['item_value'];
 											
 											M('Member_card_use_record')->add($arr);
 											M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$uinfo['wecha_id']))->setInc('total_score',$arr['score']);
 										}else{
 											$data['token']		= $this->token;
 											$data['wecha_id']	= $uinfo['wecha_id'];
 											$data['coupon_id']	= $value['item_value'];
 											$data['is_use']		= '0';
 											$data['cardid']		= $cardid;
 											$data['add_time']	= $now;
 											//赠卷
 											if($value['item_attr'] == '1'){						
 												$data['coupon_type']	= '1';
 												M('Member_card_coupon_record')->add($data);
 											}else{
 												$data['coupon_type']	= '2';
 												M('Member_card_coupon_record')->add($data);
 											}
 										}
 									}
 								}								
 							}
 							S('fans_'.$this->token.'_'.$this->wecha_id,NULL);
 							echo 2;exit;
 						}else {
 							echo 4;exit;
 						}
 					}

 				} //post
 			}
		}else {
			$this->display();	
		}

		
    } //end function index
   	
    function get_code(){
    	$code_db 	= M('Sms_code');
    	$code 		= $this->_create_code();
    	$phone 		= $this->_post('phone');
    	$data['code'] 			= $code;
    	$data['token'] 			= $this->token;
    	$data['wecha_id'] 		= $this->wecha_id;
    	$data['create_time'] 	= time();
    	$data['action'] 		= 'userCard';
    	
    	$action 	= GROUP_NAME.'-'.MODULE_NAME.'-'.ACTION_NAME;
    	$result 	= array();
	
    	$where 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$action);
    	$last_info 	= $code_db->where($where)->order('create_time desc')->find();
    	if(($last_info['create_time']+60) > time()){
    		$result['error']	= -1;
    		$result['info']		= '请不要频繁获取效验码';
    	}else{
    		$code_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$action,'is_use'=>'0'))->save(array('is_use'=>'1'));	
    		if($code_db->add($data)){
    			$msg 	= '您的领卡效验码为：'.$code.'，验证码5分钟内有效，如非本人操作，请无视这条消息。';
    			$result['error']	= 0;
    			$result['info']		= '';
    			
    			Sms::sendSms($this->token,$msg,$phone);
    		}
    		
    	}
    	
    	echo json_encode($result);
    }
    
    /* @param  intval length 效验码长度
     * @param  string type  效验码类型  number数字, string字母, mingle数字、字母混合
     * @return string
     */
	function randString($length=4,$type="number"){
		$array = array(
			'number' => '0123456789',
			'string' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'mixed' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		);
		$string = $array[$type];
		$count = strlen($string)-1;
		$rand = '';
		for ($i = 0; $i < $length; $i++) {
			$rand .= $string[mt_rand(0, $count)];
		}
		return $rand;
	}
    /* @param  string code 效验码
     * @param  string time 过期时间
     * @return boolean
     */
    function _check_code($code,$time=300){
    	$code_db 	= M('Sms_code');
    	$action 	= 'userCard';
    	$last_time 	= time()-$time;
    	$where 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$action,'is_use'=>'0','create_time'=>array('gt',$last_time));
    	$true_code 	= $code_db->where($where)->getField('code');
    	
    	if(!empty($true_code) && $true_code == $code){
    		return true;
    	}else{
    		return false;
    	}
    }
} // end class UserinfoAction

?>