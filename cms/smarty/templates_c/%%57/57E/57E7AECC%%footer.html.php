<?php /* Smarty version 2.6.18, created on 2013-11-03 20:37:10
         compiled from tpls/v12/footer.html */ ?>
<ul class="telbox">
	<li><center><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" class="tel2"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/tel.png" tppabs="http://900024.3g/images/tel.png" alt="电话"></a></center></li>
	<li><center><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信" class="sms2"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/sms.png" tppabs="http://900024.3g/images/sms.png" alt="短信"></a></center></li>
	<li><center><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map" title="地图" class="map2"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/map.png" tppabs="http://900024.3g/images/map.png" alt="地图"></a></center></li>
</ul>
<div class="footer">
 <p>
  <?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['n']):
?>
<?php if ($this->_tpl_vars['k'] < 6): ?>
	<a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['name']; ?>
</a><?php if ($this->_tpl_vars['k'] != 2 && $this->_tpl_vars['k'] != 5): ?> | <?php endif; ?><?php if ($this->_tpl_vars['k'] == 2): ?><br /><?php endif; ?>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
 </p>
 <p>地址：<?php echo $this->_tpl_vars['company']['address']; ?>
</p>
 <a href="<?php echo $this->_tpl_vars['homeurl']; ?>
" title="首页" class="footbtn"></a>
</div>
<!--menu start-->
<?php if ($this->_tpl_vars['showPlugMenu']): ?>
<link rel="stylesheet" href="/tpl/Wap/default/common/css/flash/css/plugmenu.css">
<div class="plug-div">
        <div class="plug-phone">
            <div class="plug-menu themeStyle" style="background:<?php echo $this->_tpl_vars['site']['plugmenucolor']; ?>
"><span class="close"></span></div> 
               <?php $_from = $this->_tpl_vars['plugmenus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['vo']):
?>
                        <div class="themeStyle plug-btn plug-btn<?php echo $this->_tpl_vars['k']+1; ?>
 close" style="background:<?php echo $this->_tpl_vars['site']['plugmenucolor']; ?>
">
							<a  href="<?php echo $this->_tpl_vars['vo']['url']; ?>
">
								<span style="background-image: url(/tpl/Wap/default/common/css/flash/images/img/<?php echo $this->_tpl_vars['vo']['name']; ?>
.png);" ></span>
							</a>
						</div>
                     <?php endforeach; endif; unset($_from); ?>
<div class="plug-btn plug-btn5 close"></div>
                    </div>
</div>
<script src="/tpl/Wap/default/common/css/flash/js/zepto.min.js" type="text/javascript"></script>
<script src="/tpl/Wap/default/common/css/flash/js/plugmenu.js" type="text/javascript"></script>
<?php endif; ?>
<!--menu end-->
</body>
</html>