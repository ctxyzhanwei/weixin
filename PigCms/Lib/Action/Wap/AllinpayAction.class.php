<?php
/*
 * Build	   By PigCms.XiaoHei 2014/09/19
 * Last Modify By PigCms.XiaoHei 2014/09/28
 */

class AllinpayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		if($_GET['wx_id']){
			$database_wxuser = D('Wxuser');
			$condition_wxuser['id'] = $_GET['wx_id'];
			$now_wxuser = $database_wxuser->field('`token`')->where($condition_wxuser)->find();
			$this->token = $now_wxuser['token'];
		}else{
			$this->token = $this->_get('token');
		}
		if($_GET['we_id']){
			$database_wecha_user = D('Wecha_user');
			$condition_wecha_user['id'] = $_GET['we_id'];
			$now_wecha_user = $database_wecha_user->field('`wecha_id`')->where($condition_wecha_user)->find();
			$this->wecha_id = $now_wecha_user['wecha_id'];
		}else{
			$this->wecha_id = $this->_get('wecha_id');
		}
		
		if (!$this->token){
			
		}
		
		//读取通联支付配置
		if(empty($_GET['platform'])){
			$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['allinpay'];
		}else{
			$payConfigInfo['merchantId'] = C('platform_allinpay_merchantId');
			$payConfigInfo['merchantKey'] = C('platform_allinpay_merchantKey');
			$this->payConfig = $payConfigInfo;
		}
	}
	public function pay(){
		//得到GET传参的订单名称，若为空则使用系统时间
		$orderName = $_GET['orderName'];
		if (!$orderName){
			$orderName = microtime();
		}
		
		//得到GET传参的系统唯一订单号
		$orderid = $_GET['orderid'];
		if (!$orderid){
			$orderid = $_GET['single_orderid']; //单个订单
		}
		
		//惯例，获取此订单号的信息
		$payHandel = new payHandle($this->token,$_GET['from'],'allinpay');
		$orderInfo = $payHandel->beforePay($orderid);
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');
		
		//判断价格是否为空。此做法可顺带查出是否是错误的订单号
		if(!$orderInfo['price'])exit('必须有价格才能支付');
		
		//为了应用 通联支付坑爹的要求（跳转地址长度为100个以内，通联数据库字段就是100的长度），，，将数据转换成ID。。。。
		//公众号
		$database_wxuser = D('Wxuser');
		$condition_wxuser['token'] = $this->token;
		$now_wxuser = $database_wxuser->field('`id` `wx_id`')->where($condition_wxuser)->find();
		if(empty($now_wxuser)){
			$this->error('查询数据异常！请重试。');
		}
		//微信用户
		$database_wecha_user = D('Wecha_user');
		$condition_wecha_user['wecha_id'] = $this->wecha_id;
		$now_wecha_user = $database_wecha_user->field('`id` `we_id`')->where($condition_wecha_user)->find();
		if(empty($now_wecha_user)){
			$this->error('查询数据异常！请重试。');
		}
		
		if(empty($_GET['platform'])){
			$return_url = C('site_url').'/index.php?g=Wap&m=Allinpay&a=r_u&wx_id='.$now_wxuser['wx_id'].'&we_id='.$now_wecha_user['we_id'].'&from='.$_GET['from'];
		}else{
			$return_url = C('site_url').'/index.php?g=Wap&m=Allinpay&a=r_u&wx_id='.$now_wxuser['wx_id'].'&we_id='.$now_wecha_user['we_id'].'&from='.$_GET['from'].'&pl=1';
		}
		
		//在此引入通联处理类，防止引入又被价格错误返回导致终止
		import('@.ORG.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('payUrl','http://ceshi.allinpay.com/mobilepayment/mobile/SaveMchtOrderServlet.action'); //提交地址
		$allinpayClass->setParameter('pickupUrl',$return_url); //跳转通知地址
		$allinpayClass->setParameter('receiveUrl',C('site_url').'/index.php?g=Wap&m=Allinpay&a=notify_url'); //异步通知地址
		$allinpayClass->setParameter('merchantId',$this->payConfig['merchantId']); //商户号
		$allinpayClass->setParameter('orderNo',$orderInfo['orderid']); //订单号
		$allinpayClass->setParameter('orderAmount',floatval($orderInfo['price'])*100); //订单金额(单位分)
		$allinpayClass->setParameter('orderDatetime',date('YmdHis',$_SERVER['REQUEST_TIME'])); //订单提交时间
		$allinpayClass->setParameter('productName',$orderName); //商品名称
		$allinpayClass->setParameter('payType',0); //支付方式
		$allinpayClass->setParameter('key',$this->payConfig['merchantKey']); //支付方式
		
		//开始跳转支付
		$allinpayClass->sendRequestForm();
	}
	public function r_u(){
		import('@.ORG.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$verify_result = $allinpayClass->verify_pay($this->payConfig['merchantKey']);
		if(!$verify_result['error']){
			$payHandel = new payHandle($this->token,$_GET['from'],'allinpay');
			$orderInfo = $payHandel->afterPay($verify_result['order_id'],$verify_result['paymentOrderId']);
			$from = $payHandel->getFrom();
			$this->redirect('/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&orderid='.$verify_result['order_id']);
		}else{
			$this->error($verify_result['msg']);
		}
	}
	public function notify_url(){
		echo 'SUCCESS';
	}
}
?>