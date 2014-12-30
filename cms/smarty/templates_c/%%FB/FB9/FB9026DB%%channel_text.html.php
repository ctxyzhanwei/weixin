<?php /* Smarty version 2.6.18, created on 2014-05-11 16:40:22
         compiled from tpls/v14/channel_text.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="sub">
		<ul class="newsul">
        <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		        <li><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
"  title="<?php echo $this->_tpl_vars['c']['title']; ?>
"><span><?php echo $this->_tpl_vars['c']['title']; ?>
</span></a></li>
                <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
               
            </ul>
					<div class="pages">
						<a href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
" title="上一页" class="no_prev">上一页</a>
						<a href="#" title="分页列表" class="page" style="readonly"><span><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</span></a>
						<a href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
"  title="下一页" class="next">下一页</a>
					</div>
		<!--<ul class="topages">
									<li class="nowpage"><a href="javascript:void(0);" title="第1页">第1页</a></li>
												<li><a href="news.php-&pageno=2.htm" tppabs="http://900029.3g/news.php?&pageno=2" title="第2页">第2页</a></li>
								</ul>-->
		<a class="bg" id="bg" href="#foot"></a>
			</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
