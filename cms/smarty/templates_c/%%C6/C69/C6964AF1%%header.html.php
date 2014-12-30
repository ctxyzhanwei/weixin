<?php /* Smarty version 2.6.18, created on 2013-11-03 19:08:40
         compiled from tpls/v10/header.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="telephone=no" name="format-detection" />
<link rel="Shortcut Icon" href="favicon.ico" />
<link rel="Bookmark Icon" href="favicon.ico" />
<link href="logo.png" sizes="114x114" rel="apple-touch-icon">
<meta name="Author" content="024" />
<title><?php echo $this->_tpl_vars['metaTitle']; ?>
</title>
<meta name="keywords" content="<?php echo $this->_tpl_vars['metaKeywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['metaDescription']; ?>
"/>
<LINK href="templates/gangse/jquery.mobile.structure-1.2.0.min.css?<?php echo $this->_tpl_vars['rand']; ?>
" tppabs="/3g/css/jquery.mobile.structure-1.2.0.min.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<LINK href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery-1.6.4.min.js" tppabs="/3g/common/js/jquery-1.6.4.min.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.js" tppabs="/3g/common/js/nav.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/search.js" tppabs="/3g/common/js/search.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/banner.js" tppabs="common/js/banner.js"></script>
</head>
<body>
<div class="top">
	<a href="<?php echo $this->_tpl_vars['homeurl']; ?>
"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" alt="" height="42" class="logo"></a>
	<div class="topbtn">
		<a href="<?php echo $this->_tpl_vars['homeurl']; ?>
" title="返回首页"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/home.png" tppabs="http://900015.3g/images/home.png" alt="返回首页" class="homebtn"></a>
		<a href="javascript:void(0);" title="导航" class="navbtn"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.png" tppabs="http://900015.3g/images/nav.png" alt="导航"></a>
	</div>
</div>
<div class="nav">
	<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><p><?php echo $this->_tpl_vars['n']['name']; ?>
</p><span></span></a>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
	<p class="backtop1"><a href="javascript:void(0);" title="返回顶部" class="navbacktop"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/navbackbtn.png" tppabs="http://900015.3g/images/navbackbtn.png" alt="返回顶部"></a></p>
</div>
<div class="search">
	<span>搜索：</span>
	<div>
		<form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
			<input type="text" class="text" name="SeaStr" id="SeaStr" style="color:#005C00" placeholder="请输入搜索关键词"/>
			<input type="submit" class="button" value="">
		</form>
	</div>
</div>