<?php
class WeixinAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		
		parent::_initialize();

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
		//读取微信支付配置
		$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
		$payConfigInfo = unserialize($payConfig['info']);
		$this->payConfig = $payConfigInfo['weixin'];
		if(ACTION_NAME == 'pay' || ACTION_NAME == 'new_pay'){
			if(empty($this->payConfig['is_old'])){
				$this->new_pay();
				exit;
			}else{
				$this->pay();
				exit;
			}
		}

	}
	public function new_pay(){
		import('@.ORG.Weixinnewpay.WxPayPubHelper');

		//使用jsapi接口
		$jsApi = new JsApi_pub($this->payConfig['new_appid'],$this->payConfig['mchid'],$this->payConfig['key'],$this->payConfig['appsecret']);

		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode(urlencode($this->siteUrl.'/wxpay/index.php?g=Wap&m=Weixin&a=new_pay&price='.$_GET['price'].'&orderName='.urlencode($_GET['orderName']).'&single_orderid='.$_GET['single_orderid'].'&showwxpaytitle=1&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id']));
			Header("Location: $url"); exit();
		}

		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
		
		//获取订单信息
		$orderid=$_GET['single_orderid'];
		$payHandel=new payHandle($this->token,$_GET['from'],'weixin');
		$orderInfo=$payHandel->beforePay($orderid);
		$price=$orderInfo['price'];
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');

		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub($this->payConfig['new_appid'],$this->payConfig['mchid'],$this->payConfig['key'],$this->payConfig['appsecret']);	
		$unifiedOrder->setParameter("openid",$openid);//商品描述
		$unifiedOrder->setParameter("body",$orderid);//商品描述
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no",$orderid);//商户订单号 
		$unifiedOrder->setParameter("total_fee",$price*100);//总金额
		$unifiedOrder->setParameter("notify_url",C('site_url').'/wxpay/notice.php');//通知地址 
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		$unifiedOrder->setParameter("attach",'token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from']);//附加数据

		$prepay_id = $unifiedOrder->getPrepayId();

		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
		$this->assign('jsApiParameters',$jsApiParameters);
		
		$from = $_GET['from'];
		$from = $from ? $from : 'Groupon';
		$from = $from!='groupon' ? $from : 'Groupon';
		
		$returnUrl = $this->siteUrl.'/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&orderid='.$orderid;
		$this->assign('returnUrl',$returnUrl);
		//$this->display();
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" /><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black" /><meta name="format-detection" content="telephone=no" /><link href="/tpl/Wap/default/common/css/style/css/hotels.css" rel="stylesheet" type="text/css" /><title>微信支付</title><script language="javascript">function callpay(){WeixinJSBridge.invoke("getBrandWCPayRequest",'.$jsApiParameters.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg=="get_brand_wcpay_request:ok"){document.getElementById("payDom").style.display="none";document.getElementById("successDom").style.display="";setTimeout("window.location.href = \''.$returnUrl.'\'",2000);}else{if(res.err_msg == "get_brand_wcpay_request:cancel"){var err_msg = "您取消了支付";}else if(res.err_msg == "get_brand_wcpay_request:fail"){var err_msg = "支付失败<br/>错误信息："+res.err_desc;}else{var err_msg = res.err_msg +"<br/>"+res.err_desc;}document.getElementById("payDom").style.display="none";document.getElementById("failDom").style.display="";document.getElementById("failRt").innerHTML=err_msg;}});}</script></head><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td>'.floatval($_GET['price']).'元</td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div></div><div id="failDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="重新进行支付" /></div></div><div id="successDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></li></ul></div></body></html>';
	}
	public function pay(){
		import("@.ORG.Weixinpay.CommonUtil");
		import("@.ORG.Weixinpay.WxPayHelper");
		$commonUtil = new CommonUtil();
		//before
		$orderid=$_GET['single_orderid'];
		$payHandel=new payHandle($this->token,$_GET['from'],'weixin');
		$orderInfo=$payHandel->beforePay($orderid);
		$price=$orderInfo['price'];
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');
		
		$wxPayHelper = new WxPayHelper($this->payConfig['appid'],$this->payConfig['paysignkey'],$this->payConfig['partnerkey']);

		$wxPayHelper->setParameter("bank_type", "WX");
		$wxPayHelper->setParameter("body", $orderid);
		$wxPayHelper->setParameter("partner", $this->payConfig['partnerid']);
		$wxPayHelper->setParameter("out_trade_no",$orderid);
		$wxPayHelper->setParameter("total_fee", $price*100);
		$wxPayHelper->setParameter("fee_type", "1");
		$wxPayHelper->setParameter("notify_url", $this->siteUrl.'/index.php?g=Wap&m=Weixin&a=return_url&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from']);
		//$wxPayHelper->setParameter("notify_url", 'http://www.baidu.com');
		$wxPayHelper->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
		$wxPayHelper->setParameter("input_charset", "GBK");
		$url=$wxPayHelper->create_biz_package();
		$this->assign('url',$url);
		//
		$from=$_GET['from'];
		$from=$from?$from:'Groupon';
		$from=$from!='groupon'?$from:'Groupon';
		switch ($from){
			default:
			case 'Groupon':
				break;
		}
		$returnUrl='/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&orderid='.$orderid;
		$this->assign('returnUrl',$returnUrl);
		//$this->display('Weixin_pay.html');
		echo '<html><head><meta http-equiv="Content-Type"content="text/html; charset=UTF-8"><meta name="viewport"content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;"><meta name="apple-mobile-web-app-capable"content="yes"><meta name="apple-mobile-web-app-status-bar-style"content="black"><meta name="format-detection"content="telephone=no"><link href="/tpl/Wap/default/common/css/style/css/hotels.css"rel="stylesheet"type="text/css"><title>微信支付</title></head><script language="javascript">function callpay(){WeixinJSBridge.invoke(\'getBrandWCPayRequest\','.$url.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg==\'get_brand_wcpay_request:ok\'){document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'successDom\').style.display=\'\';setTimeout("window.location.href = \''.$returnUrl.'\'",2000);}else{document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'failDom\').style.display=\'\';document.getElementById(\'failRt\').innerHTML=res.err_code+\'|\'+res.err_desc+\'|\'+res.err_msg;}});}</script><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid#d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>金额</th><td>'.$price.'元</td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="点击进行微信支付"/></div></div><div id="failDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="重新进行支付"/></div></div><div id="successDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>您已支付成功，页面正在跳转...</th></tr></table><div id="failRt"></div></td></tr></table></li></ul></div></body></html>';
	}
	//新版微信支付同步数据处理
	public function new_return_url (){
		$out_trade_no = $this->_get('out_trade_no');
		if(intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			//after
			$payHandel=new payHandle($_GET['token'],$_GET['from'],'weixin');
			$orderInfo=$payHandel->afterPay($out_trade_no,$_GET['transaction_id'],$_GET['transaction_id']);
			exit('SUCCESS');
		}else {
			exit('付款失败');
		}
	}
	//同步数据处理
	public function return_url (){
		S('pay',$_GET);
		$out_trade_no = $this->_get('out_trade_no');
		if(intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			//after
			$payHandel=new payHandle($_GET['token'],$_GET['from'],'weixin');
			$orderInfo=$payHandel->afterPay($out_trade_no,$_GET['transaction_id'],$_GET['transaction_id']);
			exit('SUCCESS');
		}else {
			exit('付款失败');
		}
	}
	public function notify_url(){
		echo "success"; 
		eixt();
	}
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
			}
		}
	}
}
?>