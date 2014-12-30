<?php /* Smarty version 2.6.18, created on 2014-06-07 12:07:24
         compiled from tpls/v24/content.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<style>
#content img{max-width:92%;}
</style>
'; ?>
<?php if ($this->_tpl_vars['ismap']): ?>
<?php echo $this->_tpl_vars['mapstr']; ?>

<?php else: ?>
<br><br>
<div class="view">
	<h1><p><?php echo $this->_tpl_vars['content']['title']; ?>
</p></h1>
	<div class="view" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div>
    <div class="subborder"></div>
     <?php if ($this->_tpl_vars['previousContent']): ?>
	<a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" title="上一条：<?php echo $this->_tpl_vars['previousContent']->title; ?>
" class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a> <?php endif; ?>    
    <?php if ($this->_tpl_vars['nextContent']): ?> 
     <a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" tppabs="3g/html/news_view-7.html" title="下一条：<?php echo $this->_tpl_vars['nextContent']->title; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a>	<?php endif; ?>
     
     	<a href="<?php echo $this->_tpl_vars['channel']['link']; ?>
"  title="返回列表" class="backlist"><span>返回列表</span></a>
</div>
<!--contact end-->
<div class="button">
	<ul>
    	<li><a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
"  class="tel" title="电话"></a></li>
        <li class="middle_li"><a href="sms:<?php echo $this->_tpl_vars['company']['mp']; ?>
" class="talk" title="短信"></a></li>
        <li><a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图" class="map"></a></li>
    </ul>
</div>
<div class="clear"></div>
<!--button end-->

<!--bottom-->
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>