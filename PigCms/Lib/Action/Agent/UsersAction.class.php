<?php
class UsersAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		$users_db=M('Users');
		$where=$this->agentWhere;
		if (isset($_GET['keyword'])){
			$where['username']=$this->_get('keyword');
		}
		$count      = $users_db->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$users_db->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$groups=M('User_group')->where($this->agentWhere)->select();
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
				$i++;
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function addUser(){
		if (IS_POST){
			$users_db=M('Users');
			if (trim($_POST['password'])){
				$password = $this->_post('password','trim',0);
				$repassword = $this->_post('repassword','trim',0);
				if($password != $repassword){
					$this->error('两次输入密码不一致！');
				}
				$_POST['password']=md5($password);
			}else {
				unset($_POST['password']);
				unset($_POST['repassword']);
			}
			$_POST['agentid']=$this->agentid;
			$_POST['status']=1;
			//根据表单提交的POST数据创建数据对象
			$_POST['viptime']=strtotime($_POST['viptime']);
            if($users_db->create()){				
                $user_id = $users_db->add();
                if($user_id){
					$this->success('添加成功！',U('Users/index'));                    
                }else{
                     $this->error('添加失败!');
                }
            }else{
                $this->error($users_db->getError());
            }
            
		}else {
			$this->assign('actionUrl','?g=Agent&m=Users&a=addUser');
			$this->assign('pageName','添加用户');
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$thisUser=array('viptime'=>time());
			$this->assign('info',$thisUser);
			$this->display('updateUser');
		}
	}
	public function updateUser(){
		if (IS_POST){
			$uid['uid']=intval($_POST['id']);
			$users=M('Users')->where(array('id'=>intval($_POST['id'])))->find();
			$token=M('Wxuser')->field('token')->where($uid)->select();
			$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
			$pricebyDay=intval($this->thisAgent['wxacountprice'])/365;
			$_POST['viptime']=strtotime($_POST['viptime']);
			if ($_POST['viptime']<time()){
				$this->error('到期日期不能小于当前');
			}
			if ((intval($_POST['viptime'])-$users['viptime'])<30*24*3600 && (intval($_POST['viptime'])-strtotime(date('Y-m-d',$users['viptime']))) != 0){
				$this->error('延长期限不能小于一个月');
			}
			$month=(intval($_POST['viptime'])-$users['viptime'])/(30*24*3600);
			$day=(intval($_POST['viptime'])-$users['viptime'])/(24*3600);
			$month=intval($month);
			//$price=$pricebyMonth*count($token)*$month;
			$price=$pricebyDay*count($token)*$day;
			$price=intval($price);
			if ($this->thisAgent['moneybalance']<$price){
				$this->error('余额不足，共需要'.$price.'元，而您的余额为'.$this->thisAgent['moneybalance']);
			}
			//
			$users_db=M('Users');
			if (trim($_POST['password'])){
				$password = $this->_post('password','trim',0);
				$repassword = $this->_post('repassword','trim',0);
				if($password != $repassword){
					$this->error('两次输入密码不一致！');
				}
				$_POST['password']=md5($password);
			}else {
				unset($_POST['password']);
				unset($_POST['repassword']);
			}
			unset($_POST['dosubmit']);
			unset($_POST['__hash__']);
			
			//根据表单提交的POST数据创建数据对象
			$_POST['status']=1;
			if($users_db->save($_POST)){
/* 				if($_POST['gid']!=$users['gid']){
					$fun=M('Agent_function')->field('funname,gid,isserve')->where('`gid` <= '.$_POST['gid'].' AND agentid='.$this->thisAgent['id'])->select();
					foreach($fun as $key=>$vo){
						$queryname.=$vo['funname'].',';
					}
					$open['queryname']=rtrim($queryname,',');
					
					
					if($token){
						$token_db=M('Token_open');
						foreach($token as $key=>$val){
							$wh['token']=$val['token'];
							$token_db->where($wh)->save($open);
						}
					}
				} */
				//
				if ($price){
					M('Agent')->where(array('id'=>$this->thisAgent['id']))->setDec('moneybalance',$price);
					M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'amount'=>(0-$price),'des'=>$users['username'].'(uid:'.intval($_POST['id']).')延期'.$day.'天，共'.count($token).'个公众号','status'=>1,'time'=>time()));
				}
				//
				$this->success('编辑成功！',U('Users/index'));
			}else{
				$this->error('编辑失败!');
			}
		}else {
			$id=intval($_GET['id']);
			$thisUser=M('Users')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->find();
			if (!$thisUser){
				$this->error('没有此用户');
			}
			$this->assign('actionUrl','?g=Agent&m=Users&a=updateUser');
			$this->assign('pageName','修改用户');
			$this->assign('isUpdate',1);
			$this->assign('info',$thisUser);
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->display();
		}
	}
	public function deleteUser(){
		$id=intval($_GET['id']);
		$thisUser=M('Users')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->find();
		if (!$thisUser){
			$this->error('没有此用户');
		}
		$rt=M('Users')->where(array('id'=>$id))->delete();
		if ($rt){
			$userCount=M('Users')->where(array('agentid'=>$this->thisAgent['id']))->count();
			M('Agent')->where($this->agentWhere)->save(array('usercount'=>$userCount));
			M('Wxuser')->where(array('uid'=>$id))->delete();
			$wxuserCount=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id']))->count();
			M('Agent')->where($this->agentWhere)->save(array('wxusercount'=>$wxuserCount));
		}
		$this->success('删除成功！',U('Users/index'));
	}
	public function groups(){
		$db=M('User_group');
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,200);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->select();
		if ($list){
			$i=1;
			foreach ($list as $item){
				$db->where(array('id'=>$item['id']))->save(array('taxisid'=>$i));
				$i++;
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function wxusers(){
		$db=M('Wxuser');
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$uids=array();
		if ($list){
			foreach ($list as $item){
				if (!in_array($item['uid'],$uids)){
					array_push($uids,$item['uid']);
				}
			}
		}
		if ($uids){
			$users=M('Users')->where(array('id'=>array('in',$uids)))->select();
			$usersByID=array();
			if ($users){
				foreach ($users as $u){
					$usersByID[$u['id']]=$u;
				}
			}
			if ($list){
				$i=0;
				foreach ($list as $item){
					$list[$i]['username']=$usersByID[$item['uid']]['username'];
					$i++;
				}
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function deleteWxUser(){
		$id=intval($_GET['id']);
		$thisUser=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->find();
		if (!$thisUser){
			$this->error('没有此公众号');
		}
		$rt=M('Wxuser')->where(array('id'=>$id))->delete();
		$wxuserCount=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id']))->count();
		M('Agent')->where($this->agentWhere)->save(array('wxusercount'=>$wxuserCount));
		$this->success('删除成功！',U('Users/wxusers'));
	}
	public function groupSet(){
		$user_group_db=M('User_group');
		$agent_function_db=M('Agent_function');
		$where = array_merge($this->agentWhere,array('status'=>1));
		$functions=$agent_function_db->where($where)->order('id ASC')->select();
		$this->assign('func',$functions);
		
		if (IS_POST){
			if (isset($_POST['id'])){
				$_POST['func'] = join(',',$_REQUEST['func']);
				if($user_group_db->create()){
					$user_group_db->where(array('agentid'=>$this->thisAgent['id'],'id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Users/groups'));
				}
			}else {
				if($user_group_db->create()){
					$_POST['func'] = join(',',$_REQUEST['func']);
					$_POST['agentid']=intval($this->thisAgent['id']);
					$user_group_db->add($_POST);
					$this->success('添加成功！',U('Users/groups'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisGroup=$user_group_db->where(array('agentid'=>$this->thisAgent['id'],'id'=>intval($_GET['id'])))->find();
				$this->assign('info',$thisGroup);
			}
			$this->display();
		}
	}
	public function delGroup(){
		$id=$this->_get('id','intval',0);
		if($id==0)$this->error('非法操作');
		$info = D('User_group')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->delete();
		$this->success('操作成功');
	}
}


?>