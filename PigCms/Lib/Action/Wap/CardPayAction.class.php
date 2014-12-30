<?php

class CardPayAction extends BaseAction{

	public $token;
	public $wecha_id;

	public function __construct(){
		$this->token = $this->_request('token');
		$this->wecha_id = $this->_request('wecha_id');
		
	}
//调用地址 Wap/CardPay/pay ;参数：from price single_orderid orderName token wecha_id redirect（Moudle/Action|param1:value1,param2:value2）

		public function pay(){
			$from = $this->_request('from');
			$single_orderid = $this->_request('single_orderid');
			$orderName = $this->_request('orderName');
			$redirect = $this->_request('redirect');
			$payHandel=new payHandle($this->token,$from,'CardPay');
			$orderInfo=$payHandel->beforePay($single_orderid);
			$price=$orderInfo['price'];
			
			if($price <= 0) $this->error('请输入有效金额');
		
			$paypass = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->getField('paypass');
		/*未设密码
			if($paypass == NULL){
					$this->error('请先设置支付密码',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>'CardPay/pay|token:'.$this->token.',wecha_id:'.$this->wecha_id.',from:'.$from.',price:'.$price.',single_orderid:'.$single_orderid.',orderName:'.$orderName.',redirect:'.$redirect)));
				}

			*/	
		//已输入密码
			if($_POST['pass'] != false){
				if(md5($_POST['pass']) == $paypass){

					if($redirect){
						$this->gotopay($single_orderid,$price,$from,$orderName,$redirect);
					}else{
						$this->gotopay($single_orderid,$price,$from,$orderName);
					}
					

				}else{
					$this->error('密码错误');
				}	
			}
			
			$this->display();
		}
		
		
		private function gotopay($orderid,$price,$from,$orderName,$redirect=NULL){
			
				$userinfo = M('Userinfo');
				$payrecord = M('Member_card_pay_record');
				$create = M('Member_card_create');
				$exchange = M('Member_card_exchange');

				$cardid = $create->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->getField('cardid');
				$cardid = (int)$cardid;
				$reward = $exchange->where(array('token'=>$this->token,'cardid'=>$cardid))->getField('reward');
				$reward = (int)$reward;
				$uinfo = $userinfo->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->field('id,balance,expensetotal,total_score')->find();

				if(!$orderid)$this->error('请传入订单号');
				if($uinfo['balance'] < $price){
					$this->error('余额不足');
				}
				
				if($payrecord->where("orderid = '$orderid'")->getField('id')){
					$flag1 = true;
				}else{
					$record['orderid'] = $orderid;
					$record['ordername'] = $orderName;
					$record['paytype'] = 'CardPay';
					$record['createtime'] = time();
					$record['paid'] = 0;
					$record['price'] = $price;
					$record['token'] = $this->token;
					$record['wecha_id'] = $this->wecha_id;
					$record['type'] = 0;
					$flag1 = $payrecord->add($record);
				}
					$udata['balance'] = $uinfo['balance'] - $price;
					
					$flag2 = $userinfo->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($udata);
					
					$payHandel=new payHandle($this->token,$from,'CardPay');
					
					$payHandel->afterPay($orderid);

	
						if($flag1 && $flag2){
							
							$payrecord->where(array('orderid'=>$orderid,'token'=>$this->token))->setField('paid',1);
									if (isset($redirect)){
									$urlinfo=explode('|',$_GET['redirect']);
									$parmArr=explode(',',$urlinfo[1]);
									$parms=array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paytype'=>'CardPay','orderid'=>$orderid);
									if ($parmArr){
										foreach ($parmArr as $pa){
											$pas=explode(':',$pa);
											$parms[$pas[0]]=$pas[1];
										}
									}
									$this->redirect(U($urlinfo[0],$parms));
									
								}else{
									$this->redirect(U("$from/payReturn",array('orderid'=>$orderid,'token'=>$this->token,'wecha_id'=>$this->wecha_id,'paytype'=>'CardPay')));
								}
							
						}else{
						
							$this->error('支付失败');
						}
		
		}

}
?>