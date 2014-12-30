<?php include($this->showManageTpl('header','manage'));?>
<style>
ul.cateradio li{float:left;height:390px; width:170px; margin:20px; overflow:hidden}
.iphone{height:353px; width:170px;overflow:hidden;background:url(image/radio_iphone0.png) no-repeat center top;margin:0 auto; cursor:pointer}
.iphone:hover{background:url(image/radio_iphone.png) no-repeat center top;}
.iphone img{width:143px;height:241px;margin:53px 0 0 14px}
ul.cateradio li div.text{text-align:center;margin-top:10px;}
</style>
<script>
function selectTemplate(){
	var templateIndex= $$('input[name=templateIndex]:checked').map(function(e){return e.value;}).toString();
	location.href='?m=template&c=m_template&a=setTemplate&templateindex='+templateIndex;
}
</script>
    <div class="columntitle">选择模板</div>
    <div class="ftip">更换模板将导致您设置的的原有模板信息丢失，请谨慎操作。
    <?php
    if (isset($_SESSION['previewSkin'])){
    	echo '您现在处于模板预览状态，<a style="float:none" href="?m=template&c=m_template&a=quitTemplatePreview">点击这里退出预览</a>';
    }
    ?>
   </div>
<div id="tags">
   <div class="tagContent selectTag" id="tagContent0">
<fieldset style="border:none">
 <ul class="cateradio">
 <?php
 $i=0;
 foreach ($templates as $t){
 ?>
<li class="active">
	<label>
	<div class="iphone"<?php if ((!$this->site['template']&&$i==0)||($this->site['template']&&$t['templateindex']==$this->site['template'])){echo ' style="background:url(image/radio_iphone.png) no-repeat center top;"';} ?>><img src="../smarty/templates/tpls/<?php echo $t['templateindex'];?>/logo.jpg"></div>
	<div class="text" style="height:20px;"><input class="radio" type="radio" name="templateIndex" value="<?php echo $t['templateindex'];?>"<?php if ((!$this->site['template']&&$i==0)||($this->site['template']&&$t['templateindex']==$this->site['template'])){echo ' checked';} ?> /> <?php echo $t['name'];?> <a href="?m=template&c=m_template&a=templatePreview&skin=<?php echo $t['templateindex'];?>" target="_blank">预览</a></div>
	</label>
</li>
 <?php
 $i++;
 }
 ?>
<div style="clear:both"></div>
</ul>
</fieldset>
</div>

</div>
<div style="height:30px;"></div>
<?php include($this->showManageTpl('footer','manage'));?>
<div style="text-align:center;padding:10px;background:#efefef;position:fixed;_position:absolute;height:50px;width:100%;z-index: 100;bottom:0;" id="subButton"><input style="margin:10px auto" type="button" onclick="selectTemplate()" class="btn btn-green-b" value="选中模板" /><div style="clear:both"></div></div>