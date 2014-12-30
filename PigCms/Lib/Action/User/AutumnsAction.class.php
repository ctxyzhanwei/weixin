<?php
class AutumnsAction extends ActivityBaseAction{
	public $activityType;
	public $activityTypeName;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Autumns');
		$this->activityType=1;
		$this->activityTypeName='拆礼盒';
		$this->assign('activityTypeName',$this->activityTypeName);
	}

	public function index(){
		parent::index($this->activityType);
		$count = M('Autumns_box')->where(array('token'=>$this->token))->count();
		$this->assign('count',$count);
		$this->display();
	
	}
	
	public function add(){
		parent::add($this->activityType);
	}
	
	public function edit(){
		parent::edit($this->activityType);
	}

	public function cheat(){
		parent::cheat($this->activityType);
		$this->display();
	}

	public function sn(){
		$id=$this->_get('id','intval');
		$bid=M('Lottery')->where(array('id'=>$id,'token'=>$this->token))->getField('zjpic');
		$type=$this->_get('type','intval');
		$lottery=M('Activity')->where(array('token'=>$this->token,'id'=>$bid,'type'=>$type))->find();
		$this->assign('Activity',$lottery);

		$recordcount=$lottery['fistlucknums']+$lottery['secondlucknums']+$lottery['thirdlucknums']+$lottery['fourlucknums']+$lottery['fivelucknums']+$lottery['sixlucknums'];
		$datacount=$lottery['fistnums']+$lottery['secondnums']+$lottery['thirdnums']+$lottery['fournums']+$lottery['fivenums']+$lottery['sixnums'];
		$this->assign('datacount',$datacount);
		$this->assign('recordcount',$recordcount);
		
		$box=M('Autumns_box');
		$list = $box->where(array('token'=>$this->token,'bid'=>$bid,'isprizes'=>1))->select();
		$this->assign('list',$list);

		$send = $box->where(array('bid'=>$bid,'isprizes'=>1,'sendstutas'=>1))->count();
		$this->assign('send',$send);
		
		$count=$box->where(array('bid'=>$bid,'isprize'=>1,'isprizes'=>1))->count();
		$this->assign('count',$count);

		$lottery=M('Activity')->where(array('id'=>$bid,'token'=>$this->token))->find();
		$renametel=$lottery['renametel']?$lottery['renametel']:'联系电话';
		$this->assign('renametel',$renametel);

		$this->display();
	}

	public function sendprize(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');
		dump($bid);exit;

		$where=array('bid'=>$bid,'token'=>$this->token,'id'=>$id);
	
		$data['sntime']=time();
		$data['sendstutas']=1;
		$back=M('Autumns_box')->where($where)->save($data);

		if($back==true){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	public function sendnull(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');
		dump($bid);exit;
		$where=array('bid'=>$bid,'token'=>$this->token,'id'=>$id);
		$data['sntime']='';
		$data['sendstutas']=0;
		$back=M('Autumns_box')->where($where)->save($data);
		if($back==true){
			$this->success('已经取消');
		}else{
			$this->error('操作失败');
		}
	}

	public function sndelete(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');
		$box=M('Autumns_box');
		$where=array('id'=>$id,'bid'=>$bid,'token'=>$this->token);
		$box->where($where)->delete();
		$this->success('操作成功');
	}
}

?>