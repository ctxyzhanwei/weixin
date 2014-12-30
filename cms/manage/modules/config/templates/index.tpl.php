<?php include($this->showManageTpl('header','manage'));
if (defined('NEW_INDEX')&&NEW_INDEX){
	$newIndex=1;
}else {
	$newIndex=0;
}
$config=loadConfig('index');
?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">首页设置</div>
   <form method="post" action="?m=config&c=config&a=index" id="myform">
            <table class="addTable">
            <tr <?php if(!$newIndex){echo 'style="display:none"';} ?>>
          <th>是否生成静态页面</th>
          <td><select name="info[notToHtml]"><option value="0"<?php if (!loadConfig('index','notToHtml')){echo 'selected';}?>>生成</option><option value="1"<?php if (loadConfig('index','notToHtml')){echo 'selected';}?>>不生成</option></select></td>
        </tr>
            <tr>
          <th>网页Title</th>
          <td><input type="text"  name="info[indexMetaTitle]" style="width:400px;" class="colorblur" value="<?php echo loadConfig('index','indexMetaTitle');?>">  修改后请生成首页</td>
        </tr>
        <tr>
          <th>meta关键词</th>
          <td><input type="text"  name="info[indexMetaKeyword]" style="width:400px;" class="colorblur" value="<?php echo loadConfig('index','indexMetaKeyword');?>">  修改后请生成首页</td>
        </tr>
        <tr>
          <th>meta描述</th>
          <td><textarea name="info[indexMetaDes]" style="width:400px;height:60px;font-size:12px" class="colorblur"><?php echo loadConfig('index','indexMetaDes');?></textarea>  修改后请生成首页</td>
        </tr>
        
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>