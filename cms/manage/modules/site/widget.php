<?php
bpBase::loadAppClass('front','front',0);
class widget extends front {
	function __construct() {
		parent::__construct();
	}
	function userLoginInfo(){
		if (!$this->uid){
			echo 'document.write(\'<nobr><span></span><form id="signinForm" action="/action.php?type=signin&from=mini" method="POST"><span class="login"><span id="signinTip"></span>&nbsp;用户名：<input name="m_username" id="m_username" type="text" class="input_login" style="width:80px;height:15px;border:1px solid #ccc;" />  密码：  <input type="password" name="m_password" id="m_password" class="input_login" style="width:80px;height:15px;border:1px solid #ccc;" /><input type="hidden" name="m_reference" id="m_reference" value="'.$_SERVER['HTTP_REFERER'].'"></input>  <input type="submit" class="miniSignin" value=""></input>  <input type="button" onclick="toSignup()" class="miniSignup" value=""></input></span></form></nobr>\');miniSignin();var toSignup=function(){window.location.href="'.MAIN_URL_ROOT.'/signup.do"};';
		}else {
			echo 'document.write(\'<span class="login"><a href="http://'.DOMAIN_NAME.'/user/user.php" target="_parent">'.$this->username.'的个人中心</a>&nbsp;&nbsp;<a href="http://'.DOMAIN_NAME.'/user/user.php?type=updatePassword" target="_parent">修改密码</a>&nbsp;&nbsp;<a href="http://'.DOMAIN_NAME.'/signout.do" target="_parent">退出</a></span>\')';
		}
	}
	function userLoginInfoDefault(){
		if ($this->uid<1){
			$weiboLogin='';
			if (loadConfig('sina','open')){
				$weiboLogin='<a href="/index.php?m=user&c=index&a=weiboLogin"><img style="margin:0 0 3px 0" src="'.IMG_URL_ROOT.'/share_sina.gif" align="absmiddle" /> 微博登录</a>';
			}
			$qqLogin='';
			if (loadConfig('qq','open')){
				$qqLogin=' <a href="/index.php?m=user&c=index&a=qqLogin"><img style="margin:0 0 3px 0" src="'.IMG_URL_ROOT.'/qq.png" align="absmiddle" /> QQ登录</a>';
			}
			echo 'document.write(\'<nobr><span></span><form rel="o" id="signinForm" action="'.MAIN_URL_ROOT.'/index.php?m=user&c=index&a=action_signin" method="POST"><span class="login"><input name="m_username" id="m_username" type="text" class="u_nam" value="用户名" /><input type="password" name="m_password" id="m_password" class="u_pwd" value="******" /><input type="hidden" name="m_reference" id="m_reference" value="'.$_SERVER['HTTP_REFERER'].'"></input>  <input type="submit" class="u_btn" value="登录"></input></span> '.$weiboLogin.$qqLogin.' <p class="ure"><a href="'.STORE_URL_ROOT.'/login.php">经销商登录</a>&nbsp;<a href="'.MAIN_URL_ROOT.'/signup.do">注册</a>&nbsp;<a href="'.MAIN_URL_ROOT.'/getPassword.do">忘记密码？</a></p><span class="error" id="signinTip"></span></form></nobr>\');miniSignin();if($("m_username")){
				$("m_username").addEvents({
"blur":function(){if(this.value==""){this.value="用户名";}},
"focus":function(){if(this.value=="用户名"){this.value="";}}
}
);
}

if($("m_password")){
$("m_password").addEvents(
{
"blur":function(){if(this.value==""){this.value="******";}},
"focus":function(){if(this.value=="******"){this.value="";}}
}
);
}';
		}else {
			echo 'document.write(\'<p class="login ure" style="left:10px;"><a href="'.MAIN_URL_ROOT.'/user/user.php" target="_parent">'.$this->username.'</a>&nbsp;&nbsp;<a href="'.MAIN_URL_ROOT.'/user/user.php?type=updatePassword" target="_parent">修改密码</a>&nbsp;&nbsp;<a href="http://'.DOMAIN_NAME.'/signout.do" target="_parent">退出</a></p>\')';
		}
	}
	function areaNameByIP(){
		$geoObj=bpBase::loadAppClass('geoObj','geo',1);
		$ipGeo=$geoObj->getGeoByIP(ip());
		echo 'document.write(\''.$ipGeo->name.'\');';
	}
	function citySiteLink(){
		$geoObj=bpBase::loadAppClass('geoObj','geo',1);
		if (isset($_COOKIE['cookie_cityid'])) {//按照cookie保存的来
			$geo_db = bpBase::loadModel('geo_model');
			$ipGeo=$geo_db->getGeoByID(intval($_COOKIE['cookie_cityid']));
		}else {
			$ipGeo=$geoObj->getGeoByIP(ip());
			if (!$ipGeo){
				$geo_db = bpBase::loadModel('geo_model');
				$defaultChildLocation=$geo_db->getDefaultChildLocation();
				$ipGeo=$defaultChildLocation;
			}
		}
		$childSiteConfig=loadConfig('childSite');
		$cityUrlFormat=$childSiteConfig['childSiteUrlFormat']?$childSiteConfig['childSiteUrlFormat']:MAIN_URL_ROOT.'/city/index.php?index={geoIndex}';
		$link=str_replace('{geoIndex}',$ipGeo->geoindex,$cityUrlFormat);
		echo '<a href="'.$link.'" target="_blank" class="currentCitySiteLink">'.$ipGeo->name.'</a>';
	}
	function currentCityInfo(){
		$geoObj=bpBase::loadAppClass('geoObj','geo',1);
		$ipGeo=$geoObj->getGeoByIP(ip());
		if (!$ipGeo){
			$geo_db = bpBase::loadModel('geo_model');
			$defaultChildLocation=$geo_db->getDefaultChildLocation();
			$ipGeo=$defaultChildLocation;
		}
		echo '{"city":[{"name":"'.$ipGeo->name.'","id":"'.$ipGeo->id.'","geoindex":"'.$ipGeo->geoindex.'"}]}';
	}
}
?>