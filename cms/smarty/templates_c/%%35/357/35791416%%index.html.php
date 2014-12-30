<?php /* Smarty version 2.6.18, created on 2013-12-20 18:53:51
         compiled from tpls/v21/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div data-role="content" id="main">
 
    <div class="lxfs">
      <a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/dh.png" tppabs="images/dh.png"></a>
      <a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/hw.png" tppabs="images/hw.png"></a>
      <a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map" tppabs="html/map.html" title="地图" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/dz.png" tppabs="images/dz.png"></a>
    </div>
   
   <div class="big-pic big-pic<?php if ($this->_tpl_vars['focusCount']): ?><?php echo $this->_tpl_vars['focusCount']; ?>
<?php endif; ?>">
    <div class="big-pic-in">
        <div class="pic-list">
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
    </div>
  </div>
</div>


<div data-role="content" id="main">

    <div class="padding20">
      <div class="news_i">
        <ul>
          <?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
                  <li class="news_common" onClick="this.className='news_current'"><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"   data-ajax="false" title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></li>
                   <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		
		<?php endif; ?>
                 
                </ul>

      </div>
    </div>
    
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
