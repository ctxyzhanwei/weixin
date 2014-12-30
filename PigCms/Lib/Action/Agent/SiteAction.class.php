<?php
class SiteAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		if (IS_POST){
			if (isset($_POST['statisticcode'])){
				$_POST['statisticcode']=base64_encode($_POST['statisticcode']);
			}
			if($this->agent_db->create()){
				$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save($_POST);
				$this->success('修改成功！',U('Site/'.ACTION_NAME));
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$this->display();
		}
	}
	public function regConfig(){
		if (IS_POST){
			if($this->agent_db->create()){
				$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save($_POST);

				$this->success('修改成功！');
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->display();
		}
	}
	public function functions(){
		$db=M('Function');
		$agent_function_db=M('Agent_function');
		//初始化
		$baseFunctions=$db->select();
		$functions=$agent_function_db->where($this->agentWhere)->order('id ASC')->select();
		$baseFunctionsByFunname=array();
		if ($baseFunctions){
			foreach ($baseFunctions as $bf){
				$baseFunctionsByFunname[trim($bf['funname'])]=$bf;
			}
		}
		$functionsByFunname=array();
		//
		$existFunName=array();
		
		$functionNames=array();
		if ($functions){
			foreach ($functions as $f){
				array_push($functionNames,$f['funname']);
			}
		}
		if ($functions){
			foreach ($functions as $f){
				$f['funname']=trim($f['funname']);
				$functionsByFunname[$f['funname']]=$f;
				//
				if (!key_exists($f['funname'],$baseFunctionsByFunname)||in_array($f['funname'],$existFunName)){
					$agent_function_db->where(array('funname'=>$f['funname']))->delete();
				}
				array_push($existFunName,$f['funname']);
			}
		}
		
		if ($baseFunctions){
			foreach ($baseFunctions as $bf2){
				if (!in_array($bf2['funname'],$functionNames)){
					$bf2['agentid']=$this->thisAgent['id'];
					unset($bf2['id']);
					$agent_function_db->add($bf2);
				}
			}
		}
		//
		$count      = $agent_function_db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$agent_function_db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
		$groupsByID=array();
		if ($groups){
			foreach ($groups as $g){
				$groupsByID[$g['id']]=$g;
			}
		}
		if ($list){
			$i=0;
			foreach ($list as $item){
				$list[$i]['groupName']=$groupsByID[$item['gid']]['name'];
				$list[$i]['info']=str_replace('pigcms','',$item['info']);
				$i++;
			}
		}
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function funSet(){
		$thisFunction=M('Agent_function')->where(array('id'=>intval($_GET['id'])))->find();
		$db=M('Agent_function');
		$thisFun=$db->where(array('funname'=>$thisFunction['funname'],'agentid'=>$this->thisAgent['id']))->find();
		if (IS_POST){
			if($db->create()){
				$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
				$this->success('修改成功！',U('Site/functions'));
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->assign('info',$thisFun);
			$this->display();
		}
	}
	public function links(){
		$db=M('Links');
		//
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function setLink(){
		$db=M('Links');
		if (IS_POST){
			if (isset($_POST['id'])&&intval($_POST['id'])){
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/links'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/links'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$this->display();
		}
	}
	public function deleteLink(){
		$db=M('Links');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$this->success('删除成功！',U('Site/links'));
	}
	public function cases(){
		$db=M('case');
		//
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function setCase(){
		$db=M('case');
		if (IS_POST){
			if (isset($_POST['id'])&&intval($_POST['id'])){
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/cases'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/cases'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$this->display();
		}
	}
	public function deleteCase(){
		$db=M('case');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$this->success('删除成功！',U('Site/cases'));
	}
}


?>