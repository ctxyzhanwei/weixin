<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="js/mootools1.3.js"></script>
<script src="js/mootools-more.js"></script>
<script src="js/config.php"></script>
<script src="js/manage.js"></script>
<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script src="js/ajaxUpload.js"></script>
<title></title>
</head>
<body id="body">
<?php
define('UNYUN_BUCKET',C('up_bucket'));
define('UNYUN_USERNAME',C('up_username'));
		define('UNYUN_PASSWORD',C('up_password'));
		define('UNYUN_FORM_API_SECRET',C('up_form_api_secret'));
		define('UNYUN_DOMAIN',C('up_domainname'));
		
$bucket = $this->bucket; /// 空间名
		$form_api_secret = $this->form_api_secret; /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）
		$options = array();
		$options['bucket'] = $bucket; /// 空间名
		$options['expiration'] = time()+600; /// 授权过期时间
		$options['save-key'] = '/'.$this->token.'/{year}/{mon}/{day}/'.time().'_{random}{.suffix}'; /// 文件名生成格式，请参阅 API 文档
		$options['allow-file-type'] = C('up_exts'); /// 控制文件上传的类型，可选
		$options['content-length-range'] = '0,'.intval(C('up_size'))*1000; /// 限制文件大小，可选
		if (intval($_GET['width'])){
			$options['x-gmkerl-type'] = 'fix_width';
			$options['fix_width '] = $_GET['width'];
		}
		$options['return-url'] = C('site_url').'/index.php?g=User&m=Upyun&a=uploadReturn'; /// 页面跳转型回调地址
		$policy = base64_encode(json_encode($options));
		$sign = md5($policy.'&'.$form_api_secret); /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）

		
		
if(!isset($_GET['code']) || !isset($_GET['message']) || !isset($_GET['url']) || !isset($_GET['time'])){
$bucket = 'chenyun'; /// 空间名
$form_api_secret = 'fCWGgbBFB1VFX7E0yesvPirNJ0A='; /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）

$options = array();
$options['bucket'] = $bucket; /// 空间名
$options['expiration'] = time()+600; /// 授权过期时间
$options['save-key'] = '/{year}/{mon}/{day}/'.time().'_{random}{.suffix}'; /// 文件名生成格式，请参阅 API 文档
$options['allow-file-type'] = 'jpg,jpeg,gif,png'; /// 控制文件上传的类型，可选
$options['content-length-range'] = '0,1024000'; /// 限制文件大小，可选
if (intval($_GET['width'])){
	$options['x-gmkerl-type'] = 'fix_width';
	$options['fix_width '] = $_GET['width'];
}
$options['return-url'] = 'http://demo.pigcms.com/cms/manage/admin.php?m=manage&c=background&a=upyunPicUpload'; /// 页面跳转型回调地址
//$options['notify-url'] = 'http://demo.pigcms.com/cms/manage/admin.php?m=manage&c=background&a=upyunPicUpload'; /// 服务端异步回调地址, 请注意该地址必须公网可以正常访问

$policy = base64_encode(json_encode($options));
$sign = md5($policy.'&'.$form_api_secret); /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）
?>
<form enctype="multipart/form-data" action="http://v0.api.upyun.com/<?php echo $bucket?>/" id="thumbForm" method="POST" style="padding:10px 20px;">
<input type="hidden" name="policy" value="<?php echo $policy?>">
<input type="hidden" name="signature" value="<?php echo $sign?>">
<p><div>选择:<input type="file" style="width:80%;" name="file"></input></div><div style="padding:20px 0;text-align:center;"><input id="submitbtn" name="doSubmit" type="submit" class="button" value="上传"></input></div></p>
</form>

<?php
}else {
$form_api_secret = 'fCWGgbBFB1VFX7E0yesvPirNJ0A'; /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）


?>
<script>
var domid=art.dialog.data('domid');
// 返回数据到主页面
function returnHomepage(url){
	var origin = artDialog.open.origin;
	var dom = origin.document.getElementById(domid);
	dom.value = url;
	(function(){art.dialog.close();}).delay(1000);
}
returnHomepage('<?php echo $_GET['url'];?>');
</script>
<?php
}
?>
</body>
</html>