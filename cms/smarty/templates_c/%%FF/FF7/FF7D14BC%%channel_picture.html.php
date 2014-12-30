<?php /* Smarty version 2.6.18, created on 2014-05-17 22:50:52
         compiled from 1/ryyxyc1400337989/channel_picture.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <ul class="productul">
        
        	<?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			
		        <li>
            <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
"  alt="<?php echo $this->_tpl_vars['a']['title']; ?>
 "></a>
            <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  title="<?php echo $this->_tpl_vars['a']['title']; ?>
" class="title"><?php echo $this->_tpl_vars['a']['title']; ?>
</a>
        </li>
         
		  <?php endforeach; endif; unset($_from); ?>
		  <?php endif; ?>
		       
		    </ul>
	<div class="clear"></div>
    <div class="pages">
        		<a class="next-left" href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
">&lt;</a>
		<span><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</span>
		<a class="pre-left" href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" >&gt;</a>
	    </div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>