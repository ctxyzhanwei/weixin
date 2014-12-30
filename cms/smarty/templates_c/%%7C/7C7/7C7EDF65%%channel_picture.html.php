<?php /* Smarty version 2.6.18, created on 2014-05-14 14:36:00
         compiled from 8/wnexty1399919127/channel_picture.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<!--内页内容-->
<div class="content_box">
	<!--内页标题-->
	<div class="title">
		<p class="tit_name">
			产品展示</p>
	</div>
	<!--内页标题结束-->
	<div class="product_box">
	<?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
			<!--图片列表-->
			<dl class="pro_con">
				<dt class="pro_picture"><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
">
					<img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
" width="100%"></a></dt>
				<dd class="pro_name">
					<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title=""><?php echo $this->_tpl_vars['a']['title']; ?>
</a></dd>
			</dl>
			 <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
	</div>
	<div class="menu_box" style="text-align:right">
			 <?php if ($this->_tpl_vars['previousPageLink'] != 'javascript:void(0)'): ?> <a href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
" style="float:right" title="">下一页</a><?php endif; ?>
			 <?php if ($this->_tpl_vars['nextPageLink'] != 'javascript:void(0)'): ?> <a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" style="float:right" title="">上一页</a><?php endif; ?>
		</div>
</div>
<!--内容结束-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>