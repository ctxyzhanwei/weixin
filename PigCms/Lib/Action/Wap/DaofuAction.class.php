<?php
/*
 * 货到付款
 * Build	   By PigCms.XiaoHei 2014/09/19
 * Last Modify By PigCms.XiaoHei 2014/09/19
 */

class DaofuAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
			
		}
		//读取货到付款配置
		$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
		$payConfigInfo = unserialize($payConfig['info']);
		$this->payConfig=$payConfigInfo['daofu'];
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
		$payHandel = new payHandle($this->token,$_GET['from'],'daofu');
		$orderInfo = $payHandel->beforePay($orderid);
		
		//判断价格是否为空。此做法可顺带查出是否是错误的订单号
		if(!$orderInfo['price'])exit('必须有价格才能支付');
		
		$orderInfo=$payHandel->afterPay($orderid,'');
		
		$from=$payHandel->getFrom();
		
		$this->redirect('/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&orderid='.$orderid);
	}
}
?>