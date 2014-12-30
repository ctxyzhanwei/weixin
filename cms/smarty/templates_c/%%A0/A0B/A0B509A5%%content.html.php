<?php /* Smarty version 2.6.18, created on 2013-12-19 22:46:34
         compiled from tpls/v16/content.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<style>
#content img{max-width:92%;}
</style>
'; ?>
<?php if ($this->_tpl_vars['ismap']): ?>
<?php echo $this->_tpl_vars['mapstr']; ?>

<?php else: ?>
	<h1><p><?php echo $this->_tpl_vars['channel']['name']; ?>
</p></h1>
	<!--<center><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
"  alt="搜赢天下 智引未来"></center>-->    <div class="view" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div>
	<p class="hr"></p>
    <?php if ($this->_tpl_vars['previousContent']): ?>
	<a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
"  class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a> <?php endif; ?>   <?php if ($this->_tpl_vars['nextContent']): ?><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a><?php endif; ?>	<a href="<?php echo $this->_tpl_vars['channel']['link']; ?>
" title="返回列表" class="backlist">返回列表</a>
	<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>