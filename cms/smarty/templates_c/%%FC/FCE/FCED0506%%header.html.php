<?php /* Smarty version 2.6.18, created on 2014-05-17 19:41:03
         compiled from 8/usvxht1400299488/header.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="telephone=no" name="format-detection" />
<title><?php echo $this->_tpl_vars['metaTitle']; ?>
</title>
<meta name="keywords" content="<?php echo $this->_tpl_vars['metaKeywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['metaDescription']; ?>
"/>
<LINK href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/jquery-1.6.4.min.js" tppabs="common/js/jquery-1.6.4.min.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/nav.js" tppabs="common/js/nav.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/search.js" tppabs="common/js/search.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/banner.js" tppabs="common/js/banner.js"></script>
</head>

<body>
<div class="topbg">

	<img src="smarty/templates/tpls/tianlan/searchbtn.jpg" tppabs="/images/searchbtn.png" alt="搜索按钮" class="searchbtn">

	<a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="<?php echo $this->_tpl_vars['site']['name']; ?>
" class="logo"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" height="26" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
"></a>
    <img src="smarty/templates/tpls/tianlan/navbtn.jpg" tppabs="images/navbtn.jpg" alt="导航" class="navbtn">
</div>
<div class="searchbox">

    <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
        <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
        <input type="submit" class="button" title="搜索" value="">
    </form>

</div>
<ul class="navbg">
<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<li><a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</ul>