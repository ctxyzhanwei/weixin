<?php /* Smarty version 2.6.18, created on 2013-12-18 21:40:19
         compiled from 1/gh_aab60b4c5a39/index.html */ ?>
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
			<?php if ($this->_tpl_vars['k'] < 4): ?>
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
			<?php if ($this->_tpl_vars['k'] < 4): ?>
			 <span<?php if ($this->_tpl_vars['k'] == 0): ?> class="slide-dot-cur"<?php endif; ?>></span>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
                    </div>
    </div>
</div>
<div class="icons">
	<div class="one">
	<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
    	<a href="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
" class="icons<?php echo $this->_tpl_vars['k']+1; ?>
">
        	<center><img src="smarty/templates/tpls/hongsecanting/icons<?php echo $this->_tpl_vars['k']+1; ?>
.png" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></center>
            <p><?php echo $this->_tpl_vars['a']['title']; ?>
</p>
        </a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
    </div>
    <div class="two">
    	<a href="<?php echo $this->_tpl_vars['homeUrl']; ?>
" title="HOME" class="homebtn">HOME</a>
        <a href="javascript:void(0);" title="MENU" class="menubtn">MENU</a>
        <a href="javascript:void(0);" title="SEARCH" class="searchbtn">SEARCH</a>
        <div class="search">
        	<div>
                <form method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
                    <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
                    <input type="submit" class="button" value="">
                </form>
            </div>
        </div>
        <div class="nav">
        	<a href="javascript:void(0);" title="收起" class="navback"><center><img src="smarty/templates/tpls/hongsecanting/back.png" tppabs="/images/back.png" alt="收起"></center></a>
			<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
	<a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
<span></span></a>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
        
        </div>
    </div>
    <div class="one">
    	<?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] > 2 && $this->_tpl_vars['k'] < 6): ?>
    	<a href="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
" class="icons<?php echo $this->_tpl_vars['k']-2; ?>
">
        	<center><img src="smarty/templates/tpls/hongsecanting/icons<?php echo $this->_tpl_vars['k']-2; ?>
.png" alt="<?php echo $this->_tpl_vars['a']['title']; ?>
"></center>
            <p><?php echo $this->_tpl_vars['a']['title']; ?>
</p>
        </a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
    </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>