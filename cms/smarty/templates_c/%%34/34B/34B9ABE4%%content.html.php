<?php /* Smarty version 2.6.18, created on 2014-05-07 23:37:54
         compiled from 1/gjkxqo1399214434/content.html */ ?>
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
<div class="sub">
	<div class="subsearchbox" style="height:20px;">
    	
    </div>
            <h1><p><?php echo $this->_tpl_vars['content']['title']; ?>
</p></h1>
	<div class="view" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div>
	<div style="clear:both"></div>
	<?php if ($this->_tpl_vars['previousContent']): ?><a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a><?php endif; ?>
	<?php if ($this->_tpl_vars['nextContent']): ?><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a><?php endif; ?>
	
    
</div>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>