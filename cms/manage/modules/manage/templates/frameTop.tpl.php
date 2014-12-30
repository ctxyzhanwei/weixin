<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="js/mootools1.3.js"></script>
<script src="js/mootools-more.js"></script>
<title>无标题文档</title>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:84px;background:url(image/b.jpg) repeat-x;">
  <tr>
    <td style="padding:0 0 0 20px;width:200px;"><img src="image/logo-pigcms.png" border="0" /></td>
    <td>
    <div style="border-left:1px solid #436DA6">
<div style="margin:6px 0; text-align:right; padding-right:10px;">&nbsp;</div>
<div class="navtitle">
<?php
$shu='';

foreach ($menu as $k=>$item) {
	if (!isset($item['beta'])||!$item['beta']||(defined('BETA')&&BETA)){
	echo $shu.'<a id="menu_'.$k.'" href="?m=manage&c=background&a=frameLeft&type='.$k.'" onclick="parent.frames.right.location.href=\''.$item['link'].'\'" target="left">'.$item['text'].'</a>';
	$shu='|';
	}
}
?>
|<a href="<?php echo '../index.php?token='.$this->token;?>" target="_blank">访问网站</a>|<a href="<?php echo PIGCMS_URL.'/index.php?g=User&m=Home&a=set&token='.$this->token;?>" target=_top style="color:#99C9EA">返回管理中心</a>
</div>
</div>
</td>
  </tr>
</table>

</body>
</html>