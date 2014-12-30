<?php include($this->showManageTpl('header','manage'));?>
<?php
$config=loadConfig('watermark');
if (file_exists(ABS_PATH.'/constant/watermark.config.php')){
	$waterMarkText=WATERMARK_TEXT;
	$waterMarkType=WATERMARK_TYPE;
	$useWaterMark=USE_WATERMARK;
}else {
	$waterMarkText='';
	$waterMarkType='text';
	$useWaterMark=0;
}
$config['leftTopWaterMarkText']=$config['leftTopWaterMarkText']?$config['leftTopWaterMarkText']:$config['waterMarkText'];
$config['leftDistance']=isset($config['leftDistance'])?$config['leftDistance']:9;
$config['topDistance']=isset($config['topDistance'])?$config['topDistance']:16;
$config['rightDistance']=isset($config['rightDistance'])?$config['rightDistance']:5;
$config['bottomDistance']=isset($config['bottomDistance'])?$config['bottomDistance']:10;
?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
        var value='<?php echo $config['waterMarkType']?$config['waterMarkType']:'text';?>';
        trd(value);
    });
    function trd(value){
    	var trs=$$('.tr');
    	trs.each(function(tr){
    		if(tr.getProperty('rel')&&tr.getProperty('rel').toString()!=value){
    			tr.setStyle('display','none');
    		}else{
    			tr.setStyle('display','');
    		}
    	})
    }
</script>
<div class="columntitle">水印设置</div>
   <form method="post" action="?m=config&c=config&a=watermark" id="myform">
   <div class="ftip">如使用图片水印，请把水印图片放置到“<?php echo CMS_DIR_PATH; ?>/editor/ckfinder/plugins/watermark/logo.png”</div>
            <table class="addTable">
        <tr>
          <th>是否启用水印</th>
          <td><select name="info[useWaterMark]"><option value="0"<?php if (!$config['useWaterMark']){echo 'selected';}?>>关闭</option><option value="1"<?php if ($config['useWaterMark']){echo 'selected';}?>>开启</option></select></td>
        </tr>
        <tr>
          <th>选择水印方式</th>
          <td><select name="info[waterMarkType]" onchange="trd(this.value)"><option value="text"<?php if ($config['waterMarkType']=='text'){echo ' selected';}?>>文字</option><option value="image"<?php if ($config['waterMarkType']=='image'){echo ' selected';}?>>图片</option></select></td>
        </tr>
        <tr class="tr" rel="text">
          <th>右下角文字水印内容</th>
          <td><input type="text" name="info[waterMarkText]" size="20" class="colorblur" value="<?php echo $config['waterMarkText'];?>" /></td>
        </tr>
        <tr class="tr" rel="text">
          <th>左上角文字水印内容</th>
          <td><input type="text" name="info[leftTopWaterMarkText]" size="20" class="colorblur" value="<?php echo $config['leftTopWaterMarkText'];?>" /></td>
        </tr>
        <tr class="tr" rel="text">
          <th>左上角水印</th>
          <td><select name="info[leftTop]"><option value="1"<?php if ($config['leftTop']){echo 'selected';}?>>加水印</option><option value="0"<?php if (!$config['leftTop']){echo 'selected';}?>>不加水印</option></select></td>
        </tr>
        <tr>
          <th>图片最低宽度</th>
          <td><input type="text"  name="info[picMinWidth]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['picMinWidth'];?>"> <span class="tdtip">小于这个宽度的图片不加水印</span></td>
        </tr>
        <tr>
          <th>图片最低高度</th>
          <td><input type="text"  name="info[picMinHeight]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['picMinHeight'];?>"> <span class="tdtip">小于这个高度的图片不加水印</span></td>
        </tr>
        <tr>
          <th>左上角水印居左</th>
          <td><input type="text"  name="info[leftDistance]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['leftDistance'];?>"> <span class="tdtip">px</span></td>
        </tr>
        <tr>
          <th>左上角水印居上</th>
          <td><input type="text"  name="info[topDistance]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['topDistance'];?>"> <span class="tdtip">px</span></td>
        </tr><tr>
          <th>右下角水印居右</th>
          <td><input type="text"  name="info[rightDistance]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['rightDistance'];?>"> <span class="tdtip">px</span></td>
        </tr>
        <tr>
          <th>右下角水印居下</th>
          <td><input type="text"  name="info[bottomDistance]" size="5" class="validate['required','digit'] colorblur" value="<?php echo $config['bottomDistance'];?>"> <span class="tdtip">px</span></td>
        </tr>
        
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>