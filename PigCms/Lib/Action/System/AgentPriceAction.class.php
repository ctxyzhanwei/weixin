<?php
class AgentPriceAction extends BackAction{
	public $agent_db;
	public function _initialize() {
		parent::_initialize();
		$this->agent_db=M('Agent_price');
	}
	public function index(){
		$count=$this->agent_db->count();
		$page=new Page($count,20);
		$info=$this->agent_db->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();
	}
	public function add(){
		if (isset($_GET['id'])){
			$thisPrice=$this->agent_db->where(array('id'=>intval($_GET['id'])))->find();
		}
		if(isset($_POST['dosubmit'])) {
			if($this->agent_db->create()){
				if ($thisPrice){
					$this->agent_db->where(array('id'=>$thisPrice['id']))->save($_POST);
					$this->success('修改成功！',U('AgentPrice/index'));
				}else {
					$agentid = $this->agent_db->add();
					if($agentid){
						$this->success('添加成功！',U('AgentPrice/index'));
					}else{
						$this->error('添加失败!');
					}
				}
			}else{
				$this->error($this->agent_db->getError());
			}
		}else{
			$this->assign('info',$thisPrice);
			$this->display();
		}
	}

	public function del(){
		$id=$this->_get('id','intval');
		if($this->agent_db->delete($id)){
			$this->success('操作成功',$_SERVER['HTTP_REFERER']);
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}

	
	
}