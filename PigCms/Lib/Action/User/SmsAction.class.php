<?php
class SmsAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->assign('smscount',$this->user['smscount']);
	}
	public function index(){
		$data=M('Sms_record');
		$where=array('uid'=>$this->user['id']);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('time DESC')->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	public function expense(){
		$data=M('Sms_expendrecord');
		$where=array('uid'=>$this->user['id']);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('time DESC')->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	public function set(){
		$sms_config_model=M('Sms_config');
		if(IS_POST){
			$where=array('token'=>session('token'));
			$check=$sms_config_model->where($where)->find();
			if (!$_POST['id']){
				$this->insert('Sms_config','/index');
			}else{
				if($sms_config_model->create()){
					if($sms_config_model->where($where)->save($_POST)){
						$this->success('设置成功',U('Sms/index',array('token'=>session('token'))));
					}else{
						$this->error('操作失败');
					}
				}else{
					$this->error($sms_config_model->getError());
				}
			}
		}else{
			Vendor('sms.sms');
			$sms=new sms();
			$servers=$sms->servers;
			//检查有没有短信配置记录
			
			$smsConfig=$sms_config_model->where(array('token'=>session('token')))->find();
			$serverOptions='';
			if ($smsConfig){
				$server=$smsConfig['server'];
				$this->assign('set',$smsConfig);
				$this->assign('isUpdate',1);
			}else {
				$server='';
			}
			foreach ($servers as $k=>$s){
				if ($k==$server){
					$selected=' selected';
				}else {
					$selected='';
				}
				$serverOptions.='<option value="'.$k.'"'.$selected.'>'.$s['name'].'</option>';
			}
			$this->assign('serverOptions',$serverOptions);
			//
			$this->assign('servers',$servers);
			$this->display();
		}
	}
	function buy_post(){
	
		$moneyBalance=$this->user['moneybalance'];
		$needFee=intval(C('sms_price'))*intval($_POST['count'])/100;
		if ($needFee<$moneyBalance||$needFee==$moneyBalance){
			//
			$users_db=D('Users');
			$spend=0-$needFee;
			$indent=array();
			$indent['id']=time();
			M('Indent')->data(array('uid'=>session('uid'),'month'=>0,'title'=>'购买短信'.intval($_POST['count']).'条','uname'=>$this->user['username'],'gid'=>0,'create_time'=>time(),'indent_id'=>$indent['id'],'price'=>$spend,'status'=>1))->add();
			M('Users')->where(array('id'=>$this->user['id']))->setDec('moneybalance',intval($needFee));
			M('Users')->where(array('id'=>$this->user['id']))->setInc('smscount',intval($_POST['count']));
			M('Sms_expendrecord')->add(array('uid'=>$this->user['id'],'price'=>intval($needFee),'count'=>intval($_POST['count']),'time'=>time()));
			//
			$this->success('购买成功',U('User/Sms/expense'));
		}else{
			$this->success('余额不足，请先充值',U('User/Alipay/index'));
		}
	}
}


?>