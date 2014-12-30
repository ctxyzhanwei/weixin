<?php /* Smarty version 2.6.18, created on 2014-01-08 13:20:54
         compiled from 1/gh_aab60b4c5a39/content.html */ ?>
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
<p class="bordertop"></p>
<div class="subcon">
            <h1><p><?php echo $this->_tpl_vars['content']['title']; ?>
</p></h1>
	<div class="view" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div>
	</div>
	<a href="<?php echo $_SERVER['referer']; ?>
" title="返回列表" class="backlist"><span>返回列表</span></a>
    <p class="borderbottom"></p>
</div>
</div>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>