<?php /* Smarty version 2.6.18, created on 2014-07-02 22:06:33
         compiled from 0/9ccc29f04051b69/channel_text.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <ul class="newsul">
         <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		        <li><p><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
"  title="<?php echo $this->_tpl_vars['c']['title']; ?>
"><?php echo $this->_tpl_vars['c']['title']; ?>
</a></p></li>
                <?php endforeach; endif; unset($_from); ?>
		
		<?php endif; ?>
        
		        
		    </ul>
    <div class="pages">
				<a class="next-left" href="javascript:void(0)">&lt;</a>
		<span><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</span>
		<a class="pre-left" href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" >&gt;</a>
	        <div class="clear"></div>
    </div>
	<div class="clear"></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>