<?php /* Smarty version 2.6.18, created on 2013-11-03 10:33:23
         compiled from tpls/v24/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--top end--><!--banner-->
<div class="big-pic big-pic3">
    <div class="big-pic-in">
        <div class="pic-list" >
        	     <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
                                <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"><p><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" width="100%"></p></a>
								<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                    </div>
        <div class="slide-dot">
			       <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
			 <span<?php if ($this->_tpl_vars['k'] == 0): ?> class="slide-dot-cur"<?php endif; ?>></span>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                </div>
    </div>
    </div>
<div class="clear"></div>
<!--banner end-->

<!--search-->
<div class="search">
	
	<div class="search_box">
		<form id="search" name="search" method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
			<input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
			<input type="submit" class="button2" value="">
		</form>
    </div>
</div>
<!--banner end-->

<!--list-->
<div class="list">
    <ul> <?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
    	    	<li><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></li>
            	<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		    </ul>
    <div class="list_img"></div>
</div>
<div class="clear"></div>
<!--list end-->
<div class="button">
	<ul>
    	<li><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
"  class="tel" title="电话"></a></li>
        <li class="middle_li"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" class="talk" title="短信"></a></li>
        <li><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图" class="map"></a></li>
    </ul>
</div>
<div class="clear"></div>
<!--button end-->

<!--bottom-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>