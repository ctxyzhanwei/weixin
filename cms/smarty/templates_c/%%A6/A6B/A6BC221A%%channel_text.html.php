<?php /* Smarty version 2.6.18, created on 2014-06-24 22:49:00
         compiled from 2/yxgyzk1400769965/channel_text.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div data-role="content" id="main">

</div>
    <div class="producttit">
      <div class="title"><span class="fl"><?php echo $this->_tpl_vars['channel']['name']; ?>
</span></div>
    </div>
	<?php if ($this->_tpl_vars['subChannels']): ?>
	 <div class="view_menu"><span>展开分类</span></div>
  <div class="view_menumain">
  <?php $_from = $this->_tpl_vars['subChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
         <a href="<?php echo $this->_tpl_vars['c']['link']; ?>
" class="ui-bar-g" data-ajax="false"><?php echo $this->_tpl_vars['c']['name']; ?>
</a>
         <?php endforeach; endif; unset($_from); ?>
      </div>
	<?php endif; ?>
	
	
    <div class="padding20">
      <div class="news_i">
        <ul>
                  <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
			
                  <li class="news_common" onClick="this.className='news_current'"><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
"   data-ajax="false" title="<?php echo $this->_tpl_vars['c']['title']; ?>
"><?php echo $this->_tpl_vars['c']['title']; ?>
</a></li>
                  
		<?php endforeach; endif; unset($_from); ?>
		
		<?php endif; ?>
                </ul>
				 
        <div class="clear"></div>
                  <div class="pages ui-grid-b">  
                  <div class="ui-block-a"><div class="left" id="dis">上一页</div></div>
    <div class="ui-block-b"><div class="page_change"><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</div></div>
    <div class="ui-block-c" style="margin-top:-23px;"><div class="right"><a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
"  data-ajax="false">下一页</a></div></div>
</div>
                <div class="c"></div>
      </div>
    </div>
  </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>