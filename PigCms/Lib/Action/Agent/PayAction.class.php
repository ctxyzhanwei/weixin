<?php
class PayAction extends AgentAction
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function recharge()
    {
        $amount = intval($this->_post('amount'));
        if (!$amount) {
            $amount = intval($this->_get('amount'));
        }
        $buyDiscount = 0;
        if (isset($_GET['discountpriceid'])) {
            $thisPrice = M('Agent_price')->where(array('id' => intval($_GET['discountpriceid'])))->find();
            $buyDiscount = 1;
            $amount = $thisPrice['price'];
        }
        if (!$amount) {
            $this->error('请填写金额');
        }
        import('@.ORG.Alipay.AlipaySubmit');
        $payment_type = '1';
        $notify_url = C('site_url') . U('Agent/Pay/notify');
        $return_url = C('site_url') . U('Agent/Pay/return_url', array('discountpriceid' => intval($_GET['discountpriceid'])));
        $seller_email = trim(C('alipay_name'));
        $out_trade_no = $this->thisAgent['id'] . '_' . time();
        if ($buyDiscount) {
            $subject = '购买优惠套餐' . $thisPrice['name'] . '（ID：' . $thisPrice['id'] . '）';
        } else {
            $subject = '充值' . $amount . '元';
        }
        $total_fee = $amount;
        $body = $subject;
        $show_url = C('site_url') . U('Agent/Basic/expenseRecords');
        $anti_phishing_key = '';
        $exter_invoke_ip = '';
        $body = $subject;
        $data = M('Agent_expenserecords')->data(array('agentid' => $this->thisAgent['id'], 'des' => $subject, 'time' => time(), 'orderid' => $out_trade_no, 'amount' => $total_fee))->add();
        $show_url = rtrim(C('site_url'), '/');
        $parameter = array('service' => 'create_direct_pay_by_user', 'partner' => trim(C('alipay_pid')), 'payment_type' => $payment_type, 'notify_url' => $notify_url, 'return_url' => $return_url, 'seller_email' => $seller_email, 'out_trade_no' => $out_trade_no, 'subject' => $subject, 'total_fee' => $total_fee, 'body' => $body, 'show_url' => $show_url, 'anti_phishing_key' => $anti_phishing_key, 'exter_invoke_ip' => $exter_invoke_ip, '_input_charset' => trim(strtolower('utf-8')));
        $alipaySubmit = new AlipaySubmit($this->setconfig());
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认支付');
        echo $html_text;
    }
    public function setconfig()
    {
        $alipay_config['partner'] = trim(C('alipay_pid'));
        $alipay_config['key'] = trim(C('alipay_key'));
        $alipay_config['sign_type'] = strtoupper('MD5');
        $alipay_config['input_charset'] = strtolower('utf-8');
        $alipay_config['cacert'] = getcwd() . '\\pigcms\\Lib\\ORG\\Alipay\\cacert.pem';
        $alipay_config['transport'] = 'http';
        return $alipay_config;
    }
    public function return_url()
    {
        import('@.ORG.Alipay.AlipayNotify');
        $alipayNotify = new AlipayNotify($this->setconfig());
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {
            $out_trade_no = $this->_get('out_trade_no');
            $trade_no = $this->_get('trade_no');
            $trade_status = $this->_get('trade_status');
            if ($this->_get('trade_status') == 'TRADE_FINISHED' || $this->_get('trade_status') == 'TRADE_SUCCESS') {
                $indent = M('Indent')->where(array('orderid' => $out_trade_no))->find();
                if ($indent != false) {
                    if ($indent['status'] == 1) {
                        $this->error('该订单已经处理过,请勿重复操作');
                    }
                    if (isset($_GET['discountpriceid'])) {
                        $thisPrice = M('Agent_price')->where(array('id' => intval($_GET['discountpriceid'])))->find();
                        $indent['amount'] = $thisPrice['maxaccount'] * $this->thisAgent['wxacountprice'];
                    }
                    M('Agent')->where(array('id' => $indent['agentid']))->setInc('money', intval($indent['amount']));
                    M('Agent')->where(array('id' => $indent['agentid']))->setInc('moneybalance', intval($indent['amount']));
                    $back = M('Agent_expenserecords')->where(array('id' => $indent['id']))->setField('status', 1);
                    if ($back != false) {
                        $this->success('充值成功', U('Agent/Basic/expenseRecords'));
                    } else {
                        $this->error('充值失败,请在线客服,为您处理', U('Agent/Basic/expenseRecords'));
                    }
                } else {
                    $this->error('订单不存在', U('Agent/Basic/expenseRecords'));
                }
            } else {
                $this->error('充值失败，请联系官方客户');
            }
        } else {
            $this->error('不存在的订单');
        }
    }
    public function notify()
    {
        import('@.ORG.Alipay.alipay_notify');
        $alipayNotify = new AlipayNotify($this->setconfig());
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
    }
}
?>