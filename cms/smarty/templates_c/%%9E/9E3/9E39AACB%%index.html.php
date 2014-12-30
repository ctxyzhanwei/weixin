<?php /* Smarty version 2.6.18, created on 2014-05-04 13:25:34
         compiled from 7/ltdwvc1398909644/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="banner">
	<!---->
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
	<!---->
		<ul class="ban_class">
		<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 6): ?>
				<li><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['name']; ?>
"><?php echo $this->_tpl_vars['a']['name']; ?>
</a></li>
				<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
			</ul>
	</div>
<p class="title"><?php echo $this->_tpl_vars['pictureChannel']['name']; ?>
</p>
<ul class="caseul">
		 <?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 6): ?>
		<li>
		<div>
			<center><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" style="max-width:90px" height="64" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></a></center>
			<p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p>
		</div>
	</li>
	 <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
		  <?php endif; ?>
	</ul>
<div class="clear"></div>
<div class="backtop2"><p><a href="#" title="返回顶部">返回顶部</a><span>|</span><a href="<?php echo $this->_tpl_vars['textChannel']['link']; ?>
" title="<?php echo $this->_tpl_vars['textChannel']['name']; ?>
"><?php echo $this->_tpl_vars['textChannel']['name']; ?>
</a></p></div>
<div class="copyright">
 <?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
	<p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
	
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>