<?php
class BasicAction extends AgentAction{
	public $alipayOpen;
	public function _initialize() {
		parent::_initialize();
		$this->alipayOpen=0;
		if (C('alipay_pid')&&C('alipay_name')&&C('alipay_key')){
			$this->alipayOpen=1;
		}
		$this->assign('alipayOpen',$this->alipayOpen);
	}
	public function index(){
		if (IS_POST){
			unset($_POST['name']);
			if($this->agent_db->create()){
				$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save($_POST);
				$this->success('修改成功！',U('Basic/index'));
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$this->display();
		}
	}
	public function expenseRecords(){
		$where=$this->agentWhere;
		$agent_expenserecords_db=M('Agent_expenserecords');
		$count      = $agent_expenserecords_db->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$where=$this->agentWhere;
		//$where['status']=1;
		$list=$agent_expenserecords_db->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function discountPrice(){
		$db=M('Agent_price');
		$count      = $db->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		
		$list=$db->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function recharge(){
		$this->display();
	}
	public function changePassword(){
		if (IS_POST){
			if (trim($_POST['password'])!=trim($_POST['repassword'])){
				$this->error('两次输入的密码不一致');
			}
			$password=md5(md5(trim($_POST['password'])).$this->thisAgent['salt']);
			$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save(array('password'=>$password));
			$this->success('修改成功！',U('Basic/changePassword'));
		}else {
			$this->display();
		}
	}
	
	public function buyDiscountPrice(){
		if (isset($_GET['discountpriceid'])){
			$thisPrice=M('Agent_price')->where(array('id'=>intval($_GET['discountpriceid'])))->find();
			$amount=$thisPrice['price'];
		}else {
			$this->error('错误请求');
		}
		$subject='购买优惠套餐'.$thisPrice['name'].'（ID：'.$thisPrice['id'].'）';
		$out_trade_no = $this->thisAgent['id'].'_'.time();
		$data=M('Agent_expenserecords')->data(array('agentid'=>$this->thisAgent['id'],'des'=>$subject,'time'=>time(),'orderid'=>$out_trade_no,'amount'=>$amount))->add();
		$this->success('购买成功，请联系管理员确认',U('Basic/expenseRecords'));
	}
	
}

?>