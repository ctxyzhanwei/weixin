<?php
class apiInfo {
	public function info($uid,$wxid=0,$token=''){
		if ($wxid){
			$thisWx=M('wxuser')->where(array('uid'=>intval($uid),'id'=>intval($wxid)))->find();
		}else {
			$thisWx=M('wxuser')->where(array('uid'=>intval($uid),'token'=>$token))->find();
		}
		if (!$thisWx){
			$thisWx=M('wxuser')->where(array('uid'=>intval($uid)))->find();
		}
		$urlsubfix='';
		switch ($thisWx['encode']){
			default:
			case 0:
				$thisWx['encodetype']='明文模式 (如需使用安全模式请在管理中心修改，仅限服务号和认证订阅号)';
				break;
			case 1:
				$thisWx['encodetype']='兼容模式';
				break;
			case 2:
				$thisWx['encodetype']='安全模式';
				//$urlsubfix='&encrypt_type=aes';
				break;
		}
		if (!$thisWx['pigsecret']){
			$thisWx['pigsecret']=$thisWx['token'];
		}
		$thisWx['urlsubfix']=$urlsubfix;
		return $thisWx;
	}
}
