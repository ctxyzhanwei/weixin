<?php
class PunishAction extends WapAction{
	public $punish;

	public function _initialize(){
		parent::_initialize();
		$id 		= $this->_get('id','intval');
		$where 		=  array('token'=>$this->token,'id'=>$this->_get('id','intval'),'is_open'=>'1');
		$this->punish 	= M('Punish')->where($where)->find();

		if(empty($this->punish)){
			$this->error('活动可能还没有开启！',U('Punish/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}

		$this->assign('id',$id);
		$this->assign('punish',$this->punish);
	}
	
	
	public function index(){
		$this->display();
	}
	
	public function run(){
		$id 	= $this->_get('id','intval');
		$this->punish['item'] 	= $this->get_item($id);
		
		$result = array();
		$result['status'] 	= true;
		$result['data'] 	= $this->punish;
		echo json_encode($result);
	}
	
	public function get_item($id){
		$where 	= array('token'=>$this->token,'pid'=>$id);
		
		$item 	= M('Punish_item')->where($where)->field('id,name')->select();
		$arr 	= array();
		
		foreach($item as $key=>$value){
			$arr['item'.($key+1)] = $value['name'];
		}
		
		return $arr;
	}

	public function use_num(){
		$where 	= array('token'=>$this->token,'id'=>$this->_get('id','intval'));
		M('Punish')->where($where)->setInc('use_num',1);
	}
	
}