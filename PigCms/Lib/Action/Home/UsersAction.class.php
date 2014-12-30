<?php
class UsersAction extends BaseAction{
	public function index(){
		header("Location: /");
	}
	public function companylogin() {
		$dbcom = D('Company');
		$where['username'] = $this->_post('username','trim');
		$cid = $where['id'] = $this->_post('cid', 'intval');
		$k = $this->_post('k','trim, htmlspecialchars');
		if (empty($k) || $k != md5($where['id'] . $where['username'])) {
			$this->error('帐号密码错误',U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
		}
		
		$pwd = $this->_post('password','trim,md5');
		$company = $dbcom->where($where)->find();
		if($company && ($pwd === $company['password'])){
			if ($wxuser = D('Wxuser')->where(array('token' => $company['token']))->find()) {
				$uid = $wxuser['uid'];
				$db = D('Users');
				$res = $db->where(array('id' => $uid))->find();
			} else {
				$this->error('帐号密码错误',U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
			}
			session('companyk', $k);
			session('companyLogin', 1);
			session('companyid', $company['id']);
			session('token', $company['token']);
			session('uid',$res['id']);
			session('gid',$res['gid']);
			session('uname',$res['username']);
			$info=M('user_group')->find($res['gid']);
			session('diynum',$res['diynum']);
			session('connectnum',$res['connectnum']);
			session('activitynum',$res['activitynum']);
			session('viptime',$res['viptime']);
			session('gname',$info['name']);
			//每个月第一次登陆数据清零
			$now=time();
			$month=date('m',$now);
			if($month!=$res['lastloginmonth']&&$res['lastloginmonth']!=0){
				$data['id']=$res['id'];
				$data['imgcount']=0;
				$data['diynum']=0;
				$data['textcount']=0;
				$data['musiccount']=0;
				$data['connectnum']=0;
				$data['activitynum']=0;
				$db->save($data);
				//
				session('diynum',0);
				session('connectnum',0);
				session('activitynum',0);
			}
			//登陆成功，记录本月的值到数据库
			
			//
			$db->where(array('id'=>$res['id']))->save(array('lasttime'=>$now,'lastloginmonth'=>$month,'lastip'=>$_SERVER['REMOTE_ADDR']));//最后登录时间
			$this->success('登录成功',U('User/Repast/index',array('cid' => $cid)));
		} else{
			$this->error('帐号密码错误',U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
		}
	}

	public function companyLogout()
	{
		$cid = session('companyid');
		$k = session('companyk');
		session(null);
		session_destroy();
		unset($_SESSION);
        if(session('?'.C('USER_AUTH_KEY'))) {
            session(C('USER_AUTH_KEY'),null);
           
            redirect(U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        } else {
            $this->success('已经登出！', U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        }
    
		
	}
	public function checklogin(){
		$verifycode=$this->_post('verifycode2','md5',0);
		if (isset($_POST['verifycode2'])){
			if($verifycode != $_SESSION['loginverify']){
				$this->error('验证码错误',U('Index/login'));
			}
		}
		$db=D('Users');
		$where['username']=$this->_post('username','trim');
		
		// if($db->create()==false)
			// $this->error($db->getError());
		$pwd=$this->_post('password','trim,md5');
		$res=$db->where($where)->find();
		if($res&&($pwd===$res['password'])){
			
			if($res['status']==0){
				$this->error('请联系在线客户，为你人工审核帐号');exit;
			}

			if (C('agent_version')){
				if ((int)$this->thisAgent['id']!=$res['agentid']){
					$this->error('您使用的网址不对');exit;
				}
			}
			session('uid',$res['id']);
			session('gid',$res['gid']);
			session('uname',$res['username']);
			$info=M('user_group')->find($res['gid']);
			session('diynum',$res['diynum']);
			session('connectnum',$res['connectnum']);
			session('activitynum',$res['activitynum']);
			session('viptime',$res['viptime']);
			session('gname',$info['name']);
			//每个月第一次登陆数据清零
			$now=time();
			$month=date('m',$now);
			if($month!=$res['lastloginmonth']&&$res['lastloginmonth']!=0){
				$data['id']=$res['id'];
				$data['imgcount']=0;
				$data['diynum']=0;
				$data['textcount']=0;
				$data['musiccount']=0;
				$data['connectnum']=0;
				$data['activitynum']=0;
				$db->save($data);
				//
				session('diynum',0);
				session('connectnum',0);
				session('activitynum',0);
			}
			//登陆成功，记录本月的值到数据库
			
			//
			$db->where(array('id'=>$res['id']))->save(array('lasttime'=>$now,'lastloginmonth'=>$month,'lastip'=>htmlspecialchars(trim(get_client_ip()))));//最后登录时间
			$this->success('登录成功',U('User/Index/index'));
		}else{
			$this->error('帐号密码错误',U('Index/login'));
		}
	}
	function randStr($randLength){
		$randLength=intval($randLength);
		$chars='abcdefghjkmnpqrstuvwxyz';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		return $randStr;
	}
        private function reg_ucenter() {
            $uc_db  = count(explode(',', C('ucenter_db_set')));
            $uc_web = count(explode(',', C('ucenter_web_set')));
            if ($uc_db != 6 || $uc_web != 3)return;

           $username = $this->_post('username');
           $password = $this->_post('password');
           $email    = $this->_post('email');
           $para = 'username='.$username.'&password='.$password.'&email='.$email;
           $res = file_get_contents("http://" .$_SERVER['SERVER_NAME']. "/UCenter/advanced/examples/ucexample_2.php?from_weixin_url=1&".$para);
           if (substr($res,-1) != 1)die('Ucenter Reg Error'.iconv('gbk', 'utf-8', $res));
           //exit;
        }

	public function checkreg(){
                if(C('isclosekuser')=='true'){
			$this->error('系统已经关闭注册！',U('Index/index'));
                }
		$db=D('Users');
		$info=M('User_group')->find(1);
		$verifycode=$this->_post('verifycode','md5',0);
		if (isset($_POST['verifycode'])){
			if($verifycode != $_SESSION['verify']){
				$this->error('验证码错误',U('Index/login'));
			}
		}
		if(C('reg_mp_verify') == 1){
			if(session('reg_mp') != md5($this->_post('mp'))){
				$this->error('请输入刚接收验证码的手机号');
			}
		}
		if (isset($_POST['mp'])){
			if (!preg_match('/^13[0-9]{9}$|^15[0-9]{9}$|^18[0-9]{9}$/',trim($_POST['mp']))){
				$this->error('手机号填写不正确',U('Index/login'));
			}
		}
		if ($this->isAgent){
			$_POST['agentid']=$this->thisAgent['id'];
		}
		if (isset($_POST['invitecode'])){
			//$_POST['invitecode']=$this->_get('invitecode');
			$inviteCode=$this->_post('invitecode');
			if ($inviteCode&&!ctype_alpha($inviteCode)){
				exit('invitecode colud not include other letter');
			}
			$inviter=$db->where(array('invitecode'=>$inviteCode))->find();
			$_POST['inviter']=intval($inviter['id']);
		}else {
			$_POST['inviter']=0;
		}
		$_POST['invitecode']=$this->randStr(6);
		$_POST['usertplid']=1;
		if($db->create()){
			$id=$db->add();
			if($id){
				$now=time();
				$db->where(array('id'=>$id))->save(array('lasttime'=>$now,'lastloginmonth'=>date('m',$now),'lastip'=>htmlspecialchars(trim(get_client_ip()))));//最后登录时间
				//
				Sms::sendSms('admin','有新用户注册了',$this->adminMp);
				if ($this->isAgent){
				    $usercount=M('Users')->where(array('agentid'=>$this->thisAgent['id']))->count();
				    M('Agent')->where(array('id'=>$this->thisAgent['id']))->save(array('usercount'=>$usercount));
				}
				if($this->reg_needCheck){
					$gid=$this->minGroupid;
                                        $this->reg_ucenter();
					if (C('demo')){
						session('preuid',$id);
						$this->success('注册成功,请关注我们公众号获取使用权限',U('Index/qrcode'));exit;
					}else {
						$this->success('注册成功,请联系在线客服审核帐号',U('User/Index/index'));exit;
					}
				}else{
					$viptime=time()+intval($this->reg_validDays)*24*3600;
					$gid=$this->minGroupid;
					if ($this->reg_groupid){
						$gid=intval($this->reg_groupid);
					}
					$db->where(array('id'=>$id))->save(array('viptime'=>$viptime,'status'=>1,'gid'=>$gid));
				}
				
				session('uid',$id);
				session('gid',$gid);
				session('uname',$_POST['username']);
				session('diynum',0);
				session('connectnum',0);
				session('activitynum',0);
				session('gname',$info['name']);
				// $smtpserver = C('email_server'); 
				// $port = C('email_port');
				// $smtpuser = C('email_user');
				// $smtppwd = C('email_pwd');
				// $mailtype = "TXT";
				// $sender = C('email_user');
				// $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
				// $to = $list['email']; 
				// $subject = C('reg_email_title');
				// $code = C('site_url').U('User/Index/checkFetchPass?uid='.$list['id'].'&code='.md5($list['id'].$list['password'].$list['email']));
				// $fetchcontent = C('reg_email_content');
				// $fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
				// $fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
				// $fetchcontent = str_replace('{code}',$code,$fetchcontent);
				// $body=$fetchcontent;
				//$body = iconv('UTF-8','gb2312',$fetchcontent);
				// $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
                                $this->reg_ucenter();
				$this->success('注册成功',U('User/Index/index'));
			}else{
				$this->error('注册失败',U('Index/login'));
			}
		}else{
			$this->error($db->getError(),U('Index/login'));
		}
	}
	
	public function checkpwd(){

		$where['username']=$this->_post('username');
		$where['email']=$this->_post('email');
		$db=D('Users');
		$list=$db->where($where)->find();
		if($list==false) $this->error('邮箱和帐号不正确',U('Index/regpwd'));
		
		$smtpserver = C('email_server'); 
		$port = C('email_port');
		$smtpuser = C('email_user');
		$smtppwd = C('email_pwd');
		$mailtype = "TXT";
		$sender = C('email_user');
		$smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
		$to = $list['email']; 
		$subject = C('pwd_email_title');
		$code = C('site_url').U('Index/resetpwd',array('uid'=>$list['id'],'code'=>md5($list['id'].$list['password'].$list['email']),'resettime'=>time()));
		$fetchcontent = C('pwd_email_content');
		$fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
		$fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
		$fetchcontent = str_replace('{code}',$code,$fetchcontent);
		$body=$fetchcontent;
		//$body = iconv('UTF-8','gb2312',$fetchcontent);inv
		$send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
		$this->success('请访问你的邮箱 '.$list['email'].' 验证邮箱后登录!<br/>');
		
	}
	
	public function resetpwd(){
		$where['id']=$this->_post('uid','intval');
		$where['username']=$this->_post('username');
		$where['password']=$this->_post('password','md5');
		$where['repassword']=$this->_post('repassword','md5');
		if($where['password']!==$where['repassword']){
			$this->error('两次密码不一致！');
		}
		if(M('Users')->save($where)){
			$this->success('修改成功，请登录！',U('Index/login'));
		}else{
			$this->error('密码修改失败！',U('Index/index'));
		}
	}


	public function sendMsg(){

		if(IS_POST){
			if (strlen($this->_post('mp'))!=11){
				exit('Error Phone Number!');
			}
			for($i=0;$i<6;$i++){
				$code .= rand(0,9);
			}

			session('verify',md5($code));
			session('reg_mp',md5($this->_post('mp')));
			//Sms::sendSms('admin','尊敬的客户，注册验证码是：'.$code.',我们的工作人员不会向您索取本条消息内容，切勿向任何人透漏',$this->_post('mp'));

			require('./PigCms/Lib/ORG/RestSMS.php');

			sendTempSMS($this->_post('mp'),array($code),"4764");//手机号码，替换内容数组，模板ID 4764
		
		}else{
			exit("Error!");
		}
	}


}