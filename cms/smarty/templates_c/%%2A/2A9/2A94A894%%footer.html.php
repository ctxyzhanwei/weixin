<?php /* Smarty version 2.6.18, created on 2013-12-19 13:57:02
         compiled from tpls/v15/footer.html */ ?>
<div class="footnav">
 <!-- <div class="footnav_l"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/footnav_l.png" tppabs="http://900040.3g/images/footnav_l.png" /></div>-->
  <ul>
    <?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m'] => $this->_tpl_vars['n']):
?>
	<?php if ($this->_tpl_vars['m'] < 5): ?>
	<li><a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['shortname']; ?>
</a></li>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
  </ul>
</div>

<div class="footerview">
  <ul>
    <li class="foottel" style="border-left:0;"><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/tel.png"  /><br/>电话</a></li>
    <li class="footmess"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/mess.png"  /><br/>短信</a></li>
    <li class="footmap"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/map.png"  /><br/>地图</a></li>
    <li class="footshare">
    <!--分享(-->
		    <a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=share" ><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/share.png" ><br/>分享</a>
        <!--)分享-->
    </li>
    <li class="footindex" style="border-right:0;"><a href="<?php echo $this->_tpl_vars['homeurl']; ?>
"  title="首页" style="color:#f5e65b"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/home.png"  /><br/>首页</a></li>
  </ul>
</div>

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
</body></html>