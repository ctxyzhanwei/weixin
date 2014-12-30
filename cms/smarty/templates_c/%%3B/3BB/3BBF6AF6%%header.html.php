<?php /* Smarty version 2.6.18, created on 2014-05-21 15:44:12
         compiled from 5/iutuuc1400658077/header.html */ ?>
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
<LINK href="smarty/templates/tpls/gangse/jquery.mobile.structure-1.2.0.min.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<LINK href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery-1.6.4.min.js" ></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.js" ></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/search.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/banner.js"></script>
</head>
<body>
<?php if (! $this->_tpl_vars['ismap']): ?>
<div data-role="page" data-theme="f">
<div class="wrap">
<?php endif; ?>
<div class="header" data-role="header">
  <div class="logo"><a href="<?php echo $this->_tpl_vars['homeurl']; ?>
" title=""><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" height="35" tppabs="/3g/images/logo.png" alt=""/></a></div>
  <div class="menu_but"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/menu.png" tppabs="/3g/images/menu.png" alt="导航按钮"/></div>
  <div class="clear"></div>
  <div class="nav"> <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/arrow_top.png" tppabs="/3g/images/arrow_top.png" alt="上箭头"/>
    <ul>
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
  </div>
</div>
<?php if (! $this->_tpl_vars['ismap']): ?>
<div class="search">
 <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
    <input type="text" class="search_txt" name="SeaStr" id="SeaStr" data-role="none" placeholder="请输入关键词"/>
    <input type="submit" class="search_but" data-role="none" value="">
  </form>
</div>
<?php endif; ?>