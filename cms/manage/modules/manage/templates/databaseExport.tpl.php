<?php include($this->showManageTpl('header','manage'));?>
<script>
function op(){
	$('form').setProperty('action','?m=manage&c=database&a=repair&operation=optimize');
	$('form').submit();
}
function repair(){
	$('form').setProperty('action','?m=manage&c=database&a=repair&operation=repair');
	$('form').submit();
}
</script>
<div class="columntitle">数据库备份</div>
    <form method="POST" id="form" action="?m=manage&c=database&a=action_export">
    <div style="padding:5px 20px;">每个分卷文件大小 <input type="text" class="colorblur" name="sizelimit" value="2048" size=5> K &nbsp;&nbsp;<input type="submit" name="dosubmit" value=" 开始备份数据 " class="button"></div>
    <div class="oper"><a href="###" onclick="repair()" class="back">修复表</a> <a href="###" onclick="op()" class="taxis">优化表</a></div>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('tables[]');"></td>
		    <td>表名</td>
			<td>类型</td>
			<td>编码</td>
			<td>记录数</td>
			<td>使用空间</td>
			<td>碎片</td>
			<td>最后修改</td>
			<td>操作</td>
		</tr>
<?php
	foreach($infos as $k=>$v) {
		if ($v['name']=='auto_viewlog'||$v['name']=='moopha_content_viewlog'||$v['name']=='auto_store_traffic1'){
			$checked='';
		}else {
			$checked=' checked';
		}
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['name'];?>" name="tables[]"<?php echo $checked;?>></td>
        <td><?php echo $v['name']?></td>
		<td><?php echo $v['engine']?></td>
		<td><?php echo $v['collation']?></td>
		<td><?php echo $v['rows']?></td>
		<td><?php echo $this->calSize($v['size']);?></td>
		<td><?php echo $this->calSize($v['dataFree']);?></td>
		<td><?php echo $v['updateTime']?></td>
		<td>
		<a href="?m=manage&c=database&a=repair&tables=<?php echo $v['name']?>&operation=repair">修复</a> <a href="?m=manage&c=database&a=repair&tables=<?php echo $v['name']?>&operation=optimize">优化</a>
		</td>
    </tr>
<?php
	}
?>
</table>
<input type="hidden" name="except" value="1" />
<div class="oper"><a href="###" onclick="repair()" class="back">修复表</a> <a href="###" onclick="op()" class="taxis">优化表</a></div>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>