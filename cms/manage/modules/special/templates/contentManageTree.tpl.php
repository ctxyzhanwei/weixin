<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<meta name="robots" content="noindex,nofollow" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="/js/mootools1.3.js"></script>
<script src="/js/mootools-1.2-plugin.js"></script>
<script src="/script/js/moopha.js"></script>
<script src="/script/js/tree.js"></script>
<title></title>
</head>

<body>
<script>
function showChannels(id,domid){
	var req = new Request.HTML({url:'?m=special&c=m_special&a=specialChannelsTreeStr&specialid='+id, urlEncoded:true, async:true,onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
		$(domid).set('html',responseHTML);
		},onFailure: function() {}});req.send();
}
</script>
<style>
.leftMiddleTop{background:url(image/bg_left2.gif) no-repeat; height:40px; color:#0a4173; line-height:30px; font-size:13px; padding:0 0 8px 20px; font-weight:bold;}
</style>
<div class="e a_Tree">
<div class="leftMiddleTop">专题内容管理</div>
<div style="padding:0 0 0 10px; margin-top:-10px;">
<?php
if ($cats){
	foreach ($cats as $cat){
?>
<!--cat start-->
<div id="divTog_0"><div style="padding:0 0 0 0;"><nobr><img src="image/plus.gif" align="absmiddle" rel="divTog_<?php echo $cat['id'];?>" class="tog" /><img src="image/folder.gif" align="absmiddle"></img> <a href="?m=special&c=m_special&a=specials&catid=<?php echo $cat['id'];?>" target="sright"><?php echo $cat['name'];?></a></nobr></div>
<!--类别下所有专题 start-->
<div id="divTog_<?php echo $cat['id'];?>" style="padding:0;margin:0;display:none">
<?php
if ($catSpecials[$cat['id']]){
	foreach ($catSpecials[$cat['id']] as $s){
?>
<!--专题循环 start-->
<div style="padding:0 0 0 16px;"><nobr><img src="image/plus.gif" onclick="showChannels(<?php echo $s['id'];?>,'divTog_<?php echo $cat['id'].'_'.$s['id'];?>')" rel="divTog_<?php echo $cat['id'].'_'.$s['id'];?>" class="tog" align="absmiddle" /><a href="<?php echo $s['url'];?>" target="_blank"><img src="image/folder.gif" align="absmiddle" /></a> <a href="?m=special&c=m_special&a=catChannels&specialid=<?php echo $s['id'];?>" target="sright"><?php echo $s['name'];?></a></nobr></div>
<!--栏目 start-->
<div id="divTog_<?php echo $cat['id'].'_'.$s['id'];?>" style="padding:0;margin:0;display:none">
</div>
<!--栏目 end-->
<!--专题循环 end-->
<?php
	}
}else {
	echo '<div style="padding:0 0 0 16px;">该分类下暂无专题</div>';
}
?>
</div>
<!--类别下所有专题 end-->
</div>
<!--cat end-->
<?php
	}
}
?>
</div>
</div>
</body>
</html>