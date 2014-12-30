<?php /* Smarty version 2.6.18, created on 2013-12-17 20:56:03
         compiled from tpls/gangse/channel_picture.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div><div class="viewtitle">
  <div><span class="fl"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/ico3.png" tppabs="/3g/images/ico3.png" alt="图标"/><?php echo $this->_tpl_vars['channel']['name']; ?>
</span></div>
</div>
<div data-role="content" class="content">
  <div class="maintop"></div>
  <?php if ($this->_tpl_vars['subChannels']): ?>
  <div class="view_menu"><span><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/view_nav.png" tppabs="/3g/images/view_nav.png" alt="二级菜单按钮"/>展开<?php echo $this->_tpl_vars['channel']['name']; ?>
分类</span>
    <div class="clear"></div>
    <div class="view_menumain" style="display:none">
    <?php $_from = $this->_tpl_vars['subChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sc']):
?>
          <a href="<?php echo $this->_tpl_vars['sc']['link']; ?>
" tppabs="/3g/product.php?tid=3" class="ui-bar-g" data-ajax="false"><?php echo $this->_tpl_vars['sc']['name']; ?>
</a>
          <?php endforeach; endif; unset($_from); ?>
        </div>
  </div>
  <?php endif; ?>
  <div class="clear"></div>
  <div class="padding20">
    <div class="proul">
      <ul class="ui-grid-a">
							<?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
		        			<li class="ui-block-b">
				<div><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-ajax="false"><img width="200" height="160" src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
" />
					<p><?php echo $this->_tpl_vars['a']['title']; ?>
</p>
					</a></div>
			</li>
		     <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
			 
			 		       </ul>
    </div>
     <div class="clear"></div>
          <div class="pages ui-grid-b">  <div class="ui-block-a"><a href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
" class="left ui-bar-h" id="dis"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/left.png" tppabs="/3g/images/left.png" alt="上一页"/></a></div>
    <div class="ui-block-b"><div class="page_change"><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</div></div>
    <div class="ui-block-c"><a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" tppabs="/3g/news.php?&pageno=2" title="下一页" data-ajax="false" class="right ui-bar-h"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/right.png" tppabs="/3g/images/right.png" alt="下一页"/></a></div>
</div>
      </div>
</div>
<div class="clear"></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>