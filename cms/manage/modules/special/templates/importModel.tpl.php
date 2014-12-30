<?php include($this->showManageTpl('header','manage'));?>
<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="/js/calendar.js"></script>
<script src="/js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="/js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo MAIN_URL_ROOT;?>/upload/a1.html"></script>
<script type="text/javascript" src="<?php echo JS_URL_ROOT;?>/autoSelect.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){
	new FormCheck('myform');
});
</script>
<div class="columntitle">专题模型导入</div>
<div class="oper"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="back">返回</a></div>
   <form method="post" action="?m=special&c=m_special&a=importModel" id="myform">
   <div style="padding:20px;">
   <?php
   if ($ms){
   	foreach ($ms as $m){
   		echo '<div style="text-align:center;width:160px;overflow:hidden;float:left;padding:20px;"><p style="text-align:center"><a href="'.$m['logo'].'" target="_blank"><img width="80" height="80" src="'.$m['logo'].'" /></a></p><input type="checkbox" name="info['.$m['enname'].']" value="'.$m['enname'].'" checked /> 导入<br>名称 <input type="text" class="colorblur" name="'.$m['enname'].'[name]" style="width:90px;" value="'.$m['name'].'" /><p style="text-align:center;margin:5px 0 0 0">简介 <input class="colorblur" style="width:90px;" type="text" name="'.$m['enname'].'[intro]" value="'.$m['intro'].'" /></p></div>';
   	}
   }
   ?>
   <div style="clear:both"></div>
    <div style="text-align:center"><input type="submit" name="doSubmit" value="提交" class="button"/></div>
   </div>
     

</form>
<?php include($this->showManageTpl('footer','manage'));?>