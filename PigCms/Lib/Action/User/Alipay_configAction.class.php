<?php
class Alipay_configAction extends UserAction{
	public $pay_config_db;
	public function _initialize() {
		parent::_initialize();
		$this->pay_config_db=M('Alipay_config');
		if (!$this->token){
			exit();
		}
	}
	public function index(){
		//找出支付的配置文件
		$where['token'] = $this->token;
		$config = $this->pay_config_db->where($where)->find();
		if(IS_POST){
			$data_alipay_config['token'] = $this->token;
			$data_alipay_config['name'] = strval(trim($_POST['alipay']['name']));
			$data_alipay_config['pid'] = strval(trim($_POST['alipay']['pid']));
			$data_alipay_config['key'] = strval(trim($_POST['alipay']['key']));
			$data_alipay_config['partnerkey'] = strval(trim($_POST['tenpayComputer']['partnerkey']));
			$data_alipay_config['appsecret'] = strval(trim($_POST['weixin']['appsecret']));
			$data_alipay_config['appid'] = strval(trim($_POST['weixin']['appid']));
			//$data_alipay_config['paysignkey'] = strval(trim($_POST['weixin']['key']));
			$data_alipay_config['partnerid'] = strval(trim($_POST['tenpayComputer']['partnerid']));
			$data_alipay_config['mchid'] = strval(trim($_POST['weixin']['mchid']));
			$data_alipay_config['open'] = strval(trim($_POST['is_open']));
			
			
			

			unset($_POST[C('TOKEN_NAME')],$_POST['token']);
			//为了前台查询快速不用多次分析配置的值，将前台的值序列化了。
			$data_alipay_config['info'] = serialize($_POST); 	//因TP在系统变量中已经自动处理了表单中不安全的因素，故而不进行任何处理。
			if($config){
				$this->pay_config_db->where($where)->data($data_alipay_config)->save();
			}else{
				$this->pay_config_db->where($where)->data($data_alipay_config)->add();
			}
			
			$this->success('设置成功',U('Alipay_config/index',$where));
		}else{
			if($config){
				$config = unserialize($config['info']);
				$this->assign('config',$config);
			}
			$this->display();
		}
	}
}


?>