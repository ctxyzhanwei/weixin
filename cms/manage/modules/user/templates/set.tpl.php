<?php include($this->showManageTpl('header','manage'));?>

<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="/js/calendar.js"></script>
<script src="/js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="/js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){
	new FormCheck('myform');
});
</script>

<div class="columntitle">用户<?php if (!isset($_GET['id'])){echo '添加';}else{echo '修改';}?></div>
   <form method="post" action="/index.php?m=user&c=user&a=set" id="myform">
            <table class="addTable">
        <tr>
          <th>姓名</th>
          <td><input type="text"  name="info[realname]" size="60" class="validate['required'] colorblur" value="<?php echo $thisRow['realname'];?>"> </td>
        </tr>
         <tr>
          <th>用户名</th>
          <td><input type="text"  name="info[username]" size="60" class="validate['required'] colorblur" value="<?php echo $thisRow['username'];?>"> </td>
        </tr>
        <tr>
          <th>密码</th>
          <td><input type="text"  name="info[password]" size="60" class="colorblur" value=""> <?php if (!isset($_GET['id'])){echo '';}else{echo '如不修改密码请留空';}?></td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td>
            <?php
            echo '<input type="hidden" value="'.intval($_GET['isadmin']).'" name="info[isadmin]" />';
            if (isset($_GET['id'])){
            	echo '<input type="hidden" value="'.$_GET['id'].'" name="info[id]" />';
            	echo '<input type="hidden" value="'.$_SERVER['HTTP_REFERER'].'" name="referer" />';
            }
          ?>
            <input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>