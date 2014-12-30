<?php /* Smarty version 2.6.18, created on 2014-05-29 09:28:30
         compiled from 8/zjunnb1401244703/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="main"><div class="" id="dom0">
      <!--focus start-->
	  <div id="drag0" class="pb"><div id="drag0_h" name="colName"><div class="banner_box">
  <div id="Cimgf0d5c2216b8cbscroller_imglist" class="roll_img_mb_01">
    <div class="img_box" style="mix-height:200px">
      <ul>
	   <?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
                                <li><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
"><img src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
" width="100%"></a></li>
								<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
      </ul>
    </div>
    <div class="nav_box">
      <ul id="li_on_name">
		<?php if ($this->_tpl_vars['channel_focus_contents']): ?>
			<?php $_from = $this->_tpl_vars['channel_focus_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
			 <li<?php if ($this->_tpl_vars['k'] == 0): ?> class="li_on"<?php endif; ?>></li>
                    <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
      </ul>
    </div>
  </div><script type="text/javascript">
            WapCircleImg_01("Cimgf0d5c2216b8cbscroller_imglist", <?php echo $this->_tpl_vars['k']+1; ?>
, true);
          </script></div></div><div class="clear"></div></div>
	  <!--focus end-->
      <div id="drag1" class="pb"><div id="drag1_h" name="colName"><div class="content_box">
  <div class="title">
    <p class="tit_name"><?php echo $this->_tpl_vars['pictureChannel']['name']; ?>
</p>
    <p class="more"><a href="<?php echo $this->_tpl_vars['pictureChannel']['link']; ?>
" data-ajax="false">更多&gt;&gt;</a></p>
  </div>
  <div class="list_box">
    <div class="pro_list">
     
	  <?php if ($this->_tpl_vars['pictureContents']): ?>
			<?php $_from = $this->_tpl_vars['pictureContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 3): ?>
			 <ul>
        <li class="pro_pic"><a title="<?php echo $this->_tpl_vars['a']['title']; ?>
" href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-ajax="false"><img alt="<?php echo $this->_tpl_vars['a']['title']; ?>
" src="<?php echo $this->_tpl_vars['a']['thumb']; ?>
"></a></li>
        <li class="pro_intro"><article class="pro_text"><header><h4><a title="<?php echo $this->_tpl_vars['a']['title']; ?>
" href="<?php echo $this->_tpl_vars['a']['link']; ?>
" data-ajax="false"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></h4></header><p><?php echo $this->_tpl_vars['a']['intro']; ?>
</p></article></li>
      </ul>
	  <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
    </div>
  </div>
</div></div><div class="clear"></div></div><div id="drag2" class="pb"><div id="drag2_h" name="colName"><div class="content_box" id="divCompanyIntro" config-style-data="<?php echo '{&quot;IsDefault&quot;:&quot;1&quot;,&quot;TitleBgColor&quot;:&quot;#ddd&quot;,&quot;TitleBgImage&quot;:null,&quot;TitleTextColor&quot;:&quot;#ddd&quot;,&quot;IsContentBorder&quot;:&quot;1&quot;,&quot;ContentBorderColor&quot;:&quot;#ddd&quot;,&quot;ContentOpacity&quot;:&quot;1&quot;,&quot;ContentTextColor&quot;:&quot;#ddd&quot;,&quot;ContentLinkColor&quot;:&quot;#ddd&quot;}'; ?>
">


</div><script type="text/javascript">
        setCustomColStyle('divCompanyIntro')
      </script></div><div class="clear"></div></div><div id="drag3" class="pb"><div id="drag3_h" name="colName"><div class="content_box">
  <div class="title" style="margin-top:-20px;">
    <p class="tit_name"><?php echo $this->_tpl_vars['textChannel']['name']; ?>
</p>
  </div>
  <div class="list_box">
    <ul class="pro_class_list">
     <?php if ($this->_tpl_vars['textContents']): ?>
			<?php $_from = $this->_tpl_vars['textContents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			<?php if ($this->_tpl_vars['k'] < 5): ?>
    	    	<li><a href="<?php echo $this->_tpl_vars['a']['link']; ?>
" title="<?php echo $this->_tpl_vars['a']['title']; ?>
"><?php echo $this->_tpl_vars['a']['title']; ?>
</a></li>
            	<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
    </ul>
  </div>
</div></div><div class="clear"></div></div>

<!--share start-->
<div id="drag2" style="margin-top:20px;" class="pb"><div id="drag2_h" name="colName"><div class="share">
  <div class="share_menu" onClick="showShare('sharebox');" href="javascript:void(0);">一键分享</div>
</div>
<div class="share_box_bg" id="shareboxbg" style="display:none;"></div>
<div class="share_box" id="sharebox" style="display:none;">
  <div class="box_title">
    <p class="name">分享至：</p>
    <p class="close"><a href="javascript:void(0);" title="关闭" onClick="hideShare('sharebox')"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/close.png" tppabs="http://ui.tiantis.com/Images/MShopDec/close.png" alt="关闭"></a></p>
  </div>
  <div class="share_nr"><a href="sms:?body=<?php echo $this->_tpl_vars['site']['name']; ?>
   http://<?php echo $this->_tpl_vars['smart']['server']['HTTP_HOST']; ?>
<?php echo $this->_tpl_vars['smart']['server']['REQUEST_URI']; ?>
" id="smssharebox" title="分享到短信"><p class="share_ico"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/sms.png" tppabs="http://ui.tiantis.com/Images/MShopDec/sms.png" alt="分享到短信"></p>
      <p class="share_name">分享到短信</p></a><a rel="nofollow" href="<?php echo 'javascript:void((function(s,d,e){try{}catch(e){}var%20f=\'http://v.t.sina.com.cn/share/share.php?\',u=d.location.href,p=[\'url=\',e(u),\'&title=\',e(d.title),\'&appkey=1392530042\'].join(\'\');function a(){if(!window.open([f,p].join(\'\'),\'mb\',[\'toolbar=0,status=0,resizable=1,width=620,height=450,left=\',(s.width-620)/2,\',top=\',(s.height-450)/2].join(\'\')))u.href=[f,p].join(\'\');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));'; ?>
" id="weibosharebox" title="分享到新浪微博"><p class="share_ico"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/sina.png" tppabs="http://ui.tiantis.com/Images/MShopDec/sina.png" alt="分享新浪微博"></p>
      <p class="share_name">分享到新浪微博</p></a><a href="javascript:void(0)" onclick="<?php echo '{ var _t = encodeURI(document.title);  var _url = encodeURI(window.location); var _appkey = \'333cf198acc94876a684d043a6b48e14\'; var _site = encodeURI; var _pic = \'\'; var _u = \'http://v.t.qq.com/share/share.php?title=\'+_t+\'&url=\'+_url+\'&appkey=\'+_appkey+\'&site=\'+_site+\'&pic=\'+_pic; window.open( _u,\'转播到腾讯微博\', \'width=700, height=580, top=180, left=320, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no\' );  };'; ?>
" id="tx_weibosharebox" title="分享到腾讯微博"><p class="share_ico"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/tengxun.png" tppabs="http://ui.tiantis.com/Images/MShopDec/tengxun.png" alt="分享腾讯微博"></p>
      <p class="share_name">分享到腾讯微博</p></a></div>
</div></div><div class="clear"></div></div>
<!--share end-->

</div></div><div class="clear"></div><input type="hidden" value="8" id="page__Id"><input type="hidden" id="col_alphaimgpath"><input type="hidden" id="hid_uinfo" value="anlilp8">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>