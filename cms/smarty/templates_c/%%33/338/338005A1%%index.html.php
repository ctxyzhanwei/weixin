<?php /* Smarty version 2.6.18, created on 2014-05-17 17:03:44
         compiled from 7/xpsnkh1400316768/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="big-pic big-pic<?php if ($this->_tpl_vars['focusCount']): ?><?php echo $this->_tpl_vars['focusCount']; ?>
<?php endif; ?>">
    <div class="big-pic-in">
        <div class="pic-list" >
        	             <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
                                <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"><p><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" width="100%"></p></a>
								<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                    </div>
        <div class="slide-dot">
			<center>
			            <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
			 <span<?php if ($this->_tpl_vars['k'] == 0): ?> class="slide-dot-cur"<?php endif; ?>></span>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
        	           
        	            </center>
        </div>
    </div>
</div>
<div class="picbox">
 <ul>
<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 6): ?>
			<li>
    	<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
">
        	<div class="imgbox"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></div>
            <p><?php echo $this->_tpl_vars['a']['title']; ?>
</p>
        </a>
        </li>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	 </ul>
	 </div>
	 <div style="clear:both"></div>
 <ul class="newsul">
  <?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
		        <li><p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p></li>
		     <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		
		<?php endif; ?>  
        </ul>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>