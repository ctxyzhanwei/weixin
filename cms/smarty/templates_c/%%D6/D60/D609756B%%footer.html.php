<?php /* Smarty version 2.6.18, created on 2013-11-03 20:41:52
         compiled from 6/yicms/footer.html */ ?>
<div class="clear"></div>
<ul class="footnav">
<?php if ($this->_tpl_vars['navChannels']): ?>
<?php $_from = $this->_tpl_vars['navChannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['n']):
?>
<?php if ($this->_tpl_vars['k'] < 5): ?>
	<li><a href="<?php echo $this->_tpl_vars['n']['link']; ?>
" title="<?php echo $this->_tpl_vars['n']['name']; ?>
"><?php echo $this->_tpl_vars['n']['shortname']; ?>
</a></li>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</ul>
<ul class="footbg">
	<li class="sms"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" title="短信"><span>短信</span></a></li>
    <li class="tel"><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话"><span>电话</span></a></li>
    <li class="map"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map" title="地图"><span>地图</span></a></li>
        <li class="share"><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=share" title="分享"><span>分享</span></a></li>
</ul>
    <!--商桥(-->
	<!--
    <div id="best3g_swt" style='position:absolute;top:40%;left:80%; margin-top: 150px; width:60px; height:40px; padding-top:10px;  background-color:#2380D4;text-align:center;display:none;filter:alpha(opacity=80); -moz-opacity:0.8; -khtml-opacity: 0.8; opacity: 0.8;-moz-border-radius:.4em ;-webkit-border-radius:.4em;border-radius:.4em; z-index:10000;'><a target="_blank" href="#" tppabs="http://www.baidu.com/"><img src="smarty/templates/tpls/tianlan/sq_button.png" tppabs="images/sq_button.png" width="47" height="29" border="0" /></a></div>
	
    <?php echo '
<script>
function showzx()
{
	if(document.getElementById(\'best3g_swt\').style.display=="none"){
	document.getElementById(\'best3g_swt\').style.display=\'\';
	}
	window.setInterval("heartBeat()",1);
}

lastScrollY=0;
function heartBeat(){ 
	var diffY;
	if (document.documentElement && document.documentElement.scrollTop)
		diffY = document.documentElement.scrollTop;
	else if (document.body)
		diffY = document.body.scrollTop
	else
		{/*Netscape stuff*/}
	
	percent=.1*(diffY-lastScrollY); 
	if(percent>0)percent=Math.ceil(percent); 
	else percent=Math.floor(percent); 
	document.getElementById("best3g_swt").style.top=parseInt(document.getElementById("best3g_swt").style.top)+percent+"px";
	lastScrollY=lastScrollY+percent; 
}
showzx();
//setTimeout("showzx()",10000);
</script>

'; ?>

-->
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