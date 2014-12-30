<?php /* Smarty version 2.6.18, created on 2014-05-29 10:07:31
         compiled from 8/zjunnb1401244703/content.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '8/zjunnb1401244703/content.html', 14, false),)), $this); ?>
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
			<!--内页内容-->
<div class="content_box">
	<div class="news_box">
		<section class="news_content">
            <header class="news_title"><?php echo $this->_tpl_vars['content']['title']; ?>
</header>
            <p class="up_time" style="display:none">发表日期:<time puddate="pubdate"> <?php echo ((is_array($_tmp=$this->_tpl_vars['content']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</time></p>
            
            <article style="width:100%;">
             <div class="news_text" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div> 
             
            </article>
          </section>
		<div class="menu_box">
		
		<?php if ($this->_tpl_vars['nextContent']): ?><a style="float:right" href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条</a><?php endif; ?>
			<?php if ($this->_tpl_vars['previousContent']): ?><a style="float:right" href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" class="prevpage">上一条</a><?php endif; ?>
	
		</div>
	</div>
</div>
<!--内容结束-->
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>