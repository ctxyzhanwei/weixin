<?php
class login {
	function __construct() {
		parent::__construct();
	}
	public function login(){
		if(strtoupper($_SERVER['REQUEST_METHOD'])=='POST'){
			
			if (strtolower($_SESSION['validCode'])!=strtolower(trim($_POST['validCode']))){
				//记录日志
				$logInfo['success']=0;
				$logInfo['password']='';
				$user_loginlog_db->insert($logInfo);
				//echo SITE_NAME.':验证码输入错误，<a href="login.php?user='.$_POST['email'].'">返回重新登录</a>';
				echo '<script>window.location.href=\'?user='.$_POST['email'].'&error=errorcode\';</script>';
				exit();
			}else {
				$userObj=bpBase::loadAppCLass('userObj','user');
				$rt=$userObj->adminLoginWithEmail($_POST['email'],$_POST['password']);
				if ($rt>0){
					//记录日志
					$logInfo['success']=1;
					$logInfo['password']='';
					$user_loginlog_db->insert($logInfo);
					//
					if (!isah()){
						$thisUser=$userObj->getUserByUID($rt);
						setcookie('jsusername',escape($thisUser->username),SYS_TIME+2592000,'/',DOMAIN_ROOT);
						$r=setcookie('autousername',$thisUser->username,SYS_TIME+2592000,'/',DOMAIN_ROOT);
					}else {
						if (isset($_COOKIE['jsusername'])){
							setcookie('jsusername','',0);
							setcookie('jsusername','',0,'/',DOMAIN_ROOT);
							setcookie('jsusername','',0,'/',$_SERVER['HTTP_HOST']);
						}
					}
					delCache('rigthsOf'.$rt);
					delCache('citysOf'.$rt);
					$_SESSION['autoAdminUid']=$rt;
					//session_regenerate_id();
					$_SESSION['cmsuid']=$rt;
					//session_regenerate_id();

					//echo '<span style="font-size:12px;">登录成功，正在转向...如果您的浏览器不能自动跳转，<a href="index.php" style="font-size:12px;">请点击</a>';
					echo '<script>window.location.href=\'index.php\';</script></span>';
					exit();
				}else {
					//记录日志
					$logInfo['success']=0;
					$user_loginlog_db->insert($logInfo);
					//
					$_SESSION['autoAdminUid']=null;
					unset($_SESSION['autoAdminUid']);
					//echo SITE_NAME.':登录失败，<a href="login.php?user='.$_POST['email'].'">返回重新登录</a>';
					echo '<script>window.location.href=\'?user='.$_POST['email'].'&error=notmatch\';</script>';
					exit();
				}
			}
		}else{
			$m = empty($m) ? ROUTE_MODEL : $m;
			if(empty($m)) return false;
			include ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'login.tpl.php';
		}
	}
}
?>