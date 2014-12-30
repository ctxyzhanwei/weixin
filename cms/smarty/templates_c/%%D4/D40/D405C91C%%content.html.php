<?php /* Smarty version 2.6.18, created on 2014-06-18 23:58:32
         compiled from tpls/v21/content.html */ ?>
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
<div data-role="content" id="main">

    <div class="producttit">
      <div class="title"><span class="fl"><?php echo $this->_tpl_vars['channel']['name']; ?>
</span></div>
    </div>
    <div class="pNC">
      <div class="ptitle" id="content"><?php echo $this->_tpl_vars['content']['title']; ?>
</div>
           <?php echo $this->_tpl_vars['content']['content']; ?>
</div>
    <div class="fenye">
    <?php if ($this->_tpl_vars['previousContent']): ?>
      <p><a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" >上一条：<?php echo $this->_tpl_vars['previousContent']->title; ?>
</a></p> <?php endif; ?>  
      <?php if ($this->_tpl_vars['nextContent']): ?>
      <p><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" >下一条：<?php echo $this->_tpl_vars['nextContent']->title; ?>
</a></p> <?php endif; ?>  
        </div>
    <div class="clear"></div>
    <div class="viewback"><a href="<?php echo $this->_tpl_vars['channel']['link']; ?>
" title="返回列表" class="ui-bar-i" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/fh.jpg" tppabs="fh.jpg" alt="返回列表"></a></div>
  </div>
  <?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>