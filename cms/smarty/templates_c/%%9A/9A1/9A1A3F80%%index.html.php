<?php /* Smarty version 2.6.18, created on 2014-06-16 00:45:31
         compiled from 5/aiyktz1402761056/index.html */ ?>
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
			       <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
			 <span<?php if ($this->_tpl_vars['k'] == 0): ?> class="slide-dot-cur"<?php endif; ?>></span>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                    </div>
    </div>
</div>
<div class="title"><a href="<?php echo $this->_tpl_vars['pictureChannel']['link']; ?>
" data-transition="slideup"><?php echo $this->_tpl_vars['pictureChannel']['name']; ?>
<span></span></a></div>
<ul class="pro-ul">
<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 2): ?>
		<li>
    	<center><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></a>
        <p><?php echo $this->_tpl_vars['a']['title']; ?>
</p></center>
    </li>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	</ul>
<div class="clear"></div>
<div class="title"><a href="<?php echo $this->_tpl_vars['textChannel']['link']; ?>
" data-transition="slideup"><?php echo $this->_tpl_vars['textChannel']['name']; ?>
<span></span></a></div>
<div class="news-top-bg">
<?php if ($this->_tpl_vars['textContents']): ?>
<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 1): ?>
			<?php if ($this->_tpl_vars['a']['thumb']): ?>
	<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-transition="slideup"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
"></a>
	<?php endif; ?>
    <div class="news-right"<?php if (! $this->_tpl_vars['a']['thumb']): ?> style="padding:0 20px;width:80%"<?php endif; ?>>
        <p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-transition="slideup"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p>
        <p><?php echo $this->_tpl_vars['a']['intro']; ?>
</p>
    </div>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</div>
<ul class="news-font-ul">
<?php if ($this->_tpl_vars['textContents']): ?>
<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] > 0 && $this->_tpl_vars['k'] < 4): ?>
	    <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-transition="slideup"><li><?php echo $this->_tpl_vars['a']['title']; ?>
</li></a>
<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
    </ul>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
