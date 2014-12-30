<?php include($this->showManageTpl('header','manage'));?>
<?php
?>
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
<div class="columntitle">数据恢复</div>
   <form method="post" action="?m=manage&c=database&a=import" id="myform">
   <div class="ftip">备份文件夹应命名为data*****，并存放在backup里面</div>
            <table class="addTable">
        <tr>
          <th>选择数据存放文件夹</th>
          <td><select name="dir">
          <?php
          if ($dirs){
          	foreach ($dirs as $dir){
          		echo '<option value="'.$dir.'">'.$dir.'</option>';
          	}
          }
          ?>
          </select></td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td>
            <input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>