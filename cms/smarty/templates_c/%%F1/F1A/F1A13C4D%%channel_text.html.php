<?php /* Smarty version 2.6.18, created on 2014-02-21 10:55:10
         compiled from 1/pzorte1392802164/channel_text.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '1/pzorte1392802164/channel_text.html', 15, false),)), $this); ?>
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
			<?php echo $this->_tpl_vars['channel']['name']; ?>
</p>
	</div>
	<!--内页标题结束-->
	<div class="news_box">
		<!--标题列表-->
		<ul class="news_lists">
		<?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
			<li class="news_title"><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
" title="<?php echo $this->_tpl_vars['c']['title']; ?>
"><?php echo $this->_tpl_vars['c']['title']; ?>
</a><time puddate="pubdate"><?php echo ((is_array($_tmp=$this->_tpl_vars['c']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</time></li>
		     <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
				
		</ul>
	</div>
</div>
<!--内容结束-->
<div class="menu_box" style="text-align:right">
			 <?php if ($this->_tpl_vars['previousPageLink'] != 'javascript:void(0)'): ?> <a href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
" style="float:right" title="">下一页</a><?php endif; ?>
			 <?php if ($this->_tpl_vars['nextPageLink'] != 'javascript:void(0)'): ?> <a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" style="float:right" title="">上一页</a><?php endif; ?>
		</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>