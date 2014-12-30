<?php
class CompanyAction extends UserAction{
	public $token;
	public $isBranch;
	public $company_model;
	public function _initialize() {
		parent::_initialize();
		$this->token=session('token');
		$this->assign('token',$this->token);
		//权限
		if ($this->token!=$_GET['token']){
			exit();
		}
		//是否是分店
		$this->isBranch=0;
		if (isset($_GET['isBranch'])&&intval($_GET['isBranch'])){
			$this->isBranch=1;
		}
		$this->assign('isBranch',$this->isBranch);
		//
		$this->company_model=M('Company');
	}
	public function index(){
		$where=array('token'=>$this->token);
		if ($this->isBranch){
			$id=intval($_GET['id']);
			$where['id']=$id;
			$where['isbranch']=1;
		}else {
			$where['isbranch']=0;
		}
		$thisCompany=$this->company_model->where($where)->find();
		if (!$this->isBranch){
			$fatherCompany=$this->company_model->where(array('token'=>$this->token,'isbranch'=>0))->order('id ASC')->find();
			if ($fatherCompany){
				$tj=array('token'=>$this->token);
				$tj['id']=array('neq',intval($fatherCompany['id']));
				$this->company_model->where($tj)->save(array('isbranch'=>1));
			}
		}
		if(IS_POST){
			$_POST['password'] = isset($_POST['password']) && $_POST['password'] ? md5(trim($_POST['password'])) : '';
			if (!$thisCompany){
				if ($this->isBranch){
					$this->insert('Company',U('Company/branches',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
				}else {
					$this->insert('Company',U('Company/index',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
				}
			}else {
				$amap=new amap();
				if (!$thisCompany['amapid']&&$thisCompany['longitude']==$_POST['longitude']){
					$locations=$amap->coordinateConvert($thisCompany['longitude'],$thisCompany['latitude']);
					$_POST['longitude']=$locations['longitude'];
					$_POST['latitude']=$locations['latitude'];
				}
				if (!$thisCompany['amapid']){
					$ampaid=$amap->create($_POST['name'],$_POST['longitude'].','.$_POST['latitude'],$_POST['tel'],$_POST['address']);
					$_POST['amapid']=intval($ampaid);
				}else {
					$amap->update($thisCompany['amapid'],$_POST['name'],$_POST['longitude'].','.$_POST['latitude'],$_POST['tel'],$_POST['address']);
				}
				//
				if($this->company_model->create()){
					if (empty($_POST['password'])) {
						unset($_POST['password']);
					}
					if($this->company_model->where($where)->save($_POST)){
						if ($this->isBranch){
							$this->success('修改成功',U('Company/branches',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
						}else{
							$this->success('修改成功',U('Company/index',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
						}
					}else{
						$this->error('操作失败');
					}
				}else{
					$this->error($this->company_model->getError());
				}
			}
			
		}else{
			$this->assign('set',$thisCompany);
			$this->display();
		}
	}
	public function branches(){
		$thisCompany=$this->company_model->where(array('token'=>$this->token))->order('id ASC')->find();
		$where=array('token'=>$this->token);
		$where['id']=array('neq',$thisCompany['id']);
		$branches = $this->company_model->where($where)->order('taxis ASC')->select();
		$list = array();
		foreach ($branches as $b) {
			$b['url'] = $_SERVER['HTTP_HOST'] . '/index.php?m=Index&a=clogin&cid=' . $b['id'] . '&k=' . md5($b['id'] . $b['username']);
			$list[] = $b;
		}
		$this->assign('branches', $list);
		$this->display();
	}
	public function delete(){
		$where=array('token'=>$this->token,'id'=>intval($_GET['id']));
		$thisCompany=$this->company_model->where($where)->find();
		$rt=$this->company_model->where($where)->delete();
		if($rt==true){
			$amap=new amap();
			$amap->delete($thisCompany['amapid']);
			$this->success('删除成功',U('Company/branches',array('token'=>$this->token,'isBranch'=>1)));
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/branches',array('token'=>$this->token,'isBranch'=>1)));
		}
	}
}


?>