<?php /* Smarty version 2.6.18, created on 2014-05-03 16:46:02
         compiled from 0/ifgamd1399022297/header.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="telephone=no" name="format-detection" />
<meta name="keywords" content="<?php echo $this->_tpl_vars['metaKeywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['metaDescription']; ?>
"/>
<LINK href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery-1.6.4.min.js" tppabs="common/js/jquery-1.6.4.min.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.js" tppabs="common/js/nav.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/search.js" tppabs="common/js/search.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/banner.js" tppabs="common/js/banner.js"></script>
<title><?php echo $this->_tpl_vars['metaTitle']; ?>
</title>
</head>
<body>
<?php if (! $this->_tpl_vars['homepage']): ?>
<center class="subtop">
    <a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="<?php echo $this->_tpl_vars['site']['name']; ?>
"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" tppabs="3g/images/logo.png" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
" class="logo"></a>
</center>
<?php if (! $this->_tpl_vars['ismap']): ?>
<div class="sub">
	<div class="iconleft"></div>
    <div class="iconright">
    	<p><a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="首页" class="iconhome"></a></p>
        <p><a href="javascript:void(0);" title="搜索" class="iconsearch"></a></p>
        <p><a href="javascript:void(0);" title="导航" class="iconnav"></a></p>
    </div>
   	<div class="search">
    	<div>
            <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
                <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
                <input type="submit" class="button" value="">
            </form>
        </div>
    </div>
    <div class="nav" id="nav">
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
        <a href="#" class="backtop"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/backtop.png" tppabs="3g/images/backtop.png" alt="返回顶部"></a>
    </div>
    <p class="clear"></p>
<?php endif; ?>
<?php endif; ?>