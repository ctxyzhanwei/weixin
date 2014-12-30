<?php include($this->showManageTpl('header','manage'));?>
<?php
$config=loadConfig('system');
?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"></script>
<script type="text/javascript" src="js/formCheck/formcheck.js"></script>

<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">系统配置</div>
   <form method="post" action="?m=config&c=config&a=system" id="myform">
     <table class="addTable">
       
        <tr>
          <th>调试模式</th>
          <td><select name="info[debug]"><option value="0"<?php if (!loadConfig('system','debug')){echo 'selected';}?>>关闭调试</option><option value="1"<?php if (loadConfig('system','debug')){echo 'selected';}?>>开启调试</option></select></td>
        </tr>
        <tr>
          <th>开启GZIP</th>
          <td><select name="info[gzip]"><option value="0"<?php if (!loadConfig('system','gzip')){echo 'selected';}?>>关闭</option><option value="1"<?php if (loadConfig('system','gzip')){echo 'selected';}?>>开启</option></select></td>
        </tr>
        <tr style="display:none">
          <th>计划任务</th>
          <td><select name="info[cron]"><option value="0"<?php if (!loadConfig('system','cron')){echo 'selected';}?>>关闭计划任务</option><option value="1"<?php if (loadConfig('system','cron')){echo 'selected';}?>>开启计划任务</option></select></td>
        </tr>
        <tr>
          <th>第三方统计代码</th>
          <td><textarea class="colorblur" onfocus="this.className=\'colorfocus\'" onblur="this.className=\'colorblur\'" name="info[statisticCode]" style="width:460px;height:80px;font-size:12px;"><?php echo base64_decode(loadConfig('system','statisticCode'));?></textarea></td>
        </tr>
        <tr>
          <th>上传大小限制</th>
          <td><input type="text"  name="info[maxUploadSize]" size="20" class="validate['required','digit'] colorblur" value="<?php echo $config['maxUploadSize'];?>"> <span class="tdtip">M</span></td>
        </tr>
        <tr>
          <th>远程图片保存方式</th>
          <td><select name="info[fileSaveMethod]"><option value="file_get_contents"<?php if (!loadConfig('system','fileSaveMethod')||$config['fileSaveMethod']=='file_get_contents'){echo 'selected';}?>>file_get_contents</option><option value="curl"<?php if ($config['fileSaveMethod']=='curl'){echo 'selected';}?>>curl</option><option value="socket"<?php if ($config['fileSaveMethod']=='socket'){echo 'selected';}?>>socket</option></select></td>
        </tr>
        <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/><input type="hidden" value="1" name="except" /></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>