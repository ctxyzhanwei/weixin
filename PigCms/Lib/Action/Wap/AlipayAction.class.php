<?php
class AlipayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $alipayConfig;
	public function __construct(){
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
			//
			$product_cart_model=M('product_cart');
			$out_trade_no = $this->_get('out_trade_no');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			if (!$order){
				$order=$product_cart_model->where(array('id'=>intval($this->_get('out_trade_no'))))->find();
			}
			$this->token=$order['token'];
		}
		//读取配置
		$alipay_config_db=M('Alipay_config');
		$this->alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
	}
	public function pay(){
		//参数数据
		$orderName=$_GET['orderName'];
		$orderid=$_GET['orderid'];
		if (!$orderid){
			$orderid=$_GET['single_orderid'];//单个订单
		}
		//before
		
		$payHandel=new payHandle($this->token,$_GET['from']);
		$orderInfo=$payHandel->beforePay($orderid);
		$price=$orderInfo['price'];
		if(!$price)exit('必须有价格才能支付');
		$from=isset($_GET['from'])?$_GET['from']:'shop';
		$alipayConfig = $this->alipayConfig;
		
		//反序列化得到支付的配置信息
		$now_pay_type = '';
		if($alipayConfig){
			$pay_setting = array();
			$config_info = unserialize($alipayConfig['info']);
			foreach($config_info as $key=>$value){
				if(is_array($value)){
					if($value['open']){
						$key = ($key == 'alipay') ? 'Alipaytype' : ucwords($key);
						$pay_setting[$key] = $value;
						$now_pay_type = $key;
					}
				}
			}
		}
		if(empty($alipayConfig) || empty($now_pay_type)){
			$now_pay_type = 'Alipaytype';
		}
		if(count($pay_setting) == 1){
			if($now_pay_type == 'Weixin'){
				header('Location:/wxpay/index.php?g=Wap&m=Weixin&a=pay&price='.$price.'&orderName='.$orderName.'&single_orderid='.$orderid.'&showwxpaytitle=1&from='.$from.'&token='.$this->token.'&wecha_id='.$this->wecha_id);
			}else if($now_pay_type != 'Platform'){
				header('Location:/index.php?g=Wap&m='.$now_pay_type.'&a=pay&price='.$price.'&orderName='.$orderName.'&single_orderid='.$orderid.'&from='.$from.'&token='.$this->token.'&wecha_id='.$this->wecha_id);
			}
		}
		$this->assign('price',$price);
		$this->assign('orderName',$orderName);
		$this->assign('orderid',$orderid);
		$this->assign('from',$from);
		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
		
		$this->assign('pay_setting',$pay_setting);
		$this->display();
	}
	public function go_pay(){
		if($_GET['pay_type'] == 'Weixin'){
			header('Location:/wxpay/index.php?g=Wap&m=Weixin&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&showwxpaytitle=1&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id']);
		}else{
			header('Location:/index.php?g=Wap&m='.$_GET['pay_type'].'&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']));
		}
	}
}
?>