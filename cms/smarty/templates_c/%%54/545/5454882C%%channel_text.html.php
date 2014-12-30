<?php /* Smarty version 2.6.18, created on 2014-05-11 15:02:33
         compiled from 1/jfepja1399789782/channel_text.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>
<div class="sub">
	<div class="subbtn">
        <a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图" class="mapbtn"><p>地图</p></a>
        <a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" class="telbtn"><p>电话</p></a>
        <a href="<?php echo $this->_tpl_vars['homeurl']; ?>
"  title="首页" class="homebtn"><p>首页</p></a>
    </div>	   
     <ul class="newsul">
     <?php if ($this->_tpl_vars['contents']): ?>
			<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		    	<li><a href="<?php echo $this->_tpl_vars['c']['link']; ?>
"  title="<?php echo $this->_tpl_vars['c']['title']; ?>
"><p><?php echo $this->_tpl_vars['c']['title']; ?>
</p></a></li>
                <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
             
		    	
		    </ul>
    <div class="pages">
    			<a class="next-left" href="<?php echo $this->_tpl_vars['previousPageLink']; ?>
">&lt;</a>
		<span><?php echo $this->_tpl_vars['currentPage']; ?>
/<?php echo $this->_tpl_vars['totalPage']; ?>
</span>
		<a class="pre-left" href="<?php echo $this->_tpl_vars['nextPageLink']; ?>
" >&gt;</a>
	        <div class="clear"></div>
    </div>
	</div>
<p class="subbottombg"></p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>