<?php include($this->showManageTpl('header','manage'));?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="js/calendar.js"></script>
<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script src="js/moopha.js"></script>
<script src="js/content.js"></script>
<script type="text/javascript" src="../editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../editor/ckfinder/ckfinder.js"></script>
<script>
window.parent.top.document.getElementById("bottomframes").cols="0,7,*";
window.parent.top.document.getElementById("separator").contentWindow.document.getElementById('ImgArrow').src="image/separator2.gif"
window.addEvent('domready', function(){
	//new FormCheck('myform');
	
});
function checkSameTitle(){
	var reqVC = new Request.HTML({url:'?m=article&c=m_article&a=action_sameTitleCheck&title='+$('title').value,
	onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
		if(responseHTML.toInt()>0){
			alert('已有相同标题的文章，请检查内容是否重复')
		}
	}
	});
	reqVC.send();
}
</script>
<?php
$specialTxtChannels=array();
$specialChannels=array();
$specialPicChannels=array();

?>
<style>
/******************内容分页**************/
.abn_navul{height:28px; list-style:none;padding:0;margin:0}
*html .abn_navul{height:28px; list-style:none;padding:0;margin:0;display:none}
.abn_navul li{float:left;height:26px;margin-right:1px;border:solid 1px #E3ECF6;background:#E3ECF6;border-bottom:0px}
*html .abn_navul li{float:left;height:26px;margin-right:1px;border:solid 1px #E3ECF6;background:#E3ECF6;border-bottom:0px;width:120px;}
*html .abn_navul li #tabAdd{width:32px}
.abn_navul li span.tabdelete,.abn_navul li span.tabdelete_hover,.abn_navul li span.tabadd,.abn_navul li span.tabadd_hover{background:url(image/tabo.gif) no-repeat;width:16px;height:15px;display:block;float:right;margin:5px 6px 0 0;cursor:pointer}
.abn_navul li span.tabdelete{background-position:0 -15px;}
.abn_navul li span.tabdelete_hover{background-position:0 0;}
.abn_navul li span.tabadd{background-position:0 -30px;margin:5px 6px 0 6px;}
.abn_navul li span.tabadd_hover{background-position:0 -45px;margin:5px 6px 0 6px;}
.abn_navul li a{display:inline-block;font:normal 14px/25px SimSun;color:#333;padding:0 10px;border-bottom:0px}
.abn_navul li.active{height:29px;border:0px;border:solid 1px #C1D9F3;border-bottom:none;background:#C1D9F3}
.abn_navul li.active a{color:#333;font:bold 14px/29px SimSun;height:29px;padding:0 10px;border:none}
.abn_navul li.active a:hover{text-decoration:none;color:#fff}
.abn_navul li a:hover{color:#333}
.abn_navdt{background:#B9D3EF;min-height:2px;height:auto!important;height:2px;margin:0 0 5px 0;font-size:0px;width:96%;}
*html .abn_navdt{display:none}
*html #pContent_a1{display:none}
.abn_navdt a{color:#FFF;text-decoration:none;margin-left:24px;}
.abn_navdt a.active{color:#fe9900;font-weight:bold;}
.abn_navdt a:hover{color:#fe9900;}
i{font-style:normal}
.content_a div{margin:5px 0}
</style>
<?php
if (isset($_GET['id'])){//update
	$actName='修改';
	$actionUrl='?m=article&c=m_article&a=action_update&id='.$_GET['id'].'&start='.$_GET['start'];
}else {
	$actName='添加';
	$actionUrl='?m=article&c=m_article&a=action_add';
}
?>
    <div class="columntitle">【<?php echo $thisChannel->name;?>】文章<?php echo $actName;?></div>
    <div class="ftip"><?php echo L('linkIntro');?></div>
    <table cellspacing="1" cellpadding="1" Align="center" border="0" style="width:100%;word-break: break-all">
	<tr>
		<td>
       <div style="margin:0 10px;" class="ax moophaContentAdd"><div id="tip"></div>
       <form id="form" action="<?php echo $actionUrl; ?>" method="POST">
       <input type="hidden" id="cmsDir" value="<?php echo CMS_DIR; ?>" />
       <table width="100%">
<tr id="trtitle"><td width="11%" valign="middle" align="right">标题 </td><td width="85%" valign="middle"><input name="title" id="title" value="<?php echo $thisContent->title;?>" type="text" class="colorblur" style="width:360px;height:16px;background:url(/<?php echo MANAGE_DIR; ?>/image/ruler.gif) 0px 5px repeat-x;"<?php if (!$_GET['id']){?> onblur="checkSameTitle()"<?php }?> />&nbsp;</td></tr>
<?php
if (!in_array($thisChannel->id,$specialChannels)){
?>
<tr id="trsubtitle" style="display:none"><td width="11%" valign="middle" align="right">副标题 </td><td width="85%" valign="middle"><input name="subtitle" id="subtitle" value="<?php echo $thisContent->subtitle;?>" type="text" class="colorblur" style="width:360px;height:16px;background:url(/<?php echo MANAGE_DIR; ?>/image/ruler.gif) 0px 5px repeat-x;" />&nbsp;</td></tr>
<?php
}
?>

<?php
if ($thisChannel->channeltype!=3){
?>
<tr id="trexternallink"><td width="11%" valign="middle" align="right">外部链接 </td><td width="85%" valign="middle"><input type="checkbox" id="externallink0" name="externallink[]" value="1"<?php if($thisContent->externallink||in_array($thisChannel->id,$specialChannels)){echo ' checked';}?> /> <label for="externallink0" id="externallink0label">是 </label>&nbsp;&nbsp;<span style="color: red;display:none" id="tips"></span></td></tr>
<?php
}
?>

<tr id="trlink"<?php if(!$thisContent->externallink&&!in_array($thisChannel->id,$specialChannels)){echo ' style="display:none"';}?>><td width="11%" valign="middle" align="right">链接 </td><td width="85%" valign="middle"><input name="link" id="link" value="<?php echo $thisContent->link;?>" type="text" class="colorblur" style="width:360px;height:16px;" />&nbsp;<a href="###" onclick="addLink('link',0)" class="a_choose">选择</a> </td></tr>

<?php
if (!in_array($thisChannel->id,$specialTxtChannels)){
?>
<tr id="trthumb"><td width="11%" valign="middle" align="right">缩略图 </td><td width="85%" valign="middle">
<?php
if ($thisChannel->channeltype==1){
?>
<?php
if (!in_array($thisChannel->id,$specialChannels)){
?>
<label><input name="autoThumb" value="1" checked="checked" type="checkbox">是否获取内容第 </label><input name="autoThumbNo" value="1" size="2" class="input-text" type="text"> 张图片作为缩略图 /
<?php
}
?>
上传
<?php
}
?>
<input name="thumb" id="thumb" value="<?php echo $thisContent->thumb;?>" style="width:260px;height:16px;" type="text" class="colorblur" /> <a href="###" onclick="contentPicUpload('thumb',<?php echo $thisChannel->thumbwidth;?>,<?php echo $thisChannel->thumbheight;?>,'','',<?php echo $thisChannel->id;?>)">上传</a> <a href="###" onclick="viewImg('thumb','预览')">预览</a><div id="thumbSpan"></div><script>window.addEvent('domready',function(){if($('thumb').value.length){$('thumbSpan').set('html','<img width="80" src="'+$('thumb').value+'" />');}});</script></td></tr>

<?php
}
?>

<!--content start-->
<?php
if ($thisChannel->channeltype==1&&!$thisContent->externallink&&!in_array($thisChannel->id,$specialChannels)){
	$titles=explode('|',$thisContent->titles);
	$contents=$articleObj->contentPagination($thisContent->content);
	$thisContent->pagecount=count($contents);
?>
<tr id="trcontent"><td width="11%" valign="middle" align="right">内容 </td><td width="85%" valign="middle">
					<div style="margin:5px 0 0 0;display:none" id="contentTabs"><ul class="abn_navul" id="contentTabs">
<li class="active contentTab" id="contentTab1"><a href="###" onclick="showContent(1)">第<i>1</i>页</a><span style="display:none" rel="1" id="tabDelete1" class="tabdelete" onclick="deleteContentInput(1)" onmouseover="this.className='tabdelete_hover'" onmouseout="this.className='tabdelete'"></span></li>
<?php
for ($i=2;$i<$thisContent->pagecount+1;$i++){
	echo '<li class="contentTab" id="contentTab'.$i.'"><a href="###" onclick="showContent('.$i.')">第<i>'.$i.'</i>页</a><span rel="'.$i.'" id="tabDelete'.$i.'" class="tabdelete" onclick="deleteContentInput('.$i.')" onmouseover="this.className=\'tabdelete_hover\'" onmouseout="this.className=\'tabdelete\'"></span></li>';
}
?>
<li id="tabAdd"><span class="tabadd" onclick="addContentInput()" onmouseover="this.className='tabadd_hover'" onmouseout="this.className='tabadd'"></span></li>
</ul></div><div class="abn_navdt" style="display:none"></div><div style="clear:both"></div>

<?php
//contents
if ($thisContent->pagecount>1){
	$content1=$contents[0];
	$title1=$titles[0];
}else {
	$content1=$thisContent->content;
	$title1='';
}


	$b='&nbsp;';

echo '<div id="contentArea">';
//第一项
echo '<div id="content_a1" class="content_a">
					<div id="pContent_a1" style="display:none">分页标题 <input value="'.$title1.'" type="text" style="width:300px" name="pageTitle[]" />&nbsp;&nbsp;顺序 <input style="width:30px;" type="text" name="order[]" value="1" /></div>
					<textarea name="content[]" id="content1">'.$content1.$b.'</textarea><script type="text/javascript">var editor = CKEDITOR.replace("content1",{width:"96%"});CKFinder.setupCKEditor(editor,"../editor/ckfinder/") ;</script></div>';
for ($i=2;$i<$thisContent->pagecount+1;$i++){
	echo '<div id="content_a'.$i.'" class="content_a" style="display:none">
					<div id="pContent_a'.$i.'">分页标题 <input value="'.$titles[$i-1].'" type="text" style="width:300px" name="pageTitle[]" />&nbsp;&nbsp;顺序 <input style="width:30px;" type="text" name="order[]" value="'.$i.'" /></div>
					<textarea name="content[]" id="content'.$i.'">'.$contents[$i-1].$b.'</textarea><script type="text/javascript">var editor = CKEDITOR.replace("content'.$i.'",{width:"96%"});CKFinder.setupCKEditor(editor,"'.CMS_DIR_PATH.'/editor/ckfinder/") ;</script></div>';
}
echo '</div>';
?>

</td></tr>
<?php
}
?>
<!--content end-->
<?php
if (!in_array($thisChannel->id,$specialChannels)){
?>
<?php
if ($thisChannel->channeltype!=3){
?>
					<tr id="trintro"><td width="11%" valign="middle" align="right">简介 </td><td width="85%" valign="middle">
<?php
if ($thisChannel->channeltype==1&&!in_array($thisChannel->id,$specialChannels)){
?><p style="border:1px solid #CCC; padding:5px 8px; background:#FFC; width:80%">
					<label><input name="autoIntro" value="1" checked="checked" type="checkbox">是否截取内容前</label><input class="input-text" name="autoIntroLen" value="200" size="3" type="text">字符至内容简介(仅当简介为空时有效)</p>
<?php
}
?>					
					<textarea name="intro" id="intro" value="" style="width:360px;height:60px;font-size:12px;" class="colorblur"><?php echo $thisContent->intro;?></textarea>&nbsp;</td></tr>
					<tr id="trauthor" style="display:none"><td width="11%" valign="middle" align="right">作者 </td><td width="85%" valign="middle"><input name="author" id="author" value="<?php echo $thisContent->author; ?>" type="text" class="colorblur" style="width:120px;height:16px;" />&nbsp;</td></tr>
					<tr id="trsource" style="display:none"><td width="11%" valign="middle" align="right">来源 </td><td width="85%" valign="middle"><input name="source" id="source" style="width:120px;height:16px;" type="text" class="colorblur" value="<?php echo $thisContent->source; ?>" />&nbsp;</td></tr>
					<tr id="trsource" style="display:none"><td width="11%" valign="middle" align="right">来源类型 </td><td width="85%" valign="middle"><select name="sourcetype" id="sourcetype" value="<?php echo $thisContent->source; ?>">
					<?php
					if ($sourceTypes){
						foreach ($sourceTypes as $stk=>$st){
							if ($thisContent&&$thisContent->sourcetype==$stk){
								echo '<option value="'.$stk.'" selected>'.$st.'</option>';
							}else {
								echo '<option value="'.$stk.'">'.$st.'</option>';
							}
						}
					}
					?>
					</select></td></tr>
<?php
}
?>
<?php
}
?>	

<?php
if (!in_array($thisChannel->id,$specialChannels)){
?>
<!--content grroup start-->

<!--content group end-->

<?php
if ($thisChannel->channeltype<3){
?>
<tr><td valign="middle" align="right"><nobr>关键词</nobr> </td><td colspan="2" valign="middle"><input type="text" name="keywords" value="<?php echo substr($thisContent->keywords,1); ?>" id="keywords" style="width:300px;"></input> 多个关键词用半角逗号隔开</td></tr>
<?php
}
?>
<?php
}
?>
<tr <?php if (in_array($thisChannel->id,$specialChannels)){echo 'style="display:none"';} ?> style="display:none"><td valign="middle" align="right"><nobr>添加时间</nobr> </td><td colspan="2" valign="middle">

<input type="text" name="adddate" value="<?php echo date("Y-m-d",$thisContent->time);?>" id="adddate" style="width:100px;" rel="calendar"></input><div id="calendarDiv"></div>&nbsp;<input type="text" name="addtime" id="addtime" style="width:100px;" value="<?php echo date("H:i:s",$thisContent->time);?>"></input>

<?php
if ($thisChannel->channeltype==1){
?>
&nbsp;&nbsp;<label><input type="checkbox" name="clearhref" value="1" /> 保存时清除文章内链接</label>
<?php
}
?>
</td></tr>


<?php
if ($thisChannel->channeltype==1){
?>
       <tr style="display:none"><td valign="middle" align="right"><nobr>关闭评论</nobr> </td><td colspan="2" valign="middle"><label><input type="checkbox" name="closeComment" value="1"<?php if (!$thisContent->cancomment){?> checked<?php }?> /> 关闭评论</label></td></tr>
<?php }?>
</table>
<div style="height:100px;"></div>
<div style="text-align:center;padding:10px;background:#efefef;position:fixed;_position:absolute;height:40px;width:100%;z-index: 100;bottom:0;" id="subButton"><input type="submit" class="button" style="margin:10px auto" value="提交"></input></div>
</div>
<input type="hidden" value="<?php echo $thisChannel->thumbwidth; ?>" name="thumbwidth" id="thumbwidth" />
<input type="hidden" value="<?php echo $thisChannel->id; ?>" name="channelid" id="channelid" />
<input type="hidden" value="<?php echo $thisChannel->thumbheight; ?>" name="thumbheight" id="thumbheight" />
<input type="hidden" value="<?php echo $_GET['site']; ?>" name="site" id="site" />
<input type="hidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" name="referer" id="referer" />
<input type="hidden" value="<?php echo $_SESSION['siteDir']; ?>" name="sitedir" id="sitedir" />
<input type="hidden" name="except" value="1" />
</form></div>
      </td>
	</tr>
</table>


<script>
    externalLinkChecked();
</script>
<script src="js/ajaxUpload.js"></script>
<?php 
include 'multiUpload.include.php';
?>
<?php include($this->showManageTpl('footer','manage'));?>