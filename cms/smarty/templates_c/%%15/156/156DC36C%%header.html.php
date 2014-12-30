<?php /* Smarty version 2.6.18, created on 2013-11-03 20:36:31
         compiled from tpls/blue1/header.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title><?php echo $this->_tpl_vars['metaTitle']; ?>
</title>
<meta name="keywords" content="<?php echo $this->_tpl_vars['metaKeywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['metaDescription']; ?>
"/>
<link href="templates/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery.mobile.structure-1.2.0.min.css?<?php echo $this->_tpl_vars['rand']; ?>
" tppabs="/css/jquery.mobile.structure-1.2.0.min.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel="stylesheet" type="text/css"/> 
<LINK href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
" rel=stylesheet>
<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery-1.6.4.min.js" tppabs="/js/jquery-1.6.4.min.js"></script>
<?php echo '
<script type="text/javascript">
$(document).bind("mobileinit", function(){
	$.mobile.ajaxEnabled=false;
});
</script>
'; ?>

<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/search.js" tppabs="/js/search.js"></script>
<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.js" tppabs="/js/nav.js"></script>
<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery.mobile-1.2.0.min.js" tppabs="/js/jquery.mobile-1.2.0.min.js"></script>
<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/tianlan/banner.js" tppabs="common/js/banner.js"></script>
</head>

<body class="subbg">
<div class="topbg">
	<div><a href="<?php echo $this->_tpl_vars['homeurl']; ?>
" data-transition="slideup"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" height="35" tppabs="/images/logo.png" class="logo" /></a></div>
    <div class="search">
        <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
            <input type="text" name="SeaStr" id="SeaStr" class="text" style="color:#0089BB" value="输入关键词" />
        	<input type="image" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/searchbtn.png" tppabs="/images/searchbtn.png" class="button">
        </form>
    </div>
</div>
<img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/leftbutton.png" tppabs="/images/leftbutton.png" class="leftbtn" id="leftbtn" ><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/rightbutton.png" tppabs="/images/rightbutton.png" class="rightbtn" id="rightbtn">
<div class="bg4">
    <div class="nav" id="nav">
    <div class="navbg" id="draggable">
	<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
</a>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
    </div>
    </div>
</div>