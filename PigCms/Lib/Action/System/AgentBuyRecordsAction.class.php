<?php
class AgentBuyRecordsAction extends BackAction{
	public $agent_db;
	public function _initialize() {
		parent::_initialize();
		$this->agent_db=M('Agent_price');
	}
	public function index(){
		$where='';
		$agent_expenserecords_db=M('Agent_expenserecords');
		$count      = $agent_expenserecords_db->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		//$where=$this->agentWhere;
		//$where['status']=1;
		$list=$agent_expenserecords_db->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$agentids=array();
		if ($list){
			foreach ($list as $item){
				if (!in_array($item['agentid'],$agentids)){
					array_push($agentids,$item['agentid']);
				}
			}
		}
		if ($agentids){
		$agents=M('Agent')->where(array('id'=>array('in',$agentids)))->select();
		
		$agentsbyid=array();
		if ($agents){
			foreach ($agents as $a){
				$agentsbyid[$a['id']]=$a;
			}
		}
		if ($list){
			$i=0;
			foreach ($list as $item){
				$list[$i]['agentname']=$agentsbyid[$item['agentid']]['name'];
				if(!(strpos($item['des'],'充值') === FALSE)||!(strpos($item['des'],'购买') === FALSE)){
					$list[$i]['canconfirm']=1;
				}
				$i++;
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		}
		$this->display();
	}
	
	
	function confirm(){
		$id=intval($_GET['id']);
		$record=M('Agent_expenserecords')->where(array('id'=>$id))->find();
		$thisAgent=M('Agent')->where(array('id'=>$record['agentid']))->find();
		$price=$record['amount'];
		if(!(strpos($record['des'],'购买') === FALSE)){//购买优惠套餐
			$thisPrice=M('Agent_price')->where(array('price'=>$record['amount']))->find();
			
			$price=$thisPrice['maxaccount']*$thisAgent['wxacountprice'];
		}
		if (intval($record['status'])==0){
			M('Agent')->where(array('id'=>$thisAgent['id']))->setInc('money',$price);
			M('Agent')->where(array('id'=>$thisAgent['id']))->setInc('moneybalance',$price);
			$back=M('Agent_expenserecords')->where(array('id'=>$record['id']))->setField('status',1);
		}else {
			M('Agent')->where(array('id'=>$thisAgent['id']))->setDec('money',$price);
			M('Agent')->where(array('id'=>$thisAgent['id']))->setDec('moneybalance',$price);
			$back=M('Agent_expenserecords')->where(array('id'=>$record['id']))->setField('status',0);
		}
		//
		
		//
		$this->success('成功',U('AgentBuyRecords/index'));
	}

	
	
}