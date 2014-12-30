<?php /* Smarty version 2.6.18, created on 2013-11-03 20:36:15
         compiled from tpls/hongsecanting/header.html */ ?>
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
<center><a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
" class="logo"></a></center>
<?php if (! $this->_tpl_vars['homepage'] && ! $this->_tpl_vars['ismap']): ?>
<div class="sub">
	<div class="subnav">
    	<a href="javascript:void(0);" title="搜索" class="subsearch"><center><img src="smarty/templates/tpls/hongsecanting/subsearch.png" tppabs="/images/subsearch.png" alt="搜索"></center></a>
        <a href="javascript:void(0);" title="导航" class="subnavbtn"><center><img src="smarty/templates/tpls/hongsecanting/nav.png" tppabs="/images/nav.png" alt="导航"></center></a>
        <a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="主页" class="subhome"><center><img src="smarty/templates/tpls/hongsecanting/subhome.png" tppabs="/images/subhome.png" alt="主页"></center></a>
    </div>
    <div class="subsearchbox">
        <div>
            <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
                <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
                <input type="submit" class="button" value="">
            </form>
        </div>
    </div>
    <div class="subnavbox">
       <?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
<span></span></a>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
        <a href="javascript:void(0);" title="收起" class="navback"><center><img src="smarty/templates/tpls/hongsecanting/backtop.png" tppabs="/images/backtop.png" alt="收起"></center></a>
    </div>
<?php endif; ?>