<?php
class ServiceUserAction extends UserAction
{
	public $token;
	private $data;
	private $openid;
	public function _initialize()
	{
		parent::_initialize();
		$this->openid=$this->_get('openid','htmlspecialchars');
		if($this->openid==false)
		{
		}
		$this->token=session('token');
		$this->data=D('Service_user');
	}
	public function wechatService()
	{
		if (IS_POST)
		{
			D('Wxuser')->where(array('token'=>$this->token))->save(array('transfer_customer_service'=>intval($_POST['transfer_customer_service'])));
			S('wxuser_'.$this->token,NULL);
			$this->success('设置成功');
		}
		else 
		{
			$this->wxuser=S('wxuser_'.$this->token);
			if (!$this->wxuser)
			{
				$this->wxuser=D('Wxuser')->where(array('token'=>$this->token))->find();
				S('wxuser_'.$this->token,$this->wxuser);
			}
			$this->assign('info',$this->wxuser);
			$this->display();
		}
	}
	public function index()
	{
		$where['token']=session('token');
		$count=$this->data->where($where)->count();
		$page=new Page($count,25);
		$list=$this->data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$this->assign('page',$page->show());
		if ($list)
		{
			$sepTime=30*60;
			$nowTime=time();
			$time=$nowTime-$sepTime;
			$i=0;
			foreach ($list as $item)
			{
				$list[$i]['online']=0;
				if ($item['endJoinDate']>$time)
				{
					$list[$i]['online']=1;
				}
				$i++;
			}
		}
		$this->assign('list',$list);
		$this->assign('type','list');
		$this->display();
	}
	public function add()
	{
		if(IS_POST)
		{
			$db=D("Service_user");
			if($db->create()===false)
			{
				$this->error($db->getError());
			}
			else
			{
				$id=$db->add();
				if($id==true)
				{
					M('Users')->where(array('id'=>session('uid')))->setInc('serviceUserNum');
					$this->success('操作成功');
				}
				else
				{
					$this->error('操作失败');
				}
			}
		}
		else
		{
			$this->display();
		}
	}
	public function closeService()
	{
		$where['token']=session('token');
		$endTime=time()-60*600;
		$rt=M('Service_user')->where($where)->save(array('endJoinDate'=>$endTime));
		$this->success('操作成功');
	}
	public function edit()
	{
		if(IS_POST)
		{
			if(empty($_POST['userPwd']))
			{
				unset($_POST['userPwd']);
			}
			$_POST['id']=$this->_get('id','intval');
			$this->all_save('Service_user','/index');
		}
		else
		{
			$where['id']=$this->_get('id','intval');
			$where['session']=session('session');
			$info=M('ServiceUser')->where($where)->find();
			$this->assign('serviceUser',$info);
			$this->display('add');
		}
	}
	public function chat_log()
	{
		$data=M('service_logs');
		$where['token']=session('token');
		$count=$data->where($where)->count();
		$page=new Page($count,25);
		$list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		foreach($list as $key=>$vo)
		{
			$list[$key]['name']=D('Service_user')->getServiceUser($vo['pid']);
		}
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->assign('type','list');
		$this->display();
	}
	public function del ()
	{
		M('Users')->where(array('id'=>session('uid')))->setDec('serviceUserNum');
		$this->del_id();
	}
	public function chat_log_del ()
	{
		$this->del_id('service_logs','Service/chat_log');
	}
}
?>
