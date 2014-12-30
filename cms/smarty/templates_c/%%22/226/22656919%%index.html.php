<?php /* Smarty version 2.6.18, created on 2014-06-19 13:41:27
         compiled from tpls/lvsecanyin/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="top">
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
	<div class="logobox">
    	<center>
        	<a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="<?php echo $this->_tpl_vars['site']['name']; ?>
" class="logo"><img src="<?php echo $this->_tpl_vars['site']['logourl']; ?>
" class="logo" alt="<?php echo $this->_tpl_vars['site']['name']; ?>
"></a>
        </center>
    </div>
</div>

<div class="indexicon">
	<div class="iconleft"></div>
    <div class="iconright">
    	<p><a href="index.html" tppabs="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="首页" class="iconhome"></a></p>
        <p><a href="javascript:void(0);" title="搜索" class="iconsearch"></a></p>
        <p><a href="javascript:void(0);" title="导航" class="iconnav"></a></p>
    </div>
    <ul class="pics">
    	<li>
		<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 1): ?>
		<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title=""><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="" class="ad<?php echo $this->_tpl_vars['k']+1; ?>
"></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		</li>
        <li>
        <?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3 && $this->_tpl_vars['k'] > 0): ?>
		<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title=""><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="" class="ad<?php echo $this->_tpl_vars['k']+1; ?>
"></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
        </li>
        <li>
		<?php if ($this->_tpl_vars['pictureContents']): ?>
		<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 4 && $this->_tpl_vars['k'] > 2): ?>
		<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title=""><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="" class="ad<?php echo $this->_tpl_vars['k']+1; ?>
"></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		</li>
        <li>
		<?php if ($this->_tpl_vars['pictureContents']): ?>
		<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 6 && $this->_tpl_vars['k'] > 3): ?>
		<a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title=""><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" alt="" class="ad<?php echo $this->_tpl_vars['k']+1; ?>
"></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
        	<div class="clear"></div>
        </li>
    </ul>
   	<div class="search">
    	<div>
             <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
                <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
                <input type="submit" class="button" value="">
            </form>
        </div>
    </div>
    <div class="nav" id="nav">
    	<ul>
        	
			<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<li><a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
        </ul>
        <a href="#" class="backtop"><img src="smarty/templates/tpls/lvsecanyin/backtop.png" tppabs="3g/images/backtop.png" alt="返回顶部"></a>
    </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>