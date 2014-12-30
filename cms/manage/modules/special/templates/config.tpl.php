<?php include($this->showManageTpl('header','manage'));?>
<?php
$config=loadConfig('special');
?>
<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">专题设置</div>
   <form method="post" action="?m=special&c=m_special&a=config" id="myform">
            <table class="addTable">
        <tr>
          <th>专题存放文件夹名称</th>
          <td><input type="text"  name="info[folder]" size="20" class="validate['required','alpha'] colorblur" value="<?php echo $config['folder'];?>"></td>
        </tr>
        <tr>
          <th>专题首页url格式</th>
          <td><input type="text"  name="info[urlFormate]" size="60" class="validate['required'] colorblur" value="<?php echo $config['urlFormate'];?>"> <span class="tdtip">必须以“http://”开头。{domainName}网站域名 {folder}专题存放文件夹名称 {catIndex}类别索引 {specialIndex}专题索引</span></td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>