<?php
class TenpayAction extends UserAction{
	public function index(){
		if (C('agent_version')){
			$group=M('User_group')->field('id,name,price')->where('price>0 AND agentid='.$this->agentid)->select();
		}else {
			$group=M('User_group')->field('id,name,price')->where('price>0')->select();
		}
		$user=M('User_group')->field('price')->where(array('id'=>session('gid')))->find();
		$this->assign('group',$group);
		$this->assign('user',$user);
		$this->display();
	}
	public function recharge(){		
		////////////////////////////////////
		//参数数据
		$total_fee =floatval($_POST['price']);
		$total_fee1=$total_fee;
		$total_fee =floatval($total_fee)*100;
		$body = '会员充值';
		$orderName=$body;
		$out_trade_no = $this->user['id'].'_'.time();

		$notify_url = C('site_url').U('Tenpay/notify_url');
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = C('site_url').U('Tenpay/charge_return');
		//
		if(!$total_fee)exit('必须有价格才能支付');
		
		import("@.ORG.TenpayComputer.RequestHandler");
		
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$key=trim(C('tenpay_partnerkey'));
		$partner=trim(C('tenpay_partnerid'));
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		$reqHandler->setParameter("partner", $partner);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee);  //总金额
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", '财付通在线支付');
		$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
		$reqHandler->setParameter("fee_type", "1");               //币种
		$reqHandler->setParameter("subject",'member recharge');          //商品名称，（中介交易时必填）

		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

		//业务可选参数
		$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", "");        	  //商品费用
		$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
		$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
		$reqHandler->setParameter("time_expire", "");             //订单失效时间
		$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		$reqHandler->setParameter("goods_tag", "");               //商品标记
		$reqHandler->setParameter("trade_mode",1);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter("transport_desc","");              //物流说明
		$reqHandler->setParameter("trans_type","1");              //交易类型
		$reqHandler->setParameter("agentid","");                  //平台ID
		$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter("seller_id","");                //卖家的商户号



		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();

		//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		/**/
		$debugInfo = $reqHandler->getDebugInfo();
		//
		$data=M('Indent')->data(			
		array('uid'=>session('uid'),'month'=>0,'title'=>$body,'uname'=>'','gid'=>0,'create_time'=>time(),'indent_id'=>$out_trade_no,'price'=>$total_fee1))->add();
		//
		header('Location:'.$reqUrl);
	}
	public function charge_return (){		
		
		import("@.ORG.TenpayComputer.ResponseHandler");
		$resHandler = new ResponseHandler();
		$key=trim(C('tenpay_partnerkey'));
		$resHandler->setKey($key);
		$out_trade_no = $this->_get('out_trade_no');
		//if($resHandler->isTenpaySign()) {
		$notify_id = $resHandler->getParameter("notify_id");
		//商户订单号
		$out_trade_no = $resHandler->getParameter("out_trade_no");
		//财付通订单号
		$transaction_id = $resHandler->getParameter("transaction_id");
		//金额,以分为单位
		$total_fee = $resHandler->getParameter("total_fee");
		//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
		$discount = $resHandler->getParameter("discount");
		//支付结果
		$trade_state = $resHandler->getParameter("trade_state");
		//交易模式,1即时到账
		$trade_mode = $resHandler->getParameter("trade_mode");

		if("0" == $trade_state) {
			$total_fee=$total_fee/100;
			$indent=M('Indent')->where(array('indent_id'=>$out_trade_no))->find();
			if($indent!=false){
				if($indent['status']==1){$this->error('该订单已经处理过,请勿重复操作');}
				M('Users')->where(array('id'=>$indent['uid']))->setInc('money',intval($indent['price']));
				M('Users')->where(array('id'=>$indent['uid']))->setInc('moneybalance',intval($indent['price']));
				$back=M('Indent')->where(array('id'=>$indent['id']))->setField('status',1);
				if($back!=false){
					$this->success('充值成功',U('User/Index/index'));
				}else{
					$this->error('充值失败,请在线客服,为您处理',U('User/Index/index'));
				}
			}else{
				$this->error('订单不存在',U('User/Index/index'));

			}
		}else {
			exit('付款失败');
		}
	}
	public function notify_url(){
		exit('success');
				
	}
	
}



?>