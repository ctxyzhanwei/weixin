var changeVC=function(){
	var req = new Request.HTML({url:'validcode.php',
	onComplete: function() {
		$('vcImg').setProperty('src','/images/vc.png?'+$time());
	}
	});
	req.send();
}
mooAutoObject.initAdminLogin=function(){
	$('autoForm').addEvent('submit',function(e){
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
		}else{
			new Event(e).stop();
			$('autoForm').set('send',{encoding:'gbk',urlEncoded:true,onComplete:function(){
				if(this.response.text.toInt()>0){
					if($('tip')){
						$('tip').dispose();
					}
					window.location.href='index.php'
				}else{
					switch(this.response.text){
						default:
						showErrorTip('未知错误'+this.response.text);
						break;
						case '-1':
						case '-2':
						showErrorTip('邮箱或密码错误'+this.response.text);
						break;
					    case '-3':
						showErrorTip('验证码不正确');
						break;
					}
				}
			}}).send();
		}
	});
}
var syncDiscuzUser=function(){
	showDialog(null);
	$('dialog').set('html',dialogCloseStr('同步用户')+'<div id="dialogdiv" style="padding:0 10px;margin:0;"><div id="rt" style="margin: 0pt 10px; display: none;"></div>'+$('syncDiscuzUserInput').get('html').replace('autoForm','form')+'</div>');
	$('form').addEvent('submit',function(e){
		new Event(e).stop();
		$('form').setStyle('display','none');
		$('rt').setStyle('display','block');
		$('rt').set('html','<img src="image/loading.gif" align="absmiddle"></img> 正在同步，别走开……');
		$('form').set('send',{encoding:'gbk',urlEncoded:true,onComplete:function(){
			$('rt').set('html',this.response.text+'  <a href="javascript:void(0)" onclick="syncDiscuzUser()">继续</a>');
			setOverlay();
		}}).send();
	});
	setOverlay();
}
var storeDelete=function(id){
	var elID='tr'+id;
	var url=$(elID).getProperty('rel');
	ajaxDelete($(elID),url);
}
var showUserInfoInDialog=function(uid,rootDeep){
	showDialog(null);
	var url=rootDeep?'../json.php?type=storeUser&uid='+uid:'json.php?type=storeUser&uid='+uid;
	var jr = new Request.JSON({url:url, urlEncoded:true, encoding:'gbk', onComplete: function(j){
		var users=j.user;
		var thisUser=users[0];
		$('dialog').set('html',dialogCloseStr('用户')+'<div style="padding:0 10px;margin:0; line-height:160%;">UID：'+uid+'<br>账号：'+thisUser.username+'<br>邮箱：'+thisUser.email+'<br>注册时间：'+thisUser.regTime+'('+thisUser.regIP+' '+thisUser.regAddress+')<br>最后登陆：'+thisUser.lastLoginTime+'('+thisUser.lastLoginIP+' '+thisUser.lastLoginAddress+')</div>');
		setOverlay();
	}}).get();
}