<?php include($this->showManageTpl('header','manage'));?>
<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script>

</script>
<div class="columntitle">关键词管理</div>
    <div class="oper"><a href="?m=seo&c=seo&a=keywordSet" class="add">添加</a><a href="###" onclick="$('form').submit()" class="delete">删除</a><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back">返回</a></div>
    <form method="POST" id="form" action="?m=seo&c=seo&a=keywords">
    <input type="hidden" name="doSubmit" value="1" />
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
		    <td>关键词</td>
			<td>链接</td>
			<td style="display:none">链接title</td>
			<td>链接target</td>
			<td>操作</td>
		</tr>
<?php
if ($rts){
	foreach($rts as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]"></td>
        <td><?php echo $v['keyword']?></td>
		<td><?php echo $v['link']?></td>
		<td style="display:none"><?php echo $v['title']?></td>
		<td><?php echo $v['target']?></td>
		<td><a href="?m=seo&c=seo&a=keywordSet&id=<?php echo $v['id']?>">修改</a></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>