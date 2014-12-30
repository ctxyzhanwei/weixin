<?php
class YeepayAction extends BaseAction {
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct() {
		$this->token = $this->_get('token');
		$this->wecha_id = $this->_get('wecha_id');
		if (!$this->token) {
			$this->token = $_SESSION['yeepay']['token'];
		}
		if (empty($_GET['platform'])) {
			$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['yeepay'];
		}else {
			$payConfigInfo['merchantaccount'] = C('platform_yeepay_merchantaccount');
			$payConfigInfo['merchantPrivateKey'] = C('platform_yeepay_merchantPrivateKey');
			$payConfigInfo['merchantPublicKey'] = C('platform_yeepay_merchantPublicKey');
			$payConfigInfo['yeepayPublicKey'] = C('platform_yeepay_yeepayPublicKey');
			$this->payConfig = $payConfigInfo;
		}
	}
	public function pay() {
		$orderName = $_GET['orderName'];
		if (!$orderName) {
			$orderName = microtime();
		}
		$orderid = $_GET['orderid'];
		if (!$orderid) {
			$orderid = $_GET['single_orderid'];
		}
		$payHandel = new payHandle($this->token, $_GET['from'], 'yeepay');
		$orderInfo = $payHandel->beforePay($orderid);
		if ($orderInfo['paid']) exit('您已经支付过此次订单！');
		if (!$orderInfo['price']) exit('必须有价格才能支付！');
		$database_yeepay_tmp = M('Yeepay_tmp');
		$data_yeepay_tmp['order_id'] = $orderid;
		$data_yeepay_tmp['token'] = $this->token;
		$data_yeepay_tmp['wecha_id'] = $this->wecha_id;
		$data_yeepay_tmp['from'] = $_GET['from'];
		$data_yeepay_tmp['time'] = $_SERVER['REQUEST_TIME'];
		if (!empty($_GET['platform'])) {
			$data_yeepay_tmp['platform'] = 1;
		}
		if (!$tmp_id = $database_yeepay_tmp->data($data_yeepay_tmp)->add()) {
			$this->error('下订单出现错误！请重试。');
		}
		import('@.ORG.Yeepay.yeepayMPay');
		$yeepay = new yeepayMPay($this->payConfig['merchantaccount'], $this->payConfig['merchantPublicKey'], $this->payConfig['merchantPrivateKey'], $this->payConfig['yeepayPublicKey']);
		$order_id = 'ORDER_' . $tmp_id;
		$transtime = time();
		$product_catalog = '1';
		$identity_id = $this->wecha_id;
		$identity_type = 0;
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$user_ua = $_SERVER['HTTP_USER_AGENT'];
		$callbackurl = C('site_url') . '/index.php?g=Wap&m=Yeepay&a=notify_url';
		$fcallbackurl = C('site_url') . '/wxpay/yeepay.php';
		$product_name = $orderName;
		$product_desc = $orderName;
		$other = '';
		$amount = floatval($orderInfo['price'] * 100);
		$url = $yeepay->webPay($order_id, $transtime, $amount, $product_catalog, $identity_id, $identity_type, $user_ip, $user_ua, $callbackurl, $fcallbackurl, $currency = 156, $product_name, $product_desc, $other);
		$_SESSION['yeepay']['token'] = $this->token;
		header('Location: ' . $url);
		exit;
	}
	public function return_url() {
		import('@.ORG.Yeepay.yeepayMPay');
		$yeepay = new yeepayMPay($this->payConfig['merchantaccount'], $this->payConfig['merchantPublicKey'], $this->payConfig['merchantPrivateKey'], $this->payConfig['yeepayPublicKey']);
		try {
			$data = str_replace(' ', '+', $_GET['data']);
			$encryptkey = str_replace(' ', '+', $_GET['encryptkey']);
			$return = $yeepay->callback($data, $encryptkey);
			$database_yeepay_tmp = M('Yeepay_tmp');
			$condition_yeepay_tmp['id'] = str_replace('ORDER_', '', $return['orderid']);
			$yeepay_tmp = $database_yeepay_tmp->field(true)->where($condition_yeepay_tmp)->find();
			$_GET['platform'] = $yeepay_tmp['platform'];
			$payHandel = new payHandle($yeepay_tmp['token'], $yeepay_tmp['from'], 'yeepay');
			$orderInfo = $payHandel->afterPay($yeepay_tmp['order_id'], $return['yborderid']);
			$from = $payHandel->getFrom();
			unset($_SESSION['yeepay']);
			$this->redirect('/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&orderid=' . $yeepay_tmp['order_id']);
		}
		catch (yeepayMPayException $e) {
			$this->error('支付时发生错误！错误提示：' . $e->GetMessage() . '；错误代码：' . $e->Getcode());
		}
	}
}

?>