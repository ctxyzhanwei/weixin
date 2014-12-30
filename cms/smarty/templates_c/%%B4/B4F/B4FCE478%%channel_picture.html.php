<?php /* Smarty version 2.6.18, created on 2014-05-04 13:26:45
         compiled from 7/ltdwvc1398909644/channel_picture.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><p class="title"><?php echo $this->_tpl_vars['channel']['name']; ?>
</p>
<div class="sub">
 <?php if ($this->_tpl_vars['subChannels']): ?>
	<div class="clickbtn">
		<div class="classbtn"><p>打开分类</p></div>
		<div class="classbtn2"><p>关闭分类</p></div>
	</div>
		<ul class="subnav">
		<?php $_from = $this->_tpl_vars['subChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sc']):
?>
          <li><a href="<?php echo $this->_tpl_vars['sc']['link']; ?>
" tppabs="/3g/product.php?tid=3" class="ui-bar-g" data-ajax="false"><?php echo $this->_tpl_vars['sc']['name']; ?>
</a></li>
          <?php endforeach; endif; unset($_from); ?>
				<div class="clear"></div>
	</ul>
	<?php endif; ?>
		<div class="clear"></div>
		<ul class="product">
				<?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
		        			<li class="ui-block-b">
				<div><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-ajax="false"><img width="200" height="160" src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
" /></a>
					<p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-ajax="false"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p>
					</div>
			</li>
		     <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
			
			</ul>
	<div class="clear"></div>
			<div class="pages">
		
				<a href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
" class="<?php if ($this->_tpl_vars['previousPageLink'] == 'javascript:void(0)'): ?>next-left<?php else: ?>pre-left<?php endif; ?>" title="上一页">&lt;</a>
				<span><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</span>
				<a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" class="<?php if ($this->_tpl_vars['nextPageLink'] == 'javascript:void(0)'): ?>next-left<?php else: ?>pre-left<?php endif; ?>" title="下一页">&gt;</a>
				</div>	</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>