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
<div class="columntitle">专题类别<?php if (!isset($_GET['id'])){echo '添加';}else{echo '修改';}?></div>
   <form method="post" action="?m=special&c=m_special&a=catSet" id="myform">
            <table class="addTable">
           
        <tr>
          <th>标题</th>
          <td><input type="text"  name="info[name]" size="60" class="validate['required'] colorblur" value="<?php echo $thisCat['name'];?>"> </td>
        </tr>
        <tr>
          <th>索引</th>
          <td><input type="text"  name="info[enname]" size="60" class="validate['required','alpha'] colorblur" value="<?php echo $thisCat['enname'];?>"> </td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td>
            <?php
            if (isset($_GET['id'])){
            	echo '<input type="hidden" value="'.$thisCat['id'].'" name="id" />';
            	echo '<input type="hidden" value="'.$_SERVER['HTTP_REFERER'].'" name="referer" />';
            }
          ?>
            <input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>