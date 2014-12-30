<?php
class userObj {
	public function __construct(){
		$this->user_db = bpBase::loadModel('user_model');
		$this->store_user_db = bpBase::loadModel('store_user_model');
	}
	public function isUsernameOccupied($username,$except=0,$storeUserIndependent=0){
		if (!$storeUserIndependent){
			$dbModel=$this->user_db;
		}else {
			$dbModel=$this->store_user_db;
		}
		$except=intval($except);
		if(!$except){
			$rt=$dbModel->get_one(array('username'=>$username));
		}else {
			$rt=$dbModel->get_one('`username`=\''.$username.'\' AND `uid`!='.$except);
		}
		return $rt?true:false;
	}
	public function isMpOccupied($mp,$except=0,$storeUserIndependent=0){
		if (!$storeUserIndependent){
			$dbModel=$this->user_db;
		}else {
			$dbModel=$this->store_user_db;
		}
		$except=intval($except);
		if(!$except){
			$rt=$dbModel->get_one(array('mp'=>$mp));
		}else {
			$rt=$dbModel->get_one('`mp`=\''.$mp.'\' AND `uid`!='.$except);
		}
		return $rt?1:0;
	}
	public function isEmailOccupied($email,$except=0,$storeUserIndependent=0){
		if (!$storeUserIndependent){
			$dbModel=$this->user_db;
		}else {
			$dbModel=$this->store_user_db;
		}
		$except=intval($except);
		if(!$except){
			$rt=$dbModel->get_one(array('email'=>$email));
		}else {
			$rt=$dbModel->get_one('`email`=\''.$email.'\' AND `uid`!='.$except);
		}
		return $rt?true:false;
	}
	public function signup($username,$email,$password,$storeUserIndependent=0,$options=array()){
		if (!$storeUserIndependent){
			$dbModel=$this->user_db;
		}else {
			$dbModel=$this->store_user_db;
		}
		$salt=strtolower(rand(111111,999999));
		$password=toPassword($password,$salt);
		if ($this->isUsernameOccupied($username,0,$storeUserIndependent)){
			return -3;
		}elseif(!validEmail($email)) {
			return -5;
		}elseif ($this->isEmailOccupied($email,0,$storeUserIndependent)){
			return -6;
		}else {
			$regip=ip();
			$row=array('username'=>$username,'password'=>$password,'email'=>$email,'regip'=>$regip,'regtime'=>SYS_TIME,'lastloginip'=>$regip,'lastlogintime'=>SYS_TIME,'salt'=>$salt);
			if (is_array($options)&&$options){
				foreach ($options as $k=>$v){
					$row[$k]=$v;
				}
			}
			$rt=$dbModel->insert($row,1);
			if($rt){
				return $rt;
			}else {
				return 0;
			}
		}
	}
	public function signupWithUC($username,$password,$email,$storeUserIndependent=0,$options=array()){
		$rt=1;
		if (!$storeUserIndependent){
			if (SYNC_WITH_UC){
				$ucenterUserName=$username;
				if (defined('UCENTER_CHARSET')&&UCENTER_CHARSET=='utf-8'){
					$ucenterUserName=iconv('gbk','utf-8',$ucenterUserName);
				}
				include_once(ABS_PATH.'/uc_client/client.php');
				$rt = uc_user_register($ucenterUserName, $password, $email);
			}elseif (defined('SYNC_WITH_PHPWIND')&&SYNC_WITH_PHPWIND){
				define('AUTOSYSTEM','1');
				include ABS_PATH.'/pw_api.php';
				include ABS_PATH.'/uc_client/uc_client.php';

				$rt = uc_user_register($username, md5($password), $email);
				//把错误结果转换为ucenter的错误代码
				switch ($rt){
					default:
						break;
					case -2:
						$rt=-3;//用户名已被注册
						break;
					case -3:
						$rt=-4;//邮箱非法
						break;
					case -4:
						$rt=-6;//邮箱已经被注册
						break;
				}
			}
		}
		if ($rt>0){
			$rt=$this->signup($username,$email,$password,$storeUserIndependent,$options);
			if ($rt){
				setcookie('jsusername',escape($username),SYS_TIME+2592000,'/',DOMAIN_ROOT);
			}
			//
			if ($rt<1){//cannot insert into auto_user table,so delete the record in uc_member
				if (SYNC_WITH_UC&&!$storeUserIndependent){
					include ABS_PATH.'/uc_client/client.php';
					uc_user_delete($username);
				}
			}
		}
		return $rt;
	}
	public function getUserByUsername($username){
		$user=$this->user_db->get_row(array('username'=>$username));
		return $user;
	}
	public function getUserByUID($uid,$storeUserIndependent=0){
		$uid=intval($uid);
		if ($uid>0){
			$where=array('uid'=>$uid);
			if (!$storeUserIndependent){
				$user=$this->user_db->get_row($where);
			}else {
				$user=$this->store_user_db->get_row($where);
			}
			return $user;
		}else {
			return null;
		}
	}
	public function signin($username,$password,$storeUserIndependent=0){
		$username=format_bracket(trim($username));
		$where=array('username'=>$username);
		if (!$storeUserIndependent){
			$user=$this->user_db->get_row($where);
		}else {
			$user=$this->store_user_db->get_row($where);
		}
		if (!$user){
			return -1;
		}else {
			$salt=$user->salt;
			$password=toPassword($password,$salt);
			$selectPassword=$user->password;
			if ($password!=$selectPassword){
				return -2;
			}else {
				return intval($user->uid);
			}
		}
	}
	public function foreGroundSignin($userName,$unencryptPw,$nextUrl,$rememberMe=1){
		$systemConfig=loadConfig('system');
		$oUserName=$userName;
		//判断域名是不是ip
		$hostSections=explode('.',$_SERVER['HTTP_HOST']);//ip
		$isIP=0;
		if (count($hostSections)==4&&intval($hostSections[3])){
			$isIP=1;
		}
		//
		$sync=0;
		$echoStr='';
		$signSuccess=0;
		//
		if ($systemConfig['syncWithUc']){
			$ucenterUserName=$userName;
			if (defined('UCENTER_CHARSET')&&UCENTER_CHARSET=='utf-8'){
				$ucenterUserName=iconv('gbk','utf-8',$ucenterUserName);
			}
			$sync=1;
			include ABS_PATH.'uc_client'.DIRECTORY_SEPARATOR.'client.php';
			list($uid, $userName, $password, $email) = uc_user_login($ucenterUserName, $unencryptPw);
			if($uid > 0) {
				$ucsynlogin = uc_user_synlogin($uid);
				$signSuccess=1;
				//
				$echoStr=$ucsynlogin;
			}
		}elseif ($systemConfig['syncWithPhpwind']){
			$sync=1;
			define('AUTOSYSTEM','1');
			include ABS_PATH.'pw_api.php';
			
			include ABS_PATH.'uc_client'.DIRECTORY_SEPARATOR.'uc_client.php';
			$logintype=0; 	//登陆类型 0,1,2分别为 用户名,uid,邮箱登陆
			$pw=md5($unencryptPw);
			/*
			同步登录的返回值
			Array {
			Status:-1 用户名错误，找不到用户；-2 密码错误；-3 邮箱地址重复；1 正常登陆；
			Uid：用户ID
			Username：用户名
			Synlogin: 同步登陆代码（js）
			}
			*/
			
			$userArr=uc_user_login($userName, $pw, $checkques = 0, $question = '', $answer = '');
			$thisUser=uc_user_get($userName);
			$email=$thisUser['email'];
			//
			$status=$userArr['status'];
			$uid=$userArr['uid'];
			$userName=$userArr['username'];
			if($status > 0) {//login success
				$signSuccess=1;
				$echoStr=$userArr['synlogin'];
			}
		}
		if (!$sync){//if not sync with ucenter or phpwind
			$signSuccess=$this->signin($oUserName,$unencryptPw);
			$this->uid=$signSuccess;
		}
		if ($signSuccess>0){//登录成功，设置session和cookies
			$u=$this->getUserByUsername($oUserName);

			//
			if ($u){
				$this->uid=$u->uid;
				$uid=$u->uid;
				$this->updateIP($u->uid);
				$_SESSION['autouid']=$u->uid;
				session_regenerate_id();
				if($rememberMe){
					setcookie('autousername',$oUserName,SYS_TIME+2592000,'/',DOMAIN_ROOT);
					if ($isIP){
						setcookie('autousername',$oUserName,SYS_TIME+2592000,'/',$_SERVER['HTTP_HOST']);
					}
				}
				unset($_SESSION['autoAdminUid']);
			}else {//ucenter或者phpwind上有此用户而系统内没有，注册到系统
				$rt=$this->signup($oUserName,$email,$password);
				$this->uid=$rt;
				//login
				setcookie('autousername',$oUserName,SYS_TIME+2592000,'/',DOMAIN_ROOT);
				if ($isIP){
					setcookie('autousername',$oUserName,SYS_TIME+2592000,'/',$_SERVER['HTTP_HOST']);
				}
			}
			//next url
			if (!strlen($nextUrl)){
				$nextUrl='/';
			}
			
			//检查是不是经销商
			$store_db=bpBase::loadModel('store_model');
			$storeUserIndependent=0;//经销商用户是否单独建表存储
			if (intval(loadConfig('store','storeUserIndependent'))){
				$storeUserIndependent=1;//经销商用户是否单独建表存储
			}
			$isStoreUser=0;
			if (!$storeUserIndependent){
				$isStoreUser=$store_db->get_one(array('uid'=>$uid));
				if (!$isStoreUser){
					$usedcar_store_db=bpBase::loadModel('usedcar_store_model');
					$isStoreUser=$usedcar_store_db->get_one(array('uid'=>$uid));
				}
			}
			if ($isStoreUser){//如果是经销商
				$_SESSION['autostoreuid']=$u->uid;
				if (isset($_POST['rememberme'])){
					setcookie('autostoreuid',$u->uid,SYS_TIME+2592000,'/',DOMAIN_ROOT);
				}
			}
			if ($isStoreUser>0&&AUTO_SKIN=='ahauto'){//安徽汽车网的经销商用户会自动跳转到经销商控制面板
				$nextUrl='/storeUser.php';
			}
			//
			if ($sync){//如果跟其他集成，则输出同步登陆代码
				if (defined('UCENTER_CHARSET')&&UCENTER_CHARSET=='utf-8'){
					$echoStr=iconv('utf-8','gbk',$echoStr);
				}
				//$successStr='<script>Cookie.write(\'jsusername\',\''.$oUserName.'\',{domain:\''.DOMAIN_ROOT.'\'});</script>';
			}else {
				//$successStr='<script>Cookie.write(\'jsusername\',\''.$oUserName.'\',{domain:\''.DOMAIN_ROOT.'\'});</script>';
				//return $signSuccess;
			}
			setcookie('jsusername',escape($oUserName),SYS_TIME+2592000,'/',DOMAIN_ROOT);
			if ($sync){
				echo $echoStr.'<script src="'.JS_URL_ROOT.'/mootools1.3.js"></script>
<script src="'.JS_URL_ROOT.'/mootools-more.js"></script>'.$successStr.'<span style="font-size:12px;">'.$oUserName.'，登陆成功，正在跳转</span><script>
window.addEvent(\'domready\',function(){
	(function(){window.location.href=\''.$nextUrl.'\';}).delay(2000);
})
</script>';
			}else {
				showMessage('登录成功'.$successStr,$nextUrl,2000,0,0);
			}
		}else {//登录不成功
			if (!isset($_POST['userType'])){
				$backUrl=MAIN_URL_ROOT.'/sign.php?oper=signin';
			}else {
				$backUrl=$_SERVER['HTTP_REFERER'];
			}
			showMessage('用户名和密码不匹配',$backUrl,2000,1,1);
			return -1;
		}
	}
	function showLoginSuccessMsg($tip,$nextUrl){
		showMessage($tip,$nextUrl,2000,0,0);
	}
	public function updateIP($uid,$storeUserIndependent=0){
		$updateArr=array('lastloginip'=>ip(),'lastlogintime'=>SYS_TIME);
		$whereArr=array('uid'=>$uid);
		if (!$storeUserIndependent){
			return $this->user_db->update($updateArr,$whereArr);
		}else {
			return $this->store_user_db->update($updateArr,$whereArr);
		}
	}
	public function storeSignIn($userName,$unencryptPw,$nextUrl,$rememberMe=1,$storeType=1){
		$oUserName=$userName;
		//判断域名是不是ip
		$hostSections=explode('.',$_SERVER['HTTP_HOST']);//ip
		$isIP=0;
		if (count($hostSections)==4&&intval($hostSections[3])){
			$isIP=1;
		}
		//
		$signSuccess=$this->signin($oUserName,$unencryptPw,1);
		$this->uid=$signSuccess;
		if ($signSuccess>0){//登录成功，设置session和cookies
			$oUserName=trim($oUserName);
			$u=$this->store_user_db->get_row(array('username'=>$oUserName));
			//
			if ($u){
				$this->uid=$u->uid;
				$uid=$u->uid;
				$this->updateIP($u->uid,1);
				$_SESSION['autostoreuid']=$u->uid;
				if (isset($_POST['rememberme'])){
					setcookie('autostoreuid',$u->uid,SYS_TIME+2592000,'/',DOMAIN_ROOT);
				}
				session_regenerate_id();
				unset($_SESSION['autoAdminUid']);
			}
			//next url
			if (!strlen($nextUrl)){
				//$nextUrl='/';
			}
			//
			$storeUserIndependent=0;//经销商用户是否单独建表存储
			if (intval(loadConfig('store','storeUserIndependent'))){
				$storeUserIndependent=1;//经销商用户是否单独建表存储
			}
			if (!$storeUserIndependent){
				setcookie('jsusername',escape($oUserName),SYS_TIME+2592000,'/',DOMAIN_ROOT);
			}
			$successStr='<script></script>';
			showMessage($successStr.'登录成功',$nextUrl,2000,0,0);
		}else {//登录不成功
			$backUrl=$_SERVER['HTTP_REFERER'];
			showMessage('用户名和密码不匹配',$backUrl,2000,1,1);
			return -1;
		}
	}
	public function updatePassword($uid,$newPassword,$oldPassword='',$storeUserIndependent=0){
		if (!$storeUserIndependent){
			$dbModel=$this->user_db;
		}else {
			$dbModel=$this->store_user_db;
		}
		$uid=intval($uid);
		$newPassword=trim($newPassword);
		$user=$this->getUserByUID($uid,$storeUserIndependent);
		$salt=$user->salt;
		$newPassword=toPassword($newPassword,$salt);
		$rt=$dbModel->update(array('password'=>$newPassword),array('uid'=>$uid));
		return $rt;
	}
	public function adminLoginWithEmail($email,$pw){
		$email=trim($email);
		$email=format_bracket($email);
		if (get_magic_quotes_gpc()){
			$email=mysql_real_escape_string(stripslashes($email));
		}else {
			$email=mysql_real_escape_string($email);
		}
		$thisUser=$this->user_db->get_row(array('email'=>$email));
		if ($thisUser){
			$password=toPassword($pw,$thisUser->salt);
			if ($thisUser->password==$password){
				if (intval($thisUser->isadmin)){
					return intval($thisUser->uid);
				}else {
					return -4;
				}
			}else {
				return -2;
			}
		}else {
			return -1;
		}
	}
	public function getPortrait($uid,$type,$logo=''){
		$uid=intval($uid);
		$types=array('o','m','s');
		if (!in_array($type,$types)){
			$type='s';
		}
		if (!$logo){
			$thisUser=$this->getUserByUID($uid);
			$logoType=$type.'logo';
			$logo=$thisUser->$logoType;
		}
		if(!strlen($logo)){
			$logo=MAIN_URL_ROOT.'/images/'.AUTO_SKIN.'/portrait.gif';
		}
		return $logo;
	}
}