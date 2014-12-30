<?php /* Smarty version 2.6.18, created on 2013-12-20 18:53:51
         compiled from tpls/v21/footer.html */ ?>
<div class="footer ">
  <div class="foot">
    <ul>
      <li class="foottel"><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" data-ajax="false" style="background:none"><p>电话</p></a></li>
      <li class="footmail"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信" data-ajax="false"><p>短信</p></a></li>
      <li class="footmap"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map" tppabs="html/map.html" title="MAP" data-ajax="false"><p>MAP</p></a></li>
    </ul>
    <div class="topico"><a href="<?php echo $this->_tpl_vars['homeurl']; ?>
" tppabs="html/index.html" title="首页" data-ajax="false"></a></div>
  </div>
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
