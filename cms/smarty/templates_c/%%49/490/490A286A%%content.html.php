<?php /* Smarty version 2.6.18, created on 2014-05-11 16:45:22
         compiled from tpls/v20/content.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>
<?php echo '
<style>
#content img{max-width:92%;}
</style>
'; ?>
<?php if ($this->_tpl_vars['ismap']): ?>
<?php echo $this->_tpl_vars['mapstr']; ?>

<?php else: ?>
<div data-role="content" class="content">

  <div class="padding20" id="content">
    <div class="view_title"><?php echo $this->_tpl_vars['content']['title']; ?>
</div>
        <p><?php echo $this->_tpl_vars['content']['content']; ?>
</p>
    <div class="clear"></div>
    <div class="viewpage"> <?php if ($this->_tpl_vars['previousContent']): ?>
      <a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a><?php endif; ?> 
      <?php if ($this->_tpl_vars['nextContent']): ?><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a><?php endif; ?></div>
    <div class="clear"></div>
    <div class="viewback"><a href="<?php echo $this->_tpl_vars['channel']['link']; ?>
"  title="返回列表" class="ui-bar-i" data-ajax="false">返回列表</a></div>
  </div>
</div>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>