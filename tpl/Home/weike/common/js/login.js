$(function(){
	
	$('.login').click(function(){
		user_login();
	});

	function user_login(){
	//变换为登陆中的状态
	$('.login.btn').html('登陆中...');
	$('.login.btn').css('background-color','#c2cad4');
	document.getElementById("login").submit();
	}
	
	$('.reg').click(function(){
		user_reg();
	});

	function user_reg(){
	//变换为登陆中的状态
	$('.reg.btn').html('注册中...');
	$('.reg.btn').css('background-color','#c2cad4');


if (document.getElementById("userun").value=="")
 {
   alert("用户名不能为空，请填写用户名后再次提交!")
   document.getElementById("userun").focus();
	$('.reg.btn').html('再次注册');
	$('.reg.btn').css('background-color','#2E9FFF');
	return false
  }
if (document.getElementById("userpwd").value=="")
 {
   alert("密码不能为空，请填写密码后再次提交!")
   document.getElementById("userpwd").focus();
	$('.reg.btn').html('再次注册');
	$('.reg.btn').css('background-color','#2E9FFF');
	return false
  }
if (document.getElementById("userrepwd").value=="")
 {
   alert("二次确认密码不能为空，请填写确认密码后再次提交!")
   document.getElementById("userrepwd").focus();
	$('.reg.btn').html('再次注册');
	$('.reg.btn').css('background-color','#2E9FFF');
	return false
  }
if (document.getElementById("userl_xianqu").value=="")
 {
   alert("区县信息不能为空，请填写区县信息后再次提交!")
   document.getElementById("userl_xianqu").focus();
	$('.reg.btn').html('再次注册');
	$('.reg.btn').css('background-color','#2E9FFF');
	return false
  }

	}

	$('input[name=password]').keydown(function(e){
			if(e.keyCode==13){ $('.login').click()};
	});
});
