<?php /* Smarty version 2.6.18, created on 2014-05-11 16:45:21
         compiled from tpls/v20/channel_text.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>
<div data-role="content" class="content">
  <!--<div class="title">
    <div class="title_word"><span>最新资讯</span></div>
  </div>-->
  <ul class="newslist">
  	 <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
      <li><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
"  data-ajax="false"><?php echo $this->_tpl_vars['c']['title']; ?>
</a></li>
        <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
             
  
    </ul>
  <div class="clear"></div>
      <div class="pages ui-grid-b">
      
	    <div class="ui-block-a"><span class="left ui-bar-h"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/left.png" tppabs="images/left.png" alt="上一页"/></span></div>
        <div class="ui-block-b"><div class="page_change"><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</div></div>
        <div class="ui-block-c"><a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
"  class="right ui-bar-h" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/right.png"  alt="下一页"/></a></div>
    </div>
	</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>