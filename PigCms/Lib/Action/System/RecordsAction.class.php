<?php
class RecordsAction extends BackAction{
	public function index(){
		$records=M('indent');
		//$db=M('Users');
		$count=$records->count();
		$page=new Page($count,25);
		$show= $page->show();
		$info=$records->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$this->assign('page',$show);
		$this->assign('info',$info);
		$this->display();
	}
	public function send(){
		$money=$this->_get('price','intval');
		$data['id']=$this->_get('uid','intval');
	//	dump($money);exit;
		if($money!=false&&$data['id']!=false){
			$thisR=M('Indent')->where(array('id'=>$this->_get('iid','intval')))->find();
			if ($thisR['status']!=2){
			//dump($money);exit;
			$back=M('Users')->where($data)->setInc('money',$money);
			$back=M('Users')->where($data)->setInc('moneybalance',$money);
			$status=M('Indent')->where(array('id'=>$this->_get('iid','intval')))->setField('status',2);
			if($back!=false&&$status!=false){
				$this->success('充值成功');
			}else{
				$this->error('充值失败');
			}
			}else {
				$this->error('已经入金过了');
			}
		}else{
			$this->error('非法操作');
		}
	}
	//删除记录
	public function del(){
		$id=$this->_get('iid','intval');
	$d=M('Indent')->where(array('id'=>$id))->delete();
		if($d){
			$this->success('操作成功');
		}else{
			$this->error('操作失败 ');
		}
	}

}