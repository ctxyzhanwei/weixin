<?php /* Smarty version 2.6.18, created on 2014-05-10 22:24:53
         compiled from tpls/v17/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="big-pic big-pic<?php if ($this->_tpl_vars['focusCount']): ?><?php echo $this->_tpl_vars['focusCount']; ?>
<?php endif; ?>">
    <div class="big-pic-in">
        <div class="pic-list" >
        	     <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
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
			<?php if ($this->_tpl_vars['k'] < 5): ?>
			 <span<?php if ($this->_tpl_vars['k'] == 0): ?> class="slide-dot-cur"<?php endif; ?>></span>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                </div>
    </div>
    </div>
<div class="btnbox">
	<img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/star.jpg"  alt="">
    <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/star.jpg"  alt="">
    <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/star.jpg"  alt="">
    <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/star.jpg"  alt="">
    <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/star.jpg"  alt="">
    <a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图" class="mapbtn"><p>地图</p></a>
    <a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" class="telbtn"><p>电话</p></a>
</div>
<div class="clear"></div>
<div class="picbox">
    <ul class="rightpic" style="width:100%;float:none">
	<?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
		    	<li><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></li>
				<?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
			 <?php endif; ?>
            </ul>
    <div class="clear"></div>
</div>

<p class="border"></p>
<ul class="productbox">
	<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
		<li>
    	<div>
            <a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"  ><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
"  alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></a>
            <p><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"   class="heighta"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></p>
            <div class="clear"></div>
        </div>
        <img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/boxshadow.jpg" tppabs="boxshadow.jpg" class="boxshadow" alt="盒子阴影">
    </li>
    <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
		  <?php endif; ?>
    	
    </ul>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>