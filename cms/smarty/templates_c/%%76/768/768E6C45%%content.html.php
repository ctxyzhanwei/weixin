<?php /* Smarty version 2.6.18, created on 2014-05-11 15:02:36
         compiled from 1/jfepja1399789782/content.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['header'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>
<?php echo '
<style>
#content img{max-width:92%;}
</style>
'; ?>
<?php if ($this->_tpl_vars['ismap']): ?>
<?php echo $this->_tpl_vars['mapstr']; ?>

<?php else: ?>
<div class="sub">
	<div class="subbtn">
        <a href="?token=<?php echo $this->_tpl_vars['token']; ?>
&m=site&c=home&a=map"  title="地图" class="mapbtn"><p>地图</p></a>
        <a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=<?php echo $this->_tpl_vars['company']['tel']; ?>
" title="电话" class="telbtn"><p>电话</p></a>
        <a href="<?php echo $this->_tpl_vars['homeurl']; ?>
"  title="首页" class="homebtn"><p>首页</p></a>
    </div>	<h1><p><?php echo $this->_tpl_vars['content']['title']; ?>
</p></h1>
	  <div class="view" id="content"><?php echo $this->_tpl_vars['content']['content']; ?>
</div>
	<?php if ($this->_tpl_vars['previousContent']): ?><a href="<?php echo $this->_tpl_vars['previousContent']->link; ?>
" class="prevpage">上一条：<span><?php echo $this->_tpl_vars['previousContent']->title; ?>
</span></a><?php endif; ?>   <?php if ($this->_tpl_vars['nextContent']): ?><a href="<?php echo $this->_tpl_vars['nextContent']->link; ?>
" class="nextpage">下一条：<span><?php echo $this->_tpl_vars['nextContent']->title; ?>
</span></a><?php endif; ?>	<a href="<?php echo $this->_tpl_vars['channel']['link']; ?>
" title="返回列表" class="backlist"><img src="smarty/templates/tpls/<?php echo $this->_tpl_vars['site']['template']; ?>
/backbg.jpg"  alt="返回列表"></a>
</div>
<p class="subbottombg"></p>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['footer'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>