<?php /* Smarty version 2.6.18, created on 2014-05-01 17:44:38
         compiled from tpls/gangse/content.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<style>
#content img{max-width:72%;}
</style>
'; ?>
<?php if ($this->_tpl_vars['ismap']): ?>
<?php echo $this->_tpl_vars['mapstr']; ?>

<?php else: ?>
<div class="clear"></div><div class="viewtitle">
  <div><span class="fl"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/ico3.png" tppabs="/3g/images/ico3.png" alt="图标"/><?php echo $this->_tpl_vars['channel']['name']; ?>
</span></div>
</div>
<div data-role="content" class="content">
  <div class="maintop"></div>
  <div class="view_menu">
  </div>
  <div class="clear"></div>
  <div class="padding20">
    <div id="content" class="view_title"><?php echo $this->_tpl_vars['content']['title']; ?>
</div>
        <p><?php echo $this->_tpl_vars['content']['content']; ?>
</p>
    <div class="clear"></div>
    <div class="viewpage">
	<?php if ($this->_tpl_vars['previousContent']): ?><a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a><?php endif; ?>
	<?php if ($this->_tpl_vars['nextContent']): ?><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a><?php endif; ?>
	
	</div>
    <div class="viewback"><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>
" title="返回列表" class="ui-bar-i">返回列表</a></div>
  </div>
</div>
<div class="clear"></div>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>