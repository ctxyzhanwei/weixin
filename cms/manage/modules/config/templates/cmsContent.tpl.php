<?php
include($this->showManageTpl('header','manage'));
$config=loadConfig('cmsContent');
?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">新闻内容设置</div>
   <form method="post" action="?m=config&c=config&a=cmsContent" id="myform">
            <table class="addTable">
            <tr>
          <th>内容中图片最大宽度</th>
          <td><input type="text" name="info[maxPicWidth]" size="20" class="validate['required','digit'] colorblur" value="<?php echo $config['maxPicWidth'];?>" /> <span class="tdtip">内容中上传的图片如果宽度大于这个尺寸，将自动裁切缩放</span></td>
        </tr>
        <tr>
          <th>自动加关键词链接</th>
          <td><select name="info[autoSerieLink]"><option value="0"<?php if (!$config['autoSerieLink']){echo 'selected';}?>>关闭</option><option value="1"<?php if ($config['autoSerieLink']){echo 'selected';}?>>开启</option></select></td>
        </tr>
         <tr>
          <th>多少天内标题不能重复</th>
          <td><input type="text" name="info[sameTitleDaysLimit]" size="20" class="validate['required','digit'] colorblur" value="<?php echo $config['sameTitleDaysLimit'];?>" /> <span class="tdtip">请填写整数，0代表不限制</span></td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>