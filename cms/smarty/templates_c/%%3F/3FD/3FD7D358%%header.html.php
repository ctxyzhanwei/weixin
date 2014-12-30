<?php /* Smarty version 2.6.18, created on 2013-11-03 19:30:34
         compiled from tpls/v_26/header.html */ ?>
<!doctype html>
    <html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $this->_tpl_vars['metaTitle']; ?>
</title>
<meta name="keywords" content="<?php echo $this->_tpl_vars['metaKeywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['metaDescription']; ?>
"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['cssdir']; ?>
/style.css?<?php echo $this->_tpl_vars['rand']; ?>
">
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/html5.js"></script>
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/json.js"></script>
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/globalOp.js"></script>
	
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/WapCircleImg.js"></script>
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/jquery.mobile-1.3.1.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.js"></script>
</head><body>
	
	<?php if (! $this->_tpl_vars['ismap']): ?>
	<div id="doc" class="doc"><div class="container"><div class="box_main">
	<header class="top">
	<div class="logo" style="height:65px; overflow:hidden"><a title="<?php echo $this->_tpl_vars['site']['name']; ?>
" href="<?php echo $this->_tpl_vars['homeurl']; ?>
" data-ajax="false"><img max-height="60" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
" src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
"></a>
	<div class="clear"></div>
	</div>
	
	<div name="栏目导航" class="navigation" id="divNav"><nav class="menu" id="navbgColor" config-style-data="<?php echo '{&quot;IsDefault&quot;:&quot;1&quot;,&quot;BgColor&quot;:&quot;#ddd&quot;,&quot;BgImg&quot;:null,&quot;TextColor&quot;:&quot;#ddd&quot;,&quot;HoverBgColor&quot;:&quot;#ddd&quot;,&quot;HoverBgImage&quot;:null,&quot;HoverTextColor&quot;:&quot;#ddd&quot;}'; ?>
" style="">
	<ul class="navBody" id="menu">
	 <?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['n']):
?>
<?php if ($this->_tpl_vars['k'] < 4): ?>
      <li><a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
</a></li>
	  <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
      <li><a href="javascript:void(0)" data-ajax="false" class="navbtn"><img style="margin-top:12px;height:15px;" src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/nav.png" alt="导航"></a></li>
    </ul></nav></div>
	<!--xiala nav start-->
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
	<!--xiala nav end-->
	<input type="hidden" id="txt_NavHoverBgColor"><input type="hidden" id="txt_NavHoverBgImage"><input type="hidden" id="txt_NavHoverTextColor"><input type="hidden" id="txt_NavTextColor"><script type="text/javascript">
      setCustomNavStyle('navbgColor')
    </script></header>
	<?php else: ?>
	<div class="logo" style="height:80px; overflow:hidden"><a title="<?php echo $this->_tpl_vars['site']['name']; ?>
" href="<?php echo $this->_tpl_vars['homeurl']; ?>
" data-ajax="false"><img max-width="100%" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
" src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
"></a>
	<div class="clear"></div>
	</div>
	<?php endif; ?>