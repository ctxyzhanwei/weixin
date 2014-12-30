<?php /* Smarty version 2.6.18, created on 2014-05-26 00:21:32
         compiled from 2/fgohhc1401029423/channel_picture.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '2/fgohhc1401029423/channel_picture.html', 10, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>    <div class="viewtitle"><?php echo $this->_tpl_vars['channel']['name']; ?>
</div>
    <div data-role="content" class="content">
      <div class="maintop"></div>

      <div class="proul">
        <ul class="ui-grid-a">
        <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
        		<li class="ui-block-<?php echo smarty_function_cycle(array('values' => "a,b"), $this);?>
" style="width:45%">
				<div><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  data-ajax="false"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
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
              <div class="pages ui-grid-b">  
              <div class="ui-block-a"><span class="left ui-bar-h" id="dis"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/left.png"  alt="上一页"/></span></div>
    <div class="ui-block-b"><div class="page_change"><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</div></div>
    <div class="ui-block-c"><a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
"  title="下一页" data-ajax="false" class="right ui-bar-h"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/right.png"  alt="下一页"/></a></div>
</div>
          </div>
    <div class="clear"></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>