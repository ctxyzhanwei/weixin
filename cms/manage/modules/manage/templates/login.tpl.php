<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DB_CHARSET;?>" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<title><?php echo SITE_NAME;?></title>
<script src="/js/mootools1.3.js"></script>
<script src="/js/mootools-more.js"></script>
<script src="/js/mooauto.js"></script>
<script src="/js/config.php"></script>
<script src="js/user.js"></script>
</head>
<body id="body" class="">
<div class="logintop"></div>
<div class="loginmiddle">
<?php
$randStr=autoRrandStr(4);
$_SESSION['validCode']=$randStr;
if (isset($_GET['error'])){
	$errors=array('errorcode'=>'验证码不正确','notmatch'=>'用户名和密码不匹配');
	echo '<div id="tip" style="display: block; visibility: visible; opacity: 1;">'.$errors[$_GET['error']].'</div>';
}
?>

<form method="POST" action="login.php" id="autoForm" onsubmit="return checkform();" action="">用户：<input class="colorblur" type="text" style="width:300px;height:18px;" name="email" id="email" value="<?php if (isset($_GET['user'])){echo htmlspecialchars($_REQUEST['user'],ENT_COMPAT ,'GB2312');}?>"></input><br>密码：<input class="colorblur" type="password" style="width:300px;height:18px;" name="password" id="password"></input><br>验证：<input class="colorblur" name="validCode" id="validCode" type="text" style="width:100px;height:18px;"></input> <span class="validCode"><img onclick='this.src=this.src+"&"+Math.random()' id="vcImg" src="/script.php?oper=checkCode&width=70&height=25&codeNum=4&backGround=&fontColor=" style="cursor:pointer" align="absmiddle"></img></span>&nbsp;&nbsp;<input style="cursor:pointer" value="登 陆" class="loginButton" name="dosubmit" id="dosubmit" type="submit"></input></form></div>
<div class="loginbottom"></div>
<script>
var changeVC=function(){
	var req = new Request.HTML({url:'/validcode.php',
	onComplete: function() {
		$('vcImg').setProperty('src',imgUrlRoot+'/vc.png?'+$time());
	}
	});
	req.send();
}
function checkform(){
	var emailPattern=/(\S)+[@]{1}(\S)+[.]{1}(\w)+/;
		if(!emailPattern.exec($("email").value.trim())){
			showErrorTip('用户格式不正确');
			return false;
		}else if($('password').value.trim()==''){
			showErrorTip('请输入密码');
			return false;
		}else if($('validCode').value.trim()==''){
			showErrorTip('请输入验证码');
			return false;
		}else if($('validCode').value.length!=4){
			showErrorTip('验证码不正确');
			changeVC();
			return false;
		}
		return true;
}
</script>
<script language="JavaScript">
if (window.top != self){
	window.top.location = self.location;
}
</script>
</body></html>