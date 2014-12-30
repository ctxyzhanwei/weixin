<?php
/*
 * 网银在线
 * Build	   By PigCms.XiaoHei 2014/09/24
 * Last Modify By PigCms.XiaoHei 2014/09/24
 */

class ChinabankAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
		
		}
		//读取网银在线配置
		if(empty($_GET['platform'])){
			$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['chinabank'];
		}else{
			$payConfigInfo['chinabank_account'] = C('platform_chinabank_account');
			$payConfigInfo['chinabank_key'] = C('platform_chinabank_key');
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
		$payHandel = new payHandle($this->token,$_GET['from'],'chinabank');
		$orderInfo = $payHandel->beforePay($orderid);
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');
		
		//判断价格是否为空。此做法可顺带查出是否是不存的订单号
		if(!$orderInfo['price']) exit('必须有价格才能支付！');
		
		//创建支付表单并显示
		$data_vid           = trim($this->payConfig['chinabank_account']);
        $data_orderid       = $orderid;
        $data_vamount       = $orderInfo['price'];
        $data_vmoneytype    = 'CNY';
        $data_vpaykey       = trim($this->payConfig['chinabank_key']);
        $data_vreturnurl    = C('site_url').'/index.php?g=Wap&m=Chinabank&a=return_url&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from'];

        $MD5KEY =$data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey;
        $MD5KEY = strtoupper(md5($MD5KEY));

        $def_url  = '<span style="display:none;">';
		$def_url .= '<form  method="post" action="https://pay3.chinabank.com.cn/PayGate" id="chinabanksubmit" name="chinabanksubmit">';
        $def_url .= "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
        $def_url .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
        $def_url .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
        $def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
        $def_url .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
        $def_url .= "<input type=HIDDEN name='remark1' value='".$remark1."'>";
        $def_url .= "<input type=submit class='button' value='去付款...'>";
        $def_url .= "</form>";
		$def_url .= "</span>";
		$def_url .= "<script>document.forms['chinabanksubmit'].submit();</script>";
		
		exit($def_url);
	
	}
	public function return_url(){
		$v_oid          = trim($_POST['v_oid']); //订单编号
        $v_pmode        = trim($_POST['v_pmode']); //支付方式
        $v_pstatus      = trim($_POST['v_pstatus']); //支付状态 20（表示支付成功）30（表示支付失败）
        $v_pstring      = trim($_POST['v_pstring']); //支付结果信息
        $v_amount       = trim($_POST['v_amount']); //订单总金额
        $v_moneytype    = trim($_POST['v_moneytype']); //币种
        $remark1        = trim($_POST['remark1' ]); //备注字段1
        $remark2        = trim($_POST['remark2' ]); //备注字段2
        $v_md5str       = trim($_POST['v_md5str' ]); //订单MD5校验码

        /**
         * 重新计算md5的值
         */
        $key            = $this->payConfig['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
		
		 /* 检查秘钥是否正确 */
        if ($v_md5str==$md5string){
            if ($v_pstatus == '20'){
				$order_id = $_POST['v_oid'];
				$payHandel = new payHandle($_GET['token'],$_GET['from'],'chinabank');
				$orderInfo = $payHandel->afterPay($order_id,$_POST['v_idx']);

				$from = $payHandel->getFrom();
				$this->redirect('/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&orderid='.$order_id);
            }
        }else{
           $this->error('支付时发生错误！请检查。');
        } 
	}
}
?>