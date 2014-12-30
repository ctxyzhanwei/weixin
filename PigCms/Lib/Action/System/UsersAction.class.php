<?php
class UsersAction extends BackAction{
	public function index(){
		$db=D('Users');
		$group=M('User_group')->field('id,name')->order('id desc')->select();
		$where='agentid = 0';
		if (isset($_GET['agentid'])){
			$where=array('agentid'=>intval($_GET['agentid']));
		}
		if (isset($_GET['inviter'])){
			$where=array('inviter'=>intval($_GET['inviter']));
		}
		
		$count= $db->where($where)->count();
		$Page= new Page($count,25);
		$show= $Page->show();
		
		$list = $db->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($group as $key=>$val){
			$g[$val['id']]=$val['name'];
		}
		unset($group);
		$this->assign('info',$list);
		$this->assign('page',$show);
		$this->assign('group',$g);
		$this->display();
	}
	
	// 添加用户
    public function add(){
        $UserDB = D("Users");
        if(isset($_POST['dosubmit'])) {
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            if(empty($password) || empty($repassword)){
                $this->error('密码必须填写！');
            }
            if($password != $repassword){
                $this->error('两次输入密码不一致！');
            }
            //根据表单提交的POST数据创建数据对象
			$_POST['viptime']=strtotime($_POST['viptime']);
            if($UserDB->create()){				
                $user_id = $UserDB->add();
                if($user_id){
					$this->success('添加成功！',U('Users/index'));                    
                }else{
                     $this->error('添加失败!');
                }
            }else{
                $this->error($UserDB->getError());
            }
        }else{
        	$map=array('status'=>1);
        	if (C('agent_version')){
				$map['agentid']=array('lt',1);
			}
            $role = M('User_group')->field('id,name')->where($map)->select();

			$business = include('./PigCms/Lib/ORG/Business.php');
			$i=0;
			foreach ($business as $k => $v){
				$data[$i]['key'] = $k;
				$data[$i]['val'] = $v;
				$i++;
			}
			$this->assign('business',$data);

            $this->assign('role',$role);
            $this->assign('tpltitle','添加');
            $this->display();
        }
    }
	public function search(){
		$name=$this->_post('name');
		$type=$this->_post('type');
		switch($type){
			case 1:
			$data['username']=$name;
			break;
			case 2:
			$data['id']=$name;
			break;
			case 3:
			$data['email']=$name;
		}
		//dump($where);
		$list=M('Users')->where($data)->select();
		$this->assign('info',$list);
		$this->display('index');
	
	}
    // 编辑用户
    public function edit(){
         $UserDB = D("Users");
        if(isset($_POST['dosubmit'])) {
        	S('user_'.intval($_POST['id']),NULL);
            $password = $this->_post('password','trim',0);
            $repassword = $this->_post('repassword','trim',0);
			$users=M('Users')->field('gid')->find($_POST['id']);
            if($password != $repassword){
                $this->error('两次输入密码不一致！');
            }
            if($password==false){ 
				unset($_POST['password']);
				unset($_POST['repassword']);
			}else{
				$_POST['password']=md5($password);
			}
			unset($_POST['dosubmit']);
			unset($_POST['__hash__']);
            //根据表单提交的POST数据创建数据对象
				$_POST['viptime']=strtotime($_POST['viptime']);
				if (intval($users['agentid'])){
					unset($_POST['gid']);
				}
                if($UserDB->save($_POST)){
					if($_POST['gid']!=$users['gid']&&intval($_POST['gid'])!=0){
						//$fun=M('Function')->field('funname,gid,isserve')->where('`gid` <= '.$_POST['gid'])->select();
						$queryname = M('User_group')->field('func')->where(array('id'=>$_POST['gid']))->find();

						$open['queryname'] = $queryname['func'];
						$uid['uid']=$_POST['id'];
						$token=M('Wxuser')->field('token')->where($uid)->select();

						if($token){
							$token_db=M('Token_open');
							foreach($token as $key=>$val){
								$wh['token']=$val['token'];
								$token_db->where($wh)->save($open);
							}
						}
					}
                    $this->success('编辑成功！',U('Users/index'));
                }else{
                     $this->error('编辑失败!');
                }
            
        }else{
            $id = $this->_get('id','intval',0);
            if(!$id)$this->error('参数错误!');
            $map=array('status'=>1);
        	if (C('agent_version')){
				$map['agentid']=array('lt',1);
			}
            $role = M('User_group')->field('id,name')->where($map)->select();
            $info = $UserDB->find($id);
            $inviteCount=$UserDB->where(array('inviter'=>$id))->count();

			$business = include('./PigCms/Lib/ORG/Business.php');
			$i=0;
			foreach ($business as $k => $v){
				$data[$i]['key'] = $k;
				$data[$i]['val'] = $v;
				$i++;
			}
			$this->assign('business',$data);

            $this->assign('inviteCount',$inviteCount);
            $this->assign('tpltitle','编辑');
            $this->assign('role',$role);
            $this->assign('info',$info);
            $this->display('add');
        }
    }
	
	public function addfc(){
		$token_open=M('Token_open');
		$open['uid']=session('uid');
		$open['token']=$_POST['token'];
		$gid=session('gid');
		$fun=M('Function')->field('funname,gid,isserve')->where('`gid` <= '.$gid)->select();
		foreach($fun as $key=>$vo){
			$queryname.=$vo['funname'].',';
		}
		$open['queryname']=rtrim($queryname,',');
		$token_open->data($open)->add();
	}
	
	//删除用户
    public function del(){
        $id = $this->_get('id','intval',0);
        if(!$id)$this->error('参数错误!');
        $UserDB = D('Users');
        $thisUser=$UserDB->where(array('id'=>$id))->find();
        $where['uid']=$id;
        $wxUserCount=M('wxuser')->where($where)->count();
        if($UserDB->delete($id)){
        	//
        	if ($thisUser['agentid']){
        		M('Agent')->where(array('id'=>$thisUser['agentid']))->setDec('usercount');
        		M('Agent')->where(array('id'=>$thisUser['agentid']))->setDec('wxusercount',$wxUserCount);
        	}
        	//
			
			//M('wxuser')->where($where)->delete();
			//M('token_open')->where($where)->delete();
			//M('text')->where($where)->delete();
			//M('img')->where($where)->delete();
			//M('member')->where($where)->delete();
			//M('indent')->where($where)->delete();
			//M('areply')->where($where)->delete();
			$this->assign("jumpUrl");
			$this->success('删除成功！');            
        }else{
            $this->error('删除失败!');
        }
    }
   
}