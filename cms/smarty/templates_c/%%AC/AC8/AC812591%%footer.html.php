<?php /* Smarty version 2.6.18, created on 2014-05-09 21:01:51
         compiled from 6/zuktup1399423287/footer.html */ ?>
<div class="search" style="position:relative;top:-4px;">
  <div class="searcharea">
  <form id="search" name="search" method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
" data-ajax="false">
    <input type="text" class="search_txt" name="SeaStr" id="SeaStr" data-role="none" placeholder="请输入关键词"/>
    <input type="submit" class="search_but" data-role="none" value="">
  </form>
  </div>
</div>
<div class="footer" style="position:relative;top:-4px;">
  <ul>
    <li class="foottel" style="border-left:0;"><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/tel.png" tppabs="images/tel.png" />电话</a></li>
    <li class="footmail"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/mess.png" tppabs="images/mess.png" />短信</a></li>
    <li class="footmap" style="border-right:0;"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map" tppabs="html/map.html" title="地图" data-ajax="false"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/map.png" tppabs="images/map.png" />地图</a></li>
  </ul>
</div></div>
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