<?php /* Smarty version 2.6.18, created on 2014-05-10 22:24:53
         compiled from tpls/v17/footer.html */ ?>
<ul class="footnav">
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
<div class="search">
    <form id="search" name="search" method="post" action="?m=site&c=home&a=search&token=<?php echo $this->_tpl_vars['token']; ?>
">
        <input type="text" class="text" name="SeaStr" id="SeaStr" placeholder="请输入搜索关键词"/>
        <input type="submit" class="button" title="搜索" value="">
    </form>
</div>
<div class="clear"></div>
<!--<p class="support"></p>-->
<ul class="foot" style="margin-top:16px;">
	<li class="searchbtn"><span>搜索</span></li>
    <!--分享(-->
        <li class="sharebtn"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=share"  title="分享"><span>分享</span></a></li>
        <!--)分享-->
    <li class="smsbtn"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信"><span>短信</span></a></li>
</ul>
    

    <!--)商桥-->
	
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
